@extends('front.master.master')

@section('title')
{{ $front_ins_name }} - আমাদের সম্পর্কে
@endsection

@section('css')
 <style>
        .about-hero {
            background: linear-gradient(rgba(0,0,0,0.7), rgba(0,0,0,0.7)), url('https://placehold.co/1920x600/333/fff?text=Newsroom');
            background-size: cover;
            background-position: center;
            padding: 100px 0;
            color: white;
        }
        .team-img-wrapper {
            position: relative;
            overflow: hidden;
            border-radius: 8px 8px 0 0;
        }
        .team-social {
            position: absolute;
            bottom: -50px;
            left: 0;
            width: 100%;
            background: rgba(220, 53, 69, 0.9);
            padding: 10px;
            text-align: center;
            transition: bottom 0.3s ease;
        }
        .team-card:hover .team-social {
            bottom: 0;
        }
        .team-social a { color: white; margin: 0 5px; }
        
        .member-desc {
            font-size: 0.85rem;
            line-height: 1.4;
            color: #6c757d;
            margin-top: 8px;
        }
        .btn-view-bio {
            font-size: 12px;
            padding: 5px 15px;
            border-radius: 20px;
            margin-top: 10px;
        }
        /* লিডার বা প্রথম মেম্বারের জন্য বিশেষ স্টাইল */
        .leader-card {
            max-width: 400px;
            margin: 0 auto;
        }
    </style>
@endsection

@section('body')
    
    <div class="container my-5">
        
        {{-- ১. আমাদের সম্পর্কে বর্ণনা --}}
        <div class="row align-items-center mb-5">
            <div class="col-lg-12">
                <div class="section-header-wrapper mb-3" style="border-bottom: 3px solid #dc3545;">
                    <h5 class="bg-success text-white d-inline-block px-3 py-2 m-0 fw-bold">আমাদের সম্পর্কে</h5>
                </div>
                
                <div class="text-secondary text-justify lh-lg article-content">
                    @if($about)
                        {!! $about->des !!}
                    @else
                        <p class="text-center text-muted py-4">Description is currently unavailable.</p>
                    @endif
                </div>
            </div>
        </div>

        {{-- ২. টিম সেকশন --}}
        <div class="section-header-wrapper mb-5 text-center border-0">
            <h3 class="fw-bold d-inline-block border-bottom border-danger border-3 pb-2">নেতৃত্বে যারা আছেন</h3>
        </div>

        @if($contributors->count() > 0)
            {{-- প্রথম মেম্বার (লিডার) - একক সারি --}}
            @php $leader = $contributors->first(); @endphp
            <div class="row justify-content-center mb-5">
                <div class="col-lg-4 col-md-6">
                    <div class="card border-0 shadow team-card leader-card h-100">
                        <div class="team-img-wrapper">
                            <img src="{{ $front_admin_url.'public/'.$leader->image }}" onerror="this.onerror=null;this.src='{{ $front_admin_url }}{{ $front_logo_name }}';" class="card-img-top" alt="{{ $leader->name }}" style="height: 400px; object-fit: cover;">
                            
                            @if($leader->socialLinks && $leader->socialLinks->count() > 0)
                            <div class="team-social">
                                @foreach($leader->socialLinks as $link)
                                    <a href="{{ $link->url }}" target="_blank">
                                        @if(stripos($link->name, 'facebook') !== false) <i class="fab fa-facebook-f"></i>
                                        @elseif(stripos($link->name, 'twitter') !== false) <i class="fab fa-twitter"></i>
                                        @elseif(stripos($link->name, 'linkedin') !== false) <i class="fab fa-linkedin-in"></i>
                                        @else <i class="fas fa-link"></i> @endif
                                    </a>
                                @endforeach
                            </div>
                            @endif
                        </div>
                        <div class="card-body text-center bg-white">
                            <h4 class="fw-bold mb-1">{{ $leader->name }}</h4>
                            <small class="text-danger fw-bold text-uppercase d-block mb-2">
                                {{ $leader->designations->pluck('name')->implode(', ') ?: 'Lead Contributor' }}
                            </small>
                            <button type="button" class="btn btn-outline-danger btn-sm btn-view-bio" data-bs-toggle="modal" data-bs-target="#memberModal" data-name="{{ $leader->name }}" data-designation="{{ $leader->designations->pluck('name')->implode(', ') }}" data-img="{{ $leader->image ? $front_admin_url.'public/'.$leader->image : $front_admin_url.$front_logo_name }}" data-desc="{{ $leader->short_description }}">
                                বিস্তারিত <i class="fas fa-arrow-right ms-1"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- বাকি মেম্বাররা - প্রতি সারিতে ৩ জন --}}
            <div class="row g-4 justify-content-center">
                @foreach($contributors->skip(1) as $member)
                <div class="col-lg-4 col-md-6">
                    <div class="card border-0 shadow-sm team-card h-100">
                        <div class="team-img-wrapper">
                            <img src="{{ $front_admin_url.'public/'.$member->image }}" onerror="this.onerror=null;this.src='{{ $front_admin_url }}{{ $front_logo_name }}';" class="card-img-top" alt="{{ $member->name }}" style="height: 350px; object-fit: cover;">
                            
                            @if($member->socialLinks && $member->socialLinks->count() > 0)
                            <div class="team-social">
                                @foreach($member->socialLinks as $link)
                                    <a href="{{ $link->url }}" target="_blank">
                                        @if(stripos($link->name, 'facebook') !== false) <i class="fab fa-facebook-f"></i>
                                        @elseif(stripos($link->name, 'twitter') !== false) <i class="fab fa-twitter"></i>
                                        @elseif(stripos($link->name, 'linkedin') !== false) <i class="fab fa-linkedin-in"></i>
                                        @else <i class="fas fa-link"></i> @endif
                                    </a>
                                @endforeach
                            </div>
                            @endif
                        </div>
                        <div class="card-body text-center bg-white">
                            <h5 class="fw-bold mb-1">{{ $member->name }}</h5>
                            <small class="text-danger fw-bold text-uppercase d-block mb-2">
                                {{ $member->designations->pluck('name')->implode(', ') ?: 'Contributor' }}
                            </small>
                            <button type="button" class="btn btn-outline-danger btn-sm btn-view-bio" data-bs-toggle="modal" data-bs-target="#memberModal" data-name="{{ $member->name }}" data-designation="{{ $member->designations->pluck('name')->implode(', ') }}" data-img="{{ $member->image ? $front_admin_url.'public/'.$member->image : $front_admin_url.$front_logo_name }}" data-desc="{{ $member->short_description }}">
                                বিস্তারিত <i class="fas fa-arrow-right ms-1"></i>
                            </button>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        @else
            <div class="col-12 text-center py-5">
                <p class="text-muted">কোন মেম্বার পাওয়া যায়নি।</p>
            </div>
        @endif
    </div>

    {{-- Modal (আগের মতোই থাকবে) --}}
    <div class="modal fade" id="memberModal" tabindex="-1" aria-labelledby="memberModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow">
                <div class="modal-header border-bottom-0">
                    <h5 class="modal-title fw-bold" id="modalName"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-5 mb-3 mb-md-0">
                            <img id="modalImg" src="" class="img-fluid rounded shadow-sm w-100" style="height: 350px; object-fit: cover;" alt="">
                        </div>
                        <div class="col-md-7">
                            <h6 class="text-danger text-uppercase fw-bold mb-3" id="modalDesignation"></h6>
                            <div id="modalDesc" class="text-secondary text-justify" style="font-size: 0.95rem; line-height: 1.6;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
      
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        $('#memberModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget); 
            var name = button.data('name');
            var designation = button.data('designation');
            var img = button.data('img');
            var desc = button.data('desc');

            var modal = $(this);
            modal.find('#modalName').text(name);
            modal.find('#modalDesignation').text(designation);
            modal.find('#modalImg').attr('src', img);
            modal.find('#modalDesc').html(desc ? desc : '<p class="text-muted">No description available.</p>');
        });
    });
</script>
@endsection