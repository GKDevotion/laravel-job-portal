# JobStock Laravel 13 – Batch 01 Foundation

## Delivery type
This is a **Laravel application overlay package**, not a vendor-bundled Laravel installation. Apply it to a fresh Laravel 13 project created with Composer. No npm, Node.js, Vite build, React, or Next.js runtime is required by this batch. Composer is still required to install Laravel's PHP dependencies.

## Source project audit snapshot
The supplied ZIP contains **two parallel source variants**: `Jobstock_NextJs (Typescript)` and `Jobstock_NextJs (Javascript)`. The TypeScript variant contains 326 files, 88 App Router `page.tsx` routes, 220 files under `src/app`, and 100 public files; the JavaScript variant contains 323 files, 87 App Router `page.jsx` routes, 219 files under `src/app`, and 98 public files. The TypeScript manifest specifies Next.js 15.4.6, React 19.1.0, TypeScript 5, Bootstrap 5.3.7, Swiper 11.2.10, ApexCharts through react-apexcharts, React Select, RC Slider, and Google Map React. The JavaScript manifest specifies Next.js 15.4.5 and the same core React/Bootstrap ecosystem, with an additional `rc-tooltip` dependency. This batch uses the TypeScript variant as the canonical visual asset source and copies its supplied `public/img` and CSS/JS theme assets; React/Next-specific components are not copied into the Laravel runtime. The source homepage references `/img/4268.jpg`, but that exact file is absent from the supplied TypeScript public assets; the new Laravel hero therefore uses a CSS-based responsive treatment rather than a broken image reference.

## Included in Batch 01
- Laravel route entry points for home, job search, job detail, and temporary account screens.
- Reusable Blade master layout, header, and footer.
- Responsive JobStock homepage with database-driven category, featured-job, and latest-job sections.
- Search results and job detail pages.
- `JobCategory`, `Company`, and `Job` Eloquent models.
- Initial migration for categories, companies, and jobs.
- Demo seeder with clearly marked sample content.
- Original JobStock theme images, CSS, JS, and font assets copied under `public/assets/jobstock`.
- Bootstrap 5.3.8 and Bootstrap Icons loaded from CDN; local additive responsive overrides.
- Installation and testing instructions.

## Requirements
- PHP version supported by Laravel 13 (use the current Laravel 13 installation requirements when provisioning the server).
- Composer 2.
- MySQL 8+ recommended.
- PHP extensions required by Laravel 13.
- Internet access for CDN assets and Google Fonts. For offline or strict production environments, vendor the CDN files locally after licensing and version review.

## Install into a fresh Laravel 13 project

```bash
composer create-project laravel/laravel jobstock-laravel "^13.0"
cd jobstock-laravel
```

Copy the contents of this package's `app/`, `database/`, `resources/`, `routes/`, and `public/` directories into the matching directories of the Laravel project, preserving the directory structure. Review any existing files before overwriting. In particular, merge `routes/web.php` if the fresh app already contains routes you need.

Create a MySQL database named `jobstock`, then configure `.env`:

```env
APP_NAME=JobStock
APP_ENV=local
APP_DEBUG=true
APP_URL=http://127.0.0.1:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=jobstock
DB_USERNAME=root
DB_PASSWORD=
```

Then run:

```bash
php artisan key:generate
php artisan migrate
php artisan db:seed --class=JobStockDemoSeeder
php artisan optimize:clear
php artisan serve
```

Open `http://127.0.0.1:8000`.

## Routes in this batch
- `/` – database-backed public homepage
- `/jobs` – keyword/location job search with pagination
- `/jobs/{slug}` – job details
- `/login`, `/register`, `/employer/register` – intentional placeholder screens pending the authentication batch

## Verification checklist
1. `php artisan route:list` shows `home`, `jobs.index`, `jobs.show`, `login`, `register`, and `employer.register`.
2. `php artisan migrate:fresh --seed --seeder=JobStockDemoSeeder` succeeds on a disposable development database.
3. Homepage displays the demo categories, companies, and jobs.
4. Search by keyword and location returns matching records.
5. A job detail URL opens by slug.
6. Check 360px mobile, tablet, and desktop layouts.
7. Confirm theme CSS loads from `/assets/jobstock/css/styles.css` and no Next.js asset paths remain in rendered HTML.

## Scope boundaries / next batch
This batch does **not** claim to implement authentication, role permissions, employer/candidate dashboards, job applications, file uploads, messaging, subscriptions, payment gateways, or admin CRUD. Those require their own schema, policies, validation, controllers, and tests. Account routes are placeholders and the Apply button is intentionally disabled until the candidate workflow is built.

## Implementation notes
- Sample companies and job listings are demo seed data, not live vacancies.
- Job descriptions are escaped before rendering.
- Search is server-side through Eloquent and retains query parameters through pagination.
- The supplied theme CSS includes references to plugin stylesheets and font files. The complete relevant CSS asset tree is copied to preserve relative paths; some React-only plugin behavior will be replaced by native Bootstrap/JavaScript as the relevant pages are migrated.
- Review the original theme's commercial license before deploying its assets publicly.
