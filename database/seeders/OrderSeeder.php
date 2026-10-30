<?php

namespace Database\Seeders;

use App\Models\OrderProduct;
use App\Models\OrderShipping;
use App\Models\OrderStatus;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = Product::where('status', true)->get();
        if ($products->isEmpty()) {
            return;
        }

        // Set a few low-stock and out-of-stock products for alerts
        $firstProd = $products->first();
        if ($firstProd) {
            $firstProd->update(['quantity' => 0]); // Out of stock
        }
        if ($products->count() > 1) {
            $products[1]->update(['quantity' => 3]); // Low stock
        }
        if ($products->count() > 2) {
            $products[2]->update(['quantity' => 4]); // Low stock
        }

        // Sample customers
        $customers = [
            ['name' => 'Nguyễn Văn An', 'email' => 'vanan@gmail.com', 'phone' => '0912345678', 'address' => '120 Hoàng Hoa Thám, Hà Nội'],
            ['name' => 'Trần Thị Mai', 'email' => 'thimai@gmail.com', 'phone' => '0987654321', 'address' => '45 Lê Duẩn, Đà Nẵng'],
            ['name' => 'Lê Hoàng Nam', 'email' => 'hoangnam@gmail.com', 'phone' => '0903123456', 'address' => '88 Nguyễn Huệ, TP.HCM'],
            ['name' => 'Phạm Quỳnh Nga', 'email' => 'quynhnga@gmail.com', 'phone' => '0934567890', 'address' => '12 Phan Châu Trinh, Cần Thơ'],
            ['name' => 'Võ Minh Đức', 'email' => 'minhduc@gmail.com', 'phone' => '0978901234', 'address' => '67 Trần Phú, Hải Phòng'],
            ['name' => 'Đỗ Lan Anh', 'email' => 'lananh@gmail.com', 'phone' => '0911223344', 'address' => '23 Bạch Đằng, Nha Trang'],
        ];

        // 1. Overdue pending orders (created 36 - 48 hours ago, status = 0)
        for ($i = 0; $i < 2; $i++) {
            $c = $customers[$i % count($customers)];
            $createdAt = Carbon::now()->subHours(36 + ($i * 12));

            $shipping = OrderShipping::create([
                'uuid_order_shipping' => Str::uuid(),
                'f_name_order' => explode(' ', $c['name'])[0],
                'l_name_order' => implode(' ', array_slice(explode(' ', $c['name']), 1)),
                'email' => $c['email'],
                'phone' => $c['phone'],
                'address' => $c['address'],
                'note' => 'Giao giờ hành chính, gọi trước 15p.',
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ]);

            $p = $products->random();
            $qty = rand(1, 2);
            $total = $p->price * $qty;

            $order = OrderStatus::create([
                'uuid_order_status' => Str::uuid(),
                'shipping_id' => $shipping->id_order_shipping,
                'payment_method' => 'cod',
                'total' => $total,
                'status' => OrderStatus::STATUS_PENDING, // 0: Pending (Overdue!)
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ]);

            OrderProduct::create([
                'uuid_order_product' => Str::uuid(),
                'order_status_id' => $order->id_order_status,
                'product_id' => $p->id_product,
                'quantity' => $qty,
                'price' => $p->price,
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ]);
        }

        // 2. Today's orders (completed & new pending)
        for ($i = 0; $i < 3; $i++) {
            $c = $customers[($i + 2) % count($customers)];
            $createdAt = Carbon::today()->addHours(8 + ($i * 2));

            $shipping = OrderShipping::create([
                'uuid_order_shipping' => Str::uuid(),
                'f_name_order' => explode(' ', $c['name'])[0],
                'l_name_order' => implode(' ', array_slice(explode(' ', $c['name']), 1)),
                'email' => $c['email'],
                'phone' => $c['phone'],
                'address' => $c['address'],
                'note' => 'Đơn hàng mới tạo trong ngày',
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ]);

            $p = $products->random();
            $qty = rand(1, 3);
            $total = $p->price * $qty;
            $status = $i === 0 ? OrderStatus::STATUS_PENDING : OrderStatus::STATUS_COMPLETED;

            $order = OrderStatus::create([
                'uuid_order_status' => Str::uuid(),
                'shipping_id' => $shipping->id_order_shipping,
                'payment_method' => $i % 2 === 0 ? 'cod' : 'bank_transfer',
                'total' => $total,
                'status' => $status,
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ]);

            OrderProduct::create([
                'uuid_order_product' => Str::uuid(),
                'order_status_id' => $order->id_order_status,
                'product_id' => $p->id_product,
                'quantity' => $qty,
                'price' => $p->price,
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ]);
        }

        // 3. Historical orders across the last 30 days
        for ($day = 2; $day <= 28; $day += 2) {
            $c = $customers[$day % count($customers)];
            $createdAt = Carbon::today()->subDays($day)->addHours(rand(9, 18));

            $shipping = OrderShipping::create([
                'uuid_order_shipping' => Str::uuid(),
                'f_name_order' => explode(' ', $c['name'])[0],
                'l_name_order' => implode(' ', array_slice(explode(' ', $c['name']), 1)),
                'email' => $c['email'],
                'phone' => $c['phone'],
                'address' => $c['address'],
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ]);

            $p = $products->random();
            $qty = rand(1, 4);
            $total = $p->price * $qty;
            // Mostly completed, occasionally cancelled
            $status = ($day % 10 === 0) ? OrderStatus::STATUS_CANCELLED : OrderStatus::STATUS_COMPLETED;

            $order = OrderStatus::create([
                'uuid_order_status' => Str::uuid(),
                'shipping_id' => $shipping->id_order_shipping,
                'payment_method' => 'cod',
                'total' => $total,
                'status' => $status,
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ]);

            OrderProduct::create([
                'uuid_order_product' => Str::uuid(),
                'order_status_id' => $order->id_order_status,
                'product_id' => $p->id_product,
                'quantity' => $qty,
                'price' => $p->price,
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ]);
        }
    }
}
