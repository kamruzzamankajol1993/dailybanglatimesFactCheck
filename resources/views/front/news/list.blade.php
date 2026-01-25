@extends('front.master.master')

@section('title')
{{ $category->name ?? 'News' }} | {{ $front_ins_name ?? '' }} 
@endsection

@section('css')
<style>
    /* Loading Overlay Style */
    #loading-overlay {
        position: absolute;
        top: 0; left: 0; right: 0; bottom: 0;
        background: rgba(255, 255, 255, 0.8);
        z-index: 10;
        display: none;
        align-items: center;
        justify-content: center;
    }
    
    #post-data-container {
        position: relative;
        min-height: 400px;
        transition: opacity 0.3s ease;
    }

    /* --- Custom Pagination CSS --- */
    .custom-pagination {
        display: flex;
        gap: 8px;
        align-items: center;
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
        border-radius: 0; /* Square box design */
        transition: all 0.3s ease;
        background: #fff;
        cursor: pointer;
    }
    .custom-pagination .page-link:hover {
        background-color: #f8f9fa;
        color: #dc3545; /* Red hover */
        border-color: #dc3545;
    }
    .custom-pagination .page-link.active {
        background-color: #dc3545; /* Red Active */
        color: #fff;
        border-color: #dc3545;
    }
    .custom-pagination .page-link.disabled {
        color: #ccc;
        pointer-events: none;
        background: #f9f9f9;
        border-color: #eee;
    }

    /* --- Fixed Sidebar CSS --- */
    /* Ensure the parent row has alignment that supports sticky */
    .sticky-sidebar-wrapper {
        position: -webkit-sticky; /* Safari */
        position: sticky;
        top: 90px; /* Adjust based on your header height */
        z-index: 5;
    }
</style>
@endsection

@section('body')
 <section class="category-body py-4 bg-white">
        <div class="container">
            
            {{-- Breadcrumb --}}
            <div class="d-flex align-items-center mb-4 text-secondary small border-bottom pb-2">
                <a href="{{ route('front.index') }}" class="text-dark"><i class="fas fa-home"></i></a>
                <span class="mx-2">/</span>
                <span class="fw-bold text-danger">{{ $category->name ?? 'News' }}</span>
            </div>

            <div class="row g-4">
                
                {{-- MAIN CONTENT COLUMN --}}
                <div class="col-lg-9">
                    
                    {{-- Container for AJAX Data --}}
                    <div id="post-data-container">
                        {{-- Loading Spinner --}}
                        <div id="loading-overlay">
                            <div class="spinner-border text-danger" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>

                        {{-- INITIAL DATA LOAD --}}
                        @include('front.news._category_posts_partial')

                    </div>

                </div>

                {{-- SIDEBAR AD COLUMN (FIXED) --}}
                <div class="col-lg-3">
                    {{-- Sticky Wrapper Class Added Here --}}
                    <div class="sticky-sidebar-wrapper">
                        @if(isset($category_sidebar_ad))
                            <div class="text-center">
                                {{-- Type 1: Image --}}
                                @if($category_sidebar_ad->type == 1 && !empty($category_sidebar_ad->image))
                                    <a href="{{ $category_sidebar_ad->link ?? 'javascript:void(0)' }}" {{ !empty($category_sidebar_ad->link) ? 'target="_blank"' : '' }}>
                                        <img src="{{ $front_admin_url }}public/{{ $category_sidebar_ad->image }}" 
                                             class="img-fluid border" 
                                             alt="Sidebar Advertisement"
                                             style="width: 100%; height: auto;">
                                    </a>
                                
                                {{-- Type 2: Script --}}
                                @elseif($category_sidebar_ad->type == 2 && !empty($category_sidebar_ad->script))
                                    {!! $category_sidebar_ad->script !!}
                                @endif
                            </div>
                       
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </section>
@endsection


@section('scripts')
<script>
    $(document).ready(function() {
        
        // Handle Custom Pagination Click (Selector Updated)
        $(document).on('click', '.custom-pagination .page-link', function(event) {
            event.preventDefault(); // Prevent default browser reload
            
            let pageUrl = $(this).attr('href'); // Get the URL
            
            // Check if URL exists (to prevent error on disabled buttons)
            if(!pageUrl || pageUrl === '#' || $(this).hasClass('disabled') || $(this).hasClass('active')) {
                return;
            }

            // 1. Show Loading State
            $('#loading-overlay').fadeIn(200);
            $('#post-data-container').css('opacity', '0.6');

            // 2. Perform AJAX
            fetchPosts(pageUrl);
        });

        function fetchPosts(url) {
            $.ajax({
                url: url,
                type: "get",
                datatype: "html",
            })
            .done(function(data) {
                // 3. Update Content
                $('#post-data-container').empty().html(data);
                
                // Re-add loading overlay structure
                $('#post-data-container').prepend(`
                    <div id="loading-overlay">
                        <div class="spinner-border text-danger" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                `);

                // 4. Update URL History
                window.history.pushState({path: url}, '', url);
                
                // 5. Scroll to top
                $('html, body').animate({
                    scrollTop: $(".category-body").offset().top - 60
                }, 500);

                // 6. Restore Opacity
                $('#post-data-container').css('opacity', '1');
            })
            .fail(function(jqXHR, ajaxOptions, thrownError) {
                alert('সার্ভার থেকে রেসপন্স পাওয়া যায়নি।');
                $('#loading-overlay').fadeOut();
                $('#post-data-container').css('opacity', '1');
            });
        }
    });
</script>
@endsection