<footer>
    <div class="container">
        <div class="row">
            {{-- Column 1: Logo & Description --}}
            <div class="col-lg-4 mb-5">
                <a href="{{ url('/') }}" class="d-block mb-4 text-decoration-none">
                    @if(!empty($front_mobile_version_logo))
                        <img src="{{ $front_admin_url }}{{ $front_mobile_version_logo }}" alt="{{ $front_ins_name }}" style="max-height: 70px; width: auto;">
                    @else
                        <h4 class="text-white en-font">{{ $front_ins_name }}</h4>
                    @endif
                </a>
                <p class="small text-secondary pe-lg-4">
                    {{ Str::limit($front_ins_d, 350) }}
                </p>
            </div>

            {{-- Column 2: Quick Links --}}
            <div class="col-lg-2 col-6 mb-4">
                <h5 class="text-white mb-3">কুইক লিংকস</h5>
                <ul class="list-unstyled small text-secondary">
                    <li><a href="{{ url('/') }}" class="text-decoration-none text-secondary d-block py-1 hover-white">হোম</a></li>
                    <li><a href="{{ route('front.aboutUs') }}" class="text-decoration-none text-secondary d-block py-1 hover-white">আমাদের সম্পর্কে</a></li>
                    <li><a href="{{ route('front.privacyPolicy') }}" class="text-decoration-none text-secondary d-block py-1 hover-white">গোপনীয়তা নীতি</a></li>
                    <li><a href="{{ route('front.termsCondition') }}" class="text-decoration-none text-secondary d-block py-1 hover-white">শর্তাবলী</a></li>
                </ul>
            </div>

            {{-- Column 3: Categories --}}
            <div class="col-lg-2 col-6 mb-4">
                <h5 class="text-white mb-3">ক্যাটাগরি</h5>
                <ul class="list-unstyled small text-secondary">
                    @if(isset($header_categories))
                        @foreach($header_categories->take(5) as $category)
                        <li>
                            <a href="{{ route('front.category.news', $category->slug) }}" class="text-decoration-none text-secondary d-block py-1 hover-white">
                                {{ $category->name }}
                            </a>
                        </li>
                        @endforeach
                    @endif
                </ul>
            </div>

            {{-- Column 4: Contact (UPDATED) --}}
            <div class="col-lg-4 mb-4">
                <h5 class="text-white mb-3">যোগাযোগ</h5>
                <ul class="list-unstyled small text-secondary">
                    
                    {{-- Bangladesh Address --}}
                    @if(!empty($front_ins_add))
                    <li class="mb-3">
                        <div class="d-flex">
                            <i class="fas fa-map-marker-alt mt-1 me-2 text-danger"></i> 
                            <div>
                                <strong class="d-block text-white">বাংলাদেশ অফিস:</strong>
                                {{ $front_ins_add }}
                            </div>
                        </div>
                    </li>
                    @endif

                    {{-- US Office Address (New) --}}
                    @if(!empty($front_us_office_address))
                    <li class="mb-3">
                        <div class="d-flex">
                            <i class="fas fa-globe-americas mt-1 me-2 text-danger"></i> 
                            <div>
                                <strong class="d-block text-white">ইউএস অফিস:</strong>
                                {{ $front_us_office_address }}
                            </div>
                        </div>
                    </li>
                    @endif

                    {{-- Email --}}
                    @if(!empty($front_ins_email))
                    <li class="mb-2">
                        <i class="fas fa-envelope me-2 text-danger"></i> 
                        {{ $front_ins_email }}
                    </li>
                    @endif

                    {{-- Phone --}}
                    @if(!empty($front_ins_phone))
                    <li class="mb-2">
                        <i class="fas fa-phone me-2 text-danger"></i> 
                        {{ $front_ins_phone }}
                    </li>
                    @endif
                </ul>
            </div>
        </div>
    </div>
    
    <div class="footer-bottom border-top border-secondary py-3 mt-4" style="border-color: #222 !important;">
        <div class="container text-center">
            <small class="text-secondary">
                &copy; {{ date('Y') }} {{ $front_ins_name ?? 'FactCheckBD' }}। সর্বস্বত্ব সংরক্ষিত।
            </small>
        </div>
    </div>
</footer>