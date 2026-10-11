-- ============================================================================
-- Student Hub Portal - Relational Database Schema & Seed Data (Practical 8)
-- Subject: Web Development Frameworks (ITUE203) - Semester 3
-- Charotar University of Science and Technology (CHARUSAT)
-- ============================================================================

-- Create Database if not exists
CREATE DATABASE IF NOT EXISTS `studenthub_db`
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

USE `studenthub_db`;

-- Drop existing tables in reverse dependency order
DROP TABLE IF EXISTS `registrations`;
DROP TABLE IF EXISTS `feedback`;
DROP TABLE IF EXISTS `events`;
DROP TABLE IF EXISTS `students`;
DROP TABLE IF EXISTS `users`;

-- ----------------------------------------------------------------------------
-- 1. STUDENTS TABLE (Normalized Entity)
-- ----------------------------------------------------------------------------
CREATE TABLE `students` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `student_id` VARCHAR(20) NOT NULL UNIQUE,
  `name` VARCHAR(100) NOT NULL,
  `email` VARCHAR(120) NOT NULL UNIQUE,
  `mobile` VARCHAR(15) NOT NULL,
  `course` VARCHAR(80) NOT NULL,
  `year` TINYINT NOT NULL CHECK (`year` BETWEEN 1 AND 4),
  `gender` ENUM('Male', 'Female', 'Other') NOT NULL,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------------------------------------------------------
-- 2. EVENTS TABLE (Normalized Entity)
-- ----------------------------------------------------------------------------
CREATE TABLE `events` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `title` VARCHAR(150) NOT NULL,
  `category` ENUM('SPORTS', 'TECH', 'WORKSHOP', 'CULTURAL', 'ACADEMIC') NOT NULL,
  `event_date` DATE NOT NULL,
  `venue` VARCHAR(120) NOT NULL,
  `description` TEXT NOT NULL,
  `max_capacity` INT DEFAULT 100,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------------------------------------------------------
-- 3. REGISTRATIONS TABLE (Associative Junction Table - M:N Relationship)
-- Foreign Keys with Referential Integrity (ON DELETE CASCADE)
-- ----------------------------------------------------------------------------
CREATE TABLE `registrations` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `student_id` INT NOT NULL,
  `event_id` INT NOT NULL,
  `status` ENUM('Registered', 'Waitlisted', 'Attended', 'Cancelled') DEFAULT 'Registered',
  `registered_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT `fk_reg_student` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_reg_event` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `unique_student_event` UNIQUE (`student_id`, `event_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------------------------------------------------------
-- 4. FEEDBACK TABLE (Contact & Feedback entity)
-- ----------------------------------------------------------------------------
CREATE TABLE `feedback` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `ticket_id` VARCHAR(30) NOT NULL UNIQUE,
  `name` VARCHAR(100) NOT NULL,
  `email` VARCHAR(120) NOT NULL,
  `category` VARCHAR(50) NOT NULL,
  `rating` TINYINT NOT NULL DEFAULT 5 CHECK (`rating` BETWEEN 1 AND 5),
  `message` TEXT NOT NULL,
  `submitted_at` DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------------------------------------------------------
-- 5. USERS TABLE (Authentication & Role-Based Access)
-- ----------------------------------------------------------------------------
CREATE TABLE `users` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `username` VARCHAR(50) NOT NULL UNIQUE,
  `email` VARCHAR(120) NOT NULL UNIQUE,
  `password_hash` VARCHAR(255) NOT NULL,
  `role` ENUM('student', 'admin') DEFAULT 'student',
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- ============================================================================
-- SEED DATA (Practical 8 Dataset Requirement: Min 15-20 rows per table)
-- ============================================================================

-- A. SEED STUDENTS (16 Records)
INSERT INTO `students` (`student_id`, `name`, `email`, `mobile`, `course`, `year`, `gender`) VALUES
('25DCS001', 'Aarav Sharma', 'aarav.sharma@studenthub.edu', '9876543210', 'B.Tech Computer Science', 3, 'Male'),
('25DCS002', 'Diya Patel', 'diya.patel@studenthub.edu', '9823456781', 'B.Tech Computer Science', 3, 'Female'),
('25DIT003', 'Rohan Mehta', 'rohan.mehta@studenthub.edu', '9712345678', 'B.Tech Information Technology', 3, 'Male'),
('25DCS004', 'Ananya Joshi', 'ananya.joshi@studenthub.edu', '9612345678', 'B.Tech Computer Science', 2, 'Female'),
('25BCA005', 'Kabir Verma', 'kabir.verma@studenthub.edu', '9512345678', 'BCA', 2, 'Male'),
('25DCS006', 'Isha Shah', 'isha.shah@studenthub.edu', '9412345678', 'B.Tech Computer Science', 3, 'Female'),
('25DIT007', 'Aditya Singh', 'aditya.singh@studenthub.edu', '9312345678', 'B.Tech Information Technology', 3, 'Male'),
('25MCA008', 'Pooja Trivedi', 'pooja.trivedi@studenthub.edu', '9212345678', 'MCA', 1, 'Female'),
('25DCS009', 'Harsh Desai', 'harsh.desai@studenthub.edu', '9112345678', 'B.Tech Computer Science', 3, 'Male'),
('25DCS010', 'Sneha Nair', 'sneha.nair@studenthub.edu', '9822334455', 'B.Tech Computer Science', 3, 'Female'),
('25BSC011', 'Varun Gupta', 'varun.gupta@studenthub.edu', '9733445566', 'B.Sc IT', 2, 'Male'),
('25DIT012', 'Tanvi Rao', 'tanvi.rao@studenthub.edu', '9644556677', 'B.Tech Information Technology', 3, 'Female'),
('25BCA013', 'Karan Malhotra', 'karan.malhotra@studenthub.edu', '9555667788', 'BCA', 3, 'Male'),
('25DCS014', 'Riya Choudhary', 'riya.choudhary@studenthub.edu', '9466778899', 'B.Tech Computer Science', 1, 'Female'),
('25DCS015', 'Devansh Parekh', 'devansh.parekh@studenthub.edu', '9377889900', 'B.Tech Computer Science', 3, 'Male'),
('25DCS200', 'John Doe', 'john.doe@studenthub.edu', '9876543200', 'B.Tech Computer Science', 3, 'Male');

-- B. SEED EVENTS (16 Records)
INSERT INTO `events` (`title`, `category`, `event_date`, `venue`, `description`, `max_capacity`) VALUES
('SPOURAL – 2026', 'SPORTS', '2026-10-25', 'College Main Ground', 'Annual Inter-College Sports & Athletics Championship', 300),
('Cyber Security Workshop', 'TECH', '2026-11-10', 'Auditorium B', 'Hands-on Ethical Hacking & Network Defense Bootcamp', 80),
('AI & Robotics Workshop', 'WORKSHOP', '2026-11-22', 'Computer Lab 3', 'Practical Machine Learning, Arduino & Autonomous Bots Session', 60),
('Tarang Cultural Fest', 'CULTURAL', '2026-12-05', 'Open Air Amphitheater', 'Annual Youth Festival featuring Music, Drama, Dance & Art', 500),
('WDF Web Development Hackathon', 'TECH', '2026-12-15', 'IT Lab 2', '24-hour Full-Stack Web App Development Challenge', 100),
('DSA CodeSprint Contest', 'ACADEMIC', '2026-12-20', 'Online Portal & Lab 1', 'Algorithmic Problem Solving & Competitive Coding Battle', 120),
('Java OOP Project Exhibition', 'ACADEMIC', '2026-12-28', 'Seminar Hall A', 'Semester 3 Java Object-Oriented Software Projects Showcase', 75),
('Networking & FCN Hands-on Lab', 'WORKSHOP', '2027-01-08', 'Cisco Networking Lab', 'Practical Packet Tracer, Subnetting & Network Simulation', 50),
('Cloud Computing & DevOps Summit', 'TECH', '2027-01-18', 'Conference Hall C', 'Docker, Kubernetes, AWS infrastructure deployment', 90),
('Inter-Department Badminton League', 'SPORTS', '2027-01-25', 'Indoor Sports Complex', 'Men and Women singles and doubles tournament', 64),
('National Technical Paper Presentation', 'ACADEMIC', '2027-02-05', 'Auditorium A', 'Original engineering research papers across AI and IoT', 80),
('UI/UX Design Sprint Bootcamp', 'WORKSHOP', '2027-02-14', 'Design Studio Lab', 'Master Figma design systems and interactive prototyping', 45),
('Battle of the Bands & Music Night', 'CULTURAL', '2027-02-22', 'Open Air Amphitheater', 'Live rock, acoustic, and fusion performances', 400),
('Annual Chess Championship', 'SPORTS', '2027-03-02', 'Activity Hall 2', 'FIDE rapid rules Swiss-system chess tournament', 60),
('Data Science & Python Masterclass', 'WORKSHOP', '2027-03-12', 'Computer Center 4', 'Pandas, NumPy, Scikit-learn, and predictive analytics', 70),
('Alumni Career Mentorship Conclave', 'ACADEMIC', '2027-03-20', 'Main Auditorium', 'Industry leader networking and roadmap guidance', 250);

-- C. SEED REGISTRATIONS (18 Records linking Students and Events)
INSERT INTO `registrations` (`student_id`, `event_id`, `status`) VALUES
(16, 1, 'Registered'),
(16, 2, 'Registered'),
(16, 5, 'Registered'),
(16, 6, 'Registered'),
(16, 7, 'Registered'),
(1, 1, 'Registered'),
(1, 5, 'Registered'),
(2, 2, 'Registered'),
(2, 6, 'Registered'),
(3, 3, 'Registered'),
(4, 4, 'Registered'),
(5, 5, 'Registered'),
(6, 6, 'Registered'),
(7, 8, 'Registered'),
(8, 2, 'Registered'),
(9, 1, 'Registered'),
(10, 4, 'Registered'),
(11, 7, 'Registered');

-- D. SEED USERS (Student & Admin credentials)
INSERT INTO `users` (`username`, `email`, `password_hash`, `role`) VALUES
('25DCS200', 'student@studenthub.com', '$2y$10$w8TKnN9kO0W98CfxFf8C3O1Qh.wP22c2a0vS61Z9mR9Y9uJ/KkOiC', 'student'),
('admin', 'admin@studenthub.com', '$2y$10$w8TKnN9kO0W98CfxFf8C3O1Qh.wP22c2a0vS61Z9mR9Y9uJ/KkOiC', 'admin');

-- ============================================================================
-- ADVANCED EXTENSION: STORED PROCEDURES (Practical 8 Advanced)
-- ============================================================================
DELIMITER //

-- 1. Stored Procedure to fetch all events registered by a specific student
DROP PROCEDURE IF EXISTS `sp_get_student_registrations` //
CREATE PROCEDURE `sp_get_student_registrations`(IN p_student_id INT)
BEGIN
    SELECT 
        s.student_id AS student_code,
        s.name AS student_name,
        e.id AS event_id,
        e.title AS event_title,
        e.category AS event_category,
        e.event_date,
        e.venue,
        r.status AS registration_status,
        r.registered_at
    FROM `registrations` r
    INNER JOIN `students` s ON r.student_id = s.id
    INNER JOIN `events` e ON r.event_id = e.id
    WHERE r.student_id = p_student_id
    ORDER BY e.event_date ASC;
END //

-- 2. Stored Procedure to safely register a student for an event
DROP PROCEDURE IF EXISTS `sp_register_student_event` //
CREATE PROCEDURE `sp_register_student_event`(
    IN p_student_id INT,
    IN p_event_id INT,
    OUT p_result VARCHAR(100)
)
BEGIN
    DECLARE v_count INT DEFAULT 0;
    
    SELECT COUNT(*) INTO v_count 
    FROM `registrations` 
    WHERE `student_id` = p_student_id AND `event_id` = p_event_id;
    
    IF v_count > 0 THEN
        SET p_result = 'ALREADY_REGISTERED';
    ELSE
        INSERT INTO `registrations` (`student_id`, `event_id`, `status`) 
        VALUES (p_student_id, p_event_id, 'Registered');
        SET p_result = 'SUCCESS';
    END IF;
END //

DELIMITER ;
