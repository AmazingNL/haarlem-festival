$ErrorActionPreference = 'Stop'
Set-Location $PSScriptRoot

git checkout main
git merge dev -m 'Merge branch dev into main'

git add -A
$staged = git diff --cached --name-only
if ($staged) {
    git commit -m 'Update checkout flow, email, and auth views'
}

git push origin main
git status
git log --oneline -3
