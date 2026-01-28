@extends('front.master.master')

@section('title')
ফ্যাক্ট ফাইল - আমাদের কাজের পদ্ধতি ও নীতিমালা
@endsection

@section('body')
<section class="py-5 bg-white">
    <div class="container">
        <div class="row justify-content-center mb-5">
            <div class="col-lg-8 text-center">
                <h1 class="fw-bold mb-3 text-dark">ফ্যাক্ট ফাইল</h1>
                <p class="lead text-muted">আমাদের কাজের পদ্ধতি, রেটিং সিস্টেম এবং স্বচ্ছতার নীতিমালা সম্পর্কে জানুন।</p>
                <div class="bg-primary mx-auto" style="width: 80px; height: 4px;"></div>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-lg-3">
                <div class="nav flex-column nav-pills me-3 p-3 bg-light rounded" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                    <button class="nav-link active text-start fw-bold mb-2" id="v-pills-method-tab" data-bs-toggle="pill" data-bs-target="#v-pills-method" type="button" role="tab"><i class="fas fa-search me-2"></i>কাজের পদ্ধতি</button>
                    <button class="nav-link text-start fw-bold mb-2" id="v-pills-rating-tab" data-bs-toggle="pill" data-bs-target="#v-pills-rating" type="button" role="tab"><i class="fas fa-star me-2"></i>রেটিং সিস্টেম</button>
                    <button class="nav-link text-start fw-bold mb-2" id="v-pills-correction-tab" data-bs-toggle="pill" data-bs-target="#v-pills-correction" type="button" role="tab"><i class="fas fa-undo me-2"></i>সংশোধনী নীতি</button>
                    <button class="nav-link text-start fw-bold" id="v-pills-source-tab" data-bs-toggle="pill" data-bs-target="#v-pills-source" type="button" role="tab"><i class="fas fa-link me-2"></i>উৎস ও সোর্স</button>
                </div>
            </div>

            <div class="col-lg-9">
                <div class="tab-content" id="v-pills-tabContent">
                    
                    <div class="tab-pane fade show active" id="v-pills-method" role="tabpanel">
                        <div class="card border-0 shadow-sm p-4">
                            <h3 class="fw-bold mb-4">আমাদের যাচাই পদ্ধতি</h3>
                            <p>আমরা আন্তর্জাতিক ফ্যাক্ট-চেকিং নেটওয়ার্ক (IFCN) এর মূলনীতি অনুসরণ করে কাজ করি। আমাদের যাচাই প্রক্রিয়ার ধাপগুলো নিচে দেওয়া হলো:</p>
                            
                            <ul class="list-group list-group-flush mt-3">
                                <li class="list-group-item d-flex align-items-start py-3">
                                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center flex-shrink-0 mt-1" style="width: 30px; height: 30px;">১</div>
                                    <div class="ms-3">
                                        <h5 class="fw-bold">বিষয় নির্বাচন (Selection)</h5>
                                        <p class="mb-0 text-muted">ভাইরাল হওয়া দাবি, ছবি বা ভিডিও যা জনমনে বিভ্রান্তি ছড়াচ্ছে, আমরা মনিটরিং টুলের মাধ্যমে তা শনাক্ত করি।</p>
                                    </div>
                                </li>
                                <li class="list-group-item d-flex align-items-start py-3">
                                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center flex-shrink-0 mt-1" style="width: 30px; height: 30px;">২</div>
                                    <div class="ms-3">
                                        <h5 class="fw-bold">গবেষণা ও প্রমাণ সংগ্রহ (Research)</h5>
                                        <p class="mb-0 text-muted">প্রাথমিক উৎস খুঁজে বের করা, অফিশিয়াল নথিপত্র যাচাই এবং প্রয়োজনে সংশ্লিষ্ট কর্তৃপক্ষের সাথে যোগাযোগ করা।</p>
                                    </div>
                                </li>
                                <li class="list-group-item d-flex align-items-start py-3">
                                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center flex-shrink-0 mt-1" style="width: 30px; height: 30px;">৩</div>
                                    <div class="ms-3">
                                        <h5 class="fw-bold">সিদ্ধান্ত গ্রহণ (Evaluation)</h5>
                                        <p class="mb-0 text-muted">প্রাপ্ত তথ্যের ভিত্তিতে দাবিটিকে সত্য, মিথ্যা বা বিভ্রান্তিকর হিসেবে চিহ্নিত করা হয়।</p>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="v-pills-rating" role="tabpanel">
                        <div class="card border-0 shadow-sm p-4">
                            <h3 class="fw-bold mb-4">আমাদের রেটিং বা মানদণ্ড</h3>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="p-3 border rounded h-100 border-success border-2">
                                        <h5 class="text-success fw-bold"><i class="fas fa-check-circle me-2"></i>সত্য (True)</h5>
                                        <p class="small text-muted mb-0">দাবিকৃত তথ্যটি সঠিক এবং নির্ভরযোগ্য উৎস দ্বারা প্রমাণিত।</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="p-3 border rounded h-100 border-danger border-2">
                                        <h5 class="text-danger fw-bold"><i class="fas fa-times-circle me-2"></i>মিথ্যা (False)</h5>
                                        <p class="small text-muted mb-0">দাবিটি সম্পূর্ণ ভিত্তিহীন, বানোয়াট অথবা এডিটেড কন্টেন্ট।</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="p-3 border rounded h-100 border-warning border-2">
                                        <h5 class="text-warning fw-bold text-dark"><i class="fas fa-exclamation-triangle me-2"></i>বিভ্রান্তিকর (Misleading)</h5>
                                        <p class="small text-muted mb-0">তথ্যটি আংশিক সত্য কিন্তু এমনভাবে উপস্থাপন করা হয়েছে যা ভুল বার্তা দেয়।</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="p-3 border rounded h-100 border-secondary border-2">
                                        <h5 class="text-secondary fw-bold"><i class="fas fa-theater-masks me-2"></i>ব্যঙ্গাত্মক (Satire)</h5>
                                        <p class="small text-muted mb-0">এটি মূলত কৌতুক বা স্যাটায়ার, কিন্তু অনেকে সত্য ভেবে ভুল করতে পারেন।</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="v-pills-correction" role="tabpanel">
                        <div class="card border-0 shadow-sm p-4">
                            <h3 class="fw-bold mb-4">সংশোধনী নীতি</h3>
                            <p>আমরা সর্বোচ্চ সতর্কতা অবলম্বন করি, তবুও মানুষ হিসেবে ভুল হওয়া স্বাভাবিক। যদি আমাদের কোনো প্রতিবেদনে তথ্যের ভুল প্রমাণিত হয়, আমরা দ্রুত তা সংশোধন করি।</p>
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle me-2"></i> আপনি যদি কোনো ভুল দেখতে পান, দয়া করে আমাদের <a href="{{ route('front.contactUs') }}" class="fw-bold">যোগাযোগ পেজে</a> জানান। আমরা ৭২ ঘণ্টার মধ্যে বিষয়টি রিভিউ করব।
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="v-pills-source" role="tabpanel">
                        <div class="card border-0 shadow-sm p-4">
                            <h3 class="fw-bold mb-4">উৎস বা সোর্স পলিসি</h3>
                            <p>আমরা বেনামি সোর্সের ওপর ভিত্তি করে কোনো ফ্যাক্ট-চেক করি না। আমরা সবসময় তথ্য প্রমাণ হিসেবে:</p>
                            <ul>
                             
                                <li>মূল ভিডিও বা ছবির মেটাডেটা</li>
                                <li>সরাসরি সংশ্লিষ্ট ব্যক্তির বক্তব্য</li>
                                <li>নির্ভরযোগ্য মূলধারার সংবাদমাধ্যমের আর্কাইভ</li>
                            </ul>
                            <p>ব্যবহার করে থাকি এবং প্রতিবেদনের ভেতরেই সোর্স লিংক যুক্ত করি যাতে পাঠকরাও যাচাই করতে পারেন।</p>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</section>
@endsection