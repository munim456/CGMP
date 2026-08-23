$ErrorActionPreference = 'Stop'
$root = Split-Path -Parent $PSScriptRoot
$dist = Join-Path $root 'dist'
$zip  = Join-Path $dist 'cgmp-deploy.zip'

if (-not (Test-Path (Join-Path $root 'public\build\manifest.json'))) {
    Write-Warning "public/build/manifest.json missing - run 'npm run build' first."
}

if (-not (Test-Path (Join-Path $root 'vendor'))) {
    throw "vendor/ missing - run 'composer install' first."
}

New-Item -ItemType Directory -Force -Path $dist | Out-Null
if (Test-Path $zip) { Remove-Item $zip -Force }

$excludes = @(
    '--exclude=.git',
    '--exclude=.opencode',
    '--exclude=node_modules',
    '--exclude=tests',
    '--exclude=dist',
    '--exclude=.env',
    '--exclude=storage/logs/*.log',
    '--exclude=storage/framework/sessions/*',
    '--exclude=storage/framework/cache/data/*',
    '--exclude=storage/framework/views/*.php'
)

Push-Location $root
tar.exe -a -c -f $zip @excludes app bootstrap config database deploy public resources routes storage vendor composer.json composer.lock package.json vite.config.js README.md DEPLOYMENT.md .env.example .gitignore artisan
Pop-Location

$size = [math]::Round((Get-Item $zip).Length / 1MB, 1)
Write-Host "Package created: dist\cgmp-deploy.zip ($size MB)"
Write-Host "Excluded: dev folders, .env, logs, caches. Storage images are included."
