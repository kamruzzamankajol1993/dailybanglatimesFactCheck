@extends('front.master.master')

@section('title')
{{ $front_ins_name }} 
@endsection

@section('css')

@endsection

@section('body')
   <section class="hero-area">
        <div class="container text-center">
            <h1 class="display-4 mb-3 fw-bold">কোনো খবর নিয়ে সন্দেহ?</h1>
            <p class="lead mb-5 opacity-75">ইন্টারনেটে যা দেখছেন তা কি সত্যি? যাচাই করতে সার্চ করুন।</p>
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <ul class="nav nav-tabs border-0 justify-content-center mb-4" id="myTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="search-tab" data-bs-toggle="tab" data-bs-target="#search" type="button"><i class="fas fa-search me-2"></i>সার্চ করুন</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="upload-tab" data-bs-toggle="tab" data-bs-target="#upload" type="button"><i class="fas fa-paper-plane me-2"></i>রিপোর্ট পাঠান</button>
                        </li>
                    </ul>
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="search" role="tabpanel">
                            <div class="search-box">
                                <form class="d-flex shadow-sm rounded-3 overflow-hidden">
                                    <input class="form-control form-control-lg border-0 rounded-0 ps-4" type="search" placeholder="কীওয়ার্ড লিখুন (যেমন: পদ্মা সেতু ফাটল)...">
                                    <button class="btn btn-danger px-4 fw-bold rounded-0" type="submit">খুঁজুন</button>
                                </form>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="upload" role="tabpanel">
                            <div class="upload-box text-start">
                                <form>
                                    <div class="mb-3"><input type="text" class="form-control" placeholder="সন্দেহজনক খবরের লিংক দিন অথবা টাইটেল লিখুন..."></div>
                                    <div class="mb-3"><input type="file" class="form-control"></div>
                                    <div class="d-grid"><button type="submit" class="btn btn-primary fw-bold">যাচাইয়ের জন্য সাবমিট করুন</button></div>
                                </form>
                            </div>
                        </div>
                    </div> 
                </div>
            </div>
        </div>
    </section>

    <section class="py-5 bg-white">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center mb-5">
                <div>
                    <h2 class="text-dark fw-bold mb-1">সাম্প্রতিক ফ্যাক্ট-চেক</h2>
                    <div class="bg-danger" style="width: 60px; height: 3px;"></div>
                </div>
                <a href="#" class="btn btn-outline-danger btn-sm rounded-pill px-3">সব দেখুন <i class="fas fa-arrow-right ms-1"></i></a>
            </div>
            
            <div class="row g-3 g-md-4">
                
                <div class="col-6 col-md-4 col-lg-3">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="position-relative">
                            <img src="https://placehold.co/600x400/png?text=Fake+Bridge" class="card-img-top" alt="...">
                            <span class="news-badge badge-fake"><i class="fas fa-times-circle me-1"></i> মিথ্যা</span>
                        </div>
                        <div class="card-body">
                            <h5 class="card-title fw-bold lh-base"><a href="#" class="text-dark">পদ্মা সেতুতে ফাটল ধরেছে— ভাইরাল ভিডিওটি ভুয়া</a></h5>
                            <p class="card-text small text-muted mt-2 d-none d-sm-block">ভিডিওটি মূলত ভিয়েতনামের একটি সেতুর...</p>
                        </div>
                        <div class="card-footer bg-white border-0 text-muted small py-3">
                            <i class="far fa-clock text-danger me-1"></i> ২ ঘন্টা আগে
                        </div>
                    </div>
                </div>

                <div class="col-6 col-md-4 col-lg-3">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="position-relative">
                            <img src="https://placehold.co/600x400/png?text=Education" class="card-img-top" alt="...">
                            <span class="news-badge badge-true"><i class="fas fa-check-circle me-1"></i> সত্য</span>
                        </div>
                        <div class="card-body">
                            <h5 class="card-title fw-bold lh-base"><a href="#" class="text-dark">নতুন শিক্ষাক্রমের প্রশিক্ষণ শুরু</a></h5>
                            <p class="card-text small text-muted mt-2 d-none d-sm-block">মন্ত্রণালয়ের নোটিশ অনুযায়ী কাল থেকে শুরু...</p>
                        </div>
                        <div class="card-footer bg-white border-0 text-muted small py-3">
                            <i class="far fa-clock text-success me-1"></i> ৫ ঘন্টা আগে
                        </div>
                    </div>
                </div>

                <div class="col-6 col-md-4 col-lg-3">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="position-relative">
                            <img src="https://placehold.co/600x400/png?text=Visa+News" class="card-img-top" alt="...">
                            <span class="news-badge bg-warning text-dark"><i class="fas fa-exclamation-triangle me-1"></i> বিভ্রান্তিকর</span>
                        </div>
                        <div class="card-body">
                            <h5 class="card-title fw-bold lh-base"><a href="#" class="text-dark">ভিসা ছাড়া আমেরিকা যাওয়ার সুযোগ?</a></h5>
                            <p class="card-text small text-muted mt-2 d-none d-sm-block">খবরটি আংশিক সত্য তবে সবার জন্য উন্মুক্ত নয়...</p>
                        </div>
                        <div class="card-footer bg-white border-0 text-muted small py-3">
                            <i class="far fa-clock text-warning me-1"></i> ১ দিন আগে
                        </div>
                    </div>
                </div>
                
                <div class="col-6 col-md-4 col-lg-3">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="position-relative">
                            <img src="https://placehold.co/600x400/png?text=Science+Myth" class="card-img-top" alt="...">
                            <span class="news-badge badge-fake"><i class="fas fa-times-circle me-1"></i> মিথ্যা</span>
                        </div>
                        <div class="card-body">
                            <h5 class="card-title fw-bold lh-base"><a href="#" class="text-dark">চন্দ্রগ্রহণ নিয়ে ভুল তথ্য ছড়ানো হচ্ছে</a></h5>
                            <p class="card-text small text-muted mt-2 d-none d-sm-block">নাসার নাম ব্যবহার করে ছড়ানো এই তথ্যটি ভিত্তিহীন...</p>
                        </div>
                        <div class="card-footer bg-white border-0 text-muted small py-3">
                            <i class="far fa-clock text-danger me-1"></i> ২ দিন আগে
                        </div>
                    </div>
                </div>

                <div class="col-6 col-md-4 col-lg-3">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="position-relative">
                            <img src="https://placehold.co/600x400/png?text=Sports+News" class="card-img-top" alt="...">
                            <span class="news-badge badge-true"><i class="fas fa-check-circle me-1"></i> সত্য</span>
                        </div>
                        <div class="card-body">
                            <h5 class="card-title fw-bold lh-base"><a href="#" class="text-dark">বিশ্বকাপের সময়সূচি পরিবর্তন হয়নি</a></h5>
                            <p class="card-text small text-muted mt-2 d-none d-sm-block">ফিফা নিশ্চিত করেছে যে পূর্বের সময়সূচিই বহাল থাকবে...</p>
                        </div>
                        <div class="card-footer bg-white border-0 text-muted small py-3">
                            <i class="far fa-clock text-success me-1"></i> ৩ দিন আগে
                        </div>
                    </div>
                </div>

                <div class="col-6 col-md-4 col-lg-3">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="position-relative">
                            <img src="https://placehold.co/600x400/png?text=Tech+News" class="card-img-top" alt="...">
                            <span class="news-badge badge-fake"><i class="fas fa-times-circle me-1"></i> মিথ্যা</span>
                        </div>
                        <div class="card-body">
                            <h5 class="card-title fw-bold lh-base"><a href="#" class="text-dark">বিনামূল্যে ল্যাপটপ বিতরণের লিংকটি ভুয়া</a></h5>
                            <p class="card-text small text-muted mt-2 d-none d-sm-block">সরকারি কোনো ওয়েবসাইট থেকে এমন ঘোষণা আসেনি...</p>
                        </div>
                        <div class="card-footer bg-white border-0 text-muted small py-3">
                            <i class="far fa-clock text-danger me-1"></i> ৩ দিন আগে
                        </div>
                    </div>
                </div>

                <div class="col-6 col-md-4 col-lg-3">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="position-relative">
                            <img src="https://placehold.co/600x400/png?text=Climate" class="card-img-top" alt="...">
                            <span class="news-badge badge-true"><i class="fas fa-check-circle me-1"></i> সত্য</span>
                        </div>
                        <div class="card-body">
                            <h5 class="card-title fw-bold lh-base"><a href="#" class="text-dark">শৈত্যপ্রবাহের পূর্বাভাস দিয়েছে আবহাওয়া অফিস</a></h5>
                            <p class="card-text small text-muted mt-2 d-none d-sm-block">আগামী সপ্তাহে তাপমাত্রা আরও কমতে পারে...</p>
                        </div>
                        <div class="card-footer bg-white border-0 text-muted small py-3">
                            <i class="far fa-clock text-success me-1"></i> ৪ দিন আগে
                        </div>
                    </div>
                </div>

                <div class="col-6 col-md-4 col-lg-3">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="position-relative">
                            <img src="https://placehold.co/600x400/png?text=Health" class="card-img-top" alt="...">
                            <span class="news-badge bg-warning text-dark"><i class="fas fa-exclamation-triangle me-1"></i> বিভ্রান্তিকর</span>
                        </div>
                        <div class="card-body">
                            <h5 class="card-title fw-bold lh-base"><a href="#" class="text-dark">লেবু পানি পানেই ক্যান্সার মুক্তি?</a></h5>
                            <p class="card-text small text-muted mt-2 d-none d-sm-block">বিশেষজ্ঞরা বলছেন এটি কেবল প্রতিরোধ ক্ষমতা বাড়ায়...</p>
                        </div>
                        <div class="card-footer bg-white border-0 text-muted small py-3">
                            <i class="far fa-clock text-warning me-1"></i> ৫ দিন আগে
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <section class="social-banner">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-4 mb-4 mb-lg-0 text-center text-lg-start">
                    <h2 class="fw-bold mb-2">সোশ্যাল মিডিয়া</h2>
                    <p class="mb-0 opacity-75">ভুয়া খবর রোধে আমাদের সাথে যুক্ত হোন।</p>
                </div>
                <div class="col-lg-8">
                    <div class="row row-cols-2 row-cols-md-5 g-3">
                        <div class="col">
                            <a href="#" class="social-card">
                                <i class="fab fa-facebook-f" style="color: #1877F2;"></i>
                                <span>Facebook</span>
                            </a>
                        </div>
                        <div class="col">
                            <a href="#" class="social-card">
                                <i class="fa-brands fa-x-twitter" style="color: #000;"></i>
                                <span>X</span>
                            </a>
                        </div>
                        <div class="col">
                            <a href="#" class="social-card">
                                <i class="fab fa-instagram" style="color: #E1306C;"></i>
                                <span>Instagram</span>
                            </a>
                        </div>
                        <div class="col">
                            <a href="#" class="social-card">
                                <i class="fab fa-youtube" style="color: #FF0000;"></i>
                                <span>YouTube</span>
                            </a>
                        </div>
                        <div class="col">
                            <a href="#" class="social-card">
                                <i class="fab fa-linkedin-in" style="color: #0077B5;"></i>
                                <span>LinkedIn</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
 
@endsection

@section('scripts')

@endsection