<ul>
    <li>
        <a href="{{ route('dashboard') }}">
            <i class="fas fa-home"></i>
            <span class="link-text">Dashboard</span>
        </a>
    </li>
    <li>
        <a href="{{ route('profile.index') }}">
            <i class="fas fa-user"></i>
            <span class="link-text">Profile CRUD</span>
        </a>
    </li>
    <li>
        <a href="{{ route('users.index') }}">
            <i class="fas fa-users"></i>
            <span class="link-text">User Data</span>
        </a>
    </li>
    <li>
        <a href="{{ route('geofences.page') }}">
            <i class="fas fa-map-marker-alt"></i>
            <span class="link-text">Geofencing</span>
        </a>
    </li>

    <li class="mt-3">
        <a href="{{ route('register.form') }}">
            <i class="fas fa-user-plus"></i>
            <span class="link-text">Register/Login</span>
        </a>
    </li>
</ul>
