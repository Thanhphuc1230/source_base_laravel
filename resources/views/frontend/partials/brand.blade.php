@php
    $partnerList = $partners ?? $brands ?? collect();
@endphp
@if(isset($partnerList) && $partnerList->isNotEmpty())
    <section class="max-w-7xl mx-auto px-4 py-12 scroll-anim fade-up">
        <div class="text-center max-w-xl mx-auto mb-8">
            <span class="text-gold-600 text-xs font-extrabold uppercase tracking-widest">Hợp Tác Phát Triển</span>
            <h2 class="text-2xl md:text-3xl font-heading font-extrabold text-slate-900 mt-1 uppercase tracking-wider">
                Đối Tác Chiến Lược
            </h2>
            <div class="gold-divider"></div>
        </div>

        <!-- Single Row Flex Layout for all Logos -->
        <div class="flex items-center justify-center gap-4 sm:gap-6 lg:gap-8 flex-wrap py-2">
            @foreach($partnerList as $partner)
                <div class="group flex flex-col items-center justify-center bg-white border border-slate-200/80 hover:border-gold-500/80 px-7 py-4 rounded-xl transition-all duration-300 shadow-xs hover:shadow-md hover:-translate-y-1">
                    <span class="text-sm font-heading font-bold text-slate-800 group-hover:text-gold-600 transition-colors text-center uppercase tracking-wider">
                        {{ $partner->name_vn }}
                    </span>
                </div>
            @endforeach
        </div>
    </section>
@endif
