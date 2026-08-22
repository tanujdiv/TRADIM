<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login - Tradim</title>


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
            max-width: 430px;
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
            height: 47px;
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
            margin-bottom: 18px;
        }


        .remember-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
        }


        .remember-label {
            color: #7d88a2;
            font-size: 11px;
        }


        .forgot-link {
            color: #a78bfa;
            font-size: 10px;
            text-decoration: none;
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


        .general-error {
            padding: 10px 12px;
            border-radius: 8px;
            background: rgba(239, 68, 68, .08);
            border: 1px solid rgba(239, 68, 68, .15);
            color: #fca5a5;
            font-size: 11px;
            margin-bottom: 18px;
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
                Welcome back
            </h1>


            <p class="auth-subtitle">
                Sign in to continue to Tradim.
            </p>


            @if($errors->any())

                <div class="general-error">

                    {{ $errors->first() }}

                </div>

            @endif


            <form action="{{ route('login') }}" method="POST">

                @csrf


                <div class="auth-group">

                    <label class="auth-label">
                        Email Address
                    </label>

                    <input type="email" name="email" class="auth-input" placeholder="you@example.com"
                        value="{{ old('email') }}" required autofocus>

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

                    <input type="password" name="password" class="auth-input" placeholder="Enter your password"
                        required>

                </div>


                <div class="remember-row">

                    <label class="remember-label">

                        <input type="checkbox" name="remember" value="1">

                        Remember me

                    </label>


                    <a href="#" class="forgot-link">
                        Forgot password?
                    </a>

                </div>


                <button type="submit" class="auth-button">

                    Sign In

                </button>


            </form>


            <div class="auth-footer">

                Don't have an account?

                <a href="{{ route('register') }}">
                    Create account
                </a>

            </div>


        </div>

    </div>


</body>

</html>