CREATE DATABASE laptops;
CREATE USER 'laptops_user'@'localhost' IDENTIFIED BY '123456';
GRANT ALL PRIVILEGES ON laptops.* TO 'laptops_user'@'localhost';
FLUSH PRIVILEGES;