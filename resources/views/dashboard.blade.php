<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - TUKEL</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-10">
    <div class="bg-white shadow-md rounded-lg p-8">
        <h1 class="text-2xl font-bold mb-4">Selamat Datang, {{ Auth::user()->name }}</h1>
        <p class="text-gray-700 mb-4">Kamu berhasil login ke sistem TUKEL 🎉</p>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">
                Logout
            </button>
        </form>
    </div>
</body>
</html>
