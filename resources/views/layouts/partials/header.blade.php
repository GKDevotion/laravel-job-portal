<header class="jobstock-header sticky-top">
    <nav class="navbar navbar-expand-xl navbar-light bg-white py-3">
        <div class="container">
            <a class="navbar-brand jobstock-brand" href="{{ route('home') }}" aria-label="JobStock home">Job<span>Stock</span><small>.</small></a>
            <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#jobstockNavbar" aria-controls="jobstockNavbar" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
            <div class="collapse navbar-collapse" id="jobstockNavbar">
                <ul class="navbar-nav mx-auto mb-2 mb-xl-0 gap-xl-2">
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">Home</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('jobs.*') ? 'active' : '' }}" href="{{ route('jobs.index') }}">Find Jobs</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('jobs.index', ['category' => 'technology']) }}">Explore Categories</a></li>
                    <li class="nav-item"><a class="nav-link" href="#how-it-works">How It Works</a></li>
                </ul>
                <div class="d-flex flex-column flex-xl-row gap-2 align-items-stretch align-items-xl-center">
                    <a class="btn btn-link text-decoration-none text-dark fw-semibold" href="{{ route('login') }}">Sign In</a>
                    <a class="btn btn-outline-primary rounded-pill px-4" href="{{ route('register') }}">Register</a>
                    <a class="btn btn-primary rounded-pill px-4" href="{{ route('employer.register') }}">Post a Job</a>
                </div>
            </div>
        </div>
    </nav>
</header>
