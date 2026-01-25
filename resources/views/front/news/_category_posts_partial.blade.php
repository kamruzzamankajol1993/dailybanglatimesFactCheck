@php
    // বাংলা তারিখ ও সংখ্যা কনভার্টার ফাংশন
    if (!function_exists('convertToBanglaDate')) {
        function convertToBanglaDate($date) {
            $eng_num = ['0','1','2','3','4','5','6','7','8','9'];
            $ban_num = ['০','১','২','৩','৪','৫','৬','৭','৮','৯'];
            
            $eng_month = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
            $ban_month = ['জানু', 'ফেব্রু', 'মার্চ', 'এপ্রিল', 'মে', 'জুন', 'জুলাই', 'আগস্ট', 'সেপ্টে', 'অক্টো', 'নভেম', 'ডিসেম'];

            $converted = str_replace($eng_num, $ban_num, $date);
            $converted = str_replace($eng_month, $ban_month, $converted);
            return $converted;
        }
    }
@endphp

<div class="row g-4">
    @forelse($posts as $post)
        <div class="col-md-4">
            <div class="card border-0 h-100 category-card shadow-sm">
                <div class="overflow-hidden mb-2 position-relative">
                    {{-- Image --}}
                    <a href="{{ route('front.news.details', $post->slug) }}">
                        <img  onerror="this.onerror=null;this.src='{{ $front_admin_url }}{{ $front_logo_name }}';" src="{{ $post->image ? $front_admin_url.$post->image : 'https://placehold.co/400x240/ddd/333?text=No+Image' }}" 
                             class="card-img-top rounded-0 zoom-img" 
                             alt="{{ $post->title }}">
                    </a>
                </div>
                <div class="card-body p-2">
                    <div class="d-flex mb-2">
                        {{-- Show Category Name --}}
                        <span class="badge bg-danger rounded-0 fw-normal py-1 px-2">
                            {{ $post->categories->first()->name ?? 'খবর' }}
                        </span>
                        
                        {{-- Bangla Date --}}
                        <span class="badge bg-dark rounded-0 py-1 px-2 ms-1">
                            <i class="far fa-clock"></i> 
                            {{ convertToBanglaDate($post->created_at->format('d M, Y')) }}
                        </span>
                    </div>

                    <h5 class="card-title fw-bold hover-red lh-base mb-2">
                        <a href="{{ route('front.news.details', $post->slug) }}" class="text-dark text-decoration-none">
                            {{ Str::limit($post->title, 60) }}
                        </a>
                    </h5>
                    
                    <p class="card-text text-secondary small text-justify">
                         @if($post->subtitle)
                            {{ Str::limit($post->subtitle, 100) }}
                         @else
                            {{ Str::limit(strip_tags($post->content), 100) }}
                         @endif
                    </p>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12 text-center py-5">
            <h4 class="text-muted">কোনো খবর পাওয়া যায়নি।</h4>
        </div>
    @endforelse
</div>

{{-- Custom Pagination Design --}}
@if ($posts->hasPages())
<div class="mt-5 d-flex justify-content-center custom-pagination-wrapper">
    <div class="custom-pagination">
        
        {{-- Previous Link --}}
        @if ($posts->onFirstPage())
            <span class="page-link disabled"><i class="fas fa-angle-left"></i></span>
        @else
            <a href="{{ $posts->previousPageUrl() }}" class="page-link" rel="prev"><i class="fas fa-angle-left"></i></a>
        @endif

        {{-- Pagination Elements --}}
        @foreach ($posts->getUrlRange(max(1, $posts->currentPage() - 2), min($posts->lastPage(), $posts->currentPage() + 2)) as $page => $url)
            @if ($page == $posts->currentPage())
                <span class="page-link active">{{ convertToBanglaDate($page) }}</span>
            @else
                <a href="{{ $url }}" class="page-link">{{ convertToBanglaDate($page) }}</a>
            @endif
        @endforeach

        {{-- Next Link --}}
        @if ($posts->hasMorePages())
            <a href="{{ $posts->nextPageUrl() }}" class="page-link" rel="next"><i class="fas fa-angle-right"></i></a>
        @else
            <span class="page-link disabled"><i class="fas fa-angle-right"></i></span>
        @endif
    </div>
</div>
@endif