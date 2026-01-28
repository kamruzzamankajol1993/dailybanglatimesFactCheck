<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <meta name="robots" content="index, follow">

    {{-- ডায়নামিক মেটা ট্যাগ সেকশন --}}
    @hasSection('meta')
        @yield('meta')
    @else
        {{-- ডিফল্ট মেটা ট্যাগ (হোম পেজ ও অন্যান্য পেজের জন্য) --}}
        <meta name="description" content="{{ $front_ins_d }}">
        <meta name="keywords" content="{{ $front_ins_k }}">
        <meta name="author" content="{{ $front_ins_name }} Team">

        <meta property="og:type" content="website">
        <meta property="og:url" content="{{ url()->current() }}">
        <meta property="og:title" content="{{ $front_ins_name }}">
        <meta property="og:description" content="{{ $front_ins_d }}">
        <meta property="og:image" content="{{ $front_admin_url }}{{ $front_mobile_version_logo }}">

        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:title" content="{{ $front_ins_name }}">
        <meta name="twitter:description" content="{{ $front_ins_d }}">
        <meta name="twitter:image" content="{{ $front_admin_url }}{{ $front_mobile_version_logo }}">
    @endif
   
    <title>@yield('title')</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    
    <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@300;400;500;600;700&family=Poppins:wght@300;400;600;700&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('/') }}public/front/css/style.css">
    <link rel="shortcut icon" href="{{ $front_admin_url }}{{ $front_icon_name }}">
      @yield('css')
</head>
<body class="bg-light">


    <!-- Top Header Include -->
 @include('front.include.topHeader') 
    <!-- End Top Header Include -->

     <!-- Header include -->
     @include('front.include.header')
    <!-- End Header include -->

    <!-- Headline Include -->
    {{-- @include('front.include.headline') --}}
    <!-- End Headline Include -->

    <!-- Last Header Include -->
    @include('front.include.lastHeader')
    <!-- End Last Header Include -->
  <!-- Offcanvas Include -->
    @include('front.include.offcanvas')
    <!-- End Offcanvas Include -->

    <!-- Main Content -->
    @yield('body')
    <!-- End Main Content -->

    <!-- Footer Include -->
    @include('front.include.footer')
    <!-- End Footer Include -->

  
   
   
    
 <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"
        integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
     <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  
    <script>
        function updateDateTime() {
            const now = new Date();
            const options = { 
                weekday: 'long', year: 'numeric', month: 'long', day: 'numeric',
                hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: true
            };
            const dateStr = now.toLocaleString('bn-BD', options);
            document.getElementById('bengali-date').textContent = dateStr ;
        }
        updateDateTime();
        setInterval(updateDateTime, 1000); 
    </script>
  @yield('scripts')
</body>
</html>