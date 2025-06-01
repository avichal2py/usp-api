<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Required</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('images/icon.jpg') }}">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <div class="bg-white p-8 rounded-lg shadow-xl max-w-md w-full animate__animated animate__fadeIn">
        <div class="text-center mb-6">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto text-yellow-500 animate__animated animate__pulse animate__infinite" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <h1 class="text-2xl font-bold text-gray-800 mt-4">Payment Required</h1>
            <p class="text-gray-600 mt-2">{{ $message ?? 'Please clear your dues to proceed.' }}</p>
        </div>
        
        <div class="bg-yellow-50 border-l-4 border-yellow-500 p-4 mb-6 animate__animated animate__fadeInLeft">
            <p class="text-yellow-700">Your access to this page is restricted until payment is cleared.</p>
        </div>

        <div class="flex flex-col space-y-4">
            <a href="{{ route('student.finance') }}" class="bg-blue-600 hover:bg-blue-700 text-white py-3 px-4 rounded-lg text-center transition duration-300 transform hover:scale-105">
                Go to Finance Portal
            </a>
            <a href="{{ url()->previous() }}" class="text-gray-600 hover:text-gray-800 text-center transition duration-300">
                ← Back to Previous Page
            </a>
        </div>
    </div>
</body>
</html>