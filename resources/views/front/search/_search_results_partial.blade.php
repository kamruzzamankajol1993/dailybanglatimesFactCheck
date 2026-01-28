@forelse($results as $item)
    @php
        // ১. ডাটা প্রসেসিং (Post নাকি User Request থেকে এসেছে?)
        $title = 'শিরোনাম পাওয়া যায়নি';
        $image = 'https://placehold.co/600x400/png?text=No+Image';
        $desc = '';
        $date = $item->created_at->format('d M, Y');
        $categoryName = 'সাধারণ';
        $link = '#';

        // যদি এডমিন পোস্ট হয়
        if ($item->post) {
            $title = $item->post->title;
            $image = $item->post->image ? $front_admin_url.$item->post->image : $image;
            $desc = Str::limit(strip_tags($item->post->content), 180);
            $link = route('front.news.details', $item->post->slug);
            if($item->post->categories->first()) {
                $categoryName = $item->post->categories->first()->name;
            }
        } 
        // যদি ইউজার রিকোয়েস্ট হয়
        elseif ($item->factCheckRequest) {
            $title = $item->factCheckRequest->title ?? $item->factCheckRequest->link;
            $image = $item->factCheckRequest->image ? $front_admin_url.$item->factCheckRequest->image : $image;
            $desc = Str::limit($item->factCheckRequest->description, 180);
            if($item->factCheckRequest->category) {
                $categoryName = $item->factCheckRequest->category->name;
            }
        }

        // ২. ভার্ডিক্ট ব্যাজ কালার লজিক
        $verdict = $item->verdict;
        $badgeClass = 'bg-unverified';
        $verdictText = $verdict;

        if (stripos($verdict, 'True') !== false || stripos($verdict, 'Likely True') !== false) {
            $badgeClass = 'bg-true'; $verdictText = 'সত্য';
        } elseif (stripos($verdict, 'False') !== false || stripos($verdict, 'Fake') !== false) {
            $badgeClass = 'bg-fake'; $verdictText = 'মিথ্যা';
        } elseif (stripos($verdict, 'Misleading') !== false) {
            $badgeClass = 'bg-warning text-dark'; $verdictText = 'বিভ্রান্তিকর';
        }
        // --- নতুন: Altered এর জন্য ---
        elseif (stripos($verdict, 'Altered') !== false || stripos($verdict, 'Edited') !== false) {
            $badgeClass = 'bg-altered'; 
            $verdictText = 'বিকৃত (Altered)';
        }
    @endphp

    <div class="card news-card-horizontal">
        <div class="row g-0">
            <div class="col-md-5 position-relative">
                <div class="img-wrapper">
                    <img src="{{ $image }}" alt="{{ $title }}">
                    <span class="badge-verdict {{ $badgeClass }}">{{ $verdictText }}</span>
                </div>
            </div>
            <div class="col-md-7">
                <div class="card-body p-4">
                    <div class="card-meta">
                        <i class="far fa-clock me-1"></i> {{ $date }} &nbsp;|&nbsp; 
                        <i class="fas fa-folder me-1"></i> {{ $categoryName }}
                    </div>
                    <h3 class="card-title h4"><a href="{{ $link }}">{{ $title }}</a></h3>
                    <p class="card-text text-muted">{{ $desc }}</p>
                    <a href="{{ $link }}" class="btn btn-outline-dark btn-sm rounded-pill mt-2">বিস্তারিত পড়ুন <i class="fas fa-arrow-right ms-1"></i></a>
                </div>
            </div>
        </div>
    </div>
@empty
    <div class="alert alert-warning text-center py-5">
        <h4>দুঃখিত! কোনো তথ্য পাওয়া যায়নি।</h4>
        <p>আপনার অনুসন্ধানকৃত বিষয়টি আমাদের ডাটাবেসে নেই এবং Google API থেকেও কোনো ভেরিফায়েড তথ্য পাওয়া যায়নি।</p>
        <a href="{{ route('front.index') }}#upload" class="btn btn-danger btn-sm mt-2">আমাদের কাছে রিপোর্ট করুন</a>
    </div>
@endforelse

{{-- কাস্টম প্যাজিনেশন (আপনার ডিজাইনের সাথে মিল রেখে) --}}
@if ($results->hasPages())
<nav class="mt-5">
    <ul class="pagination justify-content-center">
        {{-- Previous Page Link --}}
        @if ($results->onFirstPage())
            <li class="page-item disabled"><a class="page-link" href="#" tabindex="-1"><i class="fas fa-chevron-left"></i></a></li>
        @else
            <li class="page-item"><a class="page-link" href="{{ $results->previousPageUrl() }}"><i class="fas fa-chevron-left"></i></a></li>
        @endif

        {{-- Pagination Elements --}}
        @foreach ($results->links()->elements as $element)
            @if (is_string($element))
                <li class="page-item disabled"><a class="page-link" href="#">{{ $element }}</a></li>
            @endif

            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $results->currentPage())
                        <li class="page-item active"><a class="page-link" href="#">{{ $page }}</a></li>
                    @else
                        <li class="page-item"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next Page Link --}}
        @if ($results->hasMorePages())
            <li class="page-item"><a class="page-link" href="{{ $results->nextPageUrl() }}"><i class="fas fa-chevron-right"></i></a></li>
        @else
            <li class="page-item disabled"><a class="page-link" href="#"><i class="fas fa-chevron-right"></i></a></li>
        @endif
    </ul>
</nav>
@endif