<footer class="jobstock-footer mt-5">
    <div class="container py-5">
        <div class="row g-4">
            <div class="col-lg-4"><a class="jobstock-brand text-white" href="{{ route('home') }}">Job<span>Stock</span><small>.</small></a><p class="text-white-50 mt-3 mb-0">Find meaningful work, discover great teams and take the next step in your career.</p></div>
            <div class="col-6 col-lg-2"><h6>For Candidates</h6><a href="{{ route('jobs.index') }}">Browse Jobs</a><a href="{{ route('register') }}">Create Account</a></div>
            <div class="col-6 col-lg-2"><h6>For Employers</h6><a href="{{ route('employer.register') }}">Post a Job</a><a href="{{ route('login') }}">Employer Login</a></div>
            <div class="col-lg-4"><h6>Stay in the loop</h6><p class="text-white-50">More career resources and opportunities are coming soon.</p></div>
        </div>
        <hr class="border-secondary my-4"><div class="d-flex flex-column flex-md-row justify-content-between gap-2 text-white-50 small"><span>© {{ date('Y') }} JobStock. All rights reserved.</span><span>Built for better career connections.</span></div>
    </div>
</footer>
