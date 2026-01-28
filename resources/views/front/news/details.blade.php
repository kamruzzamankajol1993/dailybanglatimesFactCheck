@extends('front.master.master')

@php
    // --- ১. ডাটা প্রসেসিং ও নর্মালাইজেশন ---
    
    // টাইটেল
    $title = $post->title ?? ($post->link ?? 'News Details');
    
    // কন্টেন্ট (Post হলে content, Request হলে description)
    $content = $isUserRequest ? ($post->description ?? '') : ($post->content ?? '');
    
    // ইমেজ পাথ (Post হলে front_admin_url, Request হলে storage asset)
    $imageUrl = 'https://placehold.co/800x500/eee/333?text=No+Image';
    if ($post->image) {
        $imageUrl = $isUserRequest ? $front_admin_url.$post->image : $front_admin_url.$post->image;
    }

    // ক্যাটাগরি এবং স্লাগ
    $categoryName = 'News';
    $categoryLink = '#';
    if ($isUserRequest && $post->category) {
        $categoryName = $post->category->name;
        // রিকোয়েস্টের ক্যাটাগরি পেজ লিংক (যদি থাকে)
        $categoryLink = route('front.category.news', $post->category->slug ?? '#');
    } elseif (!$isUserRequest && $post->categories->count() > 0) {
        $categoryName = $post->categories->first()->name;
        $categoryLink = route('front.category.news', $post->categories->first()->slug);
    }

    // অথর
    $authorName = $isUserRequest ? 'User Submitted' : ($post->author->name ?? $front_ins_name);
    
    // তারিখ (Bangla Converter পরে অ্যাপ্লাই হবে)
    $date = $post->created_at->format('d F, Y'); 
    
    // ভিউ কাউন্ট (User Request এর কলাম না থাকলে ডিফল্ট ০)
    $viewCount = $post->view_count ?? 0;

    // --- ২. ভার্ডিক্ট লজিক ---
    $verdict = $factCheckResult->verdict ?? 'Unverified';
    
    // API রেসপন্স থেকে কমেন্ট বের করা
    $rawApi = json_decode($factCheckResult->api_response_raw ?? '{}', true);
    // যদি API কমেন্ট না থাকে, তবে অ্যাডমিন কমেন্ট, তাও না থাকলে ডিফল্ট মেসেজ
    $comment = $rawApi['comment'] ?? ($post->admin_comment ?? 'বিস্তারিত বিশ্লেষণ শীঘ্রই আসছে...');

    // ডিফল্ট স্টাইল (Unverified)
    $verdictColor = '#6c757d'; // grey
    $verdictIcon = 'fa-question-circle';
    $verdictText = $verdict;

    // ভার্ডিক্ট কালার লজিক
    if (stripos($verdict, 'True') !== false || stripos($verdict, 'Likely True') !== false) {
        $verdictColor = '#198754'; // success green
        $verdictIcon = 'fa-check-circle';
        $verdictText = 'সত্য (True)';
    } elseif (stripos($verdict, 'False') !== false || stripos($verdict, 'Fake') !== false) {
        $verdictColor = '#dc3545'; // danger red
        $verdictIcon = 'fa-times-circle';
        $verdictText = 'মিথ্যা (False)';
    } elseif (stripos($verdict, 'Misleading') !== false) {
        $verdictColor = '#ffc107'; // warning yellow
        $verdictIcon = 'fa-exclamation-triangle';
        $verdictText = 'বিভ্রান্তিকর (Misleading)';
    } elseif (stripos($verdict, 'Altered') !== false) {
        $verdictColor = '#fd7e14'; // orange
        $verdictIcon = 'fa-edit';
        $verdictText = 'বিকৃত (Altered)';
    }
@endphp

@section('title')
{{ $title }} | {{ $front_ins_name }} 
@endsection

@section('meta')
    <meta name="description" content="{{ Str::limit(strip_tags($content), 150) }}">
    <meta name="keywords" content="{{ $categoryName }}">
    <meta name="author" content="{{ $authorName }}">
    <meta property="og:title" content="{{ $title }}">
    <meta property="og:description" content="{{ Str::limit(strip_tags($content), 150) }}">
    <meta property="og:image" content="{{ $imageUrl }}">
    <meta property="og:url" content="{{ url()->current() }}">
@endsection

@section('css')
<style>
    /* --- DETAIL PAGE SPECIFIC CSS --- */
    .article-header { padding: 40px 0 20px; }
    .article-title { font-size: 2.5rem; font-weight: 700; color: var(--primary); line-height: 1.3; }
    .article-meta { font-size: 0.9rem; color: #666; margin-top: 15px; border-bottom: 1px solid #eee; padding-bottom: 20px; }
    .article-meta span { margin-right: 15px; }
    
    .article-featured-img { width: 100%; height: auto; border-radius: 8px; margin-bottom: 30px; box-shadow: 0 5px 15px rgba(0,0,0,0.05); }
    
    /* Verdict Box (Dynamic Color) */
    .verdict-box { background: #fff; border-left: 5px solid {{ $verdictColor }}; padding: 25px; margin-bottom: 30px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); border-radius: 5px; }
    .verdict-label { font-size: 0.8rem; text-transform: uppercase; letter-spacing: 1px; color: #888; font-weight: 700; }
    .verdict-result { font-size: 1.5rem; font-weight: 700; color: {{ $verdictColor }}; margin-bottom: 15px; }
    
    .claim-grid { display: grid; grid-template-columns: 100px 1fr; gap: 15px; margin-bottom: 10px; }
    .claim-grid strong { color: var(--primary); }
    
    /* Article Content */
    .article-content { font-size: 1.1rem; line-height: 1.8; color: #333; }
    .article-content h3 { font-weight: 700; margin-top: 30px; margin-bottom: 15px; color: var(--primary); }
    .article-content img { max-width: 100%; height: auto; }
    .article-content a { color: var(--accent); text-decoration: none; }
    
    /* Sidebar Mini Cards */
    .sidebar-widget { background: #fff; padding: 20px; margin-bottom: 30px; border: 1px solid #eee; }
    .mini-card { display: flex; gap: 10px; margin-bottom: 15px; padding-bottom: 15px; border-bottom: 1px solid #f9f9f9; }
    .mini-card img { width: 80px; height: 60px; object-fit: cover; border-radius: 4px; }
    .mini-card h6 { font-size: 0.95rem; line-height: 1.4; margin: 0; }
    .mini-card a { color: #333; text-decoration: none; }
    .mini-card a:hover { color: var(--accent); }


    /* Share Buttons */
    .share-section { margin-top: 40px; padding-top: 20px; border-top: 1px solid #eee; }
    
    .btn-social { 
        width: 40px; height: 40px; 
        display: inline-flex; align-items: center; justify-content: center; 
        border-radius: 50%; color: white; margin-right: 10px; 
        transition: 0.3s; text-decoration: none; 
    }
    
    .btn-fb { background: #3b5998; }       /* Facebook */
    .btn-x { background: #000000; }        /* X (Twitter) */
    .btn-in { background: #0077b5; }       /* LinkedIn */
    .btn-wa { background: #25d366; }       /* WhatsApp */
    .btn-yt { background: #ff0000; }       /* YouTube */
    .btn-print { background: #6c757d; cursor: pointer; }    /* Print */
    
    .btn-social:hover { opacity: 0.8; color: white; transform: translateY(-3px); }
    
    /* প্রিন্ট করার সময় বাটনগুলো লুকানো থাকবে */
    @media print {
        .share-section, .no-print { display: none !important; }
    }
    
   



</style>
@endsection

@section('body')
    {{-- Bangla Converter Helper --}}
    @php
        function convertToBangla($str) {
            $en_num = ['0','1','2','3','4','5','6','7','8','9'];
            $bn_num = ['০','১','২','৩','৪','৫','৬','৭','৮','৯'];
            $en_months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
            $bn_months = ['জানুয়ারি', 'ফেব্রুয়ারি', 'মার্চ', 'এপ্রিল', 'মে', 'জুন', 'জুলাই', 'আগস্ট', 'সেপ্টেম্বর', 'অক্টোবর', 'নভেম্বর', 'ডিসেম্বর'];
            
            $str = str_replace($en_months, $bn_months, $str);
            return str_replace($en_num, $bn_num, $str);
        }
    @endphp

     <section class="bg-white pb-5">
        <div class="container">
            <div class="row">
                
                {{-- MAIN CONTENT --}}
                <div class="col-lg-8">
                    
                    {{-- Breadcrumb & Header --}}
                    <div class="article-header">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb small text-uppercase">
                                <li class="breadcrumb-item"><a href="{{ route('front.index') }}" class="text-danger">হোম</a></li>
                                <li class="breadcrumb-item"><a href="{{ $categoryLink }}" class="text-danger">{{ $categoryName }}</a></li>
                                <li class="breadcrumb-item active">ফ্যাক্ট-চেক</li>
                            </ol>
                        </nav>
                        
                        <h1 class="article-title">{{ $title }}</h1>
                        
                        <div class="article-meta">
                            <span><i class="fas fa-user-edit me-2"></i>প্রতিবেদক: <strong>{{ $authorName }}</strong></span>
                            <span><i class="far fa-calendar-alt me-2"></i>{{ convertToBangla($date) }}</span>
                            <span><i class="fas fa-eye me-2"></i>{{ convertToBangla($viewCount) }} বার পঠিত</span>
                        </div>
                    </div>

                    {{-- VERDICT BOX (Dynamic) --}}
                    @if($factCheckResult)
                    <div class="verdict-box">
                        <div class="verdict-label">ফ্যাক্ট-চেক রেজাল্ট</div>
                        <div class="verdict-result">
                            <i class="fas {{ $verdictIcon }} me-2"></i>{{ $verdictText }}
                        </div>
                        
                        <div class="claim-grid">
                            <strong>বিশ্লেষণ:</strong>
                            <div style="text-align: justify;">
                                {!! $comment !!}
                            </div>
                        </div>
                        
                        {{-- Confidence Score --}}
                        @if($factCheckResult->confidence_score)
                        <div class="mt-2 text-end">
                             <span class="badge bg-light text-secondary border">AI কনফিডেন্স: {{ $factCheckResult->confidence_score * 100 }}%</span>
                        </div>
                        @endif
                    </div>
                    @endif

                    {{-- FEATURED IMAGE --}}
                    <img src="{{ $imageUrl }}" alt="{{ $title }}" class="article-featured-img">

                    {{-- ARTICLE CONTENT --}}
                    <div class="article-content text-justify">
                        {{-- Subtitle for Admin Posts --}}
                        @if(!$isUserRequest && $post->subtitle)
                             <h4 class="mb-3 text-secondary">{{ $post->subtitle }}</h4>
                        @endif
                        
                        {{-- Main Body --}}
                        {!! $content !!}

                        {{-- Final Conclusion Alert --}}
                        @if($factCheckResult)
                        <div class="alert mt-5 py-3" style="background-color: {{ $verdictColor }}15; border-left: 5px solid {{ $verdictColor }}; color: #333;">
                            <h5 class="alert-heading fw-bold"><i class="fas fa-info-circle me-2"></i>সিদ্ধান্ত</h5>
                            <p class="mb-0">
                                আমাদের যাচাই প্রক্রিয়া এবং উপলব্ধ তথ্যের ভিত্তিতে এই দাবিটি <strong>{{ $verdictText }}</strong> হিসেবে প্রমাণিত হয়েছে।
                            </p>
                        </div>
                        @endif
                    </div>

                    {{-- SHARE BUTTONS --}}
                    {{-- SHARE BUTTONS --}}
                    <div class="share-section no-print">
                        <h5 class="fw-bold mb-3">শেয়ার করুন:</h5>
                        
                        {{-- Facebook --}}
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ url()->current() }}" target="_blank" class="btn-social btn-fb" title="Facebook">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        
                        {{-- X (Twitter) --}}
                        <a href="https://twitter.com/intent/tweet?url={{ url()->current() }}&text={{ $title }}" target="_blank" class="btn-social btn-x" title="X (Twitter)">
                            <i class="fab fa-x-twitter"></i>
                        </a>

                        {{-- LinkedIn --}}
                        <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ url()->current() }}" target="_blank" class="btn-social btn-in" title="LinkedIn">
                            <i class="fab fa-linkedin-in"></i>
                        </a>

                        {{-- WhatsApp --}}
                        <a href="https://wa.me/?text={{ url()->current() }}" target="_blank" class="btn-social btn-wa" title="WhatsApp">
                            <i class="fab fa-whatsapp"></i>
                        </a>

                        {{-- YouTube (সাধারণত শেয়ারের জন্য নয়, ফলো করার জন্য ব্যবহার হয়) --}}
                        {{-- যদি $social_links থেকে ইউটিউব লিংক আনতে চান --}}
                        @php
                            $youtubeLink = '#';
                            if(isset($social_links)) {
                                foreach($social_links as $link) {
                                    if(stripos($link->title, 'youtube') !== false) {
                                        $youtubeLink = $link->link;
                                        break;
                                    }
                                }
                            }
                        @endphp
                        <a href="{{ $youtubeLink }}" target="_blank" class="btn-social btn-yt" title="Subscribe on YouTube">
                            <i class="fab fa-youtube"></i>
                        </a>

                        {{-- Print Button --}}
                        <button onclick="window.print()" class="btn-social btn-print border-0" title="Print this Article">
                            <i class="fas fa-print"></i>
                        </button>
                    </div>

                </div>

                {{-- SIDEBAR --}}
                <div class="col-lg-4 mt-5 mt-lg-0">
                    
                    {{-- 1. Latest Fact Checks Widget --}}
                    <div class="sidebar-widget mt-lg-5">
                        <h4 class="widget-title mb-4 pb-2 border-bottom">সাম্প্রতিক যাচাই</h4>
                        
                        @foreach($latestNews as $lNews)
                            @php
                                // সাইডবার আইটেম প্রসেসিং
                                $sTitle = $lNews->title;
                                $sLink = route('front.news.details', $lNews->slug);
                                $sImage = $lNews->image ? $front_admin_url.$lNews->image : 'https://placehold.co/100';
                                
                                // সাইডবার আইটেম ভার্ডিক্ট
                                $sVerdict = $lNews->factCheck->verdict ?? 'Unverified';
                                $sClass = 'text-secondary';
                                if (stripos($sVerdict, 'True') !== false) $sClass = 'text-success';
                                elseif (stripos($sVerdict, 'False') !== false) $sClass = 'text-danger';
                                elseif (stripos($sVerdict, 'Misleading') !== false) $sClass = 'text-warning';
                            @endphp

                            <div class="mini-card">
                                <img src="{{ $sImage }}" alt="{{ $sTitle }}">
                                <div>
                                    <h6><a href="{{ $sLink }}">{{ Str::limit($sTitle, 40) }}</a></h6>
                                    <small class="{{ $sClass }} fw-bold">{{ $sVerdict }}</small>
                                </div>
                            </div>
                        @endforeach

                        <a href="{{ route('front.latest.news') }}" class="btn btn-sm btn-outline-dark w-100 mt-2">আরও দেখুন</a>
                    </div>

                    {{-- 2. Categories Widget (As requested) --}}
                    <div class="sidebar-widget">
                        <h4 class="widget-title mb-3">ক্যাটাগরি</h4>
                        <div class="d-flex flex-wrap gap-2">
                            @forelse($sidebarCategories as $cat)
                                <a href="{{ route('front.category.news', $cat->slug) }}" class="badge bg-light text-dark border p-2 text-decoration-none">
                                    {{ $cat->name }}
                                </a>
                            @empty
                                <span class="text-muted small">কোনো ক্যাটাগরি পাওয়া যায়নি।</span>
                            @endforelse
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>
@endsection

@section('scripts')
@endsection