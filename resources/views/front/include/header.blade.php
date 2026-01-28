<header class="newspaper-header py-4 d-none d-lg-block">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-4">
                <div class="d-flex align-items-center border-end pe-3">
                    <span class="badge bg-danger px-2 py-1 me-2">LIVE</span>
                    <marquee scrollamount="5" class="text-secondary small fw-bold">
                        ফ্যাক্টচেক ডেইলি বাংলা টাইমস -তে স্বাগতম। যাচাই ছাড়া সংবাদ শেয়ার করবেন না...
                    </marquee>
                </div>
            </div>

            <div class="col-4 text-center">
                <a href="{{ url('/') }}" class="text-decoration-none d-block">
                    {{-- আপনার লোগোর ইমেজের সোর্স নিচে দিন --}}
                    <img src="{{ $front_admin_url }}{{ $front_mobile_version_logo }}" alt="FactCheckBD Logo" class="img-fluid" style="max-height: 80px; width: auto;">
                </a>
            </div>

            <div class="col-4">
                <div class="d-flex justify-content-end align-items-center header-social">
    @foreach($social_links as $link)
        @php
            // টাইটেল ছোট হাতের অক্ষরে কনভার্ট করা হচ্ছে ম্যাচিংয়ের সুবিধার্থে
            $title = strtolower($link->title); 
            $iconClass = 'fas fa-link'; // ডিফল্ট আইকন
            $brandClass = '';

            // টাইটেল চেক করে আইকন সেট করা হচ্ছে
            if(str_contains($title, 'facebook')) {
                $iconClass = 'fab fa-facebook-f';
                $brandClass = 'sb-fb';
            } elseif(str_contains($title, 'twitter') || $title == 'x') {
                $iconClass = 'fa-brands fa-x-twitter';
                $brandClass = 'sb-x';
            } elseif(str_contains($title, 'instagram')) {
                $iconClass = 'fab fa-instagram';
                $brandClass = 'sb-in';
            } elseif(str_contains($title, 'youtube')) {
                $iconClass = 'fab fa-youtube';
                $brandClass = 'sb-yt';
            } elseif(str_contains($title, 'linkedin')) {
                $iconClass = 'fab fa-linkedin-in';
                $brandClass = 'sb-li';
            }
        @endphp

        <a href="{{ $link->link }}" target="_blank" class="social-btn {{ $brandClass }}" title="{{ $link->title }}">
            <i class="{{ $iconClass }}"></i>
        </a>
    @endforeach
    
    <div class="vr mx-3" style="height: 30px;"></div> 
    <a href="{{ route('front.search') }}" class="btn btn-outline-dark btn-sm rounded-circle d-flex align-items-center justify-content-center" style="width: 35px; height: 35px;">
                        <i class="fas fa-search"></i>
                    </a>
</div>
            </div>
        </div>
    </div>
</header>

<header class="bg-white py-3 border-bottom sticky-top d-lg-none shadow-sm">
    <div class="container d-flex justify-content-between align-items-center">
        <button class="btn btn-link text-dark p-0" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileMenu">
            <i class="fas fa-bars fa-lg"></i>
        </button>
        
        <a href="{{ url('/') }}" class="text-dark fw-bold text-decoration-none">
            {{-- মোবাইলের জন্য লোগোর হাইট একটু কম রাখা ভালো --}}
            <img src="{{ $front_admin_url }}{{ $front_mobile_version_logo }}" alt="Logo" style="max-height: 45px; width: auto;">
        </a>

        {{-- Mobile Search Icon Link --}}
        <a href="{{ route('front.search') }}" class="btn btn-link text-dark p-0">
            <i class="fas fa-search"></i>
        </a>
    </div>
</header>