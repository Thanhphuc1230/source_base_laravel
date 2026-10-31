@extends('admin.master')
@php
    $isEn = session('locale') === 'en';
@endphp
@section('module', $isEn ? 'Dashboard' : 'Bảng điều khiển')
@section('action', $isEn ? 'Business & Operations Overview' : 'Tổng quan kinh doanh & Vận hành')
@section('content')
<div class="page-content">
    <div class="container-fluid">
        <!-- 0. TIÊU ĐỀ TRANG GỌN GÀNG CHUẨN ADMIN -->
        <div class="row mb-3">
            <div class="col-12">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div>
                        <h4 class="fs-16 fw-bold text-dark mb-1">{{ $isEn ? 'Business & Operations Overview' : 'Tổng quan hoạt động kinh doanh' }}</h4>
                        <p class="text-muted fs-12 mb-0">{{ $isEn ? 'Real-time revenue tracking, order operations, and inventory threshold alerts' : 'Theo dõi doanh thu, vận hành đơn hàng và cảnh báo tồn kho thời gian thực' }}</p>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <a href="{{ route('admin.analytics.index', ['refresh' => 1]) }}"
                            class="btn btn-outline-secondary btn-sm d-inline-flex align-items-center rounded-2 px-3"
                            style="height: 36px; font-weight: 500; font-size: 13px;" title="{{ $isEn ? 'Refresh cache and reload data' : 'Làm mới bộ nhớ đệm và nạp lại dữ liệu' }}">
                            <i class="ri-refresh-line me-1 fs-14"></i> {{ $isEn ? 'Refresh Data' : 'Làm mới dữ liệu' }}
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- 1. HÀNG DOANH THU CỐT LÕI (4 THẺ KPI CHẶT CHẼ) -->
        <div class="row g-3 mb-3">
            <!-- Doanh thu hôm nay -->
            <div class="col-xl-3 col-sm-6">
                <div class="card dashboard-card h-100 mb-0">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-center justify-content-between">
                            <span class="text-uppercase fw-semibold text-muted fs-11">{{ $isEn ? "TODAY'S REVENUE" : 'DOANH THU HÔM NAY' }}</span>
                            <div class="avatar-xs flex-shrink-0">
                                <span class="avatar-title bg-primary-subtle text-primary rounded-2 fs-15">
                                    <i class="ri-money-dollar-circle-line"></i>
                                </span>
                            </div>
                        </div>
                        <div class="mt-2">
                            <h3 class="fs-20 fw-bold text-dark mb-1">
                                {{ number_format($summary['revenue_today']) }} <span class="fs-12 fw-normal text-muted">đ</span>
                            </h3>
                            <div class="d-flex align-items-center text-muted fs-11">
                                <span class="badge bg-primary-subtle text-primary me-1 px-1.5 py-0.5">
                                    <i class="ri-shopping-cart-2-line align-middle"></i> {{ $summary['orders_today'] }} {{ $isEn ? 'orders' : 'đơn' }}
                                </span> {{ $isEn ? 'new today' : 'mới hôm nay' }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Doanh thu tuần này -->
            <div class="col-xl-3 col-sm-6">
                <div class="card dashboard-card h-100 mb-0">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-center justify-content-between">
                            <span class="text-uppercase fw-semibold text-muted fs-11">{{ $isEn ? "THIS WEEK'S REVENUE" : 'DOANH THU TUẦN NÀY' }}</span>
                            <div class="avatar-xs flex-shrink-0">
                                <span class="avatar-title bg-success-subtle text-success rounded-2 fs-15">
                                    <i class="ri-calendar-check-line"></i>
                                </span>
                            </div>
                        </div>
                        <div class="mt-2">
                            <h3 class="fs-20 fw-bold text-success mb-1">
                                {{ number_format($summary['revenue_week']) }} <span class="fs-12 fw-normal text-muted">đ</span>
                            </h3>
                            <div class="d-flex align-items-center text-muted fs-11">
                                <span class="badge bg-success-subtle text-success me-1 px-1.5 py-0.5">
                                    <i class="ri-calendar-line align-middle"></i> {{ $summary['orders_week'] }} {{ $isEn ? 'orders' : 'đơn' }}
                                </span> {{ $isEn ? 'this week' : 'trong tuần' }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Doanh thu tháng này -->
            <div class="col-xl-3 col-sm-6">
                <div class="card dashboard-card h-100 mb-0">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-center justify-content-between">
                            <span class="text-uppercase fw-semibold text-muted fs-11">{{ $isEn ? 'REVENUE FOR ' . date('M Y') : 'DOANH THU THÁNG ' . date('m/Y') }}</span>
                            <div class="avatar-xs flex-shrink-0">
                                <span class="avatar-title bg-info-subtle text-info rounded-2 fs-15">
                                    <i class="ri-line-chart-line"></i>
                                </span>
                            </div>
                        </div>
                        <div class="mt-2">
                            <h3 class="fs-20 fw-bold text-info mb-1">
                                {{ number_format($summary['revenue_month']) }} <span class="fs-12 fw-normal text-muted">đ</span>
                            </h3>
                            <div class="d-flex align-items-center text-muted fs-11">
                                <span class="badge bg-info-subtle text-info me-1 px-1.5 py-0.5">
                                    <i class="ri-file-list-3-line align-middle"></i> {{ $summary['orders_month'] }} {{ $isEn ? 'orders' : 'đơn' }}
                                </span> {{ $isEn ? 'this month' : 'trong tháng' }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tổng doanh thu tích lũy -->
            <div class="col-xl-3 col-sm-6">
                <div class="card dashboard-card h-100 mb-0">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-center justify-content-between">
                            <span class="text-uppercase fw-semibold text-muted fs-11">{{ $isEn ? 'TOTAL ACCUMULATED REVENUE' : 'TỔNG DOANH THU TÍCH LŨY' }}</span>
                            <div class="avatar-xs flex-shrink-0">
                                <span class="avatar-title bg-secondary-subtle text-secondary rounded-2 fs-15">
                                    <i class="ri-wallet-3-line"></i>
                                </span>
                            </div>
                        </div>
                        <div class="mt-2">
                            <h3 class="fs-20 fw-bold text-dark mb-1">
                                {{ number_format($summary['revenue_total']) }} <span class="fs-12 fw-normal text-muted">đ</span>
                            </h3>
                            <div class="d-flex align-items-center text-muted fs-11">
                                <span class="badge bg-secondary-subtle text-secondary me-1 px-1.5 py-0.5">
                                    <i class="ri-archive-line align-middle"></i> {{ $summary['orders_total'] }} {{ $isEn ? 'orders' : 'đơn' }}
                                </span> {{ $isEn ? 'all time' : 'toàn thời gian' }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 2. HÀNG TRẠNG THÁI VẬN HÀNH ĐƠN HÀNG (CARD COMPACT, GỌN GÀNG) -->
        <div class="row g-3 mb-3">
            <div class="col-xl-3 col-sm-6">
                <a href="{{ route('admin.order.index') }}" class="text-decoration-none">
                    <div class="card dashboard-card h-100 mb-0">
                        <div class="card-body py-2 px-3 d-flex align-items-center justify-content-between">
                            <div>
                                <span class="text-muted fs-11 fw-medium">{{ $isEn ? 'New Orders Today' : 'Đơn mới hôm nay' }}</span>
                                <h4 class="fs-18 fw-bold text-dark mb-0 mt-0.5">{{ $summary['orders_today'] }}</h4>
                            </div>
                            <div class="avatar-xs flex-shrink-0">
                                <span class="avatar-title bg-primary-subtle text-primary rounded-2 fs-14">
                                    <i class="ri-shopping-bag-3-line"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-xl-3 col-sm-6">
                <a href="{{ route('admin.order.index', ['status' => 0]) }}" class="text-decoration-none">
                    <div class="card dashboard-card h-100 mb-0">
                        <div class="card-body py-2 px-3 d-flex align-items-center justify-content-between">
                            <div>
                                <span class="text-muted fs-11 fw-medium">{{ $isEn ? 'Pending Orders' : 'Đơn chờ xử lý' }}</span>
                                <h4 class="fs-18 fw-bold text-warning mb-0 mt-0.5">{{ $summary['orders_pending'] }}</h4>
                            </div>
                            <div class="avatar-xs flex-shrink-0">
                                <span class="avatar-title bg-warning-subtle text-warning rounded-2 fs-14">
                                    <i class="ri-time-line"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-xl-3 col-sm-6">
                <a href="{{ route('admin.order.index', ['status' => 1]) }}" class="text-decoration-none">
                    <div class="card dashboard-card h-100 mb-0">
                        <div class="card-body py-2 px-3 d-flex align-items-center justify-content-between">
                            <div>
                                <span class="text-muted fs-11 fw-medium">{{ $isEn ? 'Completed Orders' : 'Đơn đã hoàn thành' }}</span>
                                <h4 class="fs-18 fw-bold text-success mb-0 mt-0.5">{{ $summary['orders_completed'] }}</h4>
                            </div>
                            <div class="avatar-xs flex-shrink-0">
                                <span class="avatar-title bg-success-subtle text-success rounded-2 fs-14">
                                    <i class="ri-checkbox-circle-line"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-xl-3 col-sm-6">
                <a href="{{ route('admin.order.index', ['status' => 2]) }}" class="text-decoration-none">
                    <div class="card dashboard-card h-100 mb-0">
                        <div class="card-body py-2 px-3 d-flex align-items-center justify-content-between">
                            <div>
                                <span class="text-muted fs-11 fw-medium">{{ $isEn ? 'Cancelled Orders' : 'Đơn đã hủy' }}</span>
                                <h4 class="fs-18 fw-bold text-danger mb-0 mt-0.5">{{ $summary['orders_cancelled'] }}</h4>
                            </div>
                            <div class="avatar-xs flex-shrink-0">
                                <span class="avatar-title bg-danger-subtle text-danger rounded-2 fs-14">
                                    <i class="ri-close-circle-line"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        <!-- 3. [MVP] KHU VỰC CẢNH BÁO VẬN HÀNH CẤP BÁCH -->
        <div class="row g-3 mb-3">
            <!-- Cảnh báo đơn chưa xử lý quá hạn (> 24h) -->
            <div class="col-lg-6">
                <div class="card dashboard-card h-100 mb-0">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h5 class="card-title mb-0 fs-13 fw-bold text-danger d-flex align-items-center">
                            <i class="ri-alarm-warning-line me-1.5 fs-15"></i> {{ $isEn ? 'Overdue Pending Orders Alert (> 24h)' : 'Cảnh báo đơn chưa xử lý quá hạn (> 24h)' }}
                        </h5>
                        <span class="badge {{ $overdue_orders_count > 0 ? 'bg-danger text-white' : 'bg-light text-muted' }} rounded-2 px-2 py-0.5 fs-11">
                            {{ $overdue_orders_count }} {{ $isEn ? 'backlogged orders' : 'đơn tồn đọng' }}
                        </span>
                    </div>
                    <div class="card-body p-0">
                        @if(!empty($overdue_orders))
                            <div class="table-responsive">
                                <table class="table table-dashboard table-hover align-middle">
                                    <thead>
                                        <tr>
                                            <th style="width: 42%;" class="ps-3 text-start">{{ $isEn ? 'Customer' : 'Khách hàng' }}</th>
                                            <th style="width: 22%;" class="text-center">{{ $isEn ? 'Waiting Time' : 'Thời gian chờ' }}</th>
                                            <th style="width: 20%;" class="text-end">{{ $isEn ? 'Total Amount' : 'Tổng tiền' }}</th>
                                            <th style="width: 16%;" class="text-center pe-3">{{ $isEn ? 'Action' : 'Xử lý' }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($overdue_orders as $ord)
                                            <tr>
                                                <td class="ps-3 text-start" style="width: 42%;">
                                                    <div class="fw-semibold text-dark text-truncate" title="{{ $ord['customer_name'] }}">{{ $ord['customer_name'] }}</div>
                                                    <div class="text-muted fs-11">{{ $ord['phone'] }}</div>
                                                </td>
                                                <td class="text-center" style="width: 22%;">
                                                    <span class="badge bg-danger-subtle text-danger px-2 py-0.5 fs-11 fw-medium">
                                                        <i class="ri-time-line me-0.5"></i>{{ $isEn ? 'Over ' . $ord['hours_overdue'] . ' hrs' : 'Quá ' . $ord['hours_overdue'] . ' giờ' }}
                                                    </span>
                                                </td>
                                                <td class="text-end fw-semibold text-dark" style="width: 20%;">
                                                    {{ number_format($ord['total']) }} đ
                                                </td>
                                                <td class="text-center pe-3" style="width: 16%;">
                                                    <a href="{{ route('admin.order.edit', $ord['uuid']) }}"
                                                        class="btn btn-sm btn-soft-danger d-inline-flex align-items-center justify-content-center px-2 py-1 fs-11 fw-medium rounded-2"
                                                        style="white-space: nowrap;" title="{{ $isEn ? 'View order details' : 'Xem chi tiết đơn hàng' }}">
                                                        <i class="ri-eye-line me-1"></i>{{ $isEn ? 'View' : 'Xem đơn' }}
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-4 text-muted">
                                <i class="ri-checkbox-circle-line fs-24 text-success d-block mb-1"></i>
                                <span class="fs-12">{{ $isEn ? 'Great! No overdue pending orders.' : 'Tuyệt vời! Không có đơn hàng nào bị tồn đọng quá hạn.' }}</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Cảnh báo sản phẩm sắp hết hàng (<= 5) -->
            <div class="col-lg-6">
                <div class="card dashboard-card h-100 mb-0">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h5 class="card-title mb-0 fs-13 fw-bold text-dark d-flex align-items-center">
                            <i class="ri-store-2-line text-warning me-1.5 fs-15"></i> {{ $isEn ? 'Inventory & Low Stock Alerts' : 'Cảnh báo tồn kho & Sắp hết hàng' }}
                        </h5>
                        <span class="badge {{ $low_stock_count > 0 ? 'bg-warning-subtle text-warning' : 'bg-light text-muted' }} rounded-2 px-2 py-0.5 fs-11">
                            {{ $low_stock_count }} {{ $isEn ? 'items need restock' : 'mặt hàng cần nhập' }}
                        </span>
                    </div>
                    <div class="card-body p-0">
                        @if(!empty($low_stock))
                            <div class="table-responsive">
                                <table class="table table-dashboard table-hover align-middle">
                                    <thead>
                                        <tr>
                                            <th style="width: 44%;" class="ps-3 text-start">{{ $isEn ? 'Product' : 'Sản phẩm' }}</th>
                                            <th style="width: 24%;" class="text-start">{{ $isEn ? 'Category' : 'Danh mục' }}</th>
                                            <th style="width: 16%;" class="text-center">{{ $isEn ? 'Stock' : 'Tồn kho' }}</th>
                                            <th style="width: 16%;" class="text-center pe-3">{{ $isEn ? 'Action' : 'Thao tác' }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($low_stock as $prod)
                                            <tr>
                                                <td class="ps-3 text-start" style="width: 44%;">
                                                    <div class="d-flex align-items-center gap-2">
                                                        @if($prod['image'])
                                                            <img src="{{ $prod['image'] }}" alt="{{ $prod['name'] }}"
                                                                class="rounded-2 object-fit-cover border flex-shrink-0" style="width: 30px; height: 30px;">
                                                        @else
                                                            <div class="rounded-2 bg-light border d-flex align-items-center justify-content-center text-muted flex-shrink-0"
                                                                style="width: 30px; height: 30px;">
                                                                <i class="ri-image-line fs-13"></i>
                                                            </div>
                                                        @endif
                                                        <span class="fw-semibold text-dark text-truncate" style="max-width: 180px;" title="{{ $prod['name'] }}">
                                                            {{ $prod['name'] }}
                                                        </span>
                                                    </div>
                                                </td>
                                                <td class="text-start" style="width: 24%;">
                                                    <span class="category-badge">{{ $prod['category'] ?? ($isEn ? 'Uncategorized' : 'Chưa phân loại') }}</span>
                                                </td>
                                                <td class="text-center" style="width: 16%;">
                                                    @if($prod['is_out_of_stock'])
                                                        <span class="badge bg-danger-subtle text-danger px-2 py-0.5 fs-11 fw-medium">
                                                            {{ $isEn ? 'Out of stock (0)' : 'Hết hàng (0)' }}
                                                        </span>
                                                    @else
                                                        <span class="badge bg-warning-subtle text-warning px-2 py-0.5 fs-11 fw-medium">
                                                            {{ $isEn ? $prod['quantity'] . ' left' : 'Còn ' . $prod['quantity'] . ' sp' }}
                                                        </span>
                                                    @endif
                                                </td>
                                                <td class="text-center pe-3" style="width: 16%;">
                                                    <a href="{{ route('admin.product.edit', ['uuid' => $prod['uuid'], 'page' => 1]) }}"
                                                        class="btn btn-sm btn-soft-primary d-inline-flex align-items-center justify-content-center px-2 py-1 fs-11 fw-medium rounded-2"
                                                        style="white-space: nowrap;" title="{{ $isEn ? 'Update info & stock' : 'Cập nhật thông tin & tồn kho' }}">
                                                        <i class="ri-edit-line me-1"></i>{{ $isEn ? 'Update' : 'Cập nhật' }}
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-4 text-muted">
                                <i class="ri-checkbox-circle-line fs-24 text-success d-block mb-1"></i>
                                <span class="fs-12">{{ $isEn ? 'All inventory stocks are currently healthy.' : 'Tồn kho các mặt hàng hiện đang ở mức an toàn.' }}</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- 4. [GIAI ĐOẠN 2] BIỂU ĐỒ DOANH THU & SỐ ĐƠN HÀNG THEO NGÀY -->
        <div class="row mb-3">
            <div class="col-12">
                <div class="card dashboard-card mb-0">
                    <div class="card-header d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <div>
                            <h5 class="card-title mb-0 fs-14 fw-bold text-dark">
                                <i class="ri-funds-line text-primary me-1.5 fs-15"></i>{{ $isEn ? 'Daily Revenue & Orders Chart' : 'Biểu đồ Doanh thu & Đơn hàng theo ngày' }}
                            </h5>
                            <p class="text-muted fs-11 mb-0">{{ $isEn ? 'Track actual revenue and order fulfillment frequency over time' : 'Theo dõi doanh thu thực tế và tần suất đặt hàng theo thời gian' }}</p>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <div class="btn-group btn-group-sm" role="group">
                                <button type="button" class="btn btn-outline-primary active revenue-filter-btn px-2.5 py-1 fs-12 rounded-start-2" data-period="7_days">{{ $isEn ? '7 Days' : '7 ngày' }}</button>
                                <button type="button" class="btn btn-outline-primary revenue-filter-btn px-2.5 py-1 fs-12" data-period="30_days">{{ $isEn ? '30 Days' : '30 ngày' }}</button>
                                <button type="button" class="btn btn-outline-primary revenue-filter-btn px-2.5 py-1 fs-12 rounded-end-2" data-period="year">{{ $isEn ? 'This Year' : 'Cả năm' }}</button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-3">
                        <div id="revenueApexChart" style="min-height: 350px;"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 5. TOP SẢN PHẨM BÁN CHẠY & ĐƠN HÀNG MỚI NHẤT -->
        <div class="row g-3 mb-3">
            <!-- Top sản phẩm bán chạy -->
            <div class="col-lg-6">
                <div class="card dashboard-card h-100 mb-0">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h5 class="card-title mb-0 text-dark fs-13 fw-bold">
                            <i class="ri-medal-line text-warning me-1.5 fs-15"></i> {{ $isEn ? 'Top 5 Best-Selling Products' : 'Top 5 Sản phẩm bán chạy nhất' }}
                        </h5>
                        <span class="badge bg-light text-muted fs-11">{{ $isEn ? 'Ranked by volume' : 'Theo số lượng' }}</span>
                    </div>
                    <div class="card-body p-0">
                        @if(!empty($top_products))
                            <div class="table-responsive">
                                <table class="table table-dashboard table-hover align-middle">
                                    <thead>
                                        <tr>
                                            <th style="width: 38px;" class="ps-3 text-center">#</th>
                                            <th style="width: 48%;" class="text-start">{{ $isEn ? 'Product' : 'Sản phẩm' }}</th>
                                            <th style="width: 24%;" class="text-center">{{ $isEn ? 'Sold' : 'Đã bán' }}</th>
                                            <th style="width: 28%;" class="text-end pe-3">{{ $isEn ? 'Revenue' : 'Doanh thu' }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($top_products as $idx => $tp)
                                            <tr>
                                                <td class="ps-3 text-center" style="width: 38px;">
                                                    @if($idx === 0)
                                                        <span class="badge bg-warning text-white rounded-2 px-1.5 py-0.5 fs-11 fw-bold">1</span>
                                                    @elseif($idx === 1)
                                                        <span class="badge bg-secondary text-white rounded-2 px-1.5 py-0.5 fs-11 fw-bold">2</span>
                                                    @elseif($idx === 2)
                                                        <span class="badge text-white rounded-2 px-1.5 py-0.5 fs-11 fw-bold" style="background-color: #cd7f32;">3</span>
                                                    @else
                                                        <span class="text-muted fw-semibold fs-12">{{ $idx + 1 }}</span>
                                                    @endif
                                                </td>
                                                <td class="text-start" style="width: 48%;">
                                                    <div class="d-flex align-items-center gap-2">
                                                        @if($tp['image'])
                                                            <img src="{{ $tp['image'] }}" alt="{{ $tp['name'] }}"
                                                                class="rounded-2 object-fit-cover border flex-shrink-0" style="width: 30px; height: 30px;">
                                                        @endif
                                                        <div class="overflow-hidden">
                                                            <div class="fw-semibold text-dark text-truncate" style="max-width: 200px;" title="{{ $tp['name'] }}">
                                                                {{ $tp['name'] }}
                                                            </div>
                                                            <div class="text-muted fs-11">{{ number_format($tp['price']) }} đ</div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="text-center" style="width: 24%;">
                                                    <div class="fw-semibold text-dark fs-12">{{ $tp['total_sold'] }} {{ $isEn ? 'pcs' : 'cái' }}</div>
                                                    <div class="progress mt-1 mx-auto" style="height: 4px; max-width: 90px;">
                                                        <div class="progress-bar bg-primary" role="progressbar"
                                                            style="width: {{ $tp['percentage'] }}%" aria-valuenow="{{ $tp['percentage'] }}" aria-valuemin="0" aria-valuemax="100"></div>
                                                    </div>
                                                </td>
                                                <td class="text-end pe-3 fw-bold text-dark" style="width: 28%;">
                                                    {{ number_format($tp['total_revenue']) }} đ
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-4 text-muted">
                                <i class="ri-inbox-line fs-24 d-block mb-1"></i>
                                <span class="fs-12">{{ $isEn ? 'No product sales recorded yet.' : 'Chưa có dữ liệu sản phẩm bán ra.' }}</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Đơn hàng gần đây -->
            <div class="col-lg-6">
                <div class="card dashboard-card h-100 mb-0">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h5 class="card-title mb-0 text-dark fs-13 fw-bold">
                            <i class="ri-history-line text-primary me-1.5 fs-15"></i> {{ $isEn ? 'Recent Incoming Orders' : 'Đơn hàng mới phát sinh' }}
                        </h5>
                        <a href="{{ route('admin.order.index') }}" class="fs-12 text-primary text-decoration-none fw-medium">
                            {{ $isEn ? 'View All' : 'Xem tất cả' }} <i class="ri-arrow-right-s-line align-middle"></i>
                        </a>
                    </div>
                    <div class="card-body p-0">
                        @if(!empty($recent_orders))
                            <div class="table-responsive">
                                <table class="table table-dashboard table-hover align-middle">
                                    <thead>
                                        <tr>
                                            <th style="width: 42%;" class="ps-3 text-start">{{ $isEn ? 'Customer' : 'Khách hàng' }}</th>
                                            <th style="width: 22%;" class="text-center">{{ $isEn ? 'Time' : 'Thời gian' }}</th>
                                            <th style="width: 20%;" class="text-end">{{ $isEn ? 'Total' : 'Tổng tiền' }}</th>
                                            <th style="width: 16%;" class="text-center pe-3">{{ $isEn ? 'Status' : 'Trạng thái' }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($recent_orders as $ro)
                                            <tr>
                                                <td class="ps-3 text-start" style="width: 42%;">
                                                    <div class="fw-semibold text-dark text-truncate" style="max-width: 170px;" title="{{ $ro['customer_name'] }}">{{ $ro['customer_name'] }}</div>
                                                    <div class="text-muted fs-11">{{ $ro['phone'] }}</div>
                                                </td>
                                                <td class="text-center text-muted fs-11" style="width: 22%;">
                                                    {{ $ro['created_at'] }}
                                                </td>
                                                <td class="text-end fw-semibold text-dark" style="width: 20%;">
                                                    {{ number_format($ro['total']) }} đ
                                                </td>
                                                <td class="text-center pe-3" style="width: 16%;">
                                                    @if($ro['status'] === 1)
                                                        <span class="badge bg-success-subtle text-success px-2 py-0.5 fs-11 fw-medium">{{ $isEn ? 'Completed' : 'Hoàn thành' }}</span>
                                                    @elseif($ro['status'] === 2)
                                                        <span class="badge bg-danger-subtle text-danger px-2 py-0.5 fs-11 fw-medium">{{ $isEn ? 'Cancelled' : 'Đã hủy' }}</span>
                                                    @else
                                                        <span class="badge bg-warning-subtle text-warning px-2 py-0.5 fs-11 fw-medium">{{ $isEn ? 'Pending' : 'Chờ xử lý' }}</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-4 text-muted">
                                <i class="ri-inbox-line fs-24 d-block mb-1"></i>
                                <span class="fs-12">{{ $isEn ? 'No orders recorded yet.' : 'Chưa có đơn hàng nào được ghi nhận.' }}</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- 6. THỐNG KÊ LƯỢT TRUY CẬP HỆ THỐNG -->
        <div class="row">
            <div class="col-12">
                <div class="card dashboard-card mb-0">
                    <div class="card-header d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <div class="flex-grow-1">
                            <h5 class="card-title mb-0 fs-14 fw-bold text-dark">
                                <i class="ri-bar-chart-grouped-line text-primary me-1.5 fs-15"></i>Thống kê lượt truy cập website
                            </h5>
                            <p class="text-muted fs-11 mb-0">Theo dõi lượt người dùng truy cập theo thời gian thực</p>
                        </div>
                        <div class="flex-shrink-0 d-flex gap-2 align-items-center">
                            <select id="filterMonth" class="form-select form-select-sm rounded-2" style="width: 115px; height: 34px; font-size: 12px;">
                                @for ($m = 1; $m <= 12; $m++)
                                    <option value="{{ $m }}" {{ $m == date('n') ? 'selected' : '' }}>Tháng {{ $m }}</option>
                                @endfor
                            </select>
                            <select id="filterYear" class="form-select form-select-sm rounded-2" style="width: 95px; height: 34px; font-size: 12px;">
                                @for ($y = date('Y'); $y >= date('Y') - 3; $y--)
                                    <option value="{{ $y }}" {{ $y == date('Y') ? 'selected' : '' }}>{{ $y }}</option>
                                @endfor
                            </select>
                        </div>
                    </div>
                    <div class="card-body p-3">
                        <div class="row text-center mb-3 bg-light p-2.5 rounded-2 g-2">
                            <div class="col-md-4 border-end border-light">
                                <span class="text-muted fs-11 text-uppercase fw-semibold">Lượt truy cập tháng này</span>
                                <h4 class="fs-16 fw-bold text-primary mb-0 mt-0.5" id="monthViewsDisplay">{{ number_format($month_views) }}</h4>
                            </div>
                            <div class="col-md-4 border-end border-light">
                                <span class="text-muted fs-11 text-uppercase fw-semibold">Lượt truy cập tháng trước</span>
                                <h4 class="fs-16 fw-bold text-secondary mb-0 mt-0.5">{{ number_format($last_month_views) }}</h4>
                            </div>
                            <div class="col-md-4">
                                <span class="text-muted fs-11 text-uppercase fw-semibold">Lượt truy cập tuần này</span>
                                <h4 class="fs-16 fw-bold text-success mb-0 mt-0.5">{{ number_format($week_views) }}</h4>
                            </div>
                        </div>
                        <div id="analyticsApexChart" style="min-height: 320px;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('admin/libs/apexcharts/apexcharts.min.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // ==========================================
    // 1. BIỂU ĐỒ DOANH THU & ĐƠN HÀNG (APEXCHARTS)
    // ==========================================
    const initialRevenueData = @json($revenueChartData);
    let revDates = initialRevenueData.map(item => item.date);
    let revAmounts = initialRevenueData.map(item => item.revenue);
    let revOrders = initialRevenueData.map(item => item.orders);

    const revenueOptions = {
        series: [
            {
                name: '{{ $isEn ? "Revenue (VND)" : "Doanh thu (VNĐ)" }}',
                type: 'area',
                data: revAmounts
            },
            {
                name: '{{ $isEn ? "Orders" : "Số đơn hàng" }}',
                type: 'column',
                data: revOrders
            }
        ],
        chart: {
            height: 350,
            type: 'line',
            stacked: false,
            toolbar: {
                show: true,
                tools: {
                    download: true,
                    selection: false,
                    zoom: false,
                    zoomin: false,
                    zoomout: false,
                    pan: false,
                    reset: false
                }
            },
            animations: {
                enabled: true,
                easing: 'easeinout',
                speed: 600
            },
            fontFamily: 'inherit'
        },
        stroke: {
            width: [2.5, 0],
            curve: 'smooth'
        },
        plotOptions: {
            bar: {
                columnWidth: '32%',
                borderRadius: 4
            }
        },
        colors: ['#2563eb', '#f59e0b'],
        fill: {
            type: ['gradient', 'solid'],
            gradient: {
                shade: 'light',
                type: 'vertical',
                shadeIntensity: 0.25,
                gradientToColors: undefined,
                inverseColors: false,
                opacityFrom: 0.35,
                opacityTo: 0.05,
                stops: [0, 90, 100]
            }
        },
        labels: revDates,
        markers: {
            size: [4, 0],
            strokeWidth: 2,
            hover: {
                size: 6
            }
        },
        xaxis: {
            type: 'category',
            categories: revDates,
            axisBorder: { show: false },
            axisTicks: { show: false },
            labels: {
                style: {
                    colors: '#64748b',
                    fontSize: '11px',
                    fontFamily: 'inherit'
                }
            }
        },
        yaxis: [
            {
                title: {
                    text: 'Doanh thu (VNĐ)',
                    style: {
                        color: '#2563eb',
                        fontSize: '11.5px',
                        fontWeight: 500,
                        fontFamily: 'inherit'
                    }
                },
                labels: {
                    formatter: function(val) {
                        if (val >= 1000000) return (val / 1000000).toFixed(1) + ' tr';
                        if (val >= 1000) return (val / 1000).toFixed(0) + ' k';
                        return new Intl.NumberFormat('vi-VN').format(val);
                    },
                    style: {
                        colors: '#64748b',
                        fontSize: '11px',
                        fontFamily: 'inherit'
                    }
                },
                min: 0
            },
            {
                opposite: true,
                title: {
                    text: 'Số đơn hàng',
                    style: {
                        color: '#f59e0b',
                        fontSize: '11.5px',
                        fontWeight: 500,
                        fontFamily: 'inherit'
                    }
                },
                labels: {
                    formatter: function(val) {
                        return Math.floor(val);
                    },
                    style: {
                        colors: '#64748b',
                        fontSize: '11px',
                        fontFamily: 'inherit'
                    }
                },
                min: 0
            }
        ],
        grid: {
            borderColor: '#f1f5f9',
            strokeDashArray: 4
        },
        legend: {
            position: 'top',
            horizontalAlign: 'right',
            offsetY: -5,
            fontSize: '12px',
            fontFamily: 'inherit'
        },
        tooltip: {
            shared: true,
            intersect: false,
            theme: 'light',
            style: {
                fontFamily: 'inherit',
                fontSize: '12px'
            },
            y: {
                formatter: function(y, opt) {
                    if (opt.seriesIndex === 0) {
                        return new Intl.NumberFormat('vi-VN').format(y) + ' đ';
                    }
                    return Math.floor(y) + ' đơn';
                }
            }
        }
    };

    let revenueChart;
    const revEl = document.querySelector("#revenueApexChart");
    if (revEl && typeof ApexCharts !== 'undefined') {
        revenueChart = new ApexCharts(revEl, revenueOptions);
        revenueChart.render();
    }

    // Filter Buttons Handling
    const filterButtons = document.querySelectorAll('.revenue-filter-btn');
    filterButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            filterButtons.forEach(b => b.classList.remove('active'));
            this.classList.add('active');

            const period = this.getAttribute('data-period');
            fetch(`{{ route('admin.analytics.index') }}?chart_type=revenue&period=${period}&ajax=1`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(res => res.json())
            .then(data => {
                if (revenueChart && data.chartData) {
                    const newDates = data.chartData.map(i => i.date);
                    const newRev = data.chartData.map(i => i.revenue);
                    const newOrders = data.chartData.map(i => i.orders);

                    revenueChart.updateOptions({
                        xaxis: { categories: newDates },
                        labels: newDates
                    });
                    revenueChart.updateSeries([
                        { name: '{{ $isEn ? "Revenue (VND)" : "Doanh thu (VNĐ)" }}', data: newRev },
                        { name: '{{ $isEn ? "Orders" : "Số đơn hàng" }}', data: newOrders }
                    ]);
                }
            })
            .catch(err => console.error('Revenue chart update error:', err));
        });
    });

    // ==========================================
    // 2. BIỂU ĐỒ LƯỢT TRUY CẬP WEBSITE
    // ==========================================
    const initialVisitData = @json(json_decode($chartData, true));
    let visitCategories = initialVisitData.map(item => item.date);
    let visitSeriesData = initialVisitData.map(item => item.count);

    const visitOptions = {
        series: [{
            name: 'Lượt truy cập',
            data: visitSeriesData
        }],
        chart: {
            type: 'area',
            height: 320,
            toolbar: { show: false },
            animations: { enabled: true, easing: 'easeinout', speed: 600 },
            fontFamily: 'inherit'
        },
        colors: ['#4338ca'],
        dataLabels: { enabled: false },
        stroke: { curve: 'smooth', width: 2 },
        fill: {
            type: 'gradient',
            gradient: {
                shadeIntensity: 1,
                opacityFrom: 0.35,
                opacityTo: 0.05,
                stops: [0, 90, 100]
            }
        },
        grid: { borderColor: '#f1f5f9', strokeDashArray: 4 },
        xaxis: {
            categories: visitCategories,
            labels: {
                style: {
                    colors: '#64748b',
                    fontSize: '11px',
                    fontFamily: 'inherit'
                }
            }
        },
        yaxis: {
            title: {
                text: 'Số lượt',
                style: {
                    color: '#64748b',
                    fontSize: '11.5px',
                    fontFamily: 'inherit'
                }
            },
            labels: {
                formatter: val => Math.floor(val),
                style: {
                    colors: '#64748b',
                    fontSize: '11px',
                    fontFamily: 'inherit'
                }
            },
            min: 0
        },
        tooltip: {
            theme: 'light',
            style: {
                fontFamily: 'inherit',
                fontSize: '12px'
            },
            y: { formatter: val => val + " lượt truy cập" }
        }
    };

    let visitChart;
    const visitEl = document.querySelector("#analyticsApexChart");
    if (visitEl && typeof ApexCharts !== 'undefined') {
        visitChart = new ApexCharts(visitEl, visitOptions);
        visitChart.render();
    }

    function updateVisitChart() {
        const month = document.getElementById('filterMonth').value;
        const year = document.getElementById('filterYear').value;

        fetch(`{{ route('admin.analytics.index') }}?month=${month}&year=${year}&ajax=1`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (visitChart && data.chartData) {
                const newDates = data.chartData.map(i => i.date);
                const newCounts = data.chartData.map(i => i.count);

                visitChart.updateOptions({ xaxis: { categories: newDates } });
                visitChart.updateSeries([{ name: 'Lượt truy cập', data: newCounts }]);

                if (document.getElementById('monthViewsDisplay')) {
                    document.getElementById('monthViewsDisplay').innerText = new Intl.NumberFormat().format(data.monthViews);
                }
            }
        })
        .catch(err => console.error('Visit chart update error:', err));
    }

    const filterMonthEl = document.getElementById('filterMonth');
    const filterYearEl = document.getElementById('filterYear');
    if (filterMonthEl) filterMonthEl.addEventListener('change', updateVisitChart);
    if (filterYearEl) filterYearEl.addEventListener('change', updateVisitChart);
});
</script>
@endpush
