@echo off
setlocal
cd /d "%~dp0"

echo ==========================================
echo JobStock Batch 05-07 Automated Feature Tests
echo ==========================================
echo.
echo This runner uses your existing Laravel/PHPUnit installation.
echo It does not run migrations against your live .env database.
echo PHPUnit is configured to use SQLite in-memory.
echo.

if not exist "artisan" (
    echo ERROR: artisan was not found. Copy this file into your Laravel project root.
    exit /b 1
)

if not exist "vendor\autoload.php" (
    echo ERROR: vendor/autoload.php was not found.
    echo Run composer install in your project first, if dependencies are not installed.
    exit /b 1
)

php artisan test --testsuite=Feature --filter=JobStockBatch0507Test
set TEST_EXIT=%ERRORLEVEL%

echo.
if "%TEST_EXIT%"=="0" (
    echo SUCCESS: JobStock Batch 05-07 tests passed.
) else (
    echo FAILED: One or more tests failed. Review the PHPUnit output above.
)
exit /b %TEST_EXIT%
