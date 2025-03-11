<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Urbansiana CMS - @yield('title')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>
    <script src="{{ asset('js/app.js') }}" defer></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-TQH2L73V');</script>
<!-- End Google Tag Manager -->

</head>
<body class="bg-gray-50 min-h-screen">
    <!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-TQH2L73V"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
    <!-- Sidebar -->
    <div class="fixed inset-y-0 left-0 w-64 bg-gray-900 text-white shadow-xl">
        <div class="p-6 border-b border-gray-800">
            <h2 class="text-2xl font-bold bg-gradient-to-r from-blue-400 to-teal-400 bg-clip-text text-transparent">Urbansiana - CMS</h2>
            <p class="text-sm text-gray-400 mt-1">Sistem Manajemen Konten</p>
        </div>
        <nav class="mt-6 space-y-2 px-3">
            <a href="{{ route('articles.create') }}" class="flex items-center px-3 py-3 rounded-lg {{ request()->routeIs('articles.create') ? 'text-white bg-gray-800 border-l-4 border-blue-500' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }} transition-all duration-200 group">
                <i class="fas fa-pen-to-square mr-3 {{ request()->routeIs('articles.create') ? 'text-blue-400' : 'text-gray-400 group-hover:text-blue-400' }}"></i>
                Tulis Berita
            </a>
            <a href="{{ route('articles.index') }}" class="flex items-center px-3 py-3 rounded-lg {{ request()->routeIs('articles.index') ? 'text-white bg-gray-800 border-l-4 border-blue-500' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }} transition-all duration-200 group">
                <i class="fas fa-newspaper mr-3 {{ request()->routeIs('articles.index') ? 'text-blue-400' : 'text-gray-400 group-hover:text-blue-400' }}"></i>
                Semua Artikel
            </a>
            <a href="{{ route('categories.index') }}" class="flex items-center px-3 py-3 rounded-lg {{ request()->routeIs('categories.index') ? 'text-white bg-gray-800 border-l-4 border-blue-500' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }} transition-all duration-200 group">
                <i class="fas fa-folder mr-3 {{ request()->routeIs('categories.index') ? 'text-blue-400' : 'text-gray-400 group-hover:text-blue-400' }}"></i>
                Kategori
            </a>
            <a href="{{ route('reporters.index') }}" class="flex items-center px-3 py-3 rounded-lg {{ request()->routeIs('reporters.index') ? 'text-white bg-gray-800 border-l-4 border-blue-500' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }} transition-all duration-200 group">
                <i class="fa-solid fa-binoculars mr-3 {{ request()->routeIs('reporters.index') ? 'text-blue-400' : 'text-gray-400 group-hover:text-blue-400' }}"></i>
                From Reporter Lapangan
            </a>
        </nav>
    </div>
    <!-- Main Content -->
    <div class="ml-64 p-8">
        <div class="max-w-5xl mx-auto">
            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-md" role="alert">
                    <p>{{ session('success') }}</p>
                </div>
            @endif
            
            @if(session('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-md" role="alert">
                    <p>{{ session('error') }}</p>
                </div>
            @endif
            
            @yield('content')
        </div>
    </div>
    
    @stack('scripts')
</body>
