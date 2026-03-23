# 🐳 Docker LEMP Stack

Stack Docker đầy đủ với **Nginx**, **PHP 8.3**, và **MySQL 8.0** để phát triển web.

## 📦 Stack bao gồm:

- **Nginx** (Alpine) - Web server
- **PHP 8.3-FPM** - PHP processor với extensions phổ biến
- **MySQL 8.0** - Database server
- **Composer** - PHP dependency manager

## 🚀 Cài đặt & Chạy

### 1. Clone hoặc tạo project structure
Đảm bảo bạn có cấu trúc thư mục như trong project này.

### 2. Cấu hình môi trường
Chỉnh sửa file `.env` nếu cần thay đổi:
- Port mặc định
- MySQL credentials
- Database name

### 3. Build và khởi động containers
```bash
docker-compose up -d --build
```

### 4. Kiểm tra status
```bash
docker-compose ps
```

### 5. Truy cập ứng dụng
Mở trình duyệt: `http://localhost`

## 📝 Các lệnh thường dùng

### Container Management
```bash
# Khởi động containers
docker-compose up -d

# Dừng containers
docker-compose down

# Dừng và xóa volumes
docker-compose down -v

# Xem logs
docker-compose logs -f

# Xem logs của service cụ thể
docker-compose logs -f php
docker-compose logs -f nginx
docker-compose logs -f mysql

# Restart service
docker-compose restart php
```

### Rebuild Containers
```bash
# Rebuild tất cả
docker-compose up -d --build

# Rebuild PHP container
docker-compose up -d --build php

# Rebuild không cache
docker-compose build --no-cache
```

### Truy cập Containers
```bash
# PHP container
docker exec -it php bash

# MySQL CLI
docker exec -it mysql mysql -u root -p

# Nginx container
docker exec -it nginx sh
```

### Database Operations
```bash
# Import SQL file
docker exec -i mysql mysql -u root -p[password] [database] < dump.sql

# Export database
docker exec mysql mysqldump -u root -p[password] [database] > dump.sql

# Create new database
docker exec -it mysql mysql -u root -p -e "CREATE DATABASE new_db;"
```

## 📂 Cấu trúc thư mục

```
docker/
├── docker-compose.yml       # Định nghĩa services
├── .env                     # Biến môi trường
├── nginx/                   # Nginx configs
│   ├── nginx.conf
│   └── conf.d/default.conf
├── php/                     # PHP configs & Dockerfile
│   ├── Dockerfile
│   ├── php.ini
│   └── www.conf
├── mysql/                   # MySQL configs & init scripts
│   ├── my.cnf
│   └── init/01-init.sql
└── www/                     # Your application code
    └── public/
        └── index.php
```

## 🔧 Tùy chỉnh

### Thay đổi PHP version
Sửa file `php/Dockerfile`:
```dockerfile
FROM php:8.2-fpm  # hoặc 8.1, 7.4
```

### Thêm PHP extensions
Sửa file `php/Dockerfile`:
```dockerfile
RUN docker-php-ext-install redis intl soap
```

### Thay đổi MySQL version
Sửa file `.env`:
```env
MYSQL_VERSION=8.0  # hoặc 5.7
```

### Thay đổi ports
Sửa file `.env`:
```env
NGINX_PORT=8080
MYSQL_PORT=3307
```

## 🐛 Troubleshooting

### Container không start
```bash
# Xem logs chi tiết
docker-compose logs

# Kiểm tra port conflicts
sudo lsof -i :80
sudo lsof -i :3306
```

### MySQL connection refused
- Đợi MySQL khởi động hoàn toàn (~30s lần đầu)
- Kiểm tra credentials trong `.env`
- Verify network: `docker network ls`

### Permission denied
```bash
# Fix quyền cho www folder
sudo chown -R $USER:$USER www/
chmod -R 755 www/
```

### PHP extensions không load
```bash
# Rebuild PHP container
docker-compose up -d --build php

# Kiểm tra extensions
docker exec -it php php -m
```

## 📊 Performance Tips

1. **Tăng memory cho MySQL**: Sửa `mysql/my.cnf`
   ```ini
   innodb_buffer_pool_size = 512M
   ```

2. **Optimize PHP-FPM**: Sửa `php/www.conf`
   ```ini
   pm.max_children = 100
   ```

3. **Enable OPcache**: Đã bật sẵn trong `php/php.ini`

## 🔒 Security Notes

- **Đổi MySQL passwords** trong `.env` trước khi deploy production
- **Không commit** file `.env` vào Git (đã có trong .gitignore)
- **Giới hạn MySQL connections** từ bên ngoài trong production
- **Sử dụng HTTPS** với Let's Encrypt cho production

## 📚 Resources

- [Docker Documentation](https://docs.docker.com/)
- [Docker Compose](https://docs.docker.com/compose/)
- [PHP Docker Hub](https://hub.docker.com/_/php)
- [MySQL Docker Hub](https://hub.docker.com/_/mysql)
- [Nginx Documentation](https://nginx.org/en/docs/)

## 💡 Tips

- Source code trong `www/` được mount vào container, thay đổi tự động có hiệu lực
- MySQL data được persist trong Docker volume `mysql-data`
- Xem phpinfo(): Tạo file `www/public/phpinfo.php` với nội dung `<?php phpinfo();`

---

**Happy coding! 🚀**
