<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pawon Jawi</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap');
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-[#FDFDFC] text-[#1B1B18] min-h-screen flex flex-col items-center justify-center p-6">

    <div class="max-w-md w-full text-center">
        <div class="mb-6 flex justify-center">
            <x-application-logo class="w-16 h-16 text-[#FF2D20]" />
        </div>

        <h1 class="text-4xl font-semibold tracking-tight mb-10">Pawon Jawi</h1>

        <div class="bg-white border border-[#e3e3e0] rounded-2xl p-8 shadow-[0px_0px_1px_0px_rgba(0,0,0,0.03),0px_1px_2px_0px_rgba(0,0,0,0.06)]">
            
            <div class="space-y-3">
                <a href="{{ route('login') }}" class="block w-full py-3 px-4 bg-[#1b1b18] text-white font-medium rounded-lg hover:bg-black transition duration-200">
                    Login Admin
                </a>

                <a href="{{ route('login') }}" class="block w-full py-3 px-4 border border-[#e3e3e0] text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition duration-200">
                    Login Kasir
                </a>
            </div>

            <div class="mt-8 pt-6 border-t border-gray-100 text-sm text-gray-400">
                &copy; {{ date('Y') }} Pawon Jawi
            </div>
        </div>
    </div>

</body>
</html>