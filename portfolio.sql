-- ============================================================
--  Aryan Uraw Portfolio Database
--  Run this in phpMyAdmin or MySQL CLI
-- ============================================================

CREATE DATABASE IF NOT EXISTS `aryan_portfolio`
  CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE `aryan_portfolio`;

-- Projects table
CREATE TABLE IF NOT EXISTS `projects` (
  `id`          INT AUTO_INCREMENT PRIMARY KEY,
  `title`       VARCHAR(255)  NOT NULL,
  `description` TEXT          NOT NULL,
  `image`       VARCHAR(500)  DEFAULT 'assets/images/project-default.jpg',
  `link`        VARCHAR(500)  DEFAULT '#',
  `category`    ENUM('Web','Design','Other') DEFAULT 'Web',
  `featured`    TINYINT(1)    DEFAULT 0,
  `sort_order`  INT           DEFAULT 0,
  `created_at`  TIMESTAMP     DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Contact messages table
CREATE TABLE IF NOT EXISTS `contacts` (
  `id`         INT AUTO_INCREMENT PRIMARY KEY,
  `name`       VARCHAR(255) NOT NULL,
  `email`      VARCHAR(255) NOT NULL,
  `message`    TEXT         NOT NULL,
  `ip_address` VARCHAR(45),
  `read_at`    TIMESTAMP    NULL,
  `created_at` TIMESTAMP    DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Admin users table
CREATE TABLE IF NOT EXISTS `admin_users` (
  `id`         INT AUTO_INCREMENT PRIMARY KEY,
  `username`   VARCHAR(100) NOT NULL UNIQUE,
  `password`   VARCHAR(255) NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ============================================================
--  Sample Projects
-- ============================================================
INSERT INTO `projects` (`title`, `description`, `image`, `link`, `category`, `featured`, `sort_order`) VALUES
('Sovryx Tech Platform', 'Enterprise-grade SaaS platform built for Sovryx Tech — featuring real-time dashboards, role-based access control, and scalable microservice architecture.', 'assets/images/proj1.jpg', 'https://sovryx.com', 'Web', 1, 1),
('Executive Brand Identity', 'Complete visual identity system for Sovryx Tech — logo, typography, color system, and brand guidelines crafted to reflect innovation and trust.', 'assets/images/proj2.jpg', '#', 'Design', 1, 2),
('Analytics Intelligence Suite', 'AI-powered analytics dashboard providing predictive insights, automated reporting, and intelligent data visualization for enterprise clients.', 'assets/images/proj3.jpg', '#', 'Web', 0, 3),
('Sovryx Mobile App', 'Cross-platform mobile application extending Sovryx Tech services to iOS and Android with offline-first architecture and seamless sync.', 'assets/images/proj4.jpg', '#', 'Web', 0, 4),
('Corporate UX Research', 'In-depth UX research and redesign project spanning user interviews, journey mapping, and high-fidelity prototyping for B2B products.', 'assets/images/proj5.jpg', '#', 'Design', 0, 5),
('Venture Pitch Deck System', 'Modular investor presentation framework and document design system used for fundraising and strategic partnership communications.', 'assets/images/proj6.jpg', '#', 'Other', 0, 6);

-- ============================================================
--  Default Admin (password: Admin@123 — CHANGE IMMEDIATELY)
-- ============================================================
INSERT INTO `admin_users` (`username`, `password`) VALUES
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');
