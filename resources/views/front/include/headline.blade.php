<div class="container mb-1 d-none d-md-block">
    {{-- ডিভের হাইট বাড়ানো হয়েছে (py-3) --}}
    <div class="ticker-wrapper bg-success text-white d-flex align-items-center rounded-top" style="height: 60px;"> 
        
        <div class="ticker-title bg-danger px-3 h-100 d-flex align-items-center fw-bold text-nowrap">সর্বশেষ:</div>
        
        <div class="ticker-content flex-grow-1 h-100 d-flex align-items-center overflow-hidden">
            <marquee scrollamount="6" onmouseover="this.stop();" onmouseout="this.start();">
                
                @if(isset($breakingNews) && count($breakingNews) > 0)
                    @foreach($breakingNews as $news)
                        <a href="{{ route('front.news.details', $news->slug) }}" class="ticker-item me-5 d-inline-flex align-items-center text-white text-decoration-none">
                            
                            {{-- ইমেজ লজিক: ইমেজ থাকলে দেখাবে, না থাকলে প্লেসহোল্ডার --}}
                            @php
                                $image = $news->image ? $front_admin_url . $news->image : 'https://placehold.co/70x45/333/fff?text=News';
                            @endphp

                            {{-- ইমেজ সাইজ বড় এবং আয়তাকার করা হয়েছে (70px x 45px), rounded-circle বাদ দেওয়া হয়েছে --}}
                            <img src="{{ $image }}" 
                                 class="rounded-1 border border-white me-2" 
                                 style="width: 70px; height: 45px; object-fit: cover;" 
                                 alt="News Image">
                            
                            {{-- টাইটেল --}}
                            <span style="font-size: 16px;">{{ $news->title }}</span>
                        </a>
                    @endforeach
                @else
                    {{-- যদি কোনো ব্রেকিং নিউজ না থাকে --}}
                    <span class="ticker-item me-5 d-inline-flex align-items-center">
                        ব্রেকিং নিউজ আপডেট হচ্ছে... সাথে থাকুন।
                    </span>
                @endif

            </marquee>
        </div>
    </div>
</div>