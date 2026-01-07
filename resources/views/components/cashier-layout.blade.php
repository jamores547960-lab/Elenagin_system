<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Elenagin') }} -  System</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Tailwind CSS CDN for quick setup -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100">
        
        <!-- Cashier Header -->
        <header class="bg-white shadow-md">
            <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8 py-4">
                <div class="flex justify-between items-center">
                    <div class="flex items-center gap-3">
                        <div class="text-2xl font-bold text-indigo-600">
                            <i class="fas fa-shopping-cart"></i> {{ config('app.name', 'Elenagin') }} POS
                        </div>
                    </div>
                    
                    <div class="flex items-center gap-6">
                        <div class="text-sm text-gray-600">
                            <span class="font-medium">Cashier:</span> 
                            <span class="text-gray-800">{{ auth()->user()->name ?? 'Guest' }}</span>
                        </div>
                        <div class="text-sm text-gray-600">
                            <span class="font-medium">Date:</span> 
                            <span class="text-gray-800">{{ now()->format('M d, Y') }}</span>
                        </div>
                        
                        @auth
                          <form method="POST" action="{{ route('logout') }}" class="inline">
                                @csrf
                                <button type="submit" class="text-sm text-red-600 hover:text-red-800 font-medium transition">
                                    Logout
                                </button>
                          </form>

                        @endauth
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main>
            {{ $slot }}
        </main>
    </div>
</body>
</html>