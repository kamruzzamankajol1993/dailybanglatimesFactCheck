<section class="sharadesh-district-section py-4 bg-light">
    <div class="container">
        <div class="row g-4">

            {{-- ১. সারা দেশ নিউজ সেকশন (Left Side) --}}
            <div class="col-lg-5">
                <div class="section-header-wrapper mb-3" style="border-bottom: 2px solid #dc3545;">
                    @php
            $saraSlug = (isset($saradeshNews) && count($saradeshNews) > 0) ? ($saradeshNews->first()->categories->first()->slug ?? '#') : '#';
        @endphp
        <a href="{{ $saraSlug != '#' ? route('front.category.news', $saraSlug) : '#' }}" class="text-white text-decoration-none">
                    <h5 class="bg-success text-white d-inline-block px-3 py-2 m-0 fw-bold">সারা দেশ</h5>
        </a>
                </div>

                @if(isset($saradeshNews) && count($saradeshNews) > 0)
                    @php $mainSaradesh = $saradeshNews->first(); @endphp
                    <div class="card border-0 bg-white h-100 sharadesh-card">
                        <div class="position-relative">
                            <a href="{{ route('front.news.details', $mainSaradesh->slug) }}">
                                <img  onerror="this.onerror=null;this.src='{{ $front_admin_url }}{{ $front_logo_name }}';" src="{{ $mainSaradesh->image ? $front_admin_url.$mainSaradesh->image : 'https://placehold.co/500x300/222/fff?text=Saradesh' }}" 
                                     class="card-img-top rounded-0" 
                                     alt="{{ $mainSaradesh->title }}">
                                <span class="camera-icon-box"><i class="fas fa-camera"></i></span>
                            </a>
                        </div>
                        <div class="card-body px-0 pt-3 pb-2">
                            <h4 class="card-title fw-bold hover-red">
                                <a href="{{ route('front.news.details', $mainSaradesh->slug) }}" class="text-dark text-decoration-none">
                                    {{ $mainSaradesh->title }}
                                </a>
                                    <small class="bangla-date"><i class="far fa-clock me-1"></i>{{ bangla_date($mainSaradesh->created_at) }}</small>
                            </h4>
                            <p class="card-text text-secondary mt-2 text-justify small">
                                @if($mainSaradesh->subtitle)
                                    {{ Str::limit($mainSaradesh->subtitle, 150) }}
                                @else
                                    {{ Str::limit(strip_tags($mainSaradesh->content), 150) }}
                                @endif
                            </p>
                        </div>
                    </div>
                @else
                    <div class="text-muted py-3">খবর পাওয়া যায়নি।</div>
                @endif
            </div>

            {{-- ২. সারা দেশ নিউজ লিস্ট (Middle Side) --}}
            <div class="col-lg-4">
                <div class="mb-5 d-none d-lg-block"></div> {{-- Spacer to align with title --}}
                <div class="row g-3">
                    @if(isset($saradeshNews) && count($saradeshNews) > 1)
                        @foreach($saradeshNews->slice(1, 4) as $news)
                            <div class="col-6">
                                <div class="card border-0 bg-white h-100 sharadesh-card">
                                    <div class="position-relative">
                                        <a href="{{ route('front.news.details', $news->slug) }}">
                                            <img  onerror="this.onerror=null;this.src='{{ $front_admin_url }}{{ $front_logo_name }}';" src="{{ $news->image ? $front_admin_url . $news->image : 'https://placehold.co/200x120/333/fff?text=News' }}" 
                                                 class="card-img-top rounded-0" 
                                                 alt="{{ $news->title }}"
                                                 style="height: 100px; object-fit: cover;">
                                        </a>

                                    </div>
                                    <div class="card-body px-0 pt-2 pb-0">
                                        <h6 class="fw-bold m-0 lh-sm small hover-red">
                                            <a href="{{ route('front.news.details', $news->slug) }}" class="text-dark text-decoration-none">
                                                {{ Str::limit($news->title, 40) }}
                                            </a>
                                            <small class="bangla-date"><i class="far fa-clock me-1"></i>{{ bangla_date($news->created_at) }}</small>
                                        </h6>
                                        
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>

            {{-- ৩. বিভাগ ও জেলা ফিল্টার (Right Side - Dynamic) --}}
            <div class="col-lg-3">
                <div class="section-header-wrapper mb-3" style="border-bottom: 2px solid #dc3545;">
                    <h6 class="bg-success text-white d-inline-block px-3 py-2 m-0 fw-bold">এক ক্লিকে বিভাগের সব খবর</h6>
                </div>

                <div class="card border-0 rounded-0 p-3 shadow-sm bg-white">
                    <form id="locationFilterForm">
                        <div class="row g-2 mb-3">
                            
                            {{-- Division Dropdown --}}
                            <div class="col-12 mb-2">
                                <label class="form-label small fw-bold text-secondary">বিভাগ বাছাই করুন</label>
                                <select id="divisionSelect" class="form-select form-select-sm rounded-0 bg-light border-secondary">
                                    <option value="" selected disabled>বিভাগ...</option>
                                    @if(isset($divisions))
                                        @foreach($divisions as $division)
                                            {{-- Only show categories that act as divisions (you can filter by specific IDs if needed) --}}
                                            <option value="{{ $division->id }}">{{ $division->name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>

                            {{-- District Dropdown (Populated by JS) --}}
                            <div class="col-12">
                                <label class="form-label small fw-bold text-secondary">জেলা বাছাই করুন</label>
                                <select id="districtSelect" class="form-select form-select-sm rounded-0 bg-light border-secondary" disabled>
                                    <option value="" selected disabled>জেলা...</option>
                                </select>
                            </div>
                        </div>
                        
                        <button type="button" onclick="goToLocationNews()" class="btn btn-danger w-100 rounded-0 fw-bold btn-sm py-2">
                            <i class="fas fa-search me-1"></i> অনুসন্ধান করুন
                        </button>
                    </form>
                </div>
                
                {{-- Optional: Ad Space below filter --}}
                <div class="mt-4 text-center">
                     <div class="bg-light border d-flex align-items-center justify-content-center text-muted small" style="height: 150px;">
                        AD SPACE
                     </div>
                </div>
            </div>

        </div>
    </div>
</section>

{{-- Script for Dynamic Dropdown Logic --}}
<script>
    (function() {
        // 1. Get Data from Blade to JS
        const divisionsData = @json($divisions ?? []);

        const divisionSelect = document.getElementById('divisionSelect');
        const districtSelect = document.getElementById('districtSelect');

        // 2. Handle Division Change
        if(divisionSelect) {
            divisionSelect.addEventListener('change', function() {
                const selectedId = this.value;
                
                // Reset District Dropdown
                districtSelect.innerHTML = '<option value="" selected disabled>জেলা...</option>';
                districtSelect.disabled = true;

                // Find the selected division object from the array
                const selectedDivision = divisionsData.find(div => div.id == selectedId);

                // If division has children (districts), populate them
                if (selectedDivision && selectedDivision.children && selectedDivision.children.length > 0) {
                    selectedDivision.children.forEach(district => {
                        const option = document.createElement('option');
                        option.value = district.slug; // We use Slug for URL
                        option.text = district.name;
                        districtSelect.add(option);
                    });
                    districtSelect.disabled = false;
                }
            });
        }

        // 3. Handle Search Button Click
        window.goToLocationNews = function() {
            const districtSlug = districtSelect.value;
            const divisionId = divisionSelect.value;
            
            // Base Route URL (We use a placeholder to be replaced)
            let baseUrl = "{{ route('front.category.news', ':slug') }}";

            if (districtSlug && districtSlug !== "") {
                // Redirect to District Page
                window.location.href = baseUrl.replace(':slug', districtSlug);
            } 
            else if (divisionId) {
                // If only Division is selected, find its slug and redirect
                const selectedDivision = divisionsData.find(div => div.id == divisionId);
                if(selectedDivision && selectedDivision.slug) {
                    window.location.href = baseUrl.replace(':slug', selectedDivision.slug);
                }
            } else {
                // Use SweetAlert if available, otherwise standard alert
                if(typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'warning',
                        title: 'লক্ষ্য করুন',
                        text: 'দয়া করে বিভাগ অথবা জেলা নির্বাচন করুন।',
                        confirmButtonColor: '#dc3545'
                    });
                } else {
                    alert('দয়া করে বিভাগ অথবা জেলা নির্বাচন করুন।');
                }
            }
        }
    })();
</script>