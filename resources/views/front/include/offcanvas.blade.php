<div class="offcanvas offcanvas-start" tabindex="-1" id="mobileMenu">
    <div class="offcanvas-header border-bottom">
        <a href="{{ url('/') }}">
             {{-- Logo --}}
            <img src="{{ $front_admin_url }}{{ $front_mobile_version_logo }}" alt="Logo" style="max-height: 50px; width: auto;">
        </a>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
    </div>
    <div class="offcanvas-body d-flex flex-column">
        <ul class="navbar-nav mb-5">
            {{-- Home Link --}}
            <li class="nav-item">
                <a class="nav-link text-dark border-bottom py-2 fw-bold" href="{{ url('/') }}">হোম</a>
            </li>

            {{-- Dynamic Categories Loop --}}
            @if(isset($header_categories))
                @foreach($header_categories as $category)
                    @if($category->children->count() > 0)
                        {{-- Dropdown for Parent Categories with Children --}}
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle text-dark border-bottom py-2 fw-bold" href="#" data-bs-toggle="dropdown">
                                {{ $category->name }}
                            </a>
                            <ul class="dropdown-menu border-0 bg-light">
                                {{-- Parent Link --}}
                                <li>
                                    <a class="dropdown-item ps-4 fw-bold" href="{{ route('front.category.news', $category->slug) }}">
                                        সব দেখুন
                                    </a>
                                </li>
                                {{-- Children Links --}}
                                @foreach($category->children as $child)
                                    <li>
                                        <a class="dropdown-item ps-4" href="{{ route('front.category.news', $child->slug) }}">
                                            {{ $child->name }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </li>
                    @else
                        {{-- Single Link for Categories without Children --}}
                        <li class="nav-item">
                            <a class="nav-link text-dark border-bottom py-2 fw-bold" href="{{ route('front.category.news', $category->slug) }}">
                                {{ $category->name }}
                            </a>
                        </li>
                    @endif
                @endforeach
            @endif

            {{-- Static Pages (Linked to Routes) --}}
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle text-dark border-bottom py-2 fw-bold" href="#" data-bs-toggle="dropdown">আমাদের সম্পর্কে</a>
                <ul class="dropdown-menu border-0 bg-light">
                    <li><a class="dropdown-item ps-4" href="{{ route('front.aboutUs') }}">মিশন ও ভিশন</a></li>
                    <li><a class="dropdown-item ps-4" href="{{ route('front.team') }}">টিম মেম্বার</a></li>
                     <li><a class="dropdown-item ps-4" href="{{ route('front.methodology') }}">কাজের পদ্ধতি</a></li>
                </ul>
            </li>
             <li class="nav-item"><a class="nav-link text-dark border-bottom py-2 fw-bold" href="{{route('front.factFile')}}">ফ্যাক্ট ফাইল</a></li>
            <li class="nav-item"><a class="nav-link text-dark border-bottom py-2 fw-bold" href="{{route('front.mediaLiteracy')}}">মিডিয়া লিটারেসি</a></li>
            <li class="nav-item">
                <a class="nav-link text-dark border-bottom py-2 fw-bold" href="{{ route('front.contactUs') }}">যোগাযোগ</a>
            </li>
        </ul>

        {{-- Footer Social Icons --}}
        <div class="mt-auto text-center">
            <p class="small text-muted fw-bold mb-3">ফলো করুন</p>
            <div class="d-flex justify-content-center gap-2 mb-4">
                @foreach($social_links as $link)
                    @php
                        $title = strtolower($link->title);
                        $iconClass = 'fas fa-link';
                        $brandClass = '';

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
            </div>
        </div>
    </div>
</div>