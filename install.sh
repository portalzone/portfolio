#!/bin/bash

# Portfolio CMS Installation Script
# Run this after uploading Laravel files to your server

echo "========================================="
echo "Portfolio CMS - Installation Script"
echo "========================================="
echo ""

# Check if we're in the right directory
if [ ! -f "artisan" ]; then
    echo "❌ Error: artisan file not found. Please run this script from your Laravel root directory."
    exit 1
fi

echo "📍 Current directory: $(pwd)"
echo ""

# Check if .env exists
if [ ! -f ".env" ]; then
    echo "⚠️  .env file not found. Creating from .env.example..."
    cp .env.example .env
    echo "✅ .env file created"
fi

# Generate APP_KEY
echo ""
echo "🔑 Generating APP_KEY..."
php artisan key:generate
echo "✅ APP_KEY generated"

# Check database connection
echo ""
echo "🗄️  Checking database connection..."
php artisan db:monitor 2>/dev/null
if [ $? -eq 0 ]; then
    echo "✅ Database connection successful"
else
    echo "⚠️  Database connection failed. Please check your .env database credentials"
    echo "   DB_DATABASE, DB_USERNAME, DB_PASSWORD"
    read -p "Press Enter to continue anyway..."
fi

# Run migrations
echo ""
echo "📊 Running database migrations..."
php artisan migrate --force
echo "✅ Migrations completed"

# Run seeder
echo ""
echo "🌱 Seeding database with initial data..."
php artisan db:seed --class=PortfolioSeeder --force
echo "✅ Database seeded"

# Create storage link
echo ""
echo "🔗 Creating storage symlink..."
php artisan storage:link
echo "✅ Storage link created"

# Set permissions
echo ""
echo "🔒 Setting file permissions..."
chmod -R 755 storage bootstrap/cache
chmod 644 .env
echo "✅ Permissions set"

# Clear and cache config
echo ""
echo "🧹 Clearing and caching configuration..."
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
echo "✅ Cache optimized"

# Installation complete
echo ""
echo "========================================="
echo "✅ Installation Complete!"
echo "========================================="
echo ""
echo "📋 Next Steps:"
echo ""
echo "1. Update your .env file with correct database credentials"
echo "2. Visit https://basepan.com to see your portfolio"
echo "3. Login to admin panel: https://basepan.com/admin"
echo "   Email: admin@basepan.com"
echo "   Password: admin123"
echo "   ⚠️  CHANGE THIS PASSWORD IMMEDIATELY!"
echo ""
echo "4. Upload frontend files to public/ directory"
echo "5. Customize your profile in the admin panel"
echo ""
echo "========================================="
echo "📚 Documentation:"
echo "   - Deployment Guide: PORTFOLIO_COMPLETE_DEPLOYMENT.md"
echo "   - API Routes: routes/api.php"
echo "   - Admin Panel: /admin"
echo "========================================="
echo ""
echo "🎉 Happy coding!"
echo ""