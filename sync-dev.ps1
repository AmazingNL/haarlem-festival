$ErrorActionPreference = 'Stop'
Set-Location $PSScriptRoot

Write-Host '=== Fetch ===' -ForegroundColor Cyan
git fetch origin

Write-Host '=== Sync dev with main (local) ===' -ForegroundColor Cyan
git checkout dev
git merge main -m 'Merge main into dev'

Write-Host '=== Merge remote dev (teammate changes) ===' -ForegroundColor Cyan
git merge origin/dev -m 'Merge origin/dev into dev'

Write-Host '=== Push dev ===' -ForegroundColor Cyan
git push origin dev

Write-Host '=== Back to main ===' -ForegroundColor Cyan
git checkout main

Write-Host ''
Write-Host 'main:' (git log --oneline -1 main)
Write-Host 'dev: ' (git log --oneline -1 dev)
Write-Host 'origin/dev:' (git log --oneline -1 origin/dev)
git status
