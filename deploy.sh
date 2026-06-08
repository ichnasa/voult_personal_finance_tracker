#!/bin/bash
# ─────────────────────────────────────────────────────────────
# PLOOM Deploy Script - VPS Tencent Lighthouse
# SSL ditangani Cloudflare (tidak perlu Let's Encrypt)
# Jalankan di VPS: bash deploy.sh
# ─────────────────────────────────────────────────────────────

set -e

APP_DIR="/root/ploom"

echo "🚀 PLOOM Deployment Script"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━"

cd "$APP_DIR"

# ── 1. Install Docker jika belum ada ──────────────────────────
if ! command -v docker &> /dev/null; then
    echo "📦 Installing Docker..."
    curl -fsSL https://get.docker.com | sh
    systemctl enable docker
    systemctl start docker
    echo "✅ Docker installed"
else
    echo "✅ Docker: $(docker --version)"
fi

# ── 2. Install Composer dependencies ──────────────────────────
if [ ! -f "$APP_DIR/vendor/autoload.php" ]; then
    echo ""
    echo "📦 Installing Composer dependencies..."
    curl -sS https://getcomposer.org/installer | php
    php composer.phar install --no-dev --optimize-autoloader
    rm composer.phar
    echo "✅ Composer done"
else
    echo "✅ Vendor already exists"
fi

# ── 3. Set permissions ─────────────────────────────────────────
echo ""
echo "🔐 Setting permissions..."
chmod -R 775 writable/
echo "✅ Permissions set"

# ── 4. Jalankan Docker services ────────────────────────────────
echo ""
echo "🐳 Building and starting Docker containers..."
docker compose up -d --build

# ── 5. Tunggu DB siap ──────────────────────────────────────────
echo ""
echo "⏳ Waiting 30s for database to be ready..."
sleep 30

# ── 6. Jalankan Migration ──────────────────────────────────────
echo ""
echo "🗃️  Running database migrations..."
docker compose exec -T app php spark migrate --all

echo ""
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo "✅ Deployment SELESAI!"
echo ""
echo "🌍 App: https://awfa.dev"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━"
