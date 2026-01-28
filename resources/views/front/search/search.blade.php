@extends('front.master.master')

@section('title')
অনুসন্ধান - {{ $query }}
@endsection

@section('css')
 <style>
        /* --- SEARCH PAGE SPECIFIC CSS --- */
        
        /* Compact Hero Search Bar */
        .search-hero-compact {
            /* আগের var(--primary) সরিয়ে গাঢ় ধূসর কালার দেওয়া হলো */
            background: #2c3e50; 
            padding: 30px 0;
            color: white;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            border-bottom: 5px solid rgba(0,0,0,0.1); /* সামান্য বর্ডার ডিজাইন */
        }
        
        /* Sidebar CTA */
        .request-widget {
            background: white;
            border: 2px dashed #ccc;
            padding: 30px;
            text-align: center;
            border-radius: 10px;
        }

        /* Loading Overlay */
        #loading-overlay {
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(255, 255, 255, 0.8);
            z-index: 10;
            display: none;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
        }
        
        #search-results-container {
            position: relative;
            min-height: 200px;
            transition: opacity 0.3s ease;
        }
</style>
@endsection

@section('body')

 <section class="search-hero-compact">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <form action="{{ route('front.search') }}" method="GET" class="d-flex position-relative">
                        <input class="form-control form-control-lg border-0 rounded-pill px-4" 
                               type="text" 
                               name="q" 
                               value="{{ $query }}" 
                               placeholder="কিওয়ার্ড দিয়ে খুঁজুন..." 
                               required>
                        <button class="btn btn-danger rounded-pill px-4 position-absolute end-0 top-0 bottom-0 m-1 fw-bold" type="submit">সার্চ</button>
                    </form>
                    
                    @if(!empty($query))
                        <p class="text-center mt-2 mb-0 small opacity-75">
                            সার্চ রেজাল্ট দেখানো হচ্ছে: <strong>"{{ $query }}"</strong>-এর জন্য
                        </p>
                    @endif
                </div>
            </div>
        </div>
    </section>

    <section class="py-5">
        <div class="container">
            <div class="row">
                
                {{-- MAIN CONTENT --}}
                <div class="col-lg-8">
                    
                    @if($results->count() > 0)
                        <h5 class="mb-4 text-muted">মোট {{ $results->total() }}টি যাচাইকৃত তথ্য পাওয়া গেছে:</h5>
                    @endif

                    {{-- Dynamic Results Container --}}
                    <div id="search-results-container">
                        {{-- Loading Spinner (Hidden by default) --}}
                        <div id="loading-overlay">
                            <div class="spinner-border text-danger" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>

                        {{-- Include the Partial View --}}
                        @include('front.search.search_results_partial')
                    </div>

                </div>

                {{-- SIDEBAR --}}
                <div class="col-lg-4 ps-lg-4 mt-5 mt-lg-0">
                    
                    {{-- 
                        FIX: 
                        1. style="top: 120px;" -> হেডারের হাইট অনুযায়ী নিচে নামানো হয়েছে।
                        2. z-index: 1; -> যাতে এটি মেনুর উপরে না উঠে যায়। 
                    --}}
                    <div class="request-widget sticky-top" style="top: 120px; z-index: 1;">
                        <div class="mb-3">
                            <i class="fas fa-search-minus fa-3x text-muted"></i>
                        </div>
                        <h4 class="fw-bold">কাঙ্ক্ষিত তথ্য পাননি?</h4>
                        <p class="text-muted small">আপনার সার্চ করা বিষয় নিয়ে হয়তো এখনো কেউ ফ্যাক্ট-চেক করেনি। আমাদের কাছে রিকোয়েস্ট পাঠান।</p>
                        
                        {{-- Redirect to Home Page Upload Tab --}}
                        <a href="{{ route('front.index') }}#upload" class="btn btn-primary w-100 mb-2">
                            <i class="fas fa-paper-plane me-2"></i>চেক করার জন্য পাঠান
                        </a>
                        <p class="small text-muted mt-2">আমরা যাচাই করে আপনাকে জানাবো।</p>
                    </div>

                    <div class="mt-5">
                        <h6 class="fw-bold border-bottom pb-2 mb-3">জনপ্রিয় ক্যাটাগরি</h6>
                        <div class="d-flex flex-wrap gap-2">
                            @forelse($categories as $cat)
                                <a href="{{ route('front.search', ['category' => $cat->id]) }}" class="badge bg-light text-dark border p-2 fw-normal text-decoration-none">
                                    {{ $cat->name }}
                                </a>
                            @empty
                                <span class="text-muted small">কোনো ক্যাটাগরি পাওয়া যায়নি।</span>
                            @endforelse
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </section>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        
        // Handle Custom Pagination Click for Search
        $(document).on('click', '.custom-pagination .page-link', function(event) {
            event.preventDefault(); 
            
            let pageUrl = $(this).attr('href');
            
            if(!pageUrl || pageUrl === '#' || $(this).hasClass('disabled') || $(this).hasClass('active')) {
                return;
            }

            // 1. Show Loading State
            $('#loading-overlay').fadeIn(200);
            $('#search-results-container').css('opacity', '0.6');

            // 2. Perform AJAX
            $.ajax({
                url: pageUrl,
                type: "get",
                datatype: "html",
            })
            .done(function(data) {
                // 3. Update Content
                $('#search-results-container').html(data);
                
                // Re-prepend loading overlay structure because .html() wiped it out
                $('#search-results-container').prepend(`
                    <div id="loading-overlay" style="display: none;">
                        <div class="spinner-border text-danger" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                `);

                // 4. Update URL History
                window.history.pushState({path: pageUrl}, '', pageUrl);
                
                // 5. Scroll to top of results
                $('html, body').animate({
                    scrollTop: $("#search-results-container").offset().top - 150
                }, 500);

                // 6. Restore Opacity
                $('#search-results-container').css('opacity', '1');
            })
            .fail(function(jqXHR, ajaxOptions, thrownError) {
                alert('সার্ভার থেকে রেসপন্স পাওয়া যায়নি।');
                $('#loading-overlay').fadeOut();
                $('#search-results-container').css('opacity', '1');
            });
        });
    });
</script>
@endsection