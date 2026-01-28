<nav class="navbar navbar-expand-lg main-navbar d-none d-lg-block sticky-top">
    <div class="container justify-content-center">
        <ul class="navbar-nav">
            {{-- হোম --}}
            <li class="nav-item"><a class="nav-link" href="{{ url('/') }}">হোম</a></li>
            
            {{-- ক্যাটাগরি (এখানে ডায়নামিক ক্যাটাগরিগুলো লুপ করা হয়েছে) --}}
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
        </ul>
    </div>
</nav>