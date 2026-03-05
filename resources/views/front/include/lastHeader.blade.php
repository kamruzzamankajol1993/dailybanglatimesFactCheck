<nav class="navbar navbar-expand-lg main-navbar d-none d-lg-block sticky-top">
    <div class="container justify-content-center">
        <ul class="navbar-nav align-items-center">
            {{-- হোম --}}
            <li class="nav-item"><a class="nav-link" href="{{ url('/') }}">হোম</a></li>
            
            {{-- ক্যাটাগরি --}}
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="{{ route('front.latest.news') }}" role="button" data-bs-toggle="dropdown" aria-expanded="false">ক্যাটাগরি</a>
                <ul class="dropdown-menu">
                    @if(isset($header_categories))
                        @foreach($header_categories as $category)
                            <li>
                                <a class="dropdown-item" href="{{ route('front.category.news', $category->slug) }}">
                                    {{ $category->name }}
                                </a>
                            </li>
                        @endforeach
                    @endif
                </ul>
            </li>

            {{-- অন্যান্য স্ট্যাটিক মেনু --}}
            <li class="nav-item"><a class="nav-link" href="{{route('front.factFile')}}">ফ্যাক্ট ফাইল</a></li>
            <li class="nav-item"><a class="nav-link" href="{{route('front.mediaLiteracy')}}">মিডিয়া লিটারেসি</a></li>

            {{-- আমাদের সম্পর্কে --}}
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">আমাদের সম্পর্কে</a>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="{{ route('front.aboutUs') }}">আমাদের মিশন</a></li>
                    <li><a class="dropdown-item" href="{{ route('front.team') }}">টিম মেম্বার</a></li>
                    <li><a class="dropdown-item" href="{{ route('front.methodology') }}">কাজের পদ্ধতি</a></li>
                </ul>
            </li>

            {{-- যোগাযোগ --}}
            <li class="nav-item"><a class="nav-link" href="{{ route('front.contactUs') }}">যোগাযোগ</a></li>

            {{-- আমাদের নেটওয়ার্ক (অটো ড্রপডাউন) --}}
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle fw-bold" href="#" id="networkDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false" >
                    <i class="fas fa-th-large me-1"></i> আমাদের নেটওয়ার্ক
                </a>
                <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 py-2" aria-labelledby="networkDropdown" style="min-width: 220px; border-top: 3px solid var(--accent) !important;">
                    <li class="px-3 py-1 small text-muted text-uppercase fw-bold" style="font-size: 10px; letter-spacing: 0.5px;">নিউজ পোর্টাল</li>
                    
                    {{-- বাংলা নিউজ সাইট --}}
                    <li>
                        <a class="dropdown-item d-flex align-items-center py-2" href="{{ $front_front_url }}" target="_blank">
                            <div class="bg-danger rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 24px; height: 24px;">
                                <i class="fas fa-newspaper text-white" style="font-size: 11px;"></i>
                            </div>
                            <span>বাংলা নিউজপেপার সাইট</span>
                        </a>
                    </li>

                    <li><hr class="dropdown-divider mx-2"></li>

                    {{-- ইংরেজি নিউজ সাইট --}}
                    <li>
                        <a class="dropdown-item d-flex align-items-center py-2" href="{{ $front_english_url }}" target="_blank">
                            <div class="bg-dark rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 24px; height: 24px;">
                                <i class="fas fa-globe text-white" style="font-size: 11px;"></i>
                            </div>
                            <span>English News Site</span>
                        </a>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
</nav>