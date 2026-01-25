@extends('front.master.master')

@section('title')
পুরানো সংবাদ আর্কাইভ
@endsection

@section('css')
 <style>
        .archive-control-box {
            background-color: #006a4e; /* Brand Green */
            padding: 20px;
            border-radius: 4px;
            color: white;
        }
        .archive-card-img {
            height: 160px;
            object-fit: cover;
            transition: transform 0.3s;
            width: 100%;
        }
        .card:hover .archive-card-img {
            transform: scale(1.05);
        }
        .calendar-widget {
            background: white;
            border: 1px solid #ddd;
        }
        .calendar-header {
            background: #dc3545;
            color: white;
            padding: 10px;
            text-align: center;
            font-weight: bold;
            font-size: 18px;
        }
        /* Calendar Link Styles */
        .calendar-date-link {
            display: block;
            width: 30px;
            height: 30px;
            line-height: 30px;
            text-align: center;
            text-decoration: none;
            color: #333;
            border-radius: 50%;
            margin: 0 auto;
            transition: 0.2s;
        }
        .calendar-date-link:hover {
            background-color: #eee;
            color: #dc3545;
        }
        .calendar-date-link.active-date {
            background-color: #006a4e;
            color: white;
            font-weight: bold;
        }
        .calendar-date-link.is-friday {
            color: #dc3545;
            font-weight: bold;
        }
    </style>
@endsection


@section('body')
 <section class="py-4">
        <div class="container">

            <div class="row g-4">
                
                {{-- বাম পাশ: সার্চ এবং রেজাল্ট --}}
                <div class="col-lg-9">
                    
                    {{-- ১. সার্চ ফিল্টার ফরম --}}
                    <div class="archive-control-box shadow-sm mb-4">
                        <form action="{{ route('front.archive') }}" method="GET">
                            <div class="row g-3 align-items-end">
                                <div class="col-12"><h4 class="mb-0 border-bottom pb-2 border-white"><i class="fas fa-calendar-alt me-2"></i>পুরানো সংবাদ খুঁজুন</h4></div>
                                
                                <div class="col-md-5">
                                    <label class="small fw-bold mb-1 text-light">তারিখ নির্বাচন করুন</label>
                                    <input type="date" name="date" value="{{ $searchDate }}" class="form-control rounded-0 border-0">
                                </div>
                                
                                <div class="col-md-5">
                                    <label class="small fw-bold mb-1 text-light">বিভাগ (অপশনাল)</label>
                                    <select name="category" class="form-select rounded-0 border-0">
                                        <option value="all">সকল বিভাগ</option>
                                        @foreach($categories as $cat)
                                            <option value="{{ $cat->id }}" {{ $searchCategory == $cat->id ? 'selected' : '' }}>
                                                {{ $cat->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-2">
                                    <button type="submit" class="btn btn-danger w-100 rounded-0 fw-bold">খুঁজুন</button>
                                </div>
                            </div>
                        </form>
                    </div>

                    {{-- ২. রেজাল্ট হেডার --}}
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="fw-bold m-0 text-dark">
                            @if($searchDate)
                                <span class="text-danger">{{ \Carbon\Carbon::parse($searchDate)->locale('bn')->isoFormat('LL') }}</span> - এর সংবাদ
                            @else
                                <span class="text-danger">সর্বশেষ সংবাদ</span>
                            @endif
                        </h5>
                        <span class="badge bg-secondary">মোট {{ $posts->total() }} টি সংবাদ</span>
                    </div>

                    {{-- ৩. নিউজের তালিকা (লুপ) --}}
                    <div class="row g-3">
                        @if($posts->count() > 0)
                            @foreach($posts as $post)
                                <div class="col-md-4 col-sm-6">
                                    <div class="card h-100 border-0 shadow-sm overflow-hidden">
                                        <div class="overflow-hidden">
                                            <img src="{{ $post->image ? $front_admin_url.$post->image : 'https://placehold.co/300x200/222/fff?text=No+Image' }}" 
                                                 class="card-img-top archive-card-img rounded-0"
                                                 loading="lazy">
                                        </div>
                                        <div class="card-body p-3">
                                            @if($post->categories->isNotEmpty())
                                                <small class="text-danger fw-bold d-block mb-1">
                                                    {{ $post->categories->first()->name }}
                                                </small>
                                            @endif
                                            <a href="{{ route('front.news.details', $post->slug) }}" class="text-decoration-none text-dark">
                                                <h6 class="fw-bold hover-red lh-base m-0">{{ Str::limit($post->title, 60) }}</h6>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="col-12 text-center py-5">
                                <h4 class="text-muted">দুঃখিত! এই তারিখে কোনো সংবাদ পাওয়া যায়নি।</h4>
                            </div>
                        @endif
                    </div>

                    {{-- ৪. পেজিনেশন --}}
                    <div class="mt-5 d-flex justify-content-center">
                        {{ $posts->links('pagination::bootstrap-5') }}
                    </div>

                </div>

                {{-- ডান পাশ: সাইডবার --}}
                <div class="col-lg-3">
                    <div class="sticky-top" style="top: 100px;">
                        
                        {{-- ৫. মাসের আর্কাইভ --}}
                        <div class="bg-white border rounded shadow-sm mb-4">
                            <div class="p-3 border-bottom bg-light">
                                <h6 class="fw-bold m-0 text-danger">মাসের আর্কাইভ</h6>
                            </div>
                            <div class="list-group list-group-flush small">
                                @foreach($monthlyArchives as $archive)
                                    @php
                                        // মাসের নাম বাংলায় কনভার্ট করা
                                        $monthName = \Carbon\Carbon::createFromDate($archive->year, $archive->month, 1)->locale('bn')->isoFormat('MMMM YYYY');
                                        // ওই মাসের ১ তারিখের লিংক জেনারেট
                                        $queryDate = \Carbon\Carbon::createFromDate($archive->year, $archive->month, 1)->format('Y-m-d');
                                    @endphp
                                    <a href="{{ route('front.archive', ['date' => $queryDate]) }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                        {{ $monthName }} 
                                        <span class="badge bg-secondary rounded-pill">{{ $archive->count }}</span>
                                    </a>
                                @endforeach
                            </div>
                        </div>

                        {{-- ৬. ডাইনামিক ক্যালেন্ডার --}}
                        <div class="calendar-widget shadow-sm mb-4">
                            <div class="calendar-header">
                                {{-- বর্তমানে সিলেক্ট করা বা চলতি মাস --}}
                                {{ $calendarData['banglaMonth'] }}
                            </div>
                            <div class="p-3 text-center">
                                <table class="table table-sm table-borderless m-0 small">
                                    <thead>
                                        <tr class="text-secondary border-bottom">
                                            <th class="text-danger">রবি</th><th>সোম</th><th>মঙ্গল</th><th>বুধ</th><th>বৃহঃ</th><th class="text-danger">শুক্র</th><th>শনি</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $dayCount = 1;
                                            $totalDays = $calendarData['daysInMonth'];
                                            $startDay = $calendarData['startDayOfWeek']; // 0 = Sunday
                                            $rows = ceil(($startDay + $totalDays) / 7);
                                        @endphp

                                        @for ($i = 0; $i < $rows; $i++)
                                            <tr>
                                                @for ($j = 0; $j < 7; $j++)
                                                    @if ($i == 0 && $j < $startDay)
                                                        {{-- ফাঁকা সেল (মাসের শুরুর আগের দিনগুলো) --}}
                                                        <td></td>
                                                    @elseif ($dayCount > $totalDays)
                                                        {{-- ফাঁকা সেল (মাসের শেষের পরের দিনগুলো) --}}
                                                        <td></td>
                                                    @else
                                                        @php
                                                            // বর্তমান লুপের তারিখ তৈরি করা
                                                            $thisDate = \Carbon\Carbon::createFromDate($calendarData['year'], $calendarData['month'], $dayCount)->format('Y-m-d');
                                                            $isActive = ($searchDate == $thisDate);
                                                            $isFriday = ($j == 5); // শুক্রবার চেক
                                                        @endphp
                                                        <td>
                                                            <a href="{{ route('front.archive', ['date' => $thisDate]) }}" 
                                                               class="calendar-date-link {{ $isActive ? 'active-date' : '' }} {{ $isFriday ? 'is-friday' : '' }}">
                                                                {{ str_replace(range(0,9), ['০','১','২','৩','৪','৫','৬','৭','৮','৯'], $dayCount) }}
                                                            </a>
                                                        </td>
                                                        @php $dayCount++; @endphp
                                                    @endif
                                                @endfor
                                            </tr>
                                        @endfor
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        
                        <div class="text-center">
                            {{-- এখানে আপনার অ্যাড কোড বসবে --}}
                            @if(isset($archive_sidebar_ad))
                                {{-- Type 1: Image --}}
                                @if($archive_sidebar_ad->type == 1 && !empty($archive_sidebar_ad->image))
                                    <a href="{{ $archive_sidebar_ad->link ?? 'javascript:void(0)' }}" {{ !empty($archive_sidebar_ad->link) ? 'target="_blank"' : '' }}>
                                        <img src="{{ $front_admin_url }}public/{{ $archive_sidebar_ad->image }}" 
                                             class="img-fluid border" 
                                             alt="Archive Advertisement"
                                             style="width: 100%; height: auto;">
                                    </a>
                                
                                {{-- Type 2: Script --}}
                                @elseif($archive_sidebar_ad->type == 2 && !empty($archive_sidebar_ad->script))
                                    {!! $archive_sidebar_ad->script !!}
                                @endif
                            @else
                                {{-- Fallback --}}
                                <div style="background: #eee; height: 250px; display: flex; align-items: center; justify-content: center; color: #999;">
                                    বিজ্ঞাপন
                                </div>
                            @endif
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </section>
@endsection

@section('scripts')
@endsection