<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login - TUKEL App</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">

    <div class="bg-white shadow-md rounded-lg p-8 w-full max-w-md">
        <h1 class="text-2xl font-bold mb-6 text-center">Login ke TUKEL</h1>

        @if ($errors->any())
            <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
                <ul class="list-disc ml-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('login.process') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label for="email" class="block text-gray-700">Email</label>
                <input type="email" name="email" id="email"
                       class="border border-gray-300 rounded w-full p-2 mt-1"
                       required>
            </div>

            <div class="mb-6">
                <label for="password" class="block text-gray-700">Password</label>
                <input type="password" name="password" id="password"
                       class="border border-gray-300 rounded w-full p-2 mt-1"
                       required>
            </div>

            <button type="submit"
                    class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700">
                Login
            </button>
        </form>

        <div class="mt-6">
            <div class="flex items-center justify-center">
                <div class="border-t border-gray-300 w-1/4"></div>
                <span class="mx-2 text-gray-500 text-sm">atau</span>
                <div class="border-t border-gray-300 w-1/4"></div>
            </div>

            <a href="{{ route('google.redirect') }}"
               class="mt-4 flex items-center justify-center gap-2 w-full py-2 px-4 border border-gray-300 rounded hover:bg-gray-100">
                <img src="https://www.svgrepo.com/show/475656/google-color.svg" width="20" alt="Google Logo">
                <span class="text-gray-700 font-medium">Login dengan Google</span>
            </a>
        </div>
    </div>

</body>
</html>
