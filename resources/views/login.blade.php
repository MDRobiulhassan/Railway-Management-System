<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-gray-100 flex items-center justify-center">
    <div class="bg-white p-8 rounded shadow w-full max-w-md">
        <h2 class="text-2xl font-semibold text-center mb-6 text-slate-700">Login</h2>
        @if (session('success'))
            <x-alert type="success">
                {{ session('success') }}
            </x-alert>
        @endif

        @if ($errors->has('login_failed'))
            <x-alert type="error">
                {{ $errors->first('login_failed') }}
            </x-alert>
        @elseif ($errors->any())
            <x-alert type="error">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </x-alert>
        @endif

        <form method="POST" action="{{ route('login.submit') }}">
            @csrf
            <input type="email" name="email" placeholder="Email" required
                value="{{ old('email') }}"
                class="w-full p-3 mb-3 rounded border border-gray-200 bg-gray-100 focus:ring-2 focus:ring-green-200 focus:border-green-400 outline-none" />

            <input type="text" name="phone" placeholder="Phone Number" required
                value="{{ old('phone') }}"
                class="w-full p-3 mb-3 rounded border border-gray-200 bg-gray-100 focus:ring-2 focus:ring-green-200 focus:border-green-400 outline-none" />

            <input type="password" name="password" placeholder="Password" required
                class="w-full p-3 mb-3 rounded border border-gray-200 bg-gray-100 focus:ring-2 focus:ring-green-200 focus:border-green-400 outline-none" />

            <a href="{{ route('password.form') }}" class="text-orange-500 text-sm mb-4 block text-right hover:underline">
                Forgot Password?
            </a>

            <button type="submit"
                class="w-full rounded-full bg-green-400 text-white font-semibold py-3 transition hover:bg-green-500 mb-2">
                Submit
            </button>
        </form>
        <p class="text-center text-sm text-gray-500 mt-4">
            Don’t have an account?
            <a href="{{ route('register.form') }}" class="text-green-700 font-semibold hover:underline">Register</a>
        </p>
    </div>
</body>
</html>
