@extends('front.master.master')

@section('title')
{{ $video->title }} | {{ $front_ins_name ?? 'Video Gallery' }} 
@endsection

@section('meta')
    <meta name="description" content="{{ Str::limit(strip_tags($video->description), 150) }}">
    <meta name="keywords" content="Video, Gallery, News, {{ $front_ins_name ?? '' }}">
    
    <meta property="og:type" content="video.other">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="{{ $video->title }}">
    <meta property="og:description" content="{{ Str::limit(strip_tags($video->description), 150) }}">
    <meta property="og:image" content="{{ $video->thumbnail ? $front_admin_url.$video->thumbnail : $front_admin_url.$front_logo_name }}">

    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $video->title }}">
    <meta name="twitter:description" content="{{ Str::limit(strip_tags($video->description), 150) }}">
    <meta name="twitter:image" content="{{ $video->thumbnail ? $front_admin_url.$video->thumbnail : $front_admin_url.$front_logo_name }}">
@endsection

@section('css')
<style>
    /* Responsive Iframe Wrapper (16:9 Aspect Ratio) */
    .video-container {
        position: relative;
        padding-bottom: 56.25%; /* 16:9 */
        height: 0;
        overflow: hidden;
        margin-bottom: 20px;
        border-radius: 5px;
    }
    .video-container iframe {
        position: absolute;
        top: 0;
        left: 0;
        border: 0;
    }

    /* Side Video List Style */
    .side-video-item {
        transition: all 0.3s ease;
    }
    .side-video-item:hover {
        background-color: #f8f9fa;
    }
    .play-icon-overlay {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        color: rgba(255, 255, 255, 0.8);
        font-size: 20px;
        text-shadow: 0 2px 4px rgba(0,0,0,0.5);
    }
</style>
@endsection

@section('body')
    {{-- Bangla Date Helper (If not global) --}}
    @php
        function convertToBanglaNum($str) {
            $en = ['0','1','2','3','4','5','6','7','8','9', 'am', 'pm'];
            $bn = ['০','১','২','৩','৪','৫','৬','৭','৮','৯', 'পূর্বাহ্ণ', 'অপরাহ্ণ'];
            return str_replace($en, $bn, $str);
        }
    @endphp

    <section class="single-page py-4 bg-white">
        <div class="container">
            
            {{-- Breadcrumb --}}
            <nav aria-label="breadcrumb" class="mb-4 border-bottom pb-2">
                <ol class="breadcrumb small text-secondary mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('front.index') }}" class="text-dark"><i class="fas fa-home"></i></a></li>
                    <li class="breadcrumb-item active">ভিডিও গ্যালারি</li>
                    <li class="breadcrumb-item active text-danger">{{ Str::limit($video->title, 50) }}</li>
                </ol>
            </nav>

            <div class="row g-4">

                {{-- MAIN CONTENT COLUMN (Left) --}}
                <div class="col-lg-8">
                    
                    {{-- 1. Video Player Section --}}
                    <div class="video-wrapper mb-4">
                        {{-- Iframe from DB --}}
                        <div class="video-container shadow-sm">
                            {!! $video->video_url !!}
                        </div>
                        
                        <h1 class="fw-bold mb-2 lh-base text-dark">{{ $video->title }}</h1>
                        
                        <div class="d-flex align-items-center text-secondary small mb-3">
                            <span class="me-3"><i class="far fa-clock text-danger me-1"></i> {{ convertToBanglaNum($video->created_at->format('d M Y, h:i A')) }}</span>
                            <span><i class="far fa-eye text-success me-1"></i> দেখা হয়েছে: {{ convertToBanglaNum($video->view_count) }} বার</span>
                        </div>

                        {{-- Social Share --}}
                        <div class="social-share-bar mb-4 d-flex gap-1">
                            <a href="https://www.facebook.com/sharer/sharer.php?u={{ url()->current() }}" target="_blank" class="share-btn fb"><i class="fab fa-facebook-f"></i></a>
                            <a href="https://twitter.com/intent/tweet?url={{ url()->current() }}&text={{ $video->title }}" target="_blank" class="share-btn x-tw"><i class="fab fa-x-twitter"></i></a>
                            <a href="https://wa.me/?text={{ $video->title }} {{ url()->current() }}" target="_blank" class="share-btn wa"><i class="fab fa-whatsapp"></i></a>
                        </div>
                    </div>

                    {{-- 2. Description --}}
                    @if($video->description)
                    <div class="article-content text-justify border-top pt-4">
                        <h5 class="fw-bold mb-3 text-secondary">বিস্তারিত</h5>
                        {!! $video->description !!}
                    </div>
                    @endif
                    
                    {{-- 3. Facebook Comments (Optional) --}}
                    {{-- আপনি চাইলে এখানে ফেসবুক কমেন্ট প্লাগিন কোড বসাতে পারেন --}}
                    
                </div>

                {{-- SIDEBAR COLUMN (Right - Related Videos) --}}
                <div class="col-lg-4">
                    <div class="sticky-top" style="top: 100px;">
                        
                        {{-- Section Header --}}
                        <div class="section-header-wrapper mb-3" style="border-bottom: 2px solid #dc3545;">
                            <h6 class="bg-success text-white d-inline-block px-3 py-1 m-0 fw-bold">
                                আরও ভিডিও দেখুন <i class="fas fa-play-circle ms-1"></i>
                            </h6>
                        </div>

                        <div class="d-flex flex-column gap-3">
                            @if(isset($relatedVideos) && count($relatedVideos) > 0)
                                @foreach($relatedVideos as $rVideo)
                                    <div class="d-flex align-items-start side-video-item border rounded p-2">
                                        {{-- Thumbnail --}}
                                        <div class="flex-shrink-0 position-relative" style="width: 120px;">
                                            <a href="{{ route('front.video.details', $rVideo->slug) }}">
                                                <img onerror="this.onerror=null;this.src='{{ $front_admin_url }}{{ $front_logo_name }}';" 
                                                     src="{{ $rVideo->thumbnail ? $front_admin_url.$rVideo->thumbnail : 'https://placehold.co/120x80/333/fff?text=Video' }}" 
                                                     class="img-fluid rounded-1 w-100" 
                                                     alt="{{ $rVideo->title }}">
                                                <div class="play-icon-overlay">
                                                    <i class="fas fa-play-circle"></i>
                                                </div>
                                            </a>
                                        </div>
                                        
                                        {{-- Title --}}
                                        <div class="flex-grow-1 ms-2">
                                            <a href="{{ route('front.video.details', $rVideo->slug) }}" class="small fw-bold text-dark text-decoration-none hover-red lh-sm d-block mb-1">
                                                {{ Str::limit($rVideo->title, 60) }}
                                            </a>
                                            <small class="text-secondary" style="font-size: 10px;">
                                                <i class="far fa-clock"></i> {{ convertToBanglaNum($rVideo->created_at->diffForHumans()) }}
                                            </small>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="text-center text-muted py-3">
                                    <small>আর কোনো ভিডিও পাওয়া যায়নি।</small>
                                </div>
                            @endif
                        </div>

                        {{-- Sidebar AD --}}
                        <div class="mt-4 text-center">
                            <div class="bg-light border d-flex align-items-center justify-content-center text-secondary" style="height: 250px; width: 100%;">
                                <div class="text-center">
                                    <h5 class="fw-bold">AD SPACE</h5>
                                    <small>300x250</small>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </section>
@endsection