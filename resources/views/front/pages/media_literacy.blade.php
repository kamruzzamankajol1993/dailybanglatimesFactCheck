@extends('front.master.master')

@section('title')
মিডিয়া লিটারেসি - সচেতন হোন, যাচাই করুন
@endsection

@section('body')
<section class="py-5 bg-white">
    <div class="container">
        
        <div class="row align-items-center mb-5">
            <div class="col-lg-12">
                <h1 class="fw-bold text-dark display-5">গুজব থেকে বাঁচতে<br><span class="text-primary">মিডিয়া লিটারেসি</span></h1>
                <p class="lead text-muted mt-3">ইন্টারনেটে যা দেখবেন, তাই বিশ্বাস করবেন না। ডিজিটাল যুগে নিজেকে নিরাপদ রাখতে তথ্যের সত্যতা যাচাই করার কৌশলগুলো জানুন।</p>
                <a href="#tools" class="btn btn-outline-primary rounded-pill px-4 mt-2">যাচাইয়ের টুলস দেখুন</a>
            </div>
            
        </div>

        <hr class="my-5">

        <div class="row g-4 mb-5">
            <div class="col-12 text-center mb-3">
                <h2 class="fw-bold">ভুয়া খবর চেনার ৫টি উপায়</h2>
            </div>
            
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm text-center p-4 hover-top">
                    <div class="display-4 text-warning mb-3"><i class="fas fa-newspaper"></i></div>
                    <h5 class="fw-bold">শিরোনাম দেখে উত্তেজিত হবেন না</h5>
                    <p class="text-muted small">চটকদার বা খুব বেশি আবেগপূর্ণ শিরোনাম সাধারণত ক্লিকবেইট বা ভুয়া হয়। পুরো খবরটি পড়ুন।</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm text-center p-4 hover-top">
                    <div class="display-4 text-info mb-3"><i class="fas fa-link"></i></div>
                    <h5 class="fw-bold">ওয়েবসাইটের ঠিকানা দেখুন</h5>
                    <p class="text-muted small">অপরিচিত ডোমেইন (যেমন .xyz, .info) বা পরিচিত পত্রিকার নামের সামান্য পরিবর্তন (যেমন prothom-alo-bd.com) খেয়াল করুন।</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm text-center p-4 hover-top">
                    <div class="display-4 text-danger mb-3"><i class="fas fa-calendar-alt"></i></div>
                    <h5 class="fw-bold">তারিখ যাচাই করুন</h5>
                    <p class="text-muted small">অনেক সময় পুরনো খবর বা ভিডিও নতুন করে শেয়ার করে বিভ্রান্তি ছড়ানো হয়। ঘটনার তারিখটি চেক করুন।</p>
                </div>
            </div>
        </div>

        <div id="tools" class="bg-light p-4 p-md-5 rounded-4 mb-5">
            <h3 class="fw-bold mb-4 text-center"><i class="fas fa-tools me-2"></i>তথ্য যাচাইয়ের প্রয়োজনীয় টুলস</h3>
            <div class="row g-3">
                <div class="col-md-6 col-lg-3">
                    <div class="bg-white p-3 rounded shadow-sm d-flex align-items-center">
                        <i class="fab fa-google fa-2x text-primary me-3"></i>
                        <div>
                            <h6 class="fw-bold mb-0">Google Lens</h6>
                            <small class="text-muted">ছবির উৎস খোঁজার জন্য</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="bg-white p-3 rounded shadow-sm d-flex align-items-center">
                        <i class="fas fa-search fa-2x text-secondary me-3"></i>
                        <div>
                            <h6 class="fw-bold mb-0">TinEye</h6>
                            <small class="text-muted"> রিভার্স ইমেজ সার্চ ইঞ্জিন</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="bg-white p-3 rounded shadow-sm d-flex align-items-center">
                        <i class="fas fa-map-marked-alt fa-2x text-success me-3"></i>
                        <div>
                            <h6 class="fw-bold mb-0">Google Maps</h6>
                            <small class="text-muted">লোকেশন বা স্থান যাচাইয়ের জন্য</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="bg-white p-3 rounded shadow-sm d-flex align-items-center">
                        <i class="fas fa-video fa-2x text-danger me-3"></i>
                        <div>
                            <h6 class="fw-bold mb-0">YouTube DataViewer</h6>
                            <small class="text-muted">ভিডিওর সত্যতা যাচাই করতে</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12 mb-3">
                <h3 class="fw-bold border-start border-4 border-danger ps-3">টিউটোরিয়াল</h3>
            </div>
            <div class="col-md-6">
                <div class="ratio ratio-16x9 shadow rounded overflow-hidden">
                    <iframe src="https://www.youtube.com/embed/AkwWcHekMdo" title="How to spot fake news" allowfullscreen></iframe>
                </div>
                <h6 class="mt-2 fw-bold">ভিডিও: কীভাবে ফেক নিউজ চিনবেন?</h6>
            </div>
            <div class="col-md-6">
                <div class="alert alert-success h-100 d-flex flex-column justify-content-center text-center">
                    <h4>আপনি কি সচেতন নাগরিক?</h4>
                    <p>আপনার বন্ধুদের সাথে এই তথ্যগুলো শেয়ার করুন এবং গুজবের বিরুদ্ধে রুখে দাঁড়ান।</p>
                    <button class="btn btn-success fw-bold">শেয়ার করুন <i class="fas fa-share ms-1"></i></button>
                </div>
            </div>
        </div>

    </div>
</section>

<style>
    .hover-top { transition: transform 0.3s; }
    .hover-top:hover { transform: translateY(-10px); }
</style>
@endsection