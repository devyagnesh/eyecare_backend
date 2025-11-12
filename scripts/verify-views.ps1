# View Verification Script
# This script checks if all required views exist based on controller view() calls

Write-Host "=== VIEW VERIFICATION SCRIPT ===" -ForegroundColor Cyan
Write-Host ""

# Get all view() calls from controllers
$viewCalls = @()
$controllerFiles = Get-ChildItem -Path "app\Http\Controllers" -Recurse -Filter "*.php"
foreach ($file in $controllerFiles) {
    $content = Get-Content $file.FullName
    foreach ($line in $content) {
        if ($line -match "return view\('([^']+)'") {
            $viewCalls += $matches[1]
        } elseif ($line -match 'return view\("([^"]+)"') {
            $viewCalls += $matches[1]
        }
    }
}
$viewCalls = $viewCalls | Sort-Object -Unique

Write-Host "Views referenced in controllers:" -ForegroundColor Yellow
$viewCalls | ForEach-Object { Write-Host "  - $_" }

Write-Host ""
Write-Host "Checking if views exist..." -ForegroundColor Yellow

$missing = @()
$existing = @()

foreach ($view in $viewCalls) {
    $viewPath = "resources\views\" + $view.Replace('.', '\') + ".blade.php"
    if (Test-Path $viewPath) {
        $existing += $view
        Write-Host "  ✓ $view" -ForegroundColor Green
    } else {
        $missing += $view
        Write-Host "  ✗ $view (MISSING)" -ForegroundColor Red
    }
}

Write-Host ""
Write-Host "=== SUMMARY ===" -ForegroundColor Cyan
Write-Host "Total views referenced: $($viewCalls.Count)" -ForegroundColor White
Write-Host "Existing views: $($existing.Count)" -ForegroundColor Green
if ($missing.Count -eq 0) {
    Write-Host "Missing views: 0" -ForegroundColor Green
} else {
    Write-Host "Missing views: $($missing.Count)" -ForegroundColor Red
}

if ($missing.Count -gt 0) {
    Write-Host ""
    Write-Host "Missing views that need to be created:" -ForegroundColor Red
    $missing | ForEach-Object { Write-Host "  - $_" -ForegroundColor Red }
    exit 1
} else {
    Write-Host ""
    Write-Host "All views are present! ✓" -ForegroundColor Green
    exit 0
}

