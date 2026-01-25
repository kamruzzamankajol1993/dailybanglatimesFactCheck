@extends('front.master.master')

@section('title')
{{ $front_ins_name }} - আমাদের সম্পর্কে
@endsection

@section('css')
 <style>
        /* Page Specific Styles */
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
        /* Custom Button Style */
        .btn-view-bio {
            font-size: 12px;
            padding: 5px 15px;
            border-radius: 20px;
            margin-top: 10px;
        }
    </style>
@endsection

@section('body')
    
    <div class="container my-5">
        
        {{-- 1. Dynamic About Us Description Section --}}
        <div class="row align-items-center mb-5">
            <div class="col-lg-12">
                <div class="section-header-wrapper mb-3" style="border-bottom: 3px solid #dc3545;">
                    {{-- Title Changed: About Us -> আমাদের সম্পর্কে --}}
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

        {{-- 2. Dynamic Contributors / Team Section --}}
        <div class="section-header-wrapper mb-4 text-center border-0">
            {{-- Title Changed: Contributors -> নেতৃত্বে যারা আছেন --}}
            <h3 class="fw-bold d-inline-block border-bottom border-danger border-3 pb-2">নেতৃত্বে যারা আছেন</h3>
        </div>

        <div class="row g-4 justify-content-center">
            @forelse($contributors as $member)
            <div class="col-lg-3 col-md-6">
                <div class="card border-0 shadow-sm team-card h-100">
                    <div class="team-img-wrapper">
                        {{-- Member Image --}}

                            <img src="{{ $front_admin_url.'public/'.$member->image }}" onerror="this.onerror=null;this.src='{{ $front_admin_url }}{{ $front_logo_name }}';" class="card-img-top" alt="{{ $member->name }}" style="height: 350px; object-fit: cover;">
                       

                        {{-- Social Links Overlay --}}
                        @if($member->socialLinks && $member->socialLinks->count() > 0)
                        <div class="team-social">
                            @foreach($member->socialLinks as $link)
                                <a href="{{ $link->url }}" target="_blank">
                                    @if(stripos($link->name, 'facebook') !== false) <i class="fab fa-facebook-f"></i>
                                    @elseif(stripos($link->name, 'twitter') !== false) <i class="fab fa-twitter"></i>
                                    @elseif(stripos($link->name, 'linkedin') !== false) <i class="fab fa-linkedin-in"></i>
                                    @elseif(stripos($link->name, 'instagram') !== false) <i class="fab fa-instagram"></i>
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

                        {{-- Truncated Description --}}
                        {{-- @if(!empty($member->short_description))
                            <p class="member-desc mb-2">
                                {{ Str::limit($member->short_description, 60) }}
                            </p>
                        @endif --}}

                        {{-- Popup Button --}}
                        <button type="button" 
                                class="btn btn-outline-danger btn-sm btn-view-bio" 
                                data-bs-toggle="modal" 
                                data-bs-target="#memberModal"
                                data-name="{{ $member->name }}"
                                data-designation="{{ $member->designations->pluck('name')->implode(', ') }}"
                                data-img="{{ $member->image ? $front_admin_url.'public/'.$member->image : $front_admin_url.$front_logo_name }}"
                                data-desc="{{ $member->short_description }}">
                            বিস্তারিত <i class="fas fa-arrow-right ms-1"></i>
                        </button>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12 text-center py-5">
                <p class="text-muted">No members found.</p>
            </div>
            @endforelse
        </div>
    </div>

    <div class="modal fade" id="memberModal" tabindex="-1" aria-labelledby="memberModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header border-bottom-0">
                    <h5 class="modal-title fw-bold" id="modalName"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4 mb-3 mb-md-0">
                            <img id="modalImg" src="" class="img-fluid rounded shadow-sm w-100" style="object-fit: cover;" alt="">
                        </div>
                        <div class="col-md-8">
                            <h6 class="text-danger text-uppercase fw-bold mb-3" id="modalDesignation"></h6>
                            <div id="modalDesc" class="text-secondary text-justify" style="font-size: 0.95rem; line-height: 1.6;"></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top-0">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
      
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Handle Modal Data Population
        $('#memberModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget); // Button that triggered the modal
            
            // Extract info from data-* attributes
            var name = button.data('name');
            var designation = button.data('designation');
            var img = button.data('img');
            var desc = button.data('desc');

            // Update the modal's content
            var modal = $(this);
            modal.find('#modalName').text(name);
            modal.find('#modalDesignation').text(designation);
            modal.find('#modalImg').attr('src', img);
            modal.find('#modalDesc').html(desc ? desc : '<p class="text-muted">No description available.</p>');
        });
    });
</script>
@endsection