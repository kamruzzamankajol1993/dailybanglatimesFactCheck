@extends('front.master.master')

@section('title')
অনুসন্ধান ফলাফল - {{ $searchQuery }}
@endsection

@section('css')
<style>
     /* --- LIST PAGE SPECIFIC CSS (আপনার দেওয়া ডিজাইন) --- */
    .page-header { background: #fff; padding: 40px 0; border-bottom: 1px solid #eee; margin-bottom: 40px; }
    .breadcrumb a { color: var(--accent); }
    
    /* News Card Horizontal */
    .news-card-horizontal { background: #fff; border: none; box-shadow: 0 2px 15px rgba(0,0,0,0.05); transition: 0.3s; margin-bottom: 30px; overflow: hidden; }
    .news-card-horizontal:hover { transform: translateY(-3px); box-shadow: 0 5px 20px rgba(0,0,0,0.1); }
    .news-card-horizontal .img-wrapper { position: relative; height: 100%; min-height: 220px; }
    .news-card-horizontal img { width: 100%; height: 100%; object-fit: cover; position: absolute; }
    
    /* Badge Verdict Styles */
    .badge-verdict { position: absolute; top: 10px; left: 10px; padding: 5px 12px; font-weight: bold; color: white; border-radius: 4px; font-size: 0.75rem; text-transform: uppercase; z-index: 2; }
    .bg-fake { background: #dc3545; }
    .bg-true { background: #198754; }
    .bg-misleading { background: #ffc107; color: #000; }
    .bg-unverified { background: #6c757d; }
    
    .card-meta { font-size: 0.85rem; color: #777; margin-bottom: 10px; }
    .card-title { font-weight: 700; margin-bottom: 15px; line-height: 1.4; }
    .card-title a:hover { color: var(--accent); }

    /* Sidebar */
    .sidebar-widget { background: #fff; padding: 25px; margin-bottom: 30px; box-shadow: 0 2px 15px rgba(0,0,0,0.05); border-top: 3px solid #dc3545; }
    .widget-title { font-size: 1.2rem; font-weight: 700; margin-bottom: 20px; border-bottom: 1px solid #eee; padding-bottom: 10px; }
    .cat-list li { border-bottom: 1px solid #f4f4f4; padding: 10px 0; display: flex; justify-content: space-between; }
    .cat-list li a { color: #555; font-weight: 500; }
    .cat-list li a:hover { color: #dc3545; }
    .cat-count { background: #eee; font-size: 0.75rem; padding: 2px 8px; border-radius: 10px; }
    
    /* Pagination CSS (আপনার ডিজাইনের সাথে মিলিয়ে) */
    .pagination .page-link { color: #dc3545; border: none; width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; margin: 0 5px; border-radius: 50%; font-weight: 600; cursor: pointer; }
    .pagination .page-item.active .page-link { background-color: #dc3545; color: white; }
    .pagination .page-item.disabled .page-link { color: #ccc; background: transparent; }

    /* Loading Spinner */
    .loading-overlay { display: none; text-align: center; padding: 20px; }
</style>
@endsection

@section('body')
<section class="pb-5 pt-4">
    <div class="container">
        <h4 class="mb-4">অনুসন্ধান ফলাফল: "{{ $searchQuery }}"</h4>
        
        <div class="row">
            
            <div class="col-lg-8">
                
                {{-- লোডিং স্পিনার (AJAX লোডের সময় দেখাবে) --}}
                <div class="loading-overlay" id="loadingSpinner">
                    <div class="spinner-border text-danger" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>

                {{-- রেজাল্ট কন্টেইনার (এখানে পার্শিয়াল ভিউ লোড হবে) --}}
                <div id="resultsWrapper">
                    @include('front.search._search_results_partial')
                </div>

            </div>

            <div class="col-lg-4 ps-lg-4">
                
                {{-- সার্চ উইজেট --}}
                <div class="sidebar-widget">
                    <h4 class="widget-title">নতুন অনুসন্ধান</h4>
                    <form action="{{ route('front.factCheck.search') }}" method="GET">
                        <div class="input-group">
                            <input type="text" name="query" class="form-control" placeholder="কিওয়ার্ড লিখুন..." required>
                            <button class="btn btn-dark"><i class="fas fa-search"></i></button>
                        </div>
                    </form>
                </div>

                {{-- ক্যাটাগরি উইজেট --}}
                <div class="sidebar-widget">
                    <h4 class="widget-title">জনপ্রিয় ক্যাটাগরি</h4>
                    <ul class="list-unstyled cat-list m-0">
                        @foreach($recentCategories as $cat)
                        <li>
                            {{-- লিংক আপাতত একই থাকছে --}}
                            <a href="{{ route('front.category.news', $cat->slug) }}">{{ $cat->name }}</a> 
                            
                            {{-- পরিবর্তন: এখানে ফ্যাক্ট চেক রিকোয়েস্টের কাউন্ট দেখানো হচ্ছে --}}
                            <span class="cat-count">{{ $cat->fact_check_requests_count }}</span>
                        </li>
                        @endforeach
                    </ul>
                </div>

                {{-- রিপোর্ট উইজেট --}}
                <div class="sidebar-widget text-center bg-light border-0">
                    <i class="fas fa-bullhorn fa-3x text-danger mb-3"></i>
                    <h5>সন্দেহজনক খবর দেখেছেন?</h5>
                    <p class="small text-muted">আমাদের কাছে পাঠান, আমরা যাচাই করব।</p>
                    <a href="{{ route('front.index') }}#upload" class="btn btn-danger w-100 fw-bold">রিপোর্ট করুন</a>
                </div>

            </div>
        </div>
    </div>
</section>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // AJAX Pagination Click Event
        $(document).on('click', '.pagination a.page-link', function(e) {
            e.preventDefault();
            
            let url = $(this).attr('href');
            if(!url || url === '#') return;

            // লোডিং দেখাই
            $('#resultsWrapper').css('opacity', '0.5');
            $('#loadingSpinner').show();

            // AJAX কল
            $.ajax({
                url: url,
                type: 'GET',
                success: function(response) {
                    $('#resultsWrapper').html(response);
                    $('#resultsWrapper').css('opacity', '1');
                    $('#loadingSpinner').hide();
                    
                    // পেজের উপরে স্ক্রল করা
                    $('html, body').animate({
                        scrollTop: $("#resultsWrapper").offset().top - 100
                    }, 500);
                },
                error: function(xhr) {
                    console.log('Error:', xhr);
                    $('#resultsWrapper').css('opacity', '1');
                    $('#loadingSpinner').hide();
                }
            });
        });
    });
</script>
@endsection