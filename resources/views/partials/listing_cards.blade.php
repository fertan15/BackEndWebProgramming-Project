@forelse ($listings as $listing)
    <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6">
        <div class="pokemon-card-wrapper group h-100">
            <a href="{{ route('card.detail', $listing->card->id) }}" class="text-decoration-none">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-3 transition-all duration-300 hover:shadow-lg h-100" style="transform: translateY(0); transition: all 0.3s ease;">
                
                <div class="relative rounded-lg overflow-hidden mb-3 bg-gray-50 shadow-inner" style="aspect-ratio: 2.5/3.5;">
                    <img 
                        src="{{ asset($listing->card->image_url) }}" 
                        alt="{{ $listing->card->name }}"
                        class="w-100 h-100 object-contain p-2 transition-transform duration-500"
                        loading="lazy"
                        style="object-fit: contain;"
                        onmouseover="this.style.transform='scale(1.05)'"
                        onmouseout="this.style.transform='scale(1)'"
                    >
                    <div class="position-absolute top-0 start-0 end-0 bottom-0 opacity-0 hover:opacity-100 transition-opacity duration-500 pointer-events-none holo-sheen"></div>
                </div>

                <div class="d-flex flex-column gap-1">
                    <h3 class="fw-bold text-dark text-truncate mb-0" style="font-size: 0.95rem;">
                        {{ $listing->card->name }}
                    </h3>
                    <p class="text-muted mb-2" style="font-size: 0.75rem;">
                        Listed by <span class="text-primary fw-medium">{{ $listing->seller->name }}</span>
                    </p>
                    
                    <div class="pt-2 border-top border-light d-flex justify-content-between align-items-center">
                        <span class="text-muted text-uppercase fw-semibold" style="font-size: 0.7rem;">Price</span>
                        <span class="fw-bold text-primary" style="font-size: 1.1rem;">
                            ${{ number_format($listing->price, 2) }}
                        </span>
                    </div>
                </div>
            </div>
            </a>
        </div>
    </div>
@empty
    <div class="col-12 py-5 text-center">
        <p class="text-muted fw-medium">No active listings found.</p>
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