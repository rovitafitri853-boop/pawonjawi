<nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom shadow-sm" style="padding: 0.75rem 1.25rem;">
    <div class="container-fluid">
        <button id="sidebarToggle" class="btn border-0 p-0 me-3 fs-4 text-dark shadow-none">
            <i class="bi bi-list"></i>
        </button>

        <div class="ms-auto dropdown">
            <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle text-dark" 
               id="userDropdown" 
               data-bs-toggle="dropdown" 
               aria-expanded="false">
                <img src="https://ui-avatars.com/api/?name={{ auth()->user()->name ?? 'Admin' }}&background=0d6efd&color=fff" 
                     alt="Profile" width="36" height="36" class="rounded-circle me-2">
                <span class="fw-medium">{{ auth()->user()->name ?? 'Admin' }}</span>
            </a>

            <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 mt-2" aria-labelledby="userDropdown">
                <li><a class="dropdown-item py-2" href="#"><i class="bi bi-person me-2"></i> Profile</a></li>
                <li><a class="dropdown-item py-2" href="#"><i class="bi bi-key me-2"></i> Reset Password</a></li>
                <li><hr class="dropdown-divider my-1"></li>
                <li>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="dropdown-item py-2 text-danger border-0 bg-transparent w-100 text-start">
                            <i class="bi bi-box-arrow-right me-2"></i> Logout
                        </button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</nav>