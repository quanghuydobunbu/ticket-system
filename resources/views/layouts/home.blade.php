@include('components/user/header')

<body class="bg-gray-50">
    @include('components/user/nav')

    {{-- @include('components/user/slider') --}}
    <!-- Event Categories -->
    
    @yield('content')

    <!-- Footer -->
    @include('components/user/footer')
    <style>
        @keyframes fade-in {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .animate-fade-in {
            animation: fade-in 0.6s ease-out;
        }
        
        .max-h-90vh {
            max-height: 90vh;
        }

        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
        }
        
        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }
        
        ::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 3px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: #a1a1a1;
        }
    </style>
</body>
</html>