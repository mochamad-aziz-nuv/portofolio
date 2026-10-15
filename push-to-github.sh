#!/bin/bash
# push-to-github.sh
# Script bantuan untuk mengunggah portofolio ke GitHub

echo "=========================================================="
echo "  Deploy Portofolio Mochamad Aziz Noviyanto ke GitHub"
echo "=========================================================="
echo ""

# Pastikan git diinisialisasi
if [ ! -d ".git" ]; then
    echo "📦 Menginisialisasi repositori Git lokal..."
    git init
    git branch -M main
fi

git add .
git commit -m "feat: inisialisasi portofolio freelance Mochamad Aziz Noviyanto" || true

# Cek apakah remote origin sudah diset
REMOTE_URL=$(git remote get-url origin 2>/dev/null)

if [ -z "$REMOTE_URL" ]; then
    echo ""
    echo "⚠️ Remote GitHub belum diset."
    echo "Silakan masukkan URL repository GitHub Anda:"
    echo "Contoh: https://github.com/aziznoviyanto/portfolio.git"
    echo ""
    read -p "URL Repo: " USER_REPO_URL

    if [ -n "$USER_REPO_URL" ]; then
        git remote add origin "$USER_REPO_URL"
        echo "✅ Remote origin berhasil ditambahkan!"
    else
        echo "❌ Dibatalkan: URL repo tidak boleh kosong."
        exit 1
    fi
fi

echo ""
echo "🚀 Melakukan push ke GitHub..."
git push -u origin main

echo ""
echo "🎉 Berhasil di-push ke GitHub!"
echo "Sekarang buka repo GitHub Anda -> Settings -> Pages -> Pilih branch 'main' -> Save."
echo "Link portofolio Anda akan langsung aktif!"
