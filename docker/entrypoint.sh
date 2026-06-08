#!/bin/bash
set -e

echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo " 🌱 PLOOM - Personal Finance Tracker"
echo " CodeIgniter 4 Application Startup"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"

# ─── Ensure writable directories exist with correct permissions ───
echo "→ Setting up writable directories..."
mkdir -p writable/cache writable/logs writable/session writable/uploads writable/debugbar
chown -R www-data:www-data writable
chmod -R 775 writable

# ─── Ensure public/uploads exists ───
if [ -d "public/uploads" ]; then
    chown -R www-data:www-data public/uploads
    chmod -R 775 public/uploads
fi

# ─── Wait for MySQL to be ready ───
echo "→ Waiting for MySQL..."
until php -r "
new mysqli(
    getenv('database.default.hostname') ?: 'db',
    getenv('database.default.username') ?: 'ploom',
    getenv('database.default.password') ?: 'ploom_secret',
    getenv('database.default.database') ?: 'fintrack_db'
);
echo 'connected';
" 2>/dev/null | grep -q "connected"; do
    echo "  MySQL not ready yet, retrying in 3s..."
    sleep 3
done
echo "  ✓ MySQL is ready!"

# ─── Run CodeIgniter database migrations ───
echo "→ Running database migrations..."
php spark migrate --all -n 2>&1 || echo "  ⚠ Migrations skipped or already up-to-date."

echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo " ✓ PLOOM is ready! Starting PHP-FPM..."
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"

exec "$@"
