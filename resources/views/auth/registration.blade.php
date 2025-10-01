<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            min-height: 100vh;
            background: linear-gradient(135deg, #667eea, #764ba2);
            display: flex;
            justify-content: center;
            align-items: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .card {
            border-radius: 15px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.2);
            padding: 2rem;
            width: 400px;
            background: rgba(255, 255, 255, 0.95);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.25);
        }
        .btn-primary {
            background: #667eea;
            border: none;
            transition: all 0.3s ease;
        }
        .btn-primary:hover {
            background: #5a67d8;
        }
        a {
            color: #667eea;
            transition: color 0.3s ease;
        }
        a:hover {
            color: #5a67d8;
        }
        .form-floating > .form-control:focus ~ label {
            color: #667eea;
        }
    </style>
</head>
<body>

<div class="card">
    <h3 class="text-center mb-4">Register</h3>

    @if ($errors->any())
        <div class="alert alert-danger">
            {{ $errors->first() }}
        </div>
    @endif

    <form action="{{ route('register') }}" method="POST">
        @csrf

        <div class="form-floating mb-3">
            <input type="text" class="form-control" id="name" placeholder="Name" name="name" value="{{ old('name') }}" required>
            <label for="name">Name</label>
        </div>

        <div class="form-floating mb-3">
            <input type="email" class="form-control" id="email" placeholder="Email" name="email" value="{{ old('email') }}" required>
            <label for="email">Email</label>
        </div>

        <div class="form-floating mb-3">
            <input type="text" class="form-control" id="address" placeholder="Address" name="address" value="{{ old('address') }}" required>
            <label for="address">Address</label>
        </div>

        <div class="form-floating mb-3">
            <input type="password" class="form-control" id="password" placeholder="Password" name="password" required>
            <label for="password">Password</label>
        </div>

        <button type="submit" class="btn btn-primary w-100 mb-3">Register</button>

        <div class="text-center">
            Already have an account? <a href="{{ route('login') }}">Login</a>
        </div>
    </form>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
