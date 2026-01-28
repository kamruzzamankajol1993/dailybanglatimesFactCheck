@extends('front.master.master')

@section('title')
{{ $category->name ?? 'News' }} | {{ $front_ins_name ?? '' }} 
@endsection

@section('css')
<style>
    /* --- Search Header Section (From your design) --- */
    .search-header-bg {
        background: linear-gradient(rgba(12, 45, 72, 0.9), rgba(12, 45, 72, 0.9)), url('https://images.unsplash.com/photo-1504711434969-e33886168f5c?auto=format&fit=crop&w=1600&q=80');
        background-size: cover;
        background-position: center;
        padding: 60px 0;
        color: white;
    }

    .custom-search-input {
        height: 55px;
        font-size: 1.1rem;
        border-radius: 50px 0 0 50px;
        border: none;
        padding-left: 30px;
    }
    .custom-search-btn {
        border-radius: 0 50px 50px 0;
        padding: 0 40px;
        font-weight: bold;
        font-size: 1.1rem;
        background-color: var(--accent);
        border: none;
        color: white;
    }
    .custom-search-btn:hover { background-color: #900000; }

    /* --- Filter Sidebar (From your design) --- */
    .filter-card {
        background: white;
        border-radius: 10px;
        border: 1px solid #eee;
        box-shadow: 0 2px 10px rgba(0,0,0,0.03);
    }
    .form-check-input:checked {
        background-color: var(--accent);
        border-color: var(--accent);
    }

    /* --- Result Cards (From your design) --- */
    .result-card {
        background: white;
        border-radius: 10px;
        overflow: hidden;
        transition: 0.3s;
        border: 1px solid #eee;
        height: 100%;
        position: relative; /* Badge positioning */
    }
    .result-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.08);
    }
    .result-img {
        height: 200px;
        object-fit: cover;
        width: 100%;
        transition: transform 0.3s;
    }
    .result-card:hover .result-img {
        transform: scale(1.05);
    }
    .badge-status {
        position: absolute;
        top: 15px; right: 15px;
        padding: 5px 12px;
        font-size: 0.8rem;
        border-radius: 5px;
        color: white;
        font-weight: bold;
        z-index: 10;
        box-shadow: 0 2px 5px rgba(0,0,0,0.2);
    }
    .meta-info { font-size: 0.85rem; color: #777; }

    /* --- Custom Pagination CSS --- */
    .custom-pagination {
        display: flex;
        gap: 5px;
        align-items: center;
        justify-content: center;
    }
    .custom-pagination .page-link {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 40px;
        height: 40px;
        border: 1px solid #ddd;
        color: #333;
        text-decoration: none;
        font-weight: bold;
        border-radius: 50%; /* Circle design like search page */
        transition: all 0.3s ease;
        background: #fff;
        cursor: pointer;
    }
    .custom-pagination .page-link:hover {
        background-color: #f8f9fa;
        color: #dc3545;
        border-color: #dc3545;
    }
    .custom-pagination .page-link.active {
        background-color: #dc3545;
        color: #fff;
        border-color: #dc3545;
    }
    .custom-pagination .page-link.disabled {
        color: #ccc;
        pointer-events: none;
        background: #f9f9f9;
        border-color: #eee;
    }

    /* --- Loading Overlay --- */
    #loading-overlay {
        position: absolute;
        top: 0; left: 0; right: 0; bottom: 0;
        background: rgba(255, 255, 255, 0.8);
        z-index: 50;
        display: none;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
    }
    
    #post-data-container {
        position: relative;
        min-height: 400px;
        transition: opacity 0.3s ease;
    }
</style>
@endsection

@section('body')
<section class="py-5 category-body">
    <div class="container">
        <div class="row">
            
            {{-- Sidebar / Filter Section --}}
            <div class="col-lg-3 mb-4">
                <div class="filter-card p-4 sticky-top" style="top: 100px; z-index: 1;">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="fw-bold m-0"><i class="fas fa-filter me-2 text-danger"></i>ফিল্টার</h5>
                        <button class="btn btn-sm btn-link text-decoration-none text-muted" id="resetFilters">রিসেট</button>
                    </div>
                    <hr>
                    
                    <div class="mb-4">
                        <h6 class="fw-bold mb-2 text-dark">{{ $category->name }}</h6>
                        <p class="small text-muted">এই ক্যাটাগরির যাচাইকৃত খবরের তালিকা।</p>
                    </div>

                    {{-- Verdict Filter --}}
                    <div class="mb-4">
                        <h6 class="fw-bold mb-2 text-dark">ফলাফলের ধরন</h6>
                        <div class="form-check">
                            <input class="form-check-input filter-checkbox" type="checkbox" value="fake" id="checkFake">
                            <label class="form-check-label" for="checkFake">মিথ্যা (Fake)</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input filter-checkbox" type="checkbox" value="true" id="checkTrue">
                            <label class="form-check-label" for="checkTrue">সত্য (True)</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input filter-checkbox" type="checkbox" value="misleading" id="checkMisleading">
                            <label class="form-check-label" for="checkMisleading">বিভ্রান্তিকর</label>
                        </div>
                    </div>

                    {{-- Date Filter --}}
                    <div class="mb-4">
                        <h6 class="fw-bold mb-2 text-dark">তারিখ</h6>
                        <select class="form-select form-select-sm border-secondary-subtle" id="dateFilter">
                            <option value="all">যেকোনো সময়</option>
                            <option value="24h">গত ২৪ ঘন্টা</option>
                            <option value="7d">গত ১ সপ্তাহ</option>
                            <option value="30d">গত ১ মাস</option>
                        </select>
                    </div>

                    {{-- Apply Button (Optional functionality, as change triggers ajax) --}}
                    {{-- <button class="btn btn-primary w-100 fw-bold" id="applyFilterBtn">এপ্লাই ফিল্টার</button> --}}
                </div>
            </div>

            {{-- Main Content Section --}}
            <div class="col-lg-9">
                <div id="post-data-container">
                    
                    {{-- Loading Overlay --}}
                    <div id="loading-overlay">
                        <div class="spinner-border text-danger" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>

                    {{-- Dynamic Content Loaded Here --}}
                    @include('front.news._category_posts_partial')

                </div>
            </div>

        </div>
    </div>
</section>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        
        // ১. ফিল্টার চেঞ্জ ইভেন্ট (Checkbox & Select)
        $('.filter-checkbox, #dateFilter').on('change', function() {
            fetchFilteredData();
        });

        // ২. রিসেট বাটন
        $('#resetFilters').on('click', function() {
            $('.filter-checkbox').prop('checked', false);
            $('#dateFilter').val('all');
            fetchFilteredData();
        });

        // ৩. প্যাজিনেশন ক্লিক হ্যান্ডলিং
        $(document).on('click', '.custom-pagination .page-link', function(event) {
            event.preventDefault();
            let pageUrl = $(this).attr('href');
            
            if(!pageUrl || pageUrl === '#') return;
            
            // ফিল্টার প্যারামিটার সহ লোড করা
            loadData(pageUrl);
        });

        // মেইন ফাংশন: বর্তমান ফিল্টার স্টেট থেকে URL তৈরি করে
        function fetchFilteredData() {
            let verdicts = [];
            $('.filter-checkbox:checked').each(function() {
                verdicts.push($(this).val());
            });

            let dateFilter = $('#dateFilter').val();
            
            // বর্তমান URL বেস নেওয়া
            let baseUrl = window.location.href.split('?')[0];
            
            // কুয়েরি প্যারামস তৈরি
            let params = new URLSearchParams();
            
            if(verdicts.length > 0) {
                verdicts.forEach(v => params.append('verdict[]', v));
            }
            
            if(dateFilter !== 'all') {
                params.append('date_filter', dateFilter);
            }

            let finalUrl = baseUrl + '?' + params.toString();
            loadData(finalUrl);
        }

        // AJAX লোডার ফাংশন
        function loadData(url) {
            // লোডিং এনিমেশন
            $('#loading-overlay').fadeIn(200);
            $('#post-data-container .row').css('opacity', '0.3');

            $.ajax({
                url: url,
                type: "get",
                datatype: "html",
            })
            .done(function(data) {
                $('#post-data-container').html(data);
                
                // Overlay পুনঃস্থাপন (কারণ html() আগের সব মুছে দেয়)
                $('#post-data-container').prepend(`
                    <div id="loading-overlay" style="display:none; position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: rgba(255, 255, 255, 0.8); z-index: 50; align-items: center; justify-content: center; border-radius: 10px;">
                        <div class="spinner-border text-danger" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                `);

                // URL আপডেট (প্যাজিনেশন ও রিলোডের সুবিধার্থে)
                window.history.pushState({path: url}, '', url);
                
                // স্ক্রল আপ
                $('html, body').animate({
                    scrollTop: $(".category-body").offset().top - 80
                }, 500);
            })
            .fail(function() {
                alert('ডাটা লোড করতে সমস্যা হয়েছে।');
                $('#loading-overlay').fadeOut();
                $('#post-data-container .row').css('opacity', '1');
            });
        }

    });
</script>
@endsection