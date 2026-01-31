@extends('front.master.master')

@php
    // --- ১. ডাটা প্রসেসিং ও নর্মালাইজেশন ---
    
    // টাইটেল
    $title = $post->title ?? ($post->link ?? 'News Details');
    
    // কন্টেন্ট (Post হলে content, Request হলে description)
    $content = $isUserRequest ? ($post->description ?? '') : ($post->content ?? '');
    
    // ২. ইমেজ পাথ লজিক (New Update)
    $imageUrl = 'https://placehold.co/800x500/eee/333?text=No+Image';
    if ($post->image) {
        // যদি পাথের মধ্যে 'uploads/requests' থাকে তবে fact_check_url ব্যবহার হবে
        if ($isUserRequest || str_contains($post->image, 'uploads/requests')) {
             $imageUrl = $front_admin_url . $post->image;
        } else {
          
             $imageUrl = $front_fact_check_url . $post->image;
        }
    }

    // ক্যাটাগরি এবং স্লাগ
    $categoryName = 'News';
    $categoryLink = '#';
    if ($isUserRequest && $post->category) {
        $categoryName = $post->category->name;
        $categoryLink = route('front.category.news', $post->category->slug ?? '#');
    } elseif (!$isUserRequest && $post->categories->count() > 0) {
        $categoryName = $post->categories->first()->name;
        $categoryLink = route('front.category.news', $post->categories->first()->slug);
    }

    // অথর
    $authorName = $isUserRequest ? 'User Submitted' : ($post->author->name ?? $front_ins_name);
    
    // তারিখ
    $date = $post->created_at->format('d F, Y'); 
    
    // ভিউ কাউন্ট
    $viewCount = $post->view_count ?? 0;

    // --- ৩. ভার্ডিক্ট লজিক ---
    $verdict = $factCheckResult->verdict ?? 'Unverified';
    $rawApi = json_decode($factCheckResult->api_response_raw ?? '{}', true);
    $comment = $rawApi['comment'] ?? ($post->admin_comment ?? 'বিস্তারিত বিশ্লেষণ শীঘ্রই আসছে...');

    $verdictColor = '#6c757d'; 
    $verdictIcon = 'fa-question-circle';
    $verdictText = $verdict;

    if (stripos($verdict, 'True') !== false || stripos($verdict, 'Likely True') !== false) {
        $verdictColor = '#198754'; 
        $verdictIcon = 'fa-check-circle';
        $verdictText = 'সত্য (True)';
    } elseif (stripos($verdict, 'False') !== false || stripos($verdict, 'Fake') !== false) {
        $verdictColor = '#dc3545'; 
        $verdictIcon = 'fa-times-circle';
        $verdictText = 'মিথ্যা (False)';
    } elseif (stripos($verdict, 'Misleading') !== false) {
        $verdictColor = '#ffc107'; 
        $verdictIcon = 'fa-exclamation-triangle';
        $verdictText = 'বিভ্রান্তিকর (Misleading)';
    } elseif (stripos($verdict, 'Altered') !== false) {
        $verdictColor = '#fd7e14'; 
        $verdictIcon = 'fa-edit';
        $verdictText = 'বিকৃত (Altered)';
    }
@endphp

@section('title')
{{ $title }} | {{ $front_ins_name }} 
@endsection

@section('meta')
    <meta name="description" content="{{ Str::limit(strip_tags($content), 150) }}">
    <meta property="og:title" content="{{ $title }}">
    <meta property="og:image" content="{{ $imageUrl }}">
    <meta property="og:url" content="{{ url()->current() }}">
@endsection

@section('css')
<style>
    .article-header { padding: 40px 0 20px; }
    .article-title { font-size: 2.5rem; font-weight: 700; line-height: 1.3; }
    .article-meta { font-size: 0.9rem; color: #666; margin-top: 15px; border-bottom: 1px solid #eee; padding-bottom: 20px; }
    .article-meta span { margin-right: 15px; }
    .article-featured-img { width: 100%; height: auto; border-radius: 8px; margin-bottom: 30px; }
    
    .verdict-box { background: #fff; border-left: 5px solid {{ $verdictColor }}; padding: 25px; margin-bottom: 30px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); border-radius: 5px; }
    .verdict-result { font-size: 1.5rem; font-weight: 700; color: {{ $verdictColor }}; margin-bottom: 15px; }
    
    .article-content { font-size: 1.1rem; line-height: 1.8; color: #333; text-align: justify; }
    .sidebar-widget { background: #fff; padding: 20px; margin-bottom: 30px; border: 1px solid #eee; }
    
    .btn-social { width: 40px; height: 40px; display: inline-flex; align-items: center; justify-content: center; border-radius: 50%; color: white; margin-right: 10px; transition: 0.3s; }
    .btn-fb { background: #3b5998; }
    .btn-x { background: #000; }
    .btn-wa { background: #25d366; }

    @media print { .no-print { display: none !important; } }
</style>
@endsection

@section('body')
    @php
        function convertToBangla($str) {
            $en = ['0','1','2','3','4','5','6','7','8','9','January','February','March','April','May','June','July','August','September','October','November','December'];
            $bn = ['০','১','২','৩','৪','৫','৬','৭','৮','৯','জানুয়ারি','ফেব্রুয়ারি','মার্চ','এপ্রিল','মে','জুন','জুলাই','আগস্ট','সেপ্টেম্বর','অক্টোবর','নভেম্বর','ডিসেম্বর'];
            return str_replace($en, $bn, $str);
        }
    @endphp

    <section class="bg-white pb-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <div class="article-header">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb small">
                                <li class="breadcrumb-item"><a href="{{ route('front.index') }}">হোম</a></li>
                                <li class="breadcrumb-item"><a href="{{ $categoryLink }}">{{ $categoryName }}</a></li>
                            </ol>
                        </nav>
                        <h1 class="article-title">{{ $title }}</h1>
                        <div class="article-meta">
                            <span><i class="fas fa-user-edit me-2"></i>প্রতিবেদক: <strong>{{ $authorName }}</strong></span>
                            <span><i class="far fa-calendar-alt me-2"></i>{{ convertToBangla($date) }}</span>
                            <span><i class="fas fa-eye me-2"></i>{{ convertToBangla($viewCount) }} বার</span>
                        </div>
                    </div>

                    @if($factCheckResult)
                    <div class="verdict-box">
                        <div class="verdict-result"><i class="fas {{ $verdictIcon }} me-2"></i>{{ $verdictText }}</div>
                        <div class="analysis"><strong>বিশ্লেষণ:</strong> {!! $comment !!}</div>
                    </div>
                    @endif

                    <img src="{{ $imageUrl }}" alt="{{ $title }}" class="article-featured-img">

                    <div class="article-content">
                        {!! $content !!}
                    </div>

                    <div class="share-section no-print mt-5">
                        <h5 class="fw-bold mb-3">শেয়ার করুন:</h5>
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ url()->current() }}" target="_blank" class="btn-social btn-fb"><i class="fab fa-facebook-f"></i></a>
                        <a href="https://twitter.com/intent/tweet?url={{ url()->current() }}" target="_blank" class="btn-social btn-x"><i class="fab fa-x-twitter"></i></a>
                        <a href="https://wa.me/?text={{ url()->current() }}" target="_blank" class="btn-social btn-wa"><i class="fab fa-whatsapp"></i></a>
                    </div>
                </div>

                <div class="col-lg-4 mt-5 mt-lg-0">
                    <div class="sidebar-widget">
                        <h4 class="border-bottom pb-2 mb-4">সাম্প্রতিক যাচাই</h4>
                        @foreach($latestNews as $lNews)
                            <div class="d-flex gap-3 mb-3 pb-3 border-bottom">
                                <img src="{{ $lNews->image ? $front_admin_url.$lNews->image : 'https://placehold.co/80'; }}" width="80" height="60" style="object-fit: cover;">
                                <div>
                                    <h6 class="mb-0"><a href="{{ route('front.news.details', $lNews->slug) }}" class="text-dark text-decoration-none">{{ Str::limit($lNews->title, 40) }}</a></h6>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection