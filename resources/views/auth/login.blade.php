<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - SIM HealtEase</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;700&display=swap');
        :root {
            --primary: #2BBBAD;
            --secondary: #1976D2;
            --background: #F9FBFC;
            --surface: #FFFFFF;
            --text: #222222;
            --border: rgba(25, 118, 210, 0.2);
            --primary-10: rgba(43, 187, 173, 0.12);
        }
        .font-modify {
            font-family: "Plus Jakarta Sans", sans-serif;
        }
        body {
            background: var(--background);
            color: var(--text);
        }
        .auth-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 1.5rem;
            box-shadow: 0 18px 40px rgba(25, 118, 210, 0.12);
        }
        .input-field {
            border: 1px solid rgba(25, 118, 210, 0.25);
        }
        .input-field:focus {
            outline: none;
            box-shadow: 0 0 0 3px var(--primary-10);
            border-color: var(--primary);
        }
        .btn-primary {
            background: var(--primary);
        }
        .btn-primary:hover {
            background: var(--secondary);
        }
        .link-primary {
            color: var(--secondary);
        }
        .link-primary:hover {
            color: var(--primary);
        }
        .hover-secondary:hover {
            color: var(--secondary);
        }
    </style>
</head>
<body class="min-h-screen font-modify">

    <div class="min-h-screen flex items-start sm:items-center justify-center px-4 py-10">
        <form action="{{ url('/login') }}" method="POST"
              autocomplete="off"
              class="auth-card w-full max-w-md p-6 sm:p-8">
        @csrf

        {{-- Logo --}}
        <div class="flex justify-center mb-4">
            <img src="{{ asset('images/logo2.png') }}" alt="Logo" class="h-16">
        </div>

        <h1 class="text-2xl font-bold mb-2 text-center" style="color: var(--secondary);">
            Selamat Datang SIM HealtEase
        </h1>

        <p class="text-sm text-gray-500 text-center mb-6">
            Silakan login untuk melanjutkan
        </p>

        {{-- Error message --}}
        @if ($errors->any())
            <div class="mb-4 text-sm text-red-600 text-center">
                {{ $errors->first() }}
            </div>
        @endif

        {{-- Email --}}
        <div class="mb-4">
            <label class="block mb-1 text-gray-700 font-medium">Email</label>
            <input type="email"
                   name="email"
                   value="{{ old('email') }}"
                   required
                   autocomplete="email"
                   class="input-field w-full px-4 py-2 rounded-md">
        </div>

        {{-- Password --}}
        <div class="mb-4">
            <label class="block mb-1 text-gray-700 font-medium">Password</label>

            <div class="relative">
                <input id="password"
                       type="password"
                       name="password"
                       required
                       autocomplete="current-password"
                       class="input-field w-full px-4 py-2 rounded-md pr-12">

                <button type="button"
                        id="togglePassword"
                        class="absolute inset-y-0 right-0 flex items-center px-3 text-gray-500 hover-secondary">
                    <svg id="eyeOpen" xmlns="http://www.w3.org/2000/svg"
                         class="h-5 w-5 text-gray-600"
                         viewBox="0 0 24 24"
                         fill="none" stroke="currentColor"
                         stroke-width="2">
                        <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/>
                        <circle cx="12" cy="12" r="3"/>
                    </svg>

                    <svg id="eyeClosed" xmlns="http://www.w3.org/2000/svg"
                         class="h-5 w-5 text-gray-600 hidden"
                         viewBox="0 0 24 24"
                         fill="none" stroke="currentColor"
                         stroke-width="2">
                        <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-10-8-10-8a18.45 18.45 0 0 1 5.06-7.94"/>
                        <path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 10 8 10 8a18.5 18.5 0 0 1-4.22 6.18"/>
                        <line x1="1" y1="1" x2="23" y2="23"/>
                    </svg>
                </button>
            </div>
        </div>

        {{-- Ingat Saya --}}
        <div class="flex items-center justify-between mb-6">
            <label class="flex items-center text-sm text-gray-600">
                <input type="checkbox" name="remember" class="mr-2 rounded border-gray-300">
                Ingat saya
            </label>
        </div>

        {{-- Login --}}
        <button type="submit"
                class="btn-primary w-full text-white font-semibold py-2 px-4 rounded-md transition">
            Login
        </button>

        {{-- Register --}}
        <div class="mt-4 text-center text-sm text-gray-600">
            Belum punya akun?
            <a href="{{ route('register.form') }}"
               class="link-primary font-semibold hover:underline">
                Daftar di sini
            </a>
        </div>

        {{-- Back to landing --}}
        <div class="mt-2 text-center text-xs">
            <a href="https://kalunaliving.store"
               class="text-gray-500 hover-secondary hover:underline">
                ← Kembali ke halaman utama
            </a>
        </div>
        </form>
    </div>

    <script>
        const passwordInput  = document.getElementById('password');
        const togglePassword = document.getElementById('togglePassword');
        const eyeOpen        = document.getElementById('eyeOpen');
        const eyeClosed      = document.getElementById('eyeClosed');

        togglePassword.addEventListener('click', () => {
            const isHidden = passwordInput.type === 'password';
            passwordInput.type = isHidden ? 'text' : 'password';
            eyeOpen.classList.toggle('hidden', isHidden);
            eyeClosed.classList.toggle('hidden', !isHidden);
        });
    </script>

</body>
</html>

