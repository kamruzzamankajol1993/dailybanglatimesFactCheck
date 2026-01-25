@extends('front.master.master')

@section('title')
গোপনীয়তা নীতি
@endsection

@section('css')
<style>
    .legal-content h4 {
        color: #198754;
        font-weight: 700;
        margin-top: 30px;
        margin-bottom: 15px;
        border-left: 4px solid #dc3545;
        padding-left: 15px;
    }
    .legal-content p {
        color: #444;
        line-height: 1.7;
        text-align: justify;
    }
    .legal-sidebar-link {
        display: block;
        padding: 10px 15px;
        border-bottom: 1px solid #eee;
        color: #555;
        transition: 0.2s;
        font-weight: 500;
        text-decoration: none;
    }
    .legal-sidebar-link:hover, .legal-sidebar-link.active {
        background-color: #f8f9fa;
        color: #dc3545;
        padding-left: 20px;
    }
    /* WYSIWYG Editor Content Styling Support */
    .dynamic-content img {
        max-width: 100%;
        height: auto;
    }
</style>
@endsection

@section('body')
 <section class="py-5">
    <div class="container">
        <div class="row g-4">
            
            {{-- Sidebar --}}
            <div class="col-lg-3">
                <div class="bg-white border shadow-sm sticky-top" style="top: 100px;">
                    <div class="p-3 border-bottom bg-light">
                        <h6 class="fw-bold m-0 text-uppercase">Legal Information</h6>
                    </div>
                    <a href="{{ route('front.termsCondition') }}" class="legal-sidebar-link">
                        <i class="fas fa-gavel me-2"></i> ব্যবহারের শর্তাবলী
                    </a>
                    <a href="{{ route('front.privacyPolicy') }}" class="legal-sidebar-link active">
                        <i class="fas fa-user-shield me-2"></i> গোপনীয়তা নীতি
                    </a>
                    <a href="{{ route('front.contactUs') }}" class="legal-sidebar-link">
                        <i class="fas fa-envelope me-2"></i> যোগাযোগ
                    </a>
                </div>
            </div>

            {{-- Main Content --}}
            <div class="col-lg-9">
                <div class="bg-white p-5 border shadow-sm legal-content">
                    
                    <h2 class="fw-bold mb-4 border-bottom pb-3">গোপনীয়তা নীতি (Privacy Policy)</h2>
                    
                    <div class="dynamic-content">
                        @if(isset($data) && $data->privacy_policy)
                            {{-- ডাটাবেজ থেকে আসা HTML রেন্ডার করার জন্য --}}
                            {!! $data->privacy_policy !!}
                        @else
                            <div class="alert alert-warning">
                                কোনো তথ্য পাওয়া যায়নি। এডমিন প্যানেল থেকে তথ্য যুক্ত করুন।
                            </div>
                        @endif
                    </div>

                </div>
            </div>

        </div>
    </div>
</section>
@endsection