<style>
    /* ল্যাপটপ এবং ছোট ডেক্সটপ (992px থেকে 1350px) এর জন্য সেটিংস */
    @media (min-width: 992px) and (max-width: 1350px) {
        .responsive-central-box {
            /* বাটন কমার কারণে জায়গা বেড়েছে, তাই সার্চ বার বড় করা হলো */
            max-width: 300px !important; 
        }
        .top-time-text {
            font-size: 11px !important; 
        }
        .btn-custom-responsive {
            font-size: 11px !important;
            padding-left: 5px !important;
            padding-right: 5px !important;
        }
        .social-responsive {
            width: 24px !important;
            height: 24px !important;
            line-height: 24px !important;
            font-size: 11px !important;
        }
    }

    /* বড় ডেক্সটপ (1350px এর উপরে) */
    @media (min-width: 1351px) {
        .responsive-central-box {
            max-width: 500px !important; 
        }
    }
</style>

<div class="top-bar bg-white py-2 d-none d-lg-block border-bottom-0">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center">
            
            {{-- ১. বাম পাশ: সময় --}}
            <div class="flex-shrink-0" style="width: 28%;">
                <small class="text-dark fw-bold top-time-text" id="banglaTime">
                    <i class="far fa-clock"></i> লোড হচ্ছে...
                </small>
            </div>
            
            {{-- ২. মাঝখান: সার্চ বার (সাইজ রেসপন্সিভ ক্লাসের মাধ্যমে কন্ট্রোল হবে) --}}
            <div class="flex-grow-1 d-flex justify-content-center px-1">
                <form class="d-flex w-100 responsive-central-box" action="{{ route('front.search') }}" method="GET">
                    <input class="form-control rounded-0 shadow-none" name="q" type="search" placeholder="অনুসন্ধান..." aria-label="Search" style="border-color: #ddd; font-size: 14px;">
                    <button class="btn btn-success rounded-0" type="submit" style="background:#006a4e; border-color:#006a4e;">
                        <i class="fas fa-search"></i>
                    </button>
                </form>
            </div>

            {{-- ৩. ডান পাশ: বাটন ও আইকন --}}
            {{-- বাংলা বাটন সরানোর ফলে এখানে জায়গা কমেছে, তাই width 35% থেকে কমিয়ে 30% করা যেতে পারে --}}
            <div class="flex-shrink-0 d-flex justify-content-end align-items-center" style="width: 30%;">
                
                {{-- বাটন গ্রুপ (শুধু English এবং Fact Check) --}}
                <div class="d-flex align-items-center gap-1 me-2">
                    {{-- বাংলা বাটনটি সরিয়ে ফেলা হয়েছে --}}
                    <a href="{{$front_english_url}}" target="_blank" class="btn btn-success btn-sm rounded-0 fw-bold px-2 btn-custom-responsive" style="background:#006a4e; border:none; font-size: 13px;">English</a>
                    <a href="#" class="btn btn-success btn-sm rounded-0 fw-bold px-2 btn-custom-responsive" style="background:#006a4e; border:none; font-size: 13px;">Fact Check</a>
                </div>
                
                {{-- সোশ্যাল আইকন --}}
                <div class="d-flex align-items-center">
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

                            <a href="{{ $link->link }}" target="_blank" class="social-icon social-responsive {{ $customClass }} ms-1" style="width: 28px; height: 28px; line-height: 28px; font-size: 13px;">
                                <i class="fab {{ $iconClass }}"></i>
                            </a>
                        @endforeach
                    @endif
                </div>

            </div>

        </div>
    </div>
</div>