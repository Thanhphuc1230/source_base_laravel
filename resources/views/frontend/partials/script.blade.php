<!-- Custom scripts or footer scripts (Non-blocking deferred execution) -->
<script defer src="{{ asset('frontend/js/image-flip.js') }}?v={{ file_exists(public_path('frontend/js/image-flip.js')) ? filemtime(public_path('frontend/js/image-flip.js')) : '1.0' }}"></script>
<script defer src="{{ asset('frontend/js/scroll-animate.js') }}?v={{ file_exists(public_path('frontend/js/scroll-animate.js')) ? filemtime(public_path('frontend/js/scroll-animate.js')) : '1.0' }}"></script>
@stack('scripts')
