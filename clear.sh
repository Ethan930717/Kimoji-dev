#!/bin/bash

# Laravel 项目的根目录路径
LARAVEL_ROOT="/var/www/html"

# 进入 Laravel 项目目录
cd "$LARAVEL_ROOT"

# 检查是否处于 Laravel 项目目录
if [ ! -f artisan ]; then
    echo "Error: artisan not found. Are you in the Laravel project root directory?"
    exit 1
fi

# 清除缓存
echo "Clearing cache..."
php artisan cache:clear

# 清除路由缓存
echo "Clearing route cache..."
php artisan route:clear

# 清除配置缓存
echo "Clearing config cache..."
php artisan config:clear

# 清除编译的视图
echo "Clearing compiled views..."
php artisan view:clear

# 重启队列（如果您使用队列）
# echo "Restarting queue..."
# php artisan queue:restart

# 输出完成消息
echo "All caches cleared!"

# 退出脚本
exit 0
