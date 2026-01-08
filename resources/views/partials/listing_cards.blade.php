@forelse ($listings as $listing)
    <div class="pokemon-card-wrapper group h-full mx-auto w-full" style="max-width: 320px;">
        <a href="{{ route('card.detail', $listing->card->id) }}" class="text-decoration-none">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 transition-all duration-300 hover:shadow-lg hover:-translate-y-2 h-full">
                
                <div class="relative aspect-[2.5/3.5] rounded-lg overflow-hidden mb-4 bg-gray-50 shadow-inner">
                    <img 
                        src="{{ asset($listing->card->image_url) }}" 
                        alt="{{ $listing->card->name }}"
                        class="w-full h-full object-contain p-2 transition-transform duration-500 group-hover:scale-105"
                        loading="lazy"
                    >
                    <div class="absolute inset-0 opacity-0 group-hover:opacity-100 transition-opacity duration-500 pointer-events-none holo-sheen"></div>
                </div>

                <div class="space-y-1">
                    <h3 class="font-bold text-gray-900 text-base truncate mb-0">
                        {{ $listing->card->name }}
                    </h3>
                    <p class="text-xs text-gray-400 mb-3">
                        Listed by <span class="text-indigo-600 fw-medium">{{ $listing->seller->name }}</span>
                    </p>
                    
                    <div class="pt-3 border-t border-gray-100 d-flex justify-content-between align-items-center">
                        <span class="text-xs text-gray-500 uppercase font-semibold">Price</span>
                        <span class="text-lg font-black text-indigo-600">
                            ${{ number_format($listing->price, 2) }}
                        </span>
                    </div>
                </div>
            </div>
        </a>
    </div>
@empty
    <div class="col-span-full py-20 text-center">
        <p class="text-gray-400 font-medium">No active listings found.</p>
    </div>
@endforelse

<style>
    .holo-sheen {
    background: linear-gradient(
        105deg,
        transparent 20%,
        rgba(255, 255, 255, 0.4) 45%,
        rgba(100, 200, 255, 0.2) 50%,
        rgba(255, 255, 255, 0.4) 55%,
        transparent 80%
    );
    z-index: 10;
    filter: brightness(1.2) contrast(1.1);
    mix-blend-mode: overlay;
    }
</style>