@extends('front.master.master')

@section('title')
ভিডিও গ্যালারি | {{ $front_ins_name ?? '' }} 
@endsection

@section('css')
<style>
    /* লোডিং এবং প্যাজিনেশন স্টাইল */
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
        transition: all 0.3s ease;
        background: #fff;
        cursor: pointer;
    }
    .custom-pagination .page-link:hover, .custom-pagination .page-link.active {
        background-color: #dc3545;
        color: #fff;
        border-color: #dc3545;
    }
    .custom-pagination .page-link.disabled {
        background: #f9f9f9; color: #ccc; pointer-events: none;
    }
    .video-card-modern:hover { transform: translateY(-5px); transition: transform 0.3s; }
</style>
@endsection

@section('body')
 <section class="category-body py-4 bg-white">
        <div class="container">
            
            {{-- Breadcrumb --}}
            <div class="d-flex align-items-center mb-4 text-secondary small border-bottom pb-2">
                <a href="{{ route('front.index') }}" class="text-dark"><i class="fas fa-home"></i></a>
                <span class="mx-2">/</span>
                <span class="fw-bold text-danger">ভিডিও গ্যালারি</span>
            </div>

            <div class="row g-4">
                {{-- MAIN CONTENT --}}
                <div class="col-lg-12">
                    <div id="post-data-container">
                        {{-- Loading Spinner --}}
                        <div id="loading-overlay">
                            <div class="spinner-border text-danger" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>

                        {{-- INITIAL DATA --}}
                        @include('front.video_gallery._video_list_partial')
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // AJAX Pagination Script
        $(document).on('click', '.custom-pagination .page-link', function(event) {
            event.preventDefault();
            let pageUrl = $(this).attr('href');
            if(!pageUrl || pageUrl === '#' || $(this).hasClass('disabled') || $(this).hasClass('active')) return;

            $('#loading-overlay').fadeIn(200);
            $('#post-data-container').css('opacity', '0.6');

            $.ajax({
                url: pageUrl,
                type: "get",
                datatype: "html",
            })
            .done(function(data) {
                $('#post-data-container').empty().html(data);
                $('#post-data-container').prepend('<div id="loading-overlay"><div class="spinner-border text-danger"></div></div>');
                window.history.pushState({path: pageUrl}, '', pageUrl);
                $('html, body').animate({ scrollTop: $(".category-body").offset().top - 60 }, 500);
                $('#post-data-container').css('opacity', '1');
            })
            .fail(function() {
                alert('Server Error!');
                $('#loading-overlay').fadeOut();
                $('#post-data-container').css('opacity', '1');
            });
        });
    });
</script>
@endsection