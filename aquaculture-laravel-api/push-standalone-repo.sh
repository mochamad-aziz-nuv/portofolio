#!/bin/bash
# Script untuk membuat dan push project ini ke repository GitHub terpisah (standalone)

set -e

REPO_NAME="aquaculture-laravel-api"
echo "=========================================================="
echo "  Deploy $REPO_NAME ke GitHub Standalone Repo"
echo "=========================================================="
echo ""

# Pastikan git diinisialisasi secara mandiri
if [ ! -d ".git" ]; then
    echo "📦 Menginisialisasi Git mandiri untuk proyek ini..."
    git init
    git branch -M main
fi

git add .
git commit -m "feat: initial release of NagroSmart Aquaculture Management Backend" || true

echo ""
echo "Masukkan URL Repository GitHub Anda (misal: https://github.com/mochamad-aziz-nuv/aquaculture-laravel-api.git):"
read -p "URL Repo: " USER_REPO_URL

if [ -n "$USER_REPO_URL" ]; then
    git remote remove origin 2>/dev/null || true
    git remote add origin "$USER_REPO_URL"
    echo "🚀 Mendorong ke $USER_REPO_URL..."
    git push -u origin main
    echo "🎉 Berhasil di-push ke repository standalone GitHub!"
else
    echo "Dibatalkan: URL tidak boleh kosong."
fi

