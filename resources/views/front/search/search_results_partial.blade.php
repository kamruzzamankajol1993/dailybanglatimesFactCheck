@php
    // Bangla Date Converter Helper
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

<div class="d-flex flex-column gap-4">
    @forelse($results as $item)
        @php
            // ডাটা প্রসেসিং (Post vs Request)
            $title = '';
            $link = '#';
            $image = 'https://placehold.co/180x110/ddd/333?text=No+Image';
            $date = $item->created_at->format('d M, Y');
            $catName = 'জেনারেল';
            $desc = '';

            // কন্ডিশন ১: অ্যাডমিন পোস্ট
            if ($item->post) {
                $title = $item->post->title;
                $link = route('front.news.details', $item->post->slug);
                
                if($item->post->image) {
                    $image = $front_admin_url . $item->post->image;
                }
                
                $desc = $item->post->subtitle ?? strip_tags($item->post->content);
                if($item->post->categories->first()) {
                    $catName = $item->post->categories->first()->name;
                }
            } 
            // কন্ডিশন ২: ইউজার রিকোয়েস্ট
            elseif ($item->factCheckRequest) {
                $title = $item->factCheckRequest->title ?? 'User Request';
                $link = route('front.news.details', $item->factCheckRequest->id);
                
                if($item->factCheckRequest->image) {
                     // ইমেজ পাথ হ্যান্ডলিং (আপনার কনফিগারেশন অনুযায়ী এডজাস্ট করুন)
                     // যদি এডমিন প্যানেলের মতো পাথ সেভ হয়:
                     $image = $front_admin_url . $item->factCheckRequest->image;
                     // অথবা যদি সরাসরি পাবলিক ফোল্ডারে থাকে: asset('uploads/requests/...')
                }

                $desc = $item->factCheckRequest->description;
                if($item->factCheckRequest->category) {
                    $catName = $item->factCheckRequest->category->name;
                }
            }
        @endphp

        <div class="search-item d-flex align-items-start border-bottom pb-3">
            <div class="flex-shrink-0 me-3">
                <a href="{{ $link }}">
                    <img src="{{ $image }}" 
                         class="rounded-1 object-fit-cover" 
                         width="180" height="110" alt="{{ $title }}">
                </a>
            </div>
            <div class="flex-grow-1">
                <a href="{{ $link }}" class="text-decoration-none text-dark">
                    <h5 class="fw-bold hover-red mb-1">
                        {{-- Search Keyword Highlight --}}
                        @if(!empty($query))
                            {!! str_ireplace($query, '<span class="bg-warning text-dark px-1">'.$query.'</span>', $title) !!}
                        @else
                            {{ $title }}
                        @endif
                    </h5>
                </a>
                <div class="search-meta mb-2">
                    <span class="text-danger fw-bold">
                        {{ $catName }}
                    </span>
                    <span class="mx-1">•</span>
                    <i class="far fa-clock small"></i> 
                    {{ convertToBanglaDate($date) }}
                </div>
                <p class="text-secondary small mb-0 text-truncate-2">
                    {{ Str::limit($desc, 150) }}
                </p>
            </div>
        </div>
    @empty
        <div class="text-center py-5">
            <h4 class="text-muted">দুঃখিত! আপনার অনুসন্ধানের সাথে কোনো সংবাদ মেলেনি।</h4>
            <p class="text-secondary">বানান সঠিক আছে কিনা যাচাই করুন অথবা ভিন্ন শব্দ ব্যবহার করুন।</p>
        </div>
    @endforelse
</div>

{{-- Pagination --}}
@if ($results->hasPages())
<div class="mt-5 d-flex justify-content-center custom-pagination-wrapper">
    <div class="custom-pagination">
        
        {{-- Previous Link --}}
        @if ($results->onFirstPage())
            <span class="page-link disabled"><i class="fas fa-angle-left"></i></span>
        @else
            <a href="{{ $results->previousPageUrl() }}" class="page-link" rel="prev"><i class="fas fa-angle-left"></i></a>
        @endif

        {{-- Pagination Elements --}}
        @foreach ($results->getUrlRange(max(1, $results->currentPage() - 2), min($results->lastPage(), $results->currentPage() + 2)) as $page => $url)
            @if ($page == $results->currentPage())
                <span class="page-link active">{{ convertToBanglaDate($page) }}</span>
            @else
                <a href="{{ $url }}" class="page-link">{{ convertToBanglaDate($page) }}</a>
            @endif
        @endforeach

        {{-- Next Link --}}
        @if ($results->hasMorePages())
            <a href="{{ $results->nextPageUrl() }}" class="page-link" rel="next"><i class="fas fa-angle-right"></i></a>
        @else
            <span class="page-link disabled"><i class="fas fa-angle-right"></i></span>
        @endif
    </div>
</div>
@endif