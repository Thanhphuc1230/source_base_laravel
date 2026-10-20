@extends('frontend.master')
@section('module', 'Liên hệ - ' . $web->meta_name)
@section('keywords', $web->meta_keyword)
@section('description', $web->meta_description)
@section('images', $web->logo)

@section('content')
    <div class="bg-gray-100 py-6 border-b border-gray-200/50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-2xs text-gray-500 flex items-center space-x-2">
            <a href="{{ route('web.home') }}" class="hover:text-emerald-950">Trang chủ</a>
            <i class="fa-solid fa-chevron-right text-3xs"></i>
            <span class="text-gray-700 font-semibold">Liên hệ</span>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="text-center max-w-xl mx-auto mb-12 space-y-2">
            <span class="text-gold-650 text-xs font-extrabold uppercase tracking-widest">Liên hệ</span>
            <h1 class="text-3xl font-heading font-black text-emerald-950">{!! lang($web, 'contact_title') ?? 'Kết Nối Với Chúng Tôi' !!}</h1>
            <div class="text-xs text-gray-500">{!! lang($web, 'contact_desc') ?? 'Gửi tin nhắn hoặc yêu cầu của bạn, chúng tôi luôn sẵn lòng lắng nghe và hỗ trợ.' !!}</div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Contact Info Sidebar (Left) -->
            <div class="space-y-6">
                <div class="bg-white rounded-2xl border border-gray-100 p-6 shadow-sm space-y-6">
                    <h3 class="font-heading font-bold text-sm text-emerald-950 border-b border-gray-150 pb-3 flex items-center">
                        <i class="fa-solid fa-circle-info text-emerald-800 mr-2"></i>
                        <span>Thông tin liên hệ</span>
                    </h3>

                    <div class="space-y-4 text-xs">
                        <div class="flex items-start space-x-3">
                            <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-950 flex items-center justify-center flex-shrink-0 mt-0.5">
                                <i class="fa-solid fa-location-dot"></i>
                            </div>
                            <div>
                                <strong class="text-emerald-950 block">Địa chỉ trụ sở</strong>
                                <span class="text-gray-500 leading-normal block">{{ $web->address ?? '' }}</span>
                            </div>
                        </div>

                        <div class="flex items-start space-x-3">
                            <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-950 flex items-center justify-center flex-shrink-0 mt-0.5">
                                <i class="fa-solid fa-phone"></i>
                            </div>
                            <div>
                                <strong class="text-emerald-950 block">Tổng đài hỗ trợ</strong>
                                <span class="text-gray-500 block">Hotline: {{ $web->phone ?? '' }}</span>
                            </div>
                        </div>

                        <div class="flex items-start space-x-3">
                            <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-950 flex items-center justify-center flex-shrink-0 mt-0.5">
                                <i class="fa-solid fa-envelope"></i>
                            </div>
                            <div>
                                <strong class="text-emerald-950 block">Hộp thư điện tử</strong>
                                <span class="text-gray-500 block">{{ $web->email ?? '' }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Google Map Iframe Placeholder / Dynamic Map -->
                @if(!empty($web->map))
                <div class="rounded-2xl border border-gray-100 overflow-hidden shadow-sm h-64 bg-gray-100">
                    @if(Str::startsWith(trim($web->map), '<iframe'))
                        {!! $web->map !!}
                    @else
                        <iframe src="{{ $web->map }}" width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                    @endif
                </div>
                @endif
            </div>

            <!-- Contact Message Form (Right, col-span-2) -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-2xl border border-gray-100 p-6 sm:p-8 shadow-sm">
                    <h3 class="font-heading font-bold text-sm text-emerald-950 border-b border-gray-150 pb-3 mb-6 flex items-center">
                        <i class="fa-solid fa-paper-plane text-emerald-800 mr-2"></i>
                        <span>Gửi yêu cầu hỗ trợ</span>
                    </h3>

                    <form action="{{ route('web.postContact') }}" method="POST" class="space-y-4">
                        @csrf

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-3xs font-extrabold text-gray-500 uppercase mb-1">Họ và tên <span class="text-red-500">*</span></label>
                                <input type="text" name="fullname" value="{{ old('fullname') }}" required class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-xs focus:outline-none focus:border-emerald-600 placeholder-gray-400 @error('fullname') is-invalid @enderror" placeholder="Nguyễn Văn A">
                                @error('fullname')
                                    <span class="text-red-550 text-3xs mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-3xs font-extrabold text-gray-500 uppercase mb-1">Số điện thoại <span class="text-red-500">*</span></label>
                                <input type="tel" name="phone" value="{{ old('phone') }}" required class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-xs focus:outline-none focus:border-emerald-600 placeholder-gray-400 @error('phone') is-invalid @enderror" placeholder="09xxxxxxxx">
                                @error('phone')
                                    <span class="text-red-550 text-3xs mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div>
                            <label class="block text-3xs font-extrabold text-gray-500 uppercase mb-1">Địa chỉ Email <span class="text-red-500">*</span></label>
                            <input type="email" name="email" value="{{ old('email') }}" required class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-xs focus:outline-none focus:border-emerald-600 placeholder-gray-400 @error('email') is-invalid @enderror" placeholder="email@example.com">
                            @error('email')
                                <span class="text-red-550 text-3xs mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-3xs font-extrabold text-slate-500 uppercase mb-1">Tiêu đề liên hệ <span class="text-red-500">*</span></label>
                            <input type="text" name="subject" value="{{ old('subject') }}" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-xs focus:outline-none focus:border-gold-500 placeholder-slate-400 @error('subject') is-invalid @enderror" placeholder="Ví dụ: Tư vấn sản phẩm, đặt hàng, hỗ trợ bảo hành...">
                            @error('subject')
                                <span class="text-red-550 text-3xs mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-3xs font-extrabold text-gray-500 uppercase mb-1">Nội dung yêu cầu <span class="text-red-500">*</span></label>
                            <textarea name="message" rows="5" required class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-xs focus:outline-none focus:border-emerald-600 placeholder-gray-400 @error('message') is-invalid @enderror" placeholder="Vui lòng nhập nội dung chi tiết...">{{ old('message') }}</textarea>
                            @error('message')
                                <span class="text-red-550 text-3xs mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="pt-2">
                            <button type="submit" class="w-full sm:w-auto bg-emerald-950 hover:bg-emerald-900 text-white font-bold uppercase tracking-wider text-xs px-8 py-3.5 rounded-xl transition-all duration-300 shadow-md hover:scale-102 flex items-center justify-center space-x-2">
                                <i class="fa-solid fa-paper-plane"></i>
                                <span>Gửi tin nhắn</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
