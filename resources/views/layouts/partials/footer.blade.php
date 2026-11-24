<footer class="jobstock-footer">
    <div class="container py-5">
        <div class="row g-4 g-xl-5">
            <div class="col-lg-4">
                <a class="jobstock-brand footer-brand" href="{{ route('home') }}">
                    <span class="brand-mark"><i class="bi bi-briefcase-fill" aria-hidden="true"></i></span>
                    <span>JobStock<span class="brand-period">.</span></span>
                </a>
                <p class="footer-description mt-3 mb-0">Find meaningful work, discover great teams and take the next step in your career.</p>
            </div>
            <div class="col-6 col-lg-2">
                <h6>For Candidates</h6>
                <a href="{{ route('jobs.index') }}">Browse Jobs</a>
                <a href="{{ route('register') }}">Create Account</a>
            </div>
            <div class="col-6 col-lg-2">
                <h6>For Employers</h6>
                <a href="{{ route('employer.register') }}">Post a Job</a>
                <a href="{{ route('login') }}">Employer Login</a>
            </div>
            <div class="col-lg-4">
                <h6>Stay in the loop</h6>
                <p class="footer-description">More career resources and opportunities are coming soon.</p>
            </div>
        </div>
        <div class="footer-bottom d-flex flex-column flex-md-row justify-content-between gap-2 mt-5 pt-4">
            <span>© {{ date('Y') }} JobStock. All rights reserved.</span>
            <span>Built for better career connections.</span>
        </div>
    </div>
</footer>
