@extends('front.master.master')

@section('title')
{{ $front_ins_name }} 
@endsection

@section('css')
<style>
    /* Fact Check Badges */
    .news-badge {
        position: absolute;
        top: 10px;
        right: 10px;
        padding: 5px 12px;
        border-radius: 30px;
        font-size: 0.75rem;
        font-weight: bold;
        color: white;
        box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        z-index: 10;
    }
    
    .badge-true {
        background-color: #198754; /* Green */
    }
    
    .badge-fake {
        background-color: #dc3545; /* Red */
    }

    /* Card Hover Effect */
    .card {
        transition: transform 0.3s ease;
    }
    .card:hover {
        transform: translateY(-5px);
    }
</style>
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
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
                                <form action="{{ route('front.factCheck.search') }}" method="GET" class="d-flex shadow-sm rounded-3 overflow-hidden">
                                    <input class="form-control form-control-lg border-0 rounded-0 ps-4" 
                                           type="search" 
                                           name="query" 
                                           placeholder="কীওয়ার্ড লিখুন (যেমন: পদ্মা সেতু ফাটল)..." 
                                           required>
                                    <button class="btn btn-danger px-4 fw-bold rounded-0" type="submit">খুঁজুন</button>
                                </form>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="upload" role="tabpanel">
                            <div class="upload-box text-start p-4">
                                <h5 class="mb-3 fw-bold text-primary"><i class="fas fa-file-upload me-2"></i>তথ্য যাচাইয়ের জন্য পাঠান</h5>
                                <form id="factCheckRequestForm" enctype="multipart/form-data">
                                    @csrf
                                    <div class="mb-3">
                                        <label class="form-label small fw-bold text-muted">খবরের শিরোনাম <span class="text-danger">*</span></label>
                                        <input type="text" name="title" class="form-control" placeholder="যেমন: পদ্মা সেতুতে ফাটল..." required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label small fw-bold text-muted">লিংক (যদি থাকে)</label>
                                        <input type="url" name="link" class="form-control" placeholder="https://example.com/news...">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label small fw-bold text-muted">ছবি / স্ক্রিনশট (সর্বোচ্চ ১ মেগাবাইট)</label>
                                        <input type="file" name="image" class="form-control" accept="image/*">
                                        <div class="form-text text-muted small">শুধুমাত্র jpg, png, webp ফরম্যাট গ্রহণযোগ্য।</div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label small fw-bold text-muted">বিস্তারিত বিবরণ</label>
                                        <textarea name="description" id="summernote" class="form-control" rows="3" placeholder="ঘটনাটি সম্পর্কে বিস্তারিত লিখুন..."></textarea>
                                    </div>
                                    <div class="d-grid">
                                        <button type="submit" class="btn btn-primary fw-bold py-2" id="btnSubmitRequest">
                                            <i class="fas fa-paper-plane me-1"></i> রিপোর্ট জমা দিন
                                        </button>
                                    </div>
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
                <a href="{{ route('front.latest.news') }}" class="btn btn-outline-danger btn-sm rounded-pill px-3">সব দেখুন <i class="fas fa-arrow-right ms-1"></i></a>
            </div>
            
            <div class="row g-3 g-md-4">
                @forelse($recentFactChecks as $check)
                    @php
                        $title = 'শিরোনাম পাওয়া যায়নি';
                        $image = 'https://placehold.co/600x400/png?text=No+Image';
                        $detailsLink = '#';
                        $shortDesc = '';
                        $rawImagePath = '';

                        // ডাটা সোর্স নির্ধারণ
                        if ($check->post) {
                            $title = $check->post->title;
                            $rawImagePath = $check->post->image;
                            $detailsLink = route('front.news.details', $check->post->slug);
                            $shortDesc = Str::limit(strip_tags($check->post->content), 60);
                        } elseif ($check->factCheckRequest) {
                            $title = $check->factCheckRequest->title ?? $check->factCheckRequest->link;
                            $rawImagePath = $check->factCheckRequest->image;
                            $detailsLink = route('front.news.details', $check->factCheckRequest->id);
                            $shortDesc = Str::limit(strip_tags($check->factCheckRequest->description), 60);
                        }

                        // ছবি প্রদর্শন লজিক (admin_url বনাম fact_check_url)
                        if($rawImagePath) {
                            if (str_contains($rawImagePath, 'uploads/requests')) {

                                  $image = $front_admin_url . $rawImagePath;
                               
                            } else {

                                 $image = $front_fact_check_url . $rawImagePath;
                              
                            }
                        }

                        // ভার্ডিক্ট লজিক
                        $verdict = $check->verdict;
                        $badgeClass = 'bg-secondary'; $icon = 'fa-question-circle'; $verdictText = $verdict;

                        if (stripos($verdict, 'True') !== false || stripos($verdict, 'Likely True') !== false) {
                            $badgeClass = 'badge-true'; $icon = 'fa-check-circle'; $verdictText = 'সত্য';
                        } elseif (stripos($verdict, 'False') !== false || stripos($verdict, 'Fake') !== false) {
                            $badgeClass = 'badge-fake'; $icon = 'fa-times-circle'; $verdictText = 'মিথ্যা';
                        } elseif (stripos($verdict, 'Misleading') !== false) {
                            $badgeClass = 'bg-warning text-dark'; $icon = 'fa-exclamation-triangle'; $verdictText = 'বিভ্রান্তিকর';
                        } elseif (stripos($verdict, 'Altered') !== false) {
                            $badgeClass = 'bg-info text-dark'; $icon = 'fa-edit'; $verdictText = 'বিকৃত';
                        }
                    @endphp

                    <div class="col-6 col-md-4 col-lg-3">
                        <div class="card h-100 border-0 shadow-sm position-relative">
                            <div class="position-relative">
                                <img src="{{ $image }}" class="card-img-top" alt="{{ $title }}" style="height: 160px; object-fit: cover;">
                                <span class="news-badge {{ $badgeClass }}">
                                    <i class="fas {{ $icon }} me-1"></i> {{ $verdictText }}
                                </span>
                            </div>
                            <div class="card-body">
                                <h5 class="card-title fw-bold lh-base" style="font-size: 1rem;">
                                    <a href="{{ $detailsLink }}" class="text-dark text-decoration-none stretched-link">{{ Str::limit($title, 50) }}</a>
                                </h5>
                                <p class="card-text small text-muted mt-2 d-none d-sm-block">{{ $shortDesc }}</p>
                            </div>
                            <div class="card-footer bg-white border-0 text-muted small py-3">
                                <i class="far fa-clock me-1"></i> {{ $check->created_at->diffForHumans() }}
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center py-5"><p class="text-muted">বর্তমানে কোনো ফ্যাক্ট-চেক ডাটা নেই।</p></div>
                @endforelse
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
                       @foreach($social_links as $link)
                        @php
                            $stitle = strtolower($link->title);
                            $iconClass = 'fas fa-link'; $color = '#555';
                            if(str_contains($stitle, 'facebook')) { $iconClass = 'fab fa-facebook-f'; $color = '#1877F2'; }
                            elseif(str_contains($stitle, 'twitter') || $stitle == 'x') { $iconClass = 'fa-brands fa-x-twitter'; $color = '#000000'; }
                            elseif(str_contains($stitle, 'instagram')) { $iconClass = 'fab fa-instagram'; $color = '#E1306C'; }
                            elseif(str_contains($stitle, 'youtube')) { $iconClass = 'fab fa-youtube'; $color = '#FF0000'; }
                            elseif(str_contains($stitle, 'linkedin')) { $iconClass = 'fab fa-linkedin-in'; $color = '#0077B5'; }
                        @endphp
                        <div class="col">
                            <a href="{{ $link->link }}" target="_blank" class="social-card">
                                <i class="{{ $iconClass }}" style="color: {{ $color }};"></i>
                                <span>{{ $link->title }}</span>
                            </a>
                        </div>
                       @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
<script>
    $(document).ready(function() {
        $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });

        $('#summernote').summernote({
            placeholder: 'বিস্তারিত লিখুন...', tabsize: 2, height: 150,
            toolbar: [['font', ['bold', 'underline', 'clear']], ['para', ['ul', 'ol']], ['insert', ['link', 'picture']], ['view', ['codeview']]]
        });

        $('#factCheckRequestForm').on('submit', function(e) {
            e.preventDefault();
            let submitBtn = $('#btnSubmitRequest');
            submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> পাঠানো হচ্ছে...');

            $.ajax({
                url: "{{ route('front.request.submit') }}",
                method: "POST",
                data: new FormData(this),
                contentType: false, processData: false,
                success: function(response) {
                    if (response.status) {
                        Swal.fire({ icon: 'success', title: 'ধন্যবাদ!', text: response.message });
                        $('#factCheckRequestForm')[0].reset();
                        $('#summernote').summernote('reset');
                    } else {
                        Swal.fire({ icon: 'error', title: 'ওহ!', text: response.message });
                    }
                },
                error: function() { Swal.fire({ icon: 'error', title: 'ব্যর্থ!', text: 'সার্ভারে সমস্যা হয়েছে।' }); },
                complete: function() { submitBtn.prop('disabled', false).html('<i class="fas fa-paper-plane me-1"></i> রিপোর্ট জমা দিন'); }
            });
        });
    });
</script>
@endsection