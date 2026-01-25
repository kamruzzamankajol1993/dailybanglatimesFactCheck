<div class="offcanvas offcanvas-start" tabindex="-1" id="mobileMenu" style="width: 300px;">
    
    {{-- Header: White Background with Original Logo --}}
    <div class="offcanvas-header bg-white d-flex align-items-center justify-content-between border-bottom">
        <img src="{{ $front_admin_url }}{{ $front_logo_name }}" alt="Logo" class="img-fluid" style="max-height: 40px;">
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    
    <div class="offcanvas-body p-0 d-flex flex-column bg-white">
        
        <div class="mobile-nav-list flex-grow-1 overflow-auto custom-scrollbar">
            <div class="list-group list-group-flush">
                
                {{-- ১. হোম লিংক --}}
                <a href="{{ route('front.index') }}" class="list-group-item list-group-item-action">
                    <i class="fas fa-home me-2"></i> প্রচ্ছদ
                </a>

                {{-- ২. ক্যাটাগরি লুপ (প্রথম ১৬টি) --}}
                @foreach($header_categories as $category)
                    @if($category->children->count() > 0)
                        {{-- সাব-ক্যাটাগরি থাকলে অ্যাকর্ডিয়ন হবে --}}
                        <div class="accordion-item border-bottom">
                            <div class="list-group-item list-group-item-action d-flex justify-content-between align-items-center" 
                                 role="button" 
                                 data-bs-toggle="collapse" 
                                 data-bs-target="#mobileMenu{{ $category->id }}"
                                 aria-expanded="false">
                                {{ $category->name }} 
                                <i class="fas fa-chevron-down small text-muted"></i>
                            </div>
                            <div id="mobileMenu{{ $category->id }}" class="collapse bg-light">
                                {{-- প্যারেন্ট লিংক --}}
                                <a href="{{ route('front.category.news', $category->slug) }}" class="list-group-item list-group-item-action ps-4 border-0 small fw-bold">
                                    <i class="fas fa-angle-right me-1"></i> {{ $category->name }} (All)
                                </a>
                                {{-- চাইল্ড লুপ --}}
                                @foreach($category->children as $child)
                                    <a href="{{ route('front.category.news', $child->slug) }}" class="list-group-item list-group-item-action ps-4 border-0 small">
                                        <i class="fas fa-angle-right me-1"></i> {{ $child->name }}
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @else
                        {{-- সাব-ক্যাটাগরি না থাকলে সাধারণ লিংক --}}
                        <a href="{{ route('front.category.news', $category->slug) }}" class="list-group-item list-group-item-action">
                            {{ $category->name }}
                        </a>
                    @endif
                @endforeach

                {{-- ৩. বাকি ক্যাটাগরি লুপ (বিবিধ-এর ভেতরের গুলো মোবাইলে নিচে দেখাবে) --}}
                @if(isset($more_categories) && count($more_categories) > 0)
                    <div class="list-group-item bg-light text-muted small fw-bold text-uppercase mt-2">অন্যান্য</div>
                    @foreach($more_categories as $category)
                        @if($category->children->count() > 0)
                            <div class="accordion-item border-bottom">
                                <div class="list-group-item list-group-item-action d-flex justify-content-between align-items-center" 
                                     role="button" 
                                     data-bs-toggle="collapse" 
                                     data-bs-target="#mobileMenuMore{{ $category->id }}">
                                    {{ $category->name }} 
                                    <i class="fas fa-chevron-down small text-muted"></i>
                                </div>
                                <div id="mobileMenuMore{{ $category->id }}" class="collapse bg-light">
                                    <a href="{{ route('front.category.news', $category->slug) }}" class="list-group-item list-group-item-action ps-4 border-0 small fw-bold">
                                        <i class="fas fa-angle-right me-1"></i> {{ $category->name }} (All)
                                    </a>
                                    @foreach($category->children as $child)
                                        <a href="{{ route('front.category.news', $child->slug) }}" class="list-group-item list-group-item-action ps-4 border-0 small">
                                            <i class="fas fa-angle-right me-1"></i> {{ $child->name }}
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @else
                            <a href="{{ route('front.category.news', $category->slug) }}" class="list-group-item list-group-item-action">
                                {{ $category->name }}
                            </a>
                        @endif
                    @endforeach
                @endif

            </div>
        </div>

        {{-- Footer: Dynamic Social Icons --}}
        <div class="offcanvas-footer p-4 border-top text-center bg-white mt-auto">
            <h6 class="fw-bold mb-3 text-secondary text-uppercase" style="letter-spacing: 1px;">FOLLOW US</h6>
            <div class="d-flex justify-content-center gap-2">
                @if(isset($social_links))
                    @foreach($social_links as $link)
                        @php
                            $title = strtolower($link->title ?? '');
                            $customClass = ''; 
                            $iconClass = 'fa-globe'; 

                            if (str_contains($title, 'facebook')) {
                                $customClass = 'fb';
                                $iconClass = 'fa-facebook-f';
                            } elseif (str_contains($title, 'youtube')) {
                                $customClass = 'yt';
                                $iconClass = 'fa-youtube';
                            } elseif (str_contains($title, 'twitter') || str_contains($title, 'x')) {
                                $customClass = 'tw';
                                $iconClass = 'fa-x-twitter';
                            } elseif (str_contains($title, 'instagram')) {
                                $customClass = 'insta';
                                $iconClass = 'fa-instagram';
                            } elseif (str_contains($title, 'linkedin')) {
                                $customClass = 'in';
                                $iconClass = 'fa-linkedin-in';
                            }
                        @endphp

                        <a href="{{ $link->link }}" target="_blank" class="mobile-social-btn {{ $customClass }}">
                            <i class="fab {{ $iconClass }}"></i>
                        </a>
                    @endforeach
                @endif
            </div>
        </div>

    </div>
</div>