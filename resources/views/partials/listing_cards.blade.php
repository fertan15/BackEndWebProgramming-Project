{{-- resources/views/partials/listing_cards.blade.php --}}

@forelse ($listings as $listing)
    <div class="pokemon-card-wrapper group">
        <a href="{{ route('card.detail', $listing->card->id) }}" class="block h-full">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-3 h-full transition-all duration-300 hover:shadow-xl hover:-translate-y-1 relative overflow-hidden">
                
                {{-- Card Image --}}
                <div class="relative aspect-[2.5/3.5] rounded-xl overflow-hidden mb-4 bg-gray-100">
                    <img 
                        src="{{ asset($listing->card->image_url) }}" 
                        alt="{{ $listing->card->name }}"
                        class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110"
                        loading="lazy"
                    >
                    <div class="absolute inset-0 opacity-0 group-hover:opacity-100 transition-opacity duration-500 pointer-events-none holo-sheen"></div>
                    
                    {{-- Time Badge --}}
                    <div class="absolute top-2 right-2 bg-black/60 backdrop-blur-md text-white text-[10px] font-bold px-2 py-1 rounded-full">
                        {{ $listing->created_at->diffForHumans(null, true, true) }}
                    </div>
                </div>

                <div class="px-1">
                    {{-- Title --}}
                    <h3 class="font-bold text-gray-900 text-lg leading-tight mb-1 group-hover:text-indigo-600 transition-colors">
                        {{ $listing->card->name }}
                    </h3>

                    {{-- Seller --}}
                    <p class="text-xs text-gray-500 mb-4 flex items-center gap-1">
                        <span>By</span>
                        <span class="font-medium text-gray-700">{{ $listing->seller->name }}</span>
                    </p>

                    {{-- Price --}}
                    <div class="flex items-center justify-between mt-auto border-t border-gray-50 pt-3">
                        <div class="flex flex-col">
                            <span class="text-[10px] text-gray-400 font-medium uppercase">Price</span>
                            <span class="text-xl font-extrabold text-indigo-600">
                                Rp {{ number_format($listing->price, 0, ',', '.') }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </div>
@empty
    <div class="col-span-full py-12 text-center text-gray-500">
        No active listings found.
    </div>
@endforelse