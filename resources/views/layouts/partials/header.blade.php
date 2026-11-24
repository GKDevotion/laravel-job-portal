<header class="jobstock-header sticky-top">
    <nav class="navbar navbar-expand-xl navbar-light bg-white">
        <div class="container">
            <a class="navbar-brand jobstock-brand" href="{{ route('home') }}" aria-label="JobStock home">
                <span class="brand-mark"><i class="bi bi-briefcase-fill" aria-hidden="true"></i></span>
                <span>JobStock<span class="brand-period">.</span></span>
            </a>

            <button class="navbar-toggler border-0 shadow-none" type="button"
                    data-bs-toggle="collapse" data-bs-target="#jobstockNavbar"
                    aria-controls="jobstockNavbar" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="jobstockNavbar">
                <ul class="navbar-nav mx-auto mb-3 mb-xl-0">
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">Home</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('jobs.*') ? 'active' : '' }}" href="{{ route('jobs.index') }}">Find Jobs</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('jobs.index', ['category' => 'technology']) }}">Explore Categories</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('home') }}#how-it-works">How It Works</a></li>
                </ul>

                <div class="header-actions d-flex flex-column flex-xl-row align-items-stretch align-items-xl-center gap-2">
                    <a class="btn btn-login" href="{{ route('login') }}">Sign In</a>
                    <a class="btn btn-outline-primary rounded-pill px-4" href="{{ route('register') }}">Register</a>
                    <a class="btn btn-primary rounded-pill px-4" href="{{ route('employer.register') }}">
                        Post a Job <i class="bi bi-arrow-up-right ms-1" aria-hidden="true"></i>
                    </a>
                </div>
            </div>
        </div>
    </nav>
</header>
