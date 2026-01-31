<div class="row g-4">
    @forelse($posts as $item)
        @php
            // ১. ডাটা প্রসেসিং
            $title = 'No Title';
            $image = 'https://placehold.co/600x400/png?text=No+Image';
            $date = $item->created_at->format('d M, Y');
            $link = '#';
            $desc = '';
            $rawImagePath = '';

            // ডাটা সোর্স নির্ধারণ (Post নাকি FactCheckRequest?)
            if ($item->post) {
                $title = $item->post->title;
                $rawImagePath = $item->post->image;
                $link = route('front.news.details', $item->post->slug);
                $desc = Str::limit(strip_tags($item->post->content), 100);
            } 
            elseif ($item->factCheckRequest) {
                $title = $item->factCheckRequest->title ?? 'User Request';
                $rawImagePath = $item->factCheckRequest->image;
                $link = route('front.news.details', $item->factCheckRequest->id);
                $desc = Str::limit(strip_tags($item->factCheckRequest->description), 100);
            }

            // ২. ইমেজ প্রদর্শন লজিক (admin_url বনাম fact_check_url)
            if($rawImagePath) {
                // যদি পাথের মধ্যে 'uploads/requests' থাকে তবে fact_check_url (লোকাল পাথ) ব্যবহার হবে
                if (str_contains($rawImagePath, 'uploads/requests')) {
                         $image = $front_admin_url . $rawImagePath;
                } else {
              
                     $image = $front_fact_check_url . $rawImagePath;
                }
            }

            // ৩. ভার্ডিক্ট ব্যাজ কালার ও টেক্সট
            $verdict = $item->verdict;
            $badgeClass = 'bg-secondary';
            $verdictText = $verdict;

            if (stripos($verdict, 'True') !== false || stripos($verdict, 'Likely True') !== false) {
                $badgeClass = 'bg-success'; 
                $verdictText = 'সত্য';
            } elseif (stripos($verdict, 'False') !== false || stripos($verdict, 'Fake') !== false) {
                $badgeClass = 'bg-danger'; 
                $verdictText = 'মিথ্যা';
            } elseif (stripos($verdict, 'Misleading') !== false) {
                $badgeClass = 'bg-warning text-dark'; 
                $verdictText = 'বিভ্রান্তিকর';
            } elseif (stripos($verdict, 'Altered') !== false) {
                $badgeClass = 'bg-info text-dark'; 
                $verdictText = 'বিকৃত';
            }
        @endphp

        <div class="col-md-6 col-lg-4">
            <div class="result-card position-relative h-100 shadow-sm border rounded overflow-hidden">
                {{-- Verdict Badge --}}
                <span class="badge-status {{ $badgeClass }}" style="position: absolute; top: 10px; right: 10px; z-index: 10; padding: 5px 10px; border-radius: 4px; color: #fff; font-weight: bold; font-size: 12px;">
                    {{ $verdictText }}
                </span>

                <div class="img-wrapper" style="height: 200px; overflow: hidden;">
                    <img src="{{ $image }}" class="w-100 h-100 object-fit-cover transition-transform" alt="{{ $title }}">
                </div>

                <div class="p-3">
                    <div class="meta-info mb-2 text-muted small">
                        <i class="far fa-calendar-alt me-1"></i> {{ $date }}
                    </div>
                    <h5 class="fw-bold mb-2">
                        <a href="{{ $link }}" class="text-dark text-decoration-none stretched-link">{{ Str::limit($title, 60) }}</a>
                    </h5>
                    <p class="small text-muted mb-3">{{ $desc }}</p>
                    <a href="{{ $link }}" class="text-danger fw-bold small text-decoration-none">
                        আরও পড়ুন <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12 text-center py-5">
            <div class="text-muted">
                <i class="far fa-folder-open fa-3x mb-3"></i>
                <h5>এই ক্যাটাগরিতে কোনো যাচাইকৃত খবর পাওয়া যায়নি।</h5>
            </div>
        </div>
    @endforelse
</div>

{{-- Custom Pagination --}}
@if ($posts->hasPages())
<div class="mt-5 d-flex justify-content-center">
    <nav aria-label="Page navigation">
        <ul class="pagination custom-pagination">
            {{-- Previous Page Link --}}
            @if ($posts->onFirstPage())
                <li class="page-item disabled"><span class="page-link"><i class="fas fa-chevron-left"></i></span></li>
            @else
                <li class="page-item"><a class="page-link" href="{{ $posts->previousPageUrl() }}"><i class="fas fa-chevron-left"></i></a></li>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($posts->links()->elements as $element)
                @if (is_string($element))
                    <li class="page-item disabled"><span class="page-link">{{ $element }}</span></li>
                @endif
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $posts->currentPage())
                            <li class="page-item active"><span class="page-link">{{ $page }}</span></li>
                        @else
                            <li class="page-item"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($posts->hasMorePages())
                <li class="page-item"><a class="page-link" href="{{ $posts->nextPageUrl() }}"><i class="fas fa-chevron-right"></i></a></li>
            @else
                <li class="page-item disabled"><span class="page-link"><i class="fas fa-chevron-right"></i></span></li>
            @endif
        </ul>
    </nav>
</div>
@endif