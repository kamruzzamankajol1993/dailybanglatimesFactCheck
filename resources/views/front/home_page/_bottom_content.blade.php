@include('front.home_page._partial.englishNew')
    
    @include('front.home_page._partial.internationalNews')

    @include('front.home_page._partial.categoryGrid')

    @include('front.home_page._partial.sports')

    @include('front.home_page._partial.lawAndExclusive')

    @include('front.home_page._partial.entertainment')

    {{-- ========================================================= --}}
    {{-- DYNAMIC ADVERTISEMENT: Home Middle Section                --}}
    {{-- ========================================================= --}}
    @if(isset($home_middle_ad))
    <section class="ad-section py-4 bg-light">
        <div class="container">
            <div class="d-flex justify-content-center">
                
                {{-- Type 1: Image --}}
                @if($home_middle_ad->type == 1 && !empty($home_middle_ad->image))
                    <a href="{{ $home_middle_ad->link ?? 'javascript:void(0)' }}" {{ !empty($home_middle_ad->link) ? 'target="_blank"' : '' }}>
                        <img src="{{ $front_admin_url }}public/{{ $home_middle_ad->image }}" 
                             class="img-fluid" 
                             alt="Advertisement"
                             style="max-width: 100%; height: auto;">
                    </a>
                
                {{-- Type 2: Script --}}
                @elseif($home_middle_ad->type == 2 && !empty($home_middle_ad->script))
                    {!! $home_middle_ad->script !!}
                @endif

            </div>
        </div>
    </section>
    @endif
    {{-- ========================================================= --}}

    @include('front.home_page._partial.artsAndFeature')
    @include('front.home_page._partial.lifestyle')
    @include('front.home_page._partial.moreCategories')
    @include('front.home_page._partial.sharadeshDistrict')
    @include('front.home_page._partial.mixedCategory')
    @include('front.home_page._partial.photoGallery')
    @include('front.home_page._partial.videoGallery')