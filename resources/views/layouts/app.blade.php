<!doctype html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>@yield('title', 'My App')</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
html, body {
    height: 100%;
    margin: 0;
}

body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    display: flex;
    flex-direction: column;
}
    
.navbar {
    z-index: 1030;
}

.content-wrapper {
    display: flex;
    flex: 1 1 auto;
    margin-top: 56px; 
}

.sidebar {
    width: 220px;
    background: #2c3e50;
    color: white;
    height: calc(100vh - 56px);
    position: fixed;
    top: 56px;
    right: 0;
    display: flex;
    flex-direction: column;
    overflow-y: auto;
    transition: all 0.3s;
}

.sidebar ul {
    list-style: none;
    padding: 0;
    margin: 0;
    flex: 1;
}

.sidebar ul li a {
    display: flex;
    align-items: center;
    color: white;
    padding: 12px 20px;
    text-decoration: none;
    transition: background 0.3s;
}

.sidebar ul li a i {
    width: 30px;
    text-align: center;
    font-size: 18px;
}

.sidebar ul li a .link-text {
    margin-left: 10px;
}

.sidebar ul li a:hover {
    background: #34495e;
}

.main-content {
    flex: 1;
    padding: 20px;
    margin-right: 220px;
}

footer {
    background: #343a40;
    color: white;
    text-align: center;
    padding: 12px 0;
    flex-shrink: 0;
}
</style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
<div class="container-fluid">
    <a class="navbar-brand" href="{{ url('/') }}">MyCompany</a>
    <div class="collapse navbar-collapse">
        <ul class="navbar-nav ms-auto">
            @guest
                <li class="nav-item"><a class="nav-link" href="{{ route('register.form') }}">Register</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Login</a></li>
            @endguest
            @auth
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('logout') }}"
                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        Logout
                    </a>
                </li>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            @endauth
        </ul>
    </div>
</div>
</nav>

<div class="content-wrapper">
    <div class="main-content">
        @yield('content')
    </div>

    <div class="sidebar">
        @include('layouts.sidebar')
    </div>
</div>

<footer>
&copy; {{ date('Y') }} MyCompany. All Rights Reserved.
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
@yield('scripts')
</body>
</html>
