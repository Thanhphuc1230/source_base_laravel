<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Services\CheckoutService;
use App\Services\CartService;
use App\Services\RateLimitService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use RealRashid\SweetAlert\Facades\Alert;

class CheckoutController extends Controller
{
    protected $checkoutService;
    protected $cartService;
    protected $rateLimitService;

    public function __construct(CheckoutService $checkoutService, CartService $cartService)
    {
        $this->checkoutService = $checkoutService;
        $this->cartService = $cartService;
        $this->rateLimitService = RateLimitService::forCheckout();
    }

    public function index()
    {
        if (!$this->checkoutService->canCheckout()) {
            Alert::error(
                app()->getLocale() == 'en' ? 'Empty Cart' : 'Giỏ hàng đang trống',
                app()->getLocale() == 'en' ? 'No product in cart' : 'Chưa có sản phẩm nào trong giỏ hàng'
            );

            return redirect()->route('web.cart');
        }

        $cart = $this->cartService->getCart();
        $total = $this->cartService->getTotalPrice();

        return view('frontend.modules.checkout.index', compact('cart', 'total'));
    }

    public function checkoutStore(Request $request)
    {
        // Ensure cart has items before processing
        if ($this->cartService->isEmpty()) {
            Alert::warning(
                app()->getLocale() == 'en' ? 'Empty Cart' : 'Giỏ hàng đang trống',
                app()->getLocale() == 'en' 
                    ? 'Your cart is empty or this order has already been processed.' 
                    : 'Giỏ hàng đang trống hoặc đơn hàng đã được xử lý trước đó.'
            );
            return redirect()->route('web.cart');
        }

        // Validate request data
        $request->validate([
            'f_name_order' => 'required|string|max:255',
            'l_name_order' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:500',
            'payment_method' => 'required|string|in:cash,card,bank_transfer',
            'total' => 'required|numeric|min:0',
            'note' => 'nullable|string|max:1000'
        ]);

        $ip = $request->ip();

        // Check rate limit for checkout attempts
        if ($this->rateLimitService->isBlocked($ip)) {
            Alert::error(
                app()->getLocale() == 'en' ? 'Too Many Checkout Attempts' : 'Quá nhiều lần thử thanh toán',
                app()->getLocale() == 'en' ? 'Please try again later' : 'Vui lòng thử lại sau ít phút'
            );

            return back()->withInput();
        }

        // Atomic lock to prevent duplicate submissions
        $sessionId = session()->getId();
        $lockKey = 'checkout_lock_' . ($sessionId ?: md5($ip . $request->userAgent()));
        $lock = Cache::lock($lockKey, 10);

        if (!$lock->get()) {
            Alert::warning(
                app()->getLocale() == 'en' ? 'Processing Order' : 'Đơn hàng đang xử lý',
                app()->getLocale() == 'en' 
                    ? 'Your order is currently being processed. Please do not submit again.' 
                    : 'Đơn hàng của bạn đang được hệ thống xử lý, vui lòng không thao tác lại!'
            );
            return back()->withInput();
        }

        try {
            $orderId = $this->checkoutService->processOrder($request);

            // Clear rate limit attempts on successful checkout
            $this->rateLimitService->clearAttempts($ip);

            return redirect()->route('web.orderSuccess', $orderId);

        } catch (\Exception $e) {
            // Increment attempts on failure
            $this->rateLimitService->incrementAttempts($ip);

            Log::error('Checkout failed: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);

            Alert::error(
                app()->getLocale() == 'en' ? 'Order Failed' : 'Đặt hàng thất bại',
                app()->getLocale() == 'en' ? 'An error occurred, please try again.' : 'Có lỗi xảy ra khi tạo đơn hàng, vui lòng thử lại!'
            );

            return back()->withInput();

        } finally {
            optional($lock)->release();
        }
    }

    public function orderSuccess(Request $request, $orderId = null)
    {
        if (!$orderId) {
            $orderId = $request->route('orderId') 
                ?? $request->query('orderId') 
                ?? $request->query('order') 
                ?? (is_numeric(key($request->query())) ? key($request->query()) : null);
        }

        $orderDetails = $orderId ? $this->checkoutService->getOrderDetails((int)$orderId) : null;

        if (!$orderDetails) {
            Alert::error(
                app()->getLocale() == 'en' ? 'Order Not Found' : 'Không tìm thấy đơn hàng'
            );

            return redirect()->route('web.home');
        }

        return view('frontend.modules.checkout.order_success', [
            'order' => $orderDetails
        ]);
    }
}
