@extends('front.master.master')

@section('title')
কাজের পদ্ধতি - আমরা কীভাবে তথ্য যাচাই করি
@endsection

@section('body')
<section class="py-5 bg-white">
    <div class="container">
        
        <div class="text-center mb-5">
            <h5 class="text-primary fw-bold text-uppercase ls-2">আমাদের কার্যপ্রণালী</h5>
            <h1 class="display-5 fw-bold text-dark">আমরা কীভাবে সত্য খুঁজে বের করি?</h1>
            <p class="lead text-muted mt-3 w-75 mx-auto">আমাদের যাচাই প্রক্রিয়াটি স্বচ্ছ, নিরপেক্ষ এবং আন্তর্জাতিক মানদণ্ড (IFCN) অনুযায়ী পরিচালিত হয়। প্রতিটি সংবাদের গভীরে গিয়ে আমরা আসল তথ্য উদঘাটন করি।</p>
        </div>

        <div class="row g-4 position-relative process-container">
            <div class="d-none d-lg-block position-absolute start-50 top-0 bottom-0 border-start border-2 border-primary opacity-25" style="transform: translateX(-50%);"></div>

            <div class="col-12 mb-4">
                <div class="row align-items-center">
                    <div class="col-lg-5 text-lg-end text-start order-2 order-lg-1">
                        <h3 class="fw-bold">১. মনিটরিং ও নির্বাচন</h3>
                        <p class="text-muted">প্রতিদিন সোশ্যাল মিডিয়া (ফেসবুক, ইউটিউব, এক্স) এবং বিভিন্ন সংবাদমাধ্যম মনিটর করা হয়। যেসব কনটেন্ট ভাইরাল হয়েছে এবং জনমনে বিভ্রান্তি সৃষ্টি করতে পারে, সেগুলোকে আমরা যাচাইয়ের জন্য তালিকাভুক্ত করি।</p>
                    </div>
                    <div class="col-lg-2 text-center position-relative order-1 order-lg-2 mb-3 mb-lg-0">
                        <div class="step-icon bg-primary text-white shadow">
                            <i class="fas fa-search-plus"></i>
                        </div>
                    </div>
                    <div class="col-lg-5 order-3"></div>
                </div>
            </div>

            <div class="col-12 mb-4">
                <div class="row align-items-center">
                    <div class="col-lg-5 order-3 order-lg-1"></div>
                    <div class="col-lg-2 text-center position-relative order-1 order-lg-2 mb-3 mb-lg-0">
                        <div class="step-icon bg-danger text-white shadow">
                            <i class="fas fa-microscope"></i>
                        </div>
                    </div>
                    <div class="col-lg-5 text-start order-2 order-lg-3">
                        <h3 class="fw-bold">২. গবেষণা ও প্রমাণ সংগ্রহ</h3>
                        <p class="text-muted">আমরা খবরের উৎস খুঁজি। রিভার্স ইমেজ সার্চ, ভিডিও ভেরিফিকেশন টুল ব্যবহার করে আসল সোর্স বের করা হয়। প্রয়োজনে সংশ্লিষ্ট ব্যক্তি বা প্রতিষ্ঠানের সাথে সরাসরি যোগাযোগ করে তাদের বক্তব্য নেওয়া হয়।</p>
                    </div>
                </div>
            </div>

            <div class="col-12 mb-4">
                <div class="row align-items-center">
                    <div class="col-lg-5 text-lg-end text-start order-2 order-lg-1">
                        <h3 class="fw-bold">৩. বিশ্লেষণ ও সিদ্ধান্ত</h3>
                        <p class="text-muted">সংগৃহীত তথ্য-উপাত্ত বিশ্লেষণ করে এডিটোরিয়াল টিম সিদ্ধান্ত নেয়। এখানে কোনো ব্যক্তিগত মতামত বা আবেগের স্থান নেই—শুধুমাত্র প্রমাণের ভিত্তিতে সিদ্ধান্ত নেওয়া হয় (সত্য, মিথ্যা বা বিভ্রান্তিকর)।</p>
                    </div>
                    <div class="col-lg-2 text-center position-relative order-1 order-lg-2 mb-3 mb-lg-0">
                        <div class="step-icon bg-warning text-dark shadow">
                            <i class="fas fa-balance-scale"></i>
                        </div>
                    </div>
                    <div class="col-lg-5 order-3"></div>
                </div>
            </div>

            <div class="col-12 mb-4">
                <div class="row align-items-center">
                    <div class="col-lg-5 order-3 order-lg-1"></div>
                    <div class="col-lg-2 text-center position-relative order-1 order-lg-2 mb-3 mb-lg-0">
                        <div class="step-icon bg-info text-white shadow">
                            <i class="fas fa-pen-fancy"></i>
                        </div>
                    </div>
                    <div class="col-lg-5 text-start order-2 order-lg-3">
                        <h3 class="fw-bold">৪. রিপোর্ট লেখা ও রিভিউ</h3>
                        <p class="text-muted">সহজ ও সাবলীল ভাষায় রিপোর্ট লেখা হয় যাতে সাধারণ মানুষ বুঝতে পারে। প্রকাশের আগে সিনিয়র এডিটররা পুনরায় ক্রস-চেক করেন যাতে কোনো ভুল না থাকে।</p>
                    </div>
                </div>
            </div>

            <div class="col-12">
                <div class="row align-items-center">
                    <div class="col-lg-5 text-lg-end text-start order-2 order-lg-1">
                        <h3 class="fw-bold">৫. প্রকাশ ও সংশোধন</h3>
                        <p class="text-muted">ওয়েবসাইট ও সোশ্যাল মিডিয়ায় ফ্যাক্ট-চেক প্রকাশ করা হয়। যদি পরবর্তীতে আমাদের রিপোর্টে কোনো ভুল প্রমাণিত হয়, তবে আমরা দ্রুত তা সংশোধন করি এবং পাঠকদের জানাই।</p>
                    </div>
                    <div class="col-lg-2 text-center position-relative order-1 order-lg-2 mb-3 mb-lg-0">
                        <div class="step-icon bg-success text-white shadow">
                            <i class="fas fa-check-double"></i>
                        </div>
                    </div>
                    <div class="col-lg-5 order-3"></div>
                </div>
            </div>

        </div>

        <div class="mt-5 pt-5 border-top">
            <h3 class="text-center fw-bold mb-4">আমরা যেসব টুল ব্যবহার করি</h3>
            <div class="row row-cols-2 row-cols-md-4 g-3 text-center">
                <div class="col">
                    <div class="p-3 border rounded bg-light h-100">
                        <i class="fab fa-google fa-2x text-primary mb-2"></i>
                        <h6 class="mb-0">Google Reverse Image</h6>
                    </div>
                </div>
                <div class="col">
                    <div class="p-3 border rounded bg-light h-100">
                        <i class="fas fa-video fa-2x text-danger mb-2"></i>
                        <h6 class="mb-0">InVID Verification</h6>
                    </div>
                </div>
                <div class="col">
                    <div class="p-3 border rounded bg-light h-100">
                        <i class="fas fa-archive fa-2x text-warning mb-2"></i>
                        <h6 class="mb-0">Internet Archive</h6>
                    </div>
                </div>
                <div class="col">
                    <div class="p-3 border rounded bg-light h-100">
                        <i class="fas fa-map-marked fa-2x text-success mb-2"></i>
                        <h6 class="mb-0">Geolocation Tools</h6>
                    </div>
                </div>
            </div>
        </div>

    </div>
</section>

<style>
    .step-icon {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        margin: 0 auto;
        position: relative;
        z-index: 2;
        border: 4px solid #fff;
    }
    .ls-2 { letter-spacing: 2px; }
</style>
@endsection