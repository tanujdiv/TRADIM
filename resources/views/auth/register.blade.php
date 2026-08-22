<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Create Account - Tradim</title>


    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">


    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css" rel="stylesheet">


    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">


    <link rel="stylesheet" href="{{ asset('css/tradim.css') }}">


    <style>
        body {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 30px 15px;
        }


        .auth-wrapper {
            width: 100%;
            max-width: 470px;
        }


        .auth-logo {
            display: flex;
            justify-content: center;
            margin-bottom: 25px;
        }


        .auth-card {
            background: rgba(16, 23, 42, .95);
            border: 1px solid rgba(255, 255, 255, .08);
            border-radius: 20px;
            padding: 35px;
            box-shadow: 0 30px 80px rgba(0, 0, 0, .35);
        }


        .auth-title {
            text-align: center;
            font-size: 25px;
            font-weight: 800;
            margin-bottom: 8px;
        }


        .auth-subtitle {
            text-align: center;
            color: #7d88a2;
            font-size: 12px;
            margin-bottom: 28px;
        }


        .auth-label {
            color: #b5bed2;
            font-size: 11px;
            font-weight: 600;
            margin-bottom: 7px;
        }


        .auth-input {
            width: 100%;
            height: 46px;
            border-radius: 9px;
            border: 1px solid rgba(255, 255, 255, .09);
            background: #0b1020;
            color: white;
            padding: 0 14px;
            outline: none;
            font-size: 13px;
        }


        .auth-input:focus {
            border-color: #8b5cf6;
            box-shadow: 0 0 0 3px rgba(139, 92, 246, .1);
        }


        .auth-input::placeholder {
            color: #58637d;
        }


        .auth-group {
            margin-bottom: 17px;
        }


        .auth-button {
            width: 100%;
            height: 47px;
            border: 0;
            border-radius: 9px;
            color: white;
            font-weight: 700;
            font-size: 13px;
            background: linear-gradient(135deg,
                    #7c3aed,
                    #ec4899);
        }


        .auth-footer {
            text-align: center;
            color: #707b95;
            font-size: 11px;
            margin-top: 22px;
        }


        .auth-footer a {
            color: #c084fc;
            text-decoration: none;
            font-weight: 700;
        }


        .error-message {
            color: #fca5a5;
            font-size: 10px;
            margin-top: 5px;
        }
    </style>

</head>


<body>


    <div class="auth-wrapper">


        <div class="auth-logo">

            <a href="{{ route('home') }}" class="tradim-logo">

                <span class="logo-symbol">
                    ∞
                </span>

                <span class="logo-text">
                    TRADIM
                </span>

            </a>

        </div>


        <div class="auth-card">


            <h1 class="auth-title">
                Create your account
            </h1>


            <p class="auth-subtitle">
                Join Tradim and start your journey.
            </p>


            <form action="{{ route('register') }}" method="POST">

                @csrf


                <div class="auth-group">

                    <label class="auth-label">
                        Full Name
                    </label>

                    <input type="text" name="name" class="auth-input" placeholder="Enter your name"
                        value="{{ old('name') }}" required>

                    @error('name')
                        <div class="error-message">
                            {{ $message }}
                        </div>
                    @enderror

                </div>


                <div class="auth-group">

                    <label class="auth-label">
                        Username
                    </label>

                    <input type="text" name="username" class="auth-input" placeholder="Choose a username"
                        value="{{ old('username') }}" required>

                    @error('username')
                        <div class="error-message">
                            {{ $message }}
                        </div>
                    @enderror

                </div>


                <div class="auth-group">

                    <label class="auth-label">
                        Email Address
                    </label>

                    <input type="email" name="email" class="auth-input" placeholder="you@example.com"
                        value="{{ old('email') }}" required>

                    @error('email')
                        <div class="error-message">
                            {{ $message }}
                        </div>
                    @enderror

                </div>


                <div class="auth-group">

                    <label class="auth-label">
                        Password
                    </label>

                    <input type="password" name="password" class="auth-input" placeholder="Minimum 8 characters"
                        required>

                    @error('password')
                        <div class="error-message">
                            {{ $message }}
                        </div>
                    @enderror

                </div>


                <div class="auth-group">

                    <label class="auth-label">
                        Confirm Password
                    </label>

                    <input type="password" name="password_confirmation" class="auth-input"
                        placeholder="Repeat your password" required>

                </div>


                <button type="submit" class="auth-button">

                    Create Account

                </button>


            </form>


            <div class="auth-footer">

                Already have an account?

                <a href="{{ route('login') }}">
                    Sign in
                </a>

            </div>


        </div>

    </div>


</body>

</html>