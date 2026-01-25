<header class="bg-white mb-0 sticky-top-mobile">
    
    {{-- মোবাইল ভিউ (অপরিবর্তিত) --}}
    <div class="container d-lg-none py-2 border-bottom">
         <div class="d-flex justify-content-between align-items-center">
            <button class="btn fs-3 border-0 p-0" data-bs-toggle="offcanvas" data-bs-target="#mobileMenu">
                <i class="fas fa-bars"></i>
            </button>
            <a href="{{ route('front.index') }}">
                <img src="{{ $front_admin_url }}{{ $front_logo_name }}" alt="Logo" class="img-fluid" style="max-height: 40px;">
            </a>
            <button class="btn fs-3 border-0 p-0" type="button" data-bs-toggle="collapse" data-bs-target="#mobileSearchBox">
                <i class="fas fa-search"></i>
            </button>
        </div>
        <div class="collapse mt-2" id="mobileSearchBox">
            <form class="d-flex" action="{{ route('front.search') }}" method="GET">
                <input class="form-control rounded-0" name="q" type="search" placeholder="কি খুঁজছেন?..." aria-label="Search">
                <button class="btn btn-danger rounded-0" type="submit"><i class="fas fa-search"></i></button>
            </form>
        </div>
    </div>

    {{-- DESKTOP VIEW --}}
    <div class="header-top-wrapper d-none d-lg-block">
        <div class="container">
            <div class="row align-items-center">
                
                {{-- ১. তারিখ ও লোকেশন --}}
                {{-- ১. বাম পাশ: বড় সময় এবং বাংলা/ইংরেজি তারিখ --}}
<div class="col-4">
    <div class="d-flex flex-column justify-content-center h-100">
        
        {{-- সময় (ফন্ট সাইজ ছোট করা হয়েছে: 19px) --}}
        <h5 class="mb-0 fw-bold text-danger" id="realTimeDisplay" style="font-size: 19px; line-height: 1.2;">
            </h5>

        {{-- তারিখ --}}
        <small class="text-secondary fw-bold mt-1" id="fullDateDisplay" style="font-size: 11px;">
            </small>

    </div>
</div>

                {{-- ২. লোগো (সাইজ বড় করা হয়েছে: 95px) --}}
                <div class="col-4 text-center">
                    <a href="{{ route('front.index') }}">
                        <img src="{{ $front_admin_url }}{{ $front_logo_name }}" alt="Logo" class="img-fluid" style="max-height: 95px; width: auto;"> 
                    </a>
                </div>

                {{-- ৩. সোশ্যাল আইকন ও বাটন --}}
                {{-- ৩. ডান পাশ: সোশ্যাল আইকন এবং বাটন --}}
<div class="col-4 d-flex justify-content-end align-items-center">
    
    {{-- সোশ্যাল আইকন (রাউন্ড ব্যাকগ্রাউন্ড) --}}
    <div class="me-3 d-flex align-items-center">
        @if(isset($social_links))
            @foreach($social_links as $link)
                @php
                    $title = strtolower($link->title ?? '');
                    $iconClass = 'fa-globe'; 
                    if (str_contains($title, 'facebook')) { $iconClass = 'fa-facebook-f'; }
                    elseif (str_contains($title, 'youtube')) { $iconClass = 'fa-youtube'; }
                    elseif (str_contains($title, 'twitter') || str_contains($title, 'x')) { $iconClass = 'fa-x-twitter'; }
                    elseif (str_contains($title, 'instagram')) { $iconClass = 'fa-instagram'; }
                    elseif (str_contains($title, 'linkedin')) { $iconClass = 'fa-linkedin-in'; }
                    elseif (str_contains($title, 'whatsapp')) { $iconClass = 'fa-whatsapp'; }
                @endphp
                {{-- ক্লাস পরিবর্তন করা হয়েছে: social-icon-round --}}
                <a href="{{ $link->link }}" target="_blank" class="text-decoration-none">
                    <i class="fab {{ $iconClass }} social-icon-round"></i>
                </a>
            @endforeach
        @endif
    </div>

    {{-- বাটন (গ্রিন কালার) --}}
    <div class="d-flex gap-2">
        <a href="{{ $front_fact_check_url }}" class="header-btn">Fact Check</a>
        <a href="{{$front_english_url ?? '#'}}" class="header-btn">English</a>
    </div>

</div>
            </div>
            
            {{-- বিজ্ঞাপন --}}
            @if(isset($header_ad))
                <div class="text-center mt-2 d-none d-lg-block">
                     @if($header_ad->type == 1 && !empty($header_ad->image))
                        <a href="{{ $header_ad->link ?? 'javascript:void(0)' }}" target="_blank">
                            <img src="{{ $front_admin_url }}public/{{ $header_ad->image }}" class="img-fluid" style="max-height: 90px;">
                        </a>
                    @elseif($header_ad->type == 2 && !empty($header_ad->script))
                        {!! $header_ad->script !!}
                    @endif
                </div>
            @endif
        </div>
    </div>
</header>

<script>
    function updateDateTime() {
        const now = new Date();

        // ১. সময় ফরম্যাটিং (Time Formatting)
        const timeOptions = { hour: 'numeric', minute: 'numeric', second: 'numeric', hour12: true };
        let timeString = new Intl.DateTimeFormat('bn-BD', timeOptions).format(now);
        
        // AM/PM ফিক্স (অপশনাল, যদি ব্রাউজার ঠিকমতো না দেয়)
        timeString = timeString.replace('AM', 'পূর্বাহ্ণ').replace('PM', 'অপরাহ্ণ');

        // ২. ইংরেজি তারিখ (English Date in Bangla)
        const dateOptions = { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' };
        let engDateString = new Intl.DateTimeFormat('bn-BD', dateOptions).format(now);
        // 'শুক্র' কে 'শুক্রবার' করা
        if(engDateString.includes('শুক্র') && !engDateString.includes('শুক্রবার')) {
            engDateString = engDateString.replace('শুক্র', 'শুক্রবার');
        }

        // ৩. বাংলা সন কনভার্টার (Bangla Calendar Date)
        const banglaDateString = getBanglaDate(now);

        // HTML আপডেট করা
        document.getElementById('realTimeDisplay').innerHTML = '<i class="far fa-clock me-1"></i>' + timeString;
        document.getElementById('fullDateDisplay').innerHTML = engDateString + ' | ' + banglaDateString;
    }

    // বাংলা সন বের করার ফাংশন
    function getBanglaDate(date) {
        const day = date.getDate();
        const month = date.getMonth(); // 0-11
        const year = date.getFullYear();

        // বাংলা মাসের নাম
        const banglaMonths = [
            "বৈশাখ", "জ্যৈষ্ঠ", "আষাঢ়", "শ্রাবণ", "ভাদ্র", "আশ্বিন", 
            "কার্তিক", "অগ্রহায়ু", "পৌষ", "মাঘ", "ফাল্গুন", "চৈত্র"
        ];

        // ইংরেজি মাসের দিন অনুযায়ী বাংলা মাসের শুরুর দিন (মোটামুটি ১৪ এপ্রিল পহেলা বৈশাখ ধরে)
        // ১৪ এপ্রিল = ১ বৈশাখ
        // এই লজিকটি সাধারণ ব্যবহারের জন্য (Traditional)
        
        let banglaYear = year - 593;
        if (month < 3 || (month === 3 && day < 14)) {
            banglaYear = year - 594;
        }

        let banglaMonthIndex;
        let banglaDay;

        // মাসের হিসেব (Simplified Logic for standard use)
        if ((month === 3 && day >= 14) || (month === 4 && day <= 14)) {
            banglaMonthIndex = 0; // বৈশাখ
            banglaDay = (month === 3) ? day - 13 : day + 17; 
        } else if ((month === 4 && day >= 15) || (month === 5 && day <= 15)) {
            banglaMonthIndex = 1; // জ্যৈষ্ঠ
            banglaDay = (month === 4) ? day - 14 : day + 16;
        } else if ((month === 5 && day >= 16) || (month === 6 && day <= 16)) {
            banglaMonthIndex = 2; // আষাঢ়
            banglaDay = (month === 5) ? day - 15 : day + 15;
        } else if ((month === 6 && day >= 17) || (month === 7 && day <= 16)) {
            banglaMonthIndex = 3; // শ্রাবণ
            banglaDay = (month === 6) ? day - 16 : day + 15;
        } else if ((month === 7 && day >= 17) || (month === 8 && day <= 17)) {
            banglaMonthIndex = 4; // ভাদ্র
            banglaDay = (month === 7) ? day - 16 : day + 14;
        } else if ((month === 8 && day >= 18) || (month === 9 && day <= 17)) {
            banglaMonthIndex = 5; // আশ্বিন
            banglaDay = (month === 8) ? day - 17 : day + 13;
        } else if ((month === 9 && day >= 18) || (month === 10 && day <= 16)) {
            banglaMonthIndex = 6; // কার্তিক
            banglaDay = (month === 9) ? day - 17 : day + 14;
        } else if ((month === 10 && day >= 17) || (month === 11 && day <= 16)) {
            banglaMonthIndex = 7; // অগ্রহায়ু
            banglaDay = (month === 10) ? day - 16 : day + 14;
        } else if ((month === 11 && day >= 17) || (month === 0 && day <= 14)) {
            banglaMonthIndex = 8; // পৌষ
            banglaDay = (month === 11) ? day - 16 : day + 14;
        } else if ((month === 0 && day >= 15) || (month === 1 && day <= 13)) {
            banglaMonthIndex = 9; // মাঘ
            banglaDay = (month === 0) ? day - 14 : day + 17;
        } else if ((month === 1 && day >= 14) || (month === 2 && day <= 14)) {
            banglaMonthIndex = 10; // ফাল্গুন
            // লিপ ইয়ার চেক (সাধারণত ফাল্গুন মাস ২৯/৩০ হয়)
            banglaDay = (month === 1) ? day - 13 : day + 15; // সহজ লজিক
        } else {
            banglaMonthIndex = 11; // চৈত্র
            banglaDay = (month === 2) ? day - 14 : day + 16;
        }

        // সংখ্যা বাংলায় কনভার্ট করা
        const toBanglaNum = (n) => n.toString().replace(/\d/g, d => "০১২৩৪৫৬৭৮৯"[d]);

        return `${toBanglaNum(banglaDay)} ${banglaMonths[banglaMonthIndex]} ${toBanglaNum(banglaYear)}`;
    }

    // প্রতি সেকেন্ডে আপডেট
    setInterval(updateDateTime, 1000);
    updateDateTime(); // প্রথম লোড
</script>