<footer>
    {{-- স্কোপড CSS (Scoped CSS) --}}
    <style>
        /* Modern Footer Scoped Styles */
        .modern-footer {
            font-family: 'Hind Siliguri', sans-serif;
            background-color: #111;
            color: #b0b0b0;
            font-size: 16px;
        }
        
        /* Top Section Styling */
        .modern-footer-top {
            background-color: #1a1a1a;
            border-bottom: 1px solid #333;
            padding: 40px 0;
        }
        .footer-logo-img {
            transition: all 0.3s ease;
            filter: drop-shadow(0px 0px 1px #fff);
            max-width: 250px;
            width: 100%;
            height: auto;
            margin-bottom: 20px;
        }
        .footer-logo-img:hover {
            filter: grayscale(100%) drop-shadow(0px 0px 0px transparent);
        }

        /* Buttons Styling */
        .lang-btn {
            border: 1px solid #fff;
            color: #fff;
            border-radius: 50px;
            padding: 6px 25px;
            font-size: 15px;
            transition: 0.3s;
            text-decoration: none;
            font-weight: 600;
        }
        .lang-btn:hover {
            background: #fff;
            color: #000;
        }
        
        /* Fact Check Button */
        .fact-check-btn {
            background-color: #dc3545;
            color: #fff;
            border: 1px solid #dc3545;
            border-radius: 50px;
            padding: 6px 25px;
            font-size: 15px;
            font-weight: 600;
            text-decoration: none;
            transition: 0.3s;
        }
        .fact-check-btn:hover {
            background-color: #bb2d3b;
            color: #fff;
        }

        /* Contributor Button */
        .contributor-btn {
            background-color: transparent;
            color: #dc3545;
            border: 1px solid #dc3545;
            border-radius: 4px;
            padding: 5px 15px;
            font-size: 13px;
            font-weight: 600;
            text-decoration: none;
            transition: 0.3s;
            display: inline-block;
            margin-top: 5px;
            text-transform: uppercase;
            cursor: pointer;
        }
        .contributor-btn:hover {
            background-color: #dc3545;
            color: #fff;
        }

        /* Social Icons */
        .social-btn-modern {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            border-radius: 6px;
            background: #2a2a2a;
            color: #fff;
            margin: 0 5px;
            transition: 0.3s;
            font-size: 18px;
            border: 1px solid #444;
            text-decoration: none;
        }
        .social-btn-modern:hover { transform: translateY(-3px); border-color: transparent; color: #fff; }
        .social-btn-modern.fb:hover { background: #1877F2; }
        .social-btn-modern.yt:hover { background: #FF0000; }
        .social-btn-modern.tw:hover { background: #000; } 
        .social-btn-modern.insta:hover { background: linear-gradient(45deg, #f09433, #dc2743, #bc1888); }
        .social-btn-modern.in:hover { background: #0077b5; }

        /* Links List */
        .modern-link-list li { margin-bottom: 12px; }
        .modern-link-list a {
            color: #ccc; text-decoration: none; display: inline-flex; align-items: center; transition: 0.2s; 
            font-size: 16px;
        }
        .modern-link-list a:before {
            content: "•"; color: #dc3545; margin-right: 10px; font-size: 20px; line-height: 1;
        }
        .modern-link-list a:hover { color: #fff; padding-left: 5px; }

        /* Publisher Card */
        .publisher-card {
            background-color: #1f1f1f;
            border-radius: 12px;
            border: 1px solid #333;
            padding: 25px;
            position: relative;
        }
        
        .publisher-avatar {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            border: 2px solid #dc3545;
            padding: 2px;
            object-fit: cover;
            cursor: pointer;
            transition: transform 0.3s;
        }
        .publisher-avatar:hover {
            transform: scale(1.05);
            border-color: #fff;
        }

        .publisher-name-link {
            font-size: 22px;
            font-weight: 700;
            color: #fff;
            text-decoration: none;
            transition: 0.3s;
            display: inline-block;
            line-height: 1.2;
            cursor: pointer;
        }
        .publisher-name-link:hover {
            color: #dc3545;
            text-decoration: underline;
        }

        .dashed-divider {
            border-top: 1px dashed #444;
            margin: 20px 0;
        }
        
        /* Contact Info Styling */
        .contact-row a {
            color: #ccc;
            text-decoration: none;
            display: flex;
            align-items: center;
            margin-bottom: 8px;
            transition: 0.2s;
            font-size: 15px;
        }
        .contact-row a:hover { color: #fff; }
        .contact-icon {
            color: #dc3545;
            margin-right: 10px;
            font-size: 16px;
            width: 20px;
            text-align: center;
        }

        /* Office Box */
        .office-box {
            background: #1f1f1f; 
            border-left: 3px solid #dc3545;
            padding: 15px;
            border-radius: 0 4px 4px 0;
            height: 100%; /* Height 100% for Equal Height Columns */
        }

        /* Titles */
        .footer-title {
            color: #fff; font-weight: 700; font-size: 20px; margin-bottom: 25px; position: relative; padding-bottom: 10px; border-bottom: 2px solid #333; display: inline-block;
        }
        .footer-title::after {
            content: ''; position: absolute; left: 0; bottom: -2px; width: 50px; height: 2px; background: #dc3545;
        }
        
        .about-text {
            font-size: 16px;
            line-height: 1.8;
            text-align: justify;
            color: rgba(255,255,255,0.7);
        }
    </style>

    <div class="modern-footer">
        
        {{-- Top Section --}}
        <div class="modern-footer-top">
            <div class="container">
                <div class="row align-items-center gy-4">
                    
                    {{-- Col 1: Logo & Buttons --}}
                    <div class="col-lg-5 col-md-12">
                        <div class="d-flex flex-column align-items-center text-center">
                            
                            <a href="#" class="d-inline-block">
                                <img src="{{ $front_admin_url }}{{ $front_bangla_footer_logo }}" 
                                     alt="Logo" 
                                     class="footer-logo-img">
                            </a>
                            
                            <div class="d-flex gap-3 justify-content-center flex-wrap">
                                <a href="{{$front_front_url}}" target="_blank" class="lang-btn">বাংলা</a>
                                <a href="{{$front_english_url}}" target="_blank" class="lang-btn">English</a>
                                <a href="{{ $front_fact_check_url }}" class="fact-check-btn">
                                    <i class="fas fa-check-circle me-1"></i> Fact Check
                                </a>
                            </div>

                        </div>
                    </div>

                    {{-- Col 2: Social Icons --}}
                    <div class="col-lg-3 col-md-6 text-center">
                        <h6 class="text-white fw-bold mb-3 text-uppercase small" style="letter-spacing: 2px;">FOLLOW US</h6>
                        <div class="d-flex justify-content-center">
                            @if(isset($social_links))
                                @foreach($social_links as $link)
                                    @php
                                        $title = strtolower($link->title ?? '');
                                        $customClass = ''; 
                                        $iconClass = 'fa-globe'; 

                                        if (str_contains($title, 'facebook')) { $customClass = 'fb'; $iconClass = 'fa-facebook-f'; }
                                        elseif (str_contains($title, 'youtube')) { $customClass = 'yt'; $iconClass = 'fa-youtube'; }
                                        elseif (str_contains($title, 'twitter') || str_contains($title, 'x')) { $customClass = 'tw'; $iconClass = 'fa-x-twitter'; }
                                        elseif (str_contains($title, 'instagram')) { $customClass = 'insta'; $iconClass = 'fa-instagram'; }
                                        elseif (str_contains($title, 'linkedin')) { $customClass = 'in'; $iconClass = 'fa-linkedin-in'; }
                                    @endphp
                                    <a href="{{ $link->link }}" target="_blank" class="social-btn-modern {{ $customClass }}">
                                        <i class="fab {{ $iconClass }}"></i>
                                    </a>
                                @endforeach
                            @endif
                        </div>
                    </div>

                    {{-- Col 3: Useful Links --}}
                    <div class="col-lg-4 col-md-6">
                        <div class="row">
                            <div class="col-6">
                                <ul class="list-unstyled modern-link-list mb-0">
                                    <li><a href="{{ route('front.aboutUs') }}">About Us</a></li>
                                    <li><a href="{{ route('front.contactUs') }}">Contact Us</a></li>
                                    <li><a href="{{ route('front.team') }}">Team</a></li>
                                </ul>
                            </div>
                            <div class="col-6">
                                <ul class="list-unstyled modern-link-list mb-0">
                                    <li><a href="{{ route('front.termsCondition') }}">Terms</a></li>
                                    <li><a href="{{ route('front.privacyPolicy') }}">Privacy</a></li>
                                    <li><a href="{{ route('front.archive') }}">Archive</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Main Section --}}
        <div class="footer-main-content py-5">
            <div class="container">
                <div class="row g-5">
                    
                    {{-- Left Side: About --}}
                    <div class="col-lg-6">
                        <h5 class="footer-title">About Daily Bangla Times</h5>
                        <p class="about-text">
                           {!! strip_tags($front_long_description, '<br><b><strong>') !!}
                        </p>
                    </div>

                    {{-- Right Side: Publisher & Offices --}}
                    <div class="col-lg-6">
                        <h5 class="footer-title">Publisher & Contact</h5>
                        
                        {{-- Publisher Card --}}
                        <div class="publisher-card mb-4">
                            <div class="d-flex align-items-center mb-3">
                                <img src="{{asset('/')}}public/front/images/bnn.jpg" 
                                     class="publisher-avatar me-3" 
                                     alt="Publisher"
                                     data-bs-toggle="modal" 
                                     data-bs-target="#publisherModal">
                                
                                <div>
                                    <a href="#" class="publisher-name-link" data-bs-toggle="modal" data-bs-target="#publisherModal">
                                        Zahid F Sarder Saddi
                                    </a>
                                    
                                    <div class="text-white-50 small my-1">
                                        <i class="fas fa-pen-nib text-danger me-1"></i> Publisher and Editor-in-Chief
                                    </div>

                                    <a href="{{ route('front.contributor') }}" class="contributor-btn">
                                        Contributor Profile
                                    </a>
                                </div>
                            </div>

                            <div class="dashed-divider"></div>

                            <div class="row contact-row">
                                <div class="col-sm-6 mb-3 mb-sm-0">
                                    <a href="tel:{{ $front_ins_phone }}">
                                        <i class="fas fa-phone-alt contact-icon"></i> {{ $front_ins_phone }}
                                    </a>
                                    <a href="tel:{{ $front_ins_phone_one }}">
                                        <i class="fas fa-phone-alt contact-icon"></i> {{ $front_ins_phone_one }}
                                    </a>
                                </div>
                                <div class="col-sm-6">
                                    <a href="mailto:{{$front_ins_email}}">
                                        <i class="fas fa-envelope contact-icon"></i> {{$front_ins_email}}
                                    </a>
                                    <a href="mailto:{{$front_email_one}}">
                                        <i class="fas fa-envelope contact-icon"></i> {{$front_email_one}}
                                    </a>
                                </div>
                            </div>
                        </div>

                        {{-- Offices Side by Side --}}
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="office-box h-100">
                                    <h6 class="text-white fw-bold mb-2 text-uppercase small">Bangladesh Office</h6>
                                    <p class="mb-0 text-white-50 small">
                                        <i class="fas fa-map-marker-alt me-2 text-danger"></i>{{$front_ins_add}}
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="office-box h-100">
                                    <h6 class="text-white fw-bold mb-2 text-uppercase small">USA Office</h6>
                                    <p class="mb-0 text-white-50 small">
                                        <i class="fas fa-map-marker-alt me-2 text-danger"></i> {{ $front_us_office_address }}
                                    </p>
                                </div>
                            </div>
                        </div>

                    </div>

                </div>
            </div>
        </div>

        {{-- Footer Bottom --}}
        <div class="py-4" style="background-color: #000; border-top: 1px solid #222;">
            <div class="container text-center">
                <p class="text-white small m-0 mb-2">
                    এই ওয়েবসাইটে প্রকাশিত সংবাদ, আলোকচিত্র ও ভিডিওচিত্র বিনা অনুমতিতে অন্য কোথাও প্রকাশ করা সম্পূর্ণ বেআইনি। সকল স্বত্ব dailybanglatimes.com কর্তৃক সংরক্ষিত
                </p>
                <p class="text-white-50 small m-0">
                    © Copyright 2008-{{ date('Y') }} <strong>{{ $front_ins_name }}</strong>. Design & Developed by <a href="#" class="text-danger text-decoration-none fw-bold">Daily Bangla Times IT</a>
                </p>
            </div>
        </div>

    </div>

    {{-- Scroll To Top --}}
    <button id="scrollToTopBtn" class="btn btn-danger rounded-circle position-fixed bottom-0 end-0 m-4 d-none shadow-lg" style="width: 45px; height: 45px; z-index: 999; border: 2px solid #fff;">
        <i class="fas fa-arrow-up text-white"></i>
    </button>

    {{-- Publisher Modal (Image Pop-up) --}}
    <div class="modal fade" id="publisherModal" tabindex="-1" aria-labelledby="publisherModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content bg-transparent border-0">
                <div class="modal-header border-0">
                    <button type="button" class="btn-close btn-close-white ms-auto" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <img src="{{asset('/')}}public/front/images/bnn.jpg" class="img-fluid rounded shadow-lg" style="max-height: 80vh;" alt="Publisher">
                    <h5 class="text-white mt-3 fw-bold">Zahid F Sarder Saddi</h5>
                    <p class="text-white-50 small">Publisher and Editor-in-Chief</p>
                </div>
            </div>
        </div>
    </div>

</footer>

{{-- Script --}}
<script>
    document.addEventListener("DOMContentLoaded", function() {
        var scrollBtn = document.getElementById("scrollToTopBtn");

        window.onscroll = function() {
            if (document.body.scrollTop > 300 || document.documentElement.scrollTop > 300) {
                scrollBtn.classList.remove("d-none");
            } else {
                scrollBtn.classList.add("d-none");
            }
        };

        scrollBtn.addEventListener("click", function() {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    });
</script>