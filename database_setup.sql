-- ============================================================
-- SHAJARDOST.COM - Tree Plantation Tracking System
-- Database Setup SQL
-- Run this in phpMyAdmin on your shared hosting
-- ============================================================

-- Gaon (Village) Data Table
CREATE TABLE IF NOT EXISTS gaon_data (
  id INT AUTO_INCREMENT PRIMARY KEY,
  gaon_naam VARCHAR(100) NOT NULL,
  district VARCHAR(100),
  province VARCHAR(50) DEFAULT 'Punjab',
  area_acres DECIMAL(10,2) NOT NULL,
  tree_species VARCHAR(50) DEFAULT 'Eucalyptus',
  trees_per_acre INT DEFAULT 150,
  trees_required INT GENERATED ALWAYS AS (CEIL(area_acres * trees_per_acre)) STORED,
  trees_planted INT DEFAULT 0,
  trees_survived INT DEFAULT 0,
  ndvi_score DECIMAL(4,3) DEFAULT NULL,
  lat DECIMAL(10,6),
  lng DECIMAL(10,6),
  plantation_date DATE,
  last_updated TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  last_satellite_check TIMESTAMP NULL,
  notes TEXT,
  added_by VARCHAR(50)
);

-- Admin Users Table
CREATE TABLE IF NOT EXISTS users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) UNIQUE NOT NULL,
  password_hash VARCHAR(255) NOT NULL,
  role ENUM('admin', 'field_worker') DEFAULT 'field_worker',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ============================================================
-- DEFAULT ADMIN USER
-- Username: admin
-- Password: shajardost2024
-- (Change this immediately after first login!)
-- ============================================================
INSERT IGNORE INTO users (username, password_hash, role)
VALUES ('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin');
-- Note: Above hash = 'password' - run setup_admin.php to set your real password

-- ============================================================
-- SAMPLE DATA (Remove after real data entry)
-- ============================================================
INSERT INTO gaon_data
(gaon_naam, district, area_acres, tree_species, trees_per_acre,
 trees_planted, trees_survived, lat, lng, plantation_date, notes)
VALUES
('Chak 47', 'Faisalabad', 5.0, 'Eucalyptus', 150, 680, 560, 31.4504, 73.1350, '2024-03-01', 'Plantation drive March 2024'),
('Garhi Shahu', 'Lahore', 3.0, 'Shisham', 100, 280, 250, 31.5497, 74.3436, '2024-02-15', 'Urban park area'),
('Kot Addu', 'Muzaffargarh', 8.0, 'Poplar', 200, 900, 820, 30.4700, 70.9640, '2024-01-20', 'River bank plantation'),
('Dinga', 'Gujrat', 4.5, 'Mango', 120, 200, 185, 32.6438, 73.7182, '2024-04-01', 'Fruit tree project'),
('Phalia', 'Mandi Bahauddin', 6.0, 'Eucalyptus', 150, 870, 700, 32.4268, 73.5800, '2024-03-15', 'Highway belt'),
('Okara City', 'Okara', 7.5, 'Shisham', 100, 300, 270, 30.8107, 73.4459, '2024-05-01', 'City beautification'),
('Sahiwal Central', 'Sahiwal', 4.0, 'Peepal', 80, 310, 295, 30.6682, 73.1068, '2024-04-15', 'School plantation');
