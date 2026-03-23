-- 1. Đảm bảo user admin có thể kết nối từ bất cứ đâu (%) và dùng mật khẩu dạng cũ cho SQLyog
ALTER USER 'admin'@'%' IDENTIFIED WITH mysql_native_password BY 'admin';

-- 2. Cấp toàn bộ quyền trên tất cả các database và table cho admin
GRANT ALL PRIVILEGES ON *.* TO 'admin'@'%' WITH GRANT OPTION;

-- 3. Làm mới hệ thống quyền để áp dụng ngay lập tức
FLUSH PRIVILEGES;