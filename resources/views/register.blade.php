<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Register</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        function validateForm() {
            const name = document.querySelector('input[name="name"]').value;
            const email = document.querySelector('input[name="email"]').value;
            const phone = document.querySelector('input[name="phone"]').value;
            const nid = document.querySelector('input[name="nid"]').value;
            const password = document.querySelector('input[name="password"]').value;
            const confirmPassword = document.querySelector('input[name="password_confirmation"]').value;
            
            let errors = [];
            
            if (!name.trim()) errors.push('Name is required');
            if (!email.includes('@')) errors.push('Valid email is required');
            if (!/^[0-9]{11}$|^[0-9]{14}$/.test(phone)) errors.push('Phone must be 11 or 14 digits');
            if (!/^[0-9]{13}$|^[0-9]{17}$/.test(nid)) errors.push('NID must be 13 or 17 digits');
            if (password.length < 8) errors.push('Password must be at least 8 characters');
            if (password !== confirmPassword) errors.push('Passwords do not match');
            
            if (errors.length > 0) {
                alert(errors.join('\n'));
                return false;
            }
            return true;
        }
        
        function validateField(field) {
            const value = field.value;
            const name = field.name;
            let isValid = true;
            
            field.classList.remove('border-red-400', 'border-green-400');
            
            switch(name) {
                case 'phone':
                    isValid = /^[0-9]{11}$|^[0-9]{14}$/.test(value);
                    break;
                case 'nid':
                    isValid = /^[0-9]{13}$|^[0-9]{17}$/.test(value);
                    break;
                case 'password':
                    isValid = value.length >= 8;
                    break;
                case 'password_confirmation':
                    const password = document.querySelector('input[name="password"]').value;
                    isValid = value === password && value.length >= 8;
                    break;
            }
            
            field.classList.add(isValid ? 'border-green-400' : 'border-red-400');
        }
    </script>
</head>

<body class="min-h-screen bg-gray-100 flex items-center justify-center">
    <div class="bg-white p-8 rounded shadow w-full max-w-md">
        <h2 class="text-2xl font-semibold text-center mb-6 text-slate-700">Register</h2>
        @if ($errors->has('nid_verification'))
            <x-alert type="error">
                {{ $errors->first('nid_verification') }}
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

        <form method="POST" action="{{ route('register.submit') }}" onsubmit="return validateForm()">
            @csrf
            <input type="text" name="name" placeholder="Full Name" required value="{{ old('name') }}"
                class="w-full p-3 mb-3 rounded border border-gray-200 bg-gray-100 focus:ring-2 focus:ring-green-200 focus:border-green-400 outline-none" />

            <input type="email" name="email" placeholder="Email" required value="{{ old('email') }}"
                class="w-full p-3 mb-3 rounded border border-gray-200 bg-gray-100 focus:ring-2 focus:ring-green-200 focus:border-green-400 outline-none" />

            <input type="text" name="phone" placeholder="Phone Number (11 or 14 digits)" required value="{{ old('phone') }}"
                class="w-full p-3 mb-3 rounded border border-gray-200 bg-gray-100 focus:ring-2 focus:ring-green-200 focus:border-green-400 outline-none" 
                onblur="validateField(this)" oninput="validateField(this)" />

            <input type="text" name="nid" placeholder="NID (13 or 17 digits)" required value="{{ old('nid') }}"
                class="w-full p-3 mb-3 rounded border border-gray-200 bg-gray-100 focus:ring-2 focus:ring-green-200 focus:border-green-400 outline-none" 
                onblur="validateField(this)" oninput="validateField(this)" />

            <input type="date" name="dob" placeholder="Date of Birth" required value="{{ old('dob') }}"
                class="w-full p-3 mb-3 rounded border border-gray-200 bg-gray-100 focus:ring-2 focus:ring-green-200 focus:border-green-400 outline-none" />

            <input type="password" name="password" placeholder="Password (min 8 characters)" required
                class="w-full p-3 mb-3 rounded border border-gray-200 bg-gray-100 focus:ring-2 focus:ring-green-200 focus:border-green-400 outline-none" 
                onblur="validateField(this)" oninput="validateField(this)" />

            <input type="password" name="password_confirmation" placeholder="Confirm Password" required
                class="w-full p-3 mb-4 rounded border border-gray-200 bg-gray-100 focus:ring-2 focus:ring-green-200 focus:border-green-400 outline-none" 
                onblur="validateField(this)" oninput="validateField(this)" />

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