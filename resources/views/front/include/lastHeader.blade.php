<div class="samakal-nav-wrapper sticky-nav-desktop-target">
    <div class="container samakal-navbar-container">
        <nav class="navbar navbar-expand-lg samakal-navbar p-0 position-static">
            
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                
                {{-- মেনু আইটেম --}}
                <ul class="navbar-nav me-auto mb-2 mb-lg-0 flex-wrap">
                    
                    {{-- ১. হোম /<i class="fa-solid fa-house"></i> --}}
                    <li class="nav-item">
                        <a class="nav-link ps-0" href="{{ route('front.index') }}">
                          <i class="fa-solid fa-house"></i>
                        </a>
                    </li>

                    {{-- ২. ক্যাটাগরি লুপ --}}
                    @foreach($header_categories as $key => $category)
                        @php $responsiveClass = ($key > 10) ? 'd-none d-xl-block' : ''; @endphp

                        @if($category->children->count() > 0)
                            <li class="nav-item dropdown {{ $responsiveClass }}">
                                <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                                    {{ $category->name }}
                                </a>
                                <ul class="dropdown-menu shadow border-0">
                                    <li><a class="dropdown-item fw-bold" href="{{ route('front.category.news', $category->slug) }}">{{ $category->name }} (All)</a></li>
                                    @foreach($category->children as $child)
                                        <li><a class="dropdown-item" href="{{ route('front.category.news', $child->slug) }}">{{ $child->name }}</a></li>
                                    @endforeach
                                </ul>
                            </li>
                        @else
                            <li class="nav-item {{ $responsiveClass }}">
                                <a class="nav-link" href="{{ route('front.category.news', $category->slug) }}">
                                    {{ $category->name }}
                                </a>
                            </li>
                        @endif
                    @endforeach
                    
                    {{-- ৩. বিবিধ --}}
                    @if($more_categories->count() > 0)
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">বিবিধ</a>
                            <ul class="dropdown-menu shadow border-0">
                                @foreach($more_categories as $moreCategory)
                                    <li><a class="dropdown-item" href="{{ route('front.category.news', $moreCategory->slug) }}">{{ $moreCategory->name }}</a></li>
                                @endforeach
                            </ul>
                        </li>
                    @endif
                </ul>

                {{-- ডান পাশ: সুন্দর সার্চ ট্রিগার --}}
                <div class="d-flex align-items-center position-relative">
                    <button class="btn border-0 p-2" type="button" id="searchToggleBtn" style="font-size: 18px; color: #333;">
                        <i class="fas fa-search"></i>
                    </button>
                    
                    {{-- নতুন স্টাইলিশ সার্চ বক্স --}}
                    <div class="search-dropdown-box" id="desktopSearchBox">
                        <form action="{{ route('front.search') }}" method="GET">
                            <div class="input-group">
                                <input class="form-control border-secondary rounded-0" name="q" type="search" placeholder="কী খুঁজতে চান? লিখুন..." aria-label="Search">
                                <button class="btn btn-danger rounded-0" type="submit">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </nav>
    </div>
</div>

{{-- সার্চ টগল স্ক্রিপ্ট (ছোট স্ক্রিপ্টটি এখানেই রাখুন বা futter এ) --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const toggleBtn = document.getElementById('searchToggleBtn');
        const searchBox = document.getElementById('desktopSearchBox');

        // টগল ক্লিক ইভেন্ট
        if(toggleBtn && searchBox) {
            toggleBtn.addEventListener('click', function(e) {
                e.stopPropagation(); // বাটন ক্লিক করলে যেন উইন্ডো ইভেন্ট ফায়ার না হয়
                searchBox.classList.toggle('show');
                
                // আইকন পরিবর্তন (Search <-> Times)
                const icon = this.querySelector('i');
                if (searchBox.classList.contains('show')) {
                    icon.classList.remove('fa-search');
                    icon.classList.add('fa-times');
                    this.style.color = '#dc3545';
                } else {
                    icon.classList.remove('fa-times');
                    icon.classList.add('fa-search');
                    this.style.color = '#333';
                }
            });

            // বাইরে ক্লিক করলে বন্ধ হবে
            window.addEventListener('click', function(e) {
                if (!searchBox.contains(e.target) && !toggleBtn.contains(e.target)) {
                    searchBox.classList.remove('show');
                    // আইকন রিসেট
                    const icon = toggleBtn.querySelector('i');
                    icon.classList.remove('fa-times');
                    icon.classList.add('fa-search');
                    toggleBtn.style.color = '#333';
                }
            });
        }
    });
</script>