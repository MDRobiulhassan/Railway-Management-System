<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Register</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-gray-100 flex items-center justify-center">
    <div class="bg-white p-8 rounded shadow w-full max-w-md">
        <h2 class="text-2xl font-semibold text-center mb-6 text-slate-700">Register</h2>
        @if ($errors->any())
            <div class="mb-4 text-red-600 text-sm">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form method="POST" action="{{ route('register.submit') }}">
            @csrf
            <input type="email" name="email" placeholder="Email" required
                value="{{ old('email') }}"
                class="w-full p-3 mb-3 rounded border border-gray-200 bg-gray-100 focus:ring-2 focus:ring-green-200 focus:border-green-400 outline-none" />

            <input type="text" name="phone" placeholder="Phone Number" required
                value="{{ old('phone') }}"
                class="w-full p-3 mb-3 rounded border border-gray-200 bg-gray-100 focus:ring-2 focus:ring-green-200 focus:border-green-400 outline-none" />

            <input type="text" name="nid" placeholder="NID" required
                value="{{ old('nid') }}"
                class="w-full p-3 mb-3 rounded border border-gray-200 bg-gray-100 focus:ring-2 focus:ring-green-200 focus:border-green-400 outline-none" />

            <input type="password" name="password" placeholder="Password" required
                class="w-full p-3 mb-3 rounded border border-gray-200 bg-gray-100 focus:ring-2 focus:ring-green-200 focus:border-green-400 outline-none" />

            <input type="password" name="password_confirmation" placeholder="Confirm Password" required
                class="w-full p-3 mb-4 rounded border border-gray-200 bg-gray-100 focus:ring-2 focus:ring-green-200 focus:border-green-400 outline-none" />

            <button type="submit"
                class="w-full rounded-full bg-green-400 text-white font-semibold py-3 transition hover:bg-green-500">
                Submit
            </button>
        </form>
        <p class="text-center text-sm text-gray-500 mt-4">
            Already have an account?
            <a href="{{ route('login.form') }}" class="text-green-700 font-semibold hover:underline">Login</a>
        </p>
    </div>
</body>
</html>
