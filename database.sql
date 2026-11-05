-- Personal Portfolio Website Database
-- InfinityFree MySQL compatible (MySQL 5.7+ / MariaDB)
-- Import this file via phpMyAdmin or mysql command line

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";

-- --------------------------------------------------------

-- Table: admins
CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `full_name` varchar(100) NOT NULL DEFAULT 'Administrator',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

-- Table: profile
CREATE TABLE `profile` (
  `id` int(11) NOT NULL,
  `full_name` varchar(100) NOT NULL DEFAULT 'Laurence Andrew B. Perlas',
  `position` varchar(100) NOT NULL DEFAULT 'Web Developer & IT Student',
  `email` varchar(100) NOT NULL DEFAULT 'xlaurence02@example.com',
  `phone` varchar(30) NOT NULL DEFAULT '+63 962 251 8929',
  `github_url` varchar(255) NOT NULL DEFAULT 'https://github.com/xlaurence02',
  `profile_image` varchar(255) NOT NULL DEFAULT 'assets/images/profile-placeholder.svg',
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

-- Table: education
CREATE TABLE `education` (
  `id` int(11) NOT NULL,
  `category` varchar(50) NOT NULL,
  `school` varchar(150) NOT NULL,
  `course` varchar(150) NOT NULL,
  `status` varchar(100) NOT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

-- Table: skills
CREATE TABLE `skills` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

-- Table: certifications
CREATE TABLE `certifications` (
  `id` int(11) NOT NULL,
  `title` varchar(150) NOT NULL,
  `description` varchar(255) NOT NULL DEFAULT 'Verified Completion Certificate',
  `date` date NOT NULL,
  `image_path` varchar(255) NOT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

-- Table: website_settings
CREATE TABLE `website_settings` (
  `id` int(11) NOT NULL,
  `logo_text` varchar(50) NOT NULL DEFAULT 'LP.dev',
  `footer_text` varchar(255) NOT NULL DEFAULT '© 2026 Laurence Andrew B. Perlas. All Rights Reserved.',
  `footer_subtext` varchar(255) NOT NULL DEFAULT 'Built with PHP, MySQL, HTML, CSS & JavaScript.',
  `site_name` varchar(100) NOT NULL DEFAULT 'Laurence Portfolio',
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

-- Table: activity_logs
CREATE TABLE `activity_logs` (
  `id` int(11) NOT NULL,
  `admin_id` int(11) DEFAULT NULL,
  `action` varchar(50) NOT NULL,
  `table_name` varchar(50) NOT NULL,
  `record_id` int(11) DEFAULT NULL,
  `description` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

-- Indexes

ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

ALTER TABLE `profile`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `education`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `skills`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `certifications`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `website_settings`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `activity_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `admin_id` (`admin_id`);

-- --------------------------------------------------------

-- AUTO_INCREMENT

ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `profile`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `education`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `skills`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `certifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `website_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `activity_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

-- --------------------------------------------------------

-- Initial data

INSERT INTO `admins` (`id`, `username`, `password_hash`, `full_name`) VALUES
(1, 'admin', '$2y$10$utLDHZK9NPvrRsHNEK/LCOkmiWEpB5Vacm.51Dise0RfoKOz4V/bG', 'Administrator');

INSERT INTO `profile` (`id`, `full_name`, `position`, `email`, `phone`, `github_url`, `profile_image`) VALUES
(1, 'Laurence Andrew B. Perlas', 'Web Developer & IT Student', 'xlaurence02@example.com', '+63 962 251 8929', 'https://github.com/xlaurence02', 'assets/images/profile.jpg');

INSERT INTO `education` (`id`, `category`, `school`, `course`, `status`, `sort_order`) VALUES
(1, 'COLLEGE', 'Data Center College of the Philippines - Bangued', 'Bachelor of Science in Information Technology', 'Expected Graduation - 2027', 1),
(2, 'SENIOR HIGH SCHOOL', 'University of Abra - Bangued', 'TVL - Technical-Vocational-Livelihood', 'Graduated 2023', 2),
(3, 'ELEMENTARY', 'Tayum Central School', 'Elementary Education', 'Graduated 2017', 3);

INSERT INTO `skills` (`id`, `name`, `sort_order`) VALUES
(1, 'PHP', 1),
(2, 'Laravel', 2),
(3, 'MySQL', 3),
(4, 'VB.NET', 4),
(5, 'Tailwind CSS', 5),
(6, 'Git & GitHub', 6);

INSERT INTO `certifications` (`id`, `title`, `description`, `date`, `image_path`, `sort_order`) VALUES
(1, 'Online Safety Through Netiquette - DICT Region II Webinar', 'Verified Completion Certificate', '2026-07-23', 'assets/images/certificate-placeholder.svg', 1);

INSERT INTO `website_settings` (`id`, `logo_text`, `footer_text`, `footer_subtext`, `site_name`) VALUES
(1, 'LP.dev', '© 2026 Laurence Andrew B. Perlas. All Rights Reserved.', 'Built with PHP, MySQL, HTML, CSS & JavaScript.', 'Laurence Portfolio');

-- --------------------------------------------------------

-- Foreign keys

ALTER TABLE `activity_logs`
  ADD CONSTRAINT `activity_logs_ibfk_1` FOREIGN KEY (`admin_id`) REFERENCES `admins` (`id`) ON DELETE SET NULL;

COMMIT;

-- DEFAULT ADMIN CREDENTIALS (change immediately after first login):
-- Username: admin
-- Password: admin123

-- Default admin credentials:
-- Username: admin
-- Password: admin123
-- IMPORTANT: Change the password immediately after first login via the admin panel or by re-running the password hash.
