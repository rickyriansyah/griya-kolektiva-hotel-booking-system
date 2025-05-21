-- Database schema for Griya Kolektiva

CREATE DATABASE IF NOT EXISTS griya_kolektiva;
USE griya_kolektiva;

-- Rooms table
CREATE TABLE IF NOT EXISTS rooms (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    description TEXT,
    capacity INT,
    status ENUM('available', 'occupied', 'maintenance') DEFAULT 'available'
);

-- Insert default rooms
INSERT INTO rooms (name, price, description, capacity, status) VALUES
('Kamar Ilusi', 110000, 'Kamar dengan tema seni yang unik, memberikan pengalaman menginap yang berbeda.', 2, 'available'),
('Ruang Kendali', 190000, 'Kamar dengan fasilitas lengkap dan nyaman, cocok untuk keluarga atau grup.', 4, 'available');

-- Bookings table
CREATE TABLE IF NOT EXISTS bookings (
    id INT PRIMARY KEY AUTO_INCREMENT,
    booking_ref VARCHAR(20) UNIQUE NOT NULL,
    guest_name VARCHAR(100) NOT NULL,
    guest_email VARCHAR(100) NOT NULL,
    guest_phone VARCHAR(20) NOT NULL,
    room_id INT NOT NULL,
    checkin_date DATE NOT NULL,
    checkout_date DATE NOT NULL,
    total_price DECIMAL(10,2) NOT NULL,
    status ENUM('pending', 'confirmed', 'cancelled') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (room_id) REFERENCES rooms(id)
);

-- Payments table
CREATE TABLE IF NOT EXISTS payments (
    id INT PRIMARY KEY AUTO_INCREMENT,
    booking_id INT NOT NULL,
    amount DECIMAL(10,2) NOT NULL,
    payment_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    payment_method VARCHAR(50),
    status ENUM('pending', 'confirmed', 'failed') DEFAULT 'pending',
    FOREIGN KEY (booking_id) REFERENCES bookings(id)
);

-- Admin users table
CREATE TABLE IF NOT EXISTS admin_users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    name VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert default admin user (password: admin123)
INSERT INTO admin_users (username, password, name) VALUES
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Administrator');
