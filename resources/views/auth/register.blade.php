<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Royal SaaS Platform</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #0a0a0a 0%, #1a1a1a 100%);
            color: #ffffff;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .container {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 40px;
            width: 100%;
            max-width: 400px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
            border: 1px solid rgba(255, 215, 0, 0.2);
        }

        .logo {
            text-align: center;
            margin-bottom: 30px;
        }

        .logo h1 {
            font-size: 2.5rem;
            font-weight: 300;
            background: linear-gradient(45deg, #ffd700, #ffed4e);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 10px;
        }

        .logo p {
            color: #b0b0b0;
            font-size: 0.9rem;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            color: #e0e0e0;
            font-weight: 500;
        }

        .form-group input {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 8px;
            background: rgba(255, 255, 255, 0.05);
            color: #ffffff;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .form-group input:focus {
            outline: none;
            border-color: #ffd700;
            box-shadow: 0 0 0 2px rgba(255, 215, 0, 0.2);
        }

        .form-group input::placeholder {
            color: #888;
        }

        .register-btn {
            width: 100%;
            padding: 15px;
            background: linear-gradient(45deg, #ffd700, #ffed4e);
            color: #000;
            border: none;
            border-radius: 10px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-bottom: 20px;
        }

        .register-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(255, 215, 0, 0.3);
        }

        .links {
            text-align: center;
        }

        .links a {
            color: #ffd700;
            text-decoration: none;
            font-size: 0.9rem;
        }

        .links a:hover {
            text-decoration: underline;
        }

        .divider {
            margin: 20px 0;
            text-align: center;
            position: relative;
        }

        .divider::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            height: 1px;
            background: rgba(255, 255, 255, 0.2);
        }

        .divider span {
            background: #1a1a1a;
            padding: 0 15px;
            color: #888;
            font-size: 0.8rem;
        }

        .social-login {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
        }

        .social-btn {
            flex: 1;
            padding: 12px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 8px;
            background: rgba(255, 255, 255, 0.05);
            color: #e0e0e0;
            text-decoration: none;
            text-align: center;
            transition: all 0.3s ease;
        }

        .social-btn:hover {
            border-color: #ffd700;
            background: rgba(255, 215, 0, 0.1);
        }

        .error {
            background: rgba(220, 38, 38, 0.1);
            border: 1px solid rgba(220, 38, 38, 0.3);
            color: #fca5a5;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
            font-size: 0.9rem;
        }

        .terms {
            font-size: 0.8rem;
            color: #888;
            text-align: center;
            margin-bottom: 20px;
        }

        .terms a {
            color: #ffd700;
        }

        @media (max-width: 480px) {
            .container {
                padding: 20px;
            }

            .logo h1 {
                font-size: 2rem;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="logo">
            <h1>ROYAL SAAS</h1>
            <p>Join the Premium Platform</p>
        </div>

        @if($errors->any())
            <div class="error">
                @foreach($errors->all() as $error)
                    {{ $error }}<br>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div class="form-group">
                <label for="name">Full Name</label>
                <input type="text" id="name" name="name" value="{{ old('name') }}" required autofocus>
            </div>

            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>

            <div class="form-group">
                <label for="password_confirmation">Confirm Password</label>
                <input type="password" id="password_confirmation" name="password_confirmation" required>
            </div>

            <div class="terms">
                By registering, you agree to our <a href="#">Terms of Service</a> and <a href="#">Privacy Policy</a>
            </div>

            <button type="submit" class="register-btn">Create Account</button>
        </form>

        <div class="divider">
            <span>or continue with</span>
        </div>

        <div class="social-login">
            <a href="#" class="social-btn">Google</a>
            <a href="#" class="social-btn">GitHub</a>
        </div>

        <div class="links">
            <a href="{{ route('login') }}">Already have an account? Sign in</a>
        </div>
    </div>
</body>
</html>
