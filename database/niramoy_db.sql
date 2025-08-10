-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 30, 2025 at 08:35 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `niramoy_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `username`, `email`, `password`) VALUES
(1, 'admin', 'admin2211@niramoy.com', 'admin123');

-- --------------------------------------------------------

--
-- Table structure for table `appointment_patient`
--

CREATE TABLE `appointment_patient` (
  `appointment_id` int(11) NOT NULL,
  `doctor_id` int(11) NOT NULL,
  `hospital_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `appointment_date` date NOT NULL,
  `appointment_time` datetime NOT NULL DEFAULT current_timestamp(),
  `status` varchar(100) DEFAULT NULL,
  `serial_no` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `appointment_patient`
--

INSERT INTO `appointment_patient` (`appointment_id`, `doctor_id`, `hospital_id`, `patient_id`, `appointment_date`, `appointment_time`, `status`, `serial_no`) VALUES
(6, 1, 1, 1, '2025-06-25', '2025-06-29 13:24:26', 'prescribed', 1),
(9, 1, 1, 8, '2025-06-25', '2025-06-29 13:24:26', 'prescribed', 2),
(10, 1, 1, 8, '2025-06-25', '2025-06-29 13:24:26', NULL, 3),
(12, 112, 7, 1, '2025-06-26', '2025-06-29 13:24:26', NULL, 1),
(13, 106, 7, 1, '2025-06-25', '2025-06-29 13:24:26', NULL, 1),
(14, 2, 1, 1, '2025-06-25', '2025-06-29 13:24:26', 'prescribed', 1),
(15, 7, 1, 1, '2025-07-01', '2025-06-29 13:24:26', NULL, 1),
(16, 2, 1, 7, '2025-06-03', '2025-06-29 13:24:26', 'prescribed', 1),
(17, 14, 2, 1, '2025-06-26', '2025-06-29 13:24:26', NULL, 1),
(19, 7, 1, 5, '2025-07-01', '2025-06-29 13:24:26', NULL, 2),
(20, 7, 1, 5, '2025-07-01', '2025-06-29 13:27:51', NULL, 3),
(21, 172, 11, 1, '2025-07-02', '2025-06-29 13:33:42', NULL, 1),
(22, 172, 11, 5, '2025-07-02', '2025-06-29 13:34:13', NULL, 2),
(23, 172, 11, 7, '2025-07-02', '2025-06-29 13:37:52', NULL, 3);

-- --------------------------------------------------------

--
-- Table structure for table `blog_posts`
--

CREATE TABLE `blog_posts` (
  `id` int(11) NOT NULL,
  `hospital_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `author` varchar(100) DEFAULT NULL,
  `thumbnail_url` varchar(500) DEFAULT NULL,
  `content` longtext NOT NULL,
  `tags` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `views` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `blog_posts`
--

INSERT INTO `blog_posts` (`id`, `hospital_id`, `title`, `author`, `thumbnail_url`, `content`, `tags`, `created_at`, `updated_at`, `views`) VALUES
(1, 1, 'Managing Diabetes Naturally', 'Dr. Afsana Rahman', 'image/diabities.jpg', 'Living with diabetes doesn’t have to be hard. \r\n Learn how food choices, regular walking, and hydration \r\n can help control your sugar naturally. \r\n Avoid processed carbs and stay active every day.', 'diabetes, natural cure, lifestyle', '2025-06-26 05:58:48', '2025-06-29 17:05:16', 10),
(2, 2, 'The Power of Morning Sunlight', 'Dr. Kamal Hossain', 'image/morningSunlight.jpg', 'Morning sunlight is a natural energy booster. \r\n Just 10–15 minutes of early sun can improve sleep, \r\n mood, and vitamin D levels. \r\n Avoid sun after 10 AM to reduce UV risks.', 'sunlight, health, wellness', '2025-06-26 05:58:48', '2025-06-29 17:33:46', 20),
(3, 3, '5 Foods That Boost Immunity', 'Dr. Nusrat Jahan', 'image/immunity.jpg', 'Ginger, garlic, citrus fruits, turmeric, and yogurt — \r\n these are nature’s immunity heroes. \r\n Eat them regularly, especially in flu seasons. \r\n Pair with good sleep and water intake for best results.', 'immunity, diet, healthy eating', '2025-06-26 05:58:48', '2025-06-29 16:55:53', 1),
(4, 4, 'Heart Health for Every Age', 'Dr. Anisur Rahman', 'image/heart-care.jpg', 'Heart issues are not just for older people. \r\n Eat low-salt, avoid trans fats, and walk 30 mins daily. \r\n Regular checkups and blood pressure tracking \r\n can prevent silent heart damage.', 'heart, diet, lifestyle, awareness', '2025-06-26 05:58:48', '2025-06-29 17:22:05', 6),
(6, 1, 'The Importance of Mental Health Awareness', 'Dr. Ayesha Rahman', 'image/thumbnail.jpg', 'Mental health is an essential part of our overall well-being, yet it is often overlooked or stigmatized in many societies. Raising awareness about mental health conditions, their symptoms, and available treatments can empower individuals to seek help early and improve their quality of life.\r\n\r\nIn Bangladesh, increasing conversations around mental health can break down cultural barriers and misconceptions. Schools, workplaces, and communities should encourage open discussions, provide support systems, and promote healthy coping strategies.\r\n\r\nRemember, mental health is as important as physical health. Let\'s work together to create a society where everyone feels safe and supported to talk about their mental well-being.\r\n\r\n---\r\n\r\n**Key tips for maintaining good mental health:**  \r\n- Stay connected with friends and family  \r\n- Practice mindfulness and meditation  \r\n- Maintain a balanced diet and regular exercise  \r\n- Seek professional help when needed  \r\n- Avoid stigma; be supportive to those facing challenges  \r\n\r\nStay informed, stay healthy!..', NULL, '2025-06-26 15:47:02', '2025-06-29 16:56:47', 1);

-- --------------------------------------------------------

--
-- Table structure for table `blog_ratings`
--

CREATE TABLE `blog_ratings` (
  `id` int(11) NOT NULL,
  `blog_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `rating` tinyint(4) NOT NULL CHECK (`rating` between 1 and 5),
  `comment` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `blog_ratings`
--

INSERT INTO `blog_ratings` (`id`, `blog_id`, `patient_id`, `rating`, `comment`, `created_at`) VALUES
(1, 2, 1, 4, 'good', '2025-06-26 06:34:52'),
(2, 2, 1, 5, 'nice', '2025-06-26 06:35:52'),
(4, 1, 1, 5, 'good', '2025-06-26 06:55:03'),
(5, 4, 1, 4, 'good', '2025-06-29 17:14:50');

-- --------------------------------------------------------

--
-- Table structure for table `chat_messages`
--

CREATE TABLE `chat_messages` (
  `id` int(11) NOT NULL,
  `request_hospital_id` int(11) NOT NULL,
  `admin_id` int(11) DEFAULT NULL,
  `patient_id` int(11) DEFAULT NULL,
  `sender` enum('patient','hospital','admin') NOT NULL,
  `message` text NOT NULL,
  `sent_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `chat_messages`
--

INSERT INTO `chat_messages` (`id`, `request_hospital_id`, `admin_id`, `patient_id`, `sender`, `message`, `sent_at`) VALUES
(1, 1, NULL, NULL, 'patient', 'hi', '2025-06-26 09:56:33'),
(2, 1, NULL, NULL, 'hospital', 'your report is reday', '2025-06-26 10:06:40'),
(3, 1, NULL, NULL, 'patient', 'i will', '2025-06-26 10:08:41'),
(4, 1, NULL, NULL, 'hospital', 'ok', '2025-06-26 10:08:51'),
(5, 13, NULL, NULL, 'hospital', 'its in process', '2025-06-26 10:16:38'),
(6, 22, NULL, NULL, 'patient', 'hi', '2025-06-27 09:43:35'),
(8, 23, NULL, NULL, 'hospital', 'hi', '2025-06-29 09:55:15'),
(9, 23, NULL, NULL, 'hospital', 'hi', '2025-06-29 09:57:19'),
(16, 29, 1, NULL, 'admin', 'hi', '2025-06-29 10:29:07'),
(17, 29, 1, NULL, 'hospital', 'hello', '2025-06-29 10:29:14'),
(18, 24, 1, NULL, 'hospital', 'hi', '2025-06-29 17:09:49'),
(19, 24, 1, NULL, 'admin', 'is all ok', '2025-06-29 17:09:57'),
(20, 13, NULL, NULL, 'patient', 'is it done', '2025-06-29 17:13:20'),
(21, 13, NULL, NULL, 'hospital', 'still in process', '2025-06-29 17:13:30');

-- --------------------------------------------------------

--
-- Table structure for table `doctor_list`
--

CREATE TABLE `doctor_list` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `specialization` varchar(255) DEFAULT NULL,
  `phone_no` varchar(15) DEFAULT NULL,
  `hospital_id` int(11) DEFAULT NULL,
  `experience_years` int(11) DEFAULT NULL,
  `availability` varchar(255) DEFAULT NULL,
  `fees` int(11) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `doctor_list`
--

INSERT INTO `doctor_list` (`id`, `name`, `specialization`, `phone_no`, `hospital_id`, `experience_years`, `availability`, `fees`, `password`) VALUES
(1, 'Dr. Asad Ali', 'General Doctor', '01700000001', 1, 6, 'Mon-Fri 9AM-3PM', 1000, 'admin123'),
(2, 'Dr. Nabila Khan', 'General Doctor', '01700000002', 1, 5, 'Mon-Fri 9AM-4PM', 3000, 'admin123'),
(3, 'Dr. Salim Ahmed', 'General Doctor', '01700000003', 1, 7, 'Mon-Fri 9AM-5PM', 2500, 'admin123'),
(5, 'Dr. Shahinur Rahman', 'General Doctor', '01700000005', 1, 8, 'Mon-Fri 9AM-3PM', 2000, 'admin123'),
(7, 'Dr. Khaled Ahmad', 'Cardiologist', '01700000007', 1, 12, 'Mon-Fri 9AM-5PM', 1500, 'admin123'),
(9, 'Dr. Sarah Islam', 'Pediatrician', '01700000009', 1, 9, 'Mon-Fri 9AM-1PM', 1000, 'admin123'),
(10, 'Dr. Tasnim Sultana', 'Gynecologist', '01700000010', 1, 10, 'Mon-Fri 9AM-4PM', 2500, 'admin123'),
(11, 'Dr. Fariha Khan', 'Dermatologist', '01700000011', 1, 8, 'Mon-Fri 9AM-3PM', 1500, 'admin123'),
(12, 'Dr. Sara Kamal', 'ENT Specialist', '01700000012', 1, 9, 'Mon-Fri 9AM-5PM', 1000, 'admin123'),
(13, 'Dr. Sharmin Akter', 'Psychiatrist', '01700000013', 1, 6, 'Mon-Fri 9AM-4PM', 1000, 'admin123'),
(14, 'Dr. Asad Ali', 'General Doctor', '01700000014', 2, 6, 'Mon-Fri 9AM-3PM', 500, 'admin123'),
(15, 'Dr. Nabila Khan', 'General Doctor', '01700000015', 2, 5, 'Mon-Fri 9AM-4PM', 500, 'admin123'),
(16, 'Dr. Salim Ahmed', 'General Doctor', '01700000016', 2, 7, 'Mon-Fri 9AM-5PM', 3000, 'admin123'),
(17, 'Dr. Zubaida Sultana', 'General Doctor', '01700000017', 2, 6, 'Mon-Fri 9AM-4PM', 2000, 'admin123'),
(19, 'Dr. Farida Begum', 'General Doctor', '01700000019', 2, 6, 'Mon-Fri 10AM-4PM', 2000, 'admin123'),
(20, 'Dr. John Doe', 'Cardiologist', '01700000020', 2, 10, 'Mon-Fri 9AM-5PM', 500, 'admin123'),
(21, 'Dr. Jane Smith', 'Orthopedic', '01700000021', 2, 8, 'Mon-Fri 9AM-4PM', 2000, 'admin123'),
(22, 'Dr. Michael Brown', 'Pediatrician', '01700000022', 2, 12, 'Mon-Fri 9AM-1PM', 500, 'admin123'),
(23, 'Dr. Laila Reza', 'Gynecologist', '01700000023', 2, 9, 'Mon-Fri 9AM-4PM', 500, 'admin123'),
(24, 'Dr. Mark Pink', 'Dermatologist', '01700000024', 2, 7, 'Mon-Fri 9AM-3PM', 1000, 'admin123'),
(25, 'Dr. Kevin Yellow', 'ENT Specialist', '01700000025', 2, 10, 'Mon-Fri 9AM-5PM', 500, 'admin123'),
(26, 'Dr. Emily Black', 'Psychiatrist', '01700000026', 2, 8, 'Mon-Fri 9AM-4PM', 3000, 'admin123'),
(27, 'Dr. Asad Ali', 'General Doctor', '01700000027', 3, 6, 'Mon-Fri 9AM-3PM', 500, 'admin123'),
(28, 'Dr. Nabila Khan', 'General Doctor', '01700000028', 3, 5, 'Mon-Fri 9AM-4PM', 2500, 'admin123'),
(29, 'Dr. Salim Ahmed', 'General Doctor', '01700000029', 3, 7, 'Mon-Fri 9AM-5PM', 2000, 'admin123'),
(30, 'Dr. Zubaida Sultana', 'General Doctor', '01700000030', 3, 6, 'Mon-Fri 9AM-4PM', 2500, 'admin123'),
(31, 'Dr. Shahinur Rahman', 'General Doctor', '01700000031', 3, 8, 'Mon-Fri 9AM-3PM', 2500, 'admin123'),
(32, 'Dr. Farida Begum', 'General Doctor', '01700000032', 3, 6, 'Mon-Fri 10AM-4PM', 3000, 'admin123'),
(33, 'Dr. Khaled Ahmad', 'Cardiologist', '01700000033', 3, 12, 'Mon-Fri 9AM-5PM', 500, 'admin123'),
(34, 'Dr. Layla Reza', 'Orthopedic', '01700000034', 3, 10, 'Mon-Fri 10AM-4PM', 500, 'admin123'),
(35, 'Dr. Sarah Islam', 'Pediatrician', '01700000035', 3, 9, 'Mon-Fri 9AM-1PM', 2000, 'admin123'),
(36, 'Dr. Tasnim Sultana', 'Gynecologist', '01700000036', 3, 10, 'Mon-Fri 9AM-4PM', 3000, 'admin123'),
(37, 'Dr. Fariha Khan', 'Dermatologist', '01700000037', 3, 8, 'Mon-Fri 9AM-3PM', 3000, 'admin123'),
(38, 'Dr. Sara Kamal', 'ENT Specialist', '01700000038', 3, 9, 'Mon-Fri 9AM-5PM', 2500, 'admin123'),
(39, 'Dr. Sharmin Akter', 'Psychiatrist', '01700000039', 3, 6, 'Mon-Fri 9AM-4PM', 3000, 'admin123'),
(40, 'Dr. Asad Ali', 'General Doctor', '01700000040', 4, 6, 'Mon-Fri 9AM-3PM', 500, 'admin123'),
(41, 'Dr. Nabila Khan', 'General Doctor', '01700000041', 4, 5, 'Mon-Fri 9AM-4PM', 1000, 'admin123'),
(42, 'Dr. Salim Ahmed', 'General Doctor', '01700000042', 4, 7, 'Mon-Fri 9AM-5PM', 2000, 'admin123'),
(43, 'Dr. Zubaida Sultana', 'General Doctor', '01700000043', 4, 6, 'Mon-Fri 9AM-4PM', 1500, 'admin123'),
(44, 'Dr. Shahinur Rahman', 'General Doctor', '01700000044', 4, 8, 'Mon-Fri 9AM-3PM', 2000, 'admin123'),
(45, 'Dr. Farida Begum', 'General Doctor', '01700000045', 4, 6, 'Mon-Fri 10AM-4PM', 1000, 'admin123'),
(46, 'Dr. John Doe', 'Cardiologist', '01700000046', 4, 10, 'Mon-Fri 9AM-5PM', 2500, 'admin123'),
(47, 'Dr. Jane Smith', 'Orthopedic', '01700000047', 4, 8, 'Mon-Fri 9AM-4PM', 2500, 'admin123'),
(48, 'Dr. Michael Brown', 'Pediatrician', '01700000048', 4, 12, 'Mon-Fri 9AM-1PM', 3000, 'admin123'),
(49, 'Dr. Laila Reza', 'Gynecologist', '01700000049', 4, 9, 'Mon-Fri 9AM-4PM', 500, 'admin123'),
(50, 'Dr. Mark Pink', 'Dermatologist', '01700000050', 4, 7, 'Mon-Fri 9AM-3PM', 1500, 'admin123'),
(51, 'Dr. Kevin Yellow', 'ENT Specialist', '01700000051', 4, 10, 'Mon-Fri 9AM-5PM', 1000, 'admin123'),
(52, 'Dr. Emily Black', 'Psychiatrist', '01700000052', 4, 8, 'Mon-Fri 9AM-4PM', 2000, 'admin123'),
(79, 'Dr. Asad Ali', 'General Doctor', '01700000079', 5, 6, 'Mon-Fri 9AM-3PM', 500, 'admin123'),
(80, 'Dr. Nabila Khan', 'General Doctor', '01700000080', 5, 5, 'Mon-Fri 9AM-4PM', 2000, 'admin123'),
(81, 'Dr. Salim Ahmed', 'General Doctor', '01700000081', 5, 7, 'Mon-Fri 9AM-5PM', 3000, 'admin123'),
(82, 'Dr. Zubaida Sultana', 'General Doctor', '01700000082', 5, 6, 'Mon-Fri 9AM-4PM', 1500, 'admin123'),
(83, 'Dr. Shahinur Rahman', 'General Doctor', '01700000083', 5, 8, 'Mon-Fri 9AM-3PM', 2500, 'admin123'),
(84, 'Dr. Farida Begum', 'General Doctor', '01700000084', 5, 6, 'Mon-Fri 10AM-4PM', 500, 'admin123'),
(85, 'Dr. Khaled Ahmad', 'Cardiologist', '01700000085', 5, 12, 'Mon-Fri 9AM-5PM', 1500, 'admin123'),
(86, 'Dr. Layla Reza', 'Orthopedic', '01700000086', 5, 10, 'Mon-Fri 10AM-4PM', 3000, 'admin123'),
(87, 'Dr. Sarah Islam', 'Pediatrician', '01700000087', 5, 9, 'Mon-Fri 9AM-1PM', 1500, 'admin123'),
(88, 'Dr. Tasnim Sultana', 'Gynecologist', '01700000088', 5, 10, 'Mon-Fri 9AM-4PM', 1500, 'admin123'),
(89, 'Dr. Fariha Khan', 'Dermatologist', '01700000089', 5, 8, 'Mon-Fri 9AM-3PM', 1500, 'admin123'),
(90, 'Dr. Sara Kamal', 'ENT Specialist', '01700000090', 5, 9, 'Mon-Fri 9AM-5PM', 500, 'admin123'),
(91, 'Dr. Sharmin Akter', 'Psychiatrist', '01700000091', 5, 6, 'Mon-Fri 9AM-4PM', 3000, 'admin123'),
(92, 'Dr. Asad Ali', 'General Doctor', '01700000092', 6, 6, 'Mon-Fri 9AM-3PM', 1000, 'admin123'),
(93, 'Dr. Nabila Khan', 'General Doctor', '01700000093', 6, 5, 'Mon-Fri 9AM-4PM', 2500, 'admin123'),
(94, 'Dr. Salim Ahmed', 'General Doctor', '01700000094', 6, 7, 'Mon-Fri 9AM-5PM', 1000, 'admin123'),
(95, 'Dr. Zubaida Sultana', 'General Doctor', '01700000095', 6, 6, 'Mon-Fri 9AM-4PM', 2500, 'admin123'),
(96, 'Dr. Shahinur Rahman', 'General Doctor', '01700000096', 6, 8, 'Mon-Fri 9AM-3PM', 2500, 'admin123'),
(97, 'Dr. Farida Begum', 'General Doctor', '01700000097', 6, 6, 'Mon-Fri 10AM-4PM', 500, 'admin123'),
(98, 'Dr. Khaled Ahmad', 'Cardiologist', '01700000098', 6, 12, 'Mon-Fri 9AM-5PM', 2000, 'admin123'),
(99, 'Dr. Layla Reza', 'Orthopedic', '01700000099', 6, 10, 'Mon-Fri 10AM-4PM', 1000, 'admin123'),
(100, 'Dr. Sarah Islam', 'Pediatrician', '01700000100', 6, 9, 'Mon-Fri 9AM-1PM', 500, 'admin123'),
(101, 'Dr. Tasnim Sultana', 'Gynecologist', '01700000101', 6, 10, 'Mon-Fri 9AM-4PM', 500, 'admin123'),
(102, 'Dr. Fariha Khan', 'Dermatologist', '01700000102', 6, 8, 'Mon-Fri 9AM-3PM', 3000, 'admin123'),
(103, 'Dr. Sara Kamal', 'ENT Specialist', '01700000103', 6, 9, 'Mon-Fri 9AM-5PM', 2000, 'admin123'),
(104, 'Dr. Sharmin Akter', 'Psychiatrist', '01700000104', 6, 6, 'Mon-Fri 9AM-4PM', 2500, 'admin123'),
(105, 'Dr. Asad Ali', 'General Doctor', '01700000105', 7, 6, 'Mon-Fri 9AM-3PM', 500, 'admin123'),
(106, 'Dr. Nabila Khan', 'General Doctor', '01700000106', 7, 5, 'Mon-Fri 9AM-4PM', 1000, 'admin123'),
(107, 'Dr. Salim Ahmed', 'General Doctor', '01700000107', 7, 7, 'Mon-Fri 9AM-5PM', 2500, 'admin123'),
(108, 'Dr. Zubaida Sultana', 'General Doctor', '01700000108', 7, 6, 'Mon-Fri 9AM-4PM', 1500, 'admin123'),
(109, 'Dr. Shahinur Rahman', 'General Doctor', '01700000109', 7, 8, 'Mon-Fri 9AM-3PM', 2000, 'admin123'),
(110, 'Dr. Farida Begum', 'General Doctor', '01700000110', 7, 6, 'Mon-Fri 10AM-4PM', 2000, 'admin123'),
(111, 'Dr. Khaled Ahmad', 'Cardiologist', '01700000111', 7, 12, 'Mon-Fri 9AM-5PM', 1000, 'admin123'),
(112, 'Dr. Layla Reza', 'Orthopedic', '01700000112', 7, 10, 'Mon-Fri 10AM-4PM', 1000, 'admin123'),
(113, 'Dr. Sarah Islam', 'Pediatrician', '01700000113', 7, 9, 'Mon-Fri 9AM-1PM', 3000, 'admin123'),
(114, 'Dr. Tasnim Sultana', 'Gynecologist', '01700000114', 7, 10, 'Mon-Fri 9AM-4PM', 2000, 'admin123'),
(115, 'Dr. Fariha Khan', 'Dermatologist', '01700000115', 7, 8, 'Mon-Fri 9AM-3PM', 1000, 'admin123'),
(116, 'Dr. Sara Kamal', 'ENT Specialist', '01700000116', 7, 9, 'Mon-Fri 9AM-5PM', 1500, 'admin123'),
(117, 'Dr. Sharmin Akter', 'Psychiatrist', '01700000117', 7, 6, 'Mon-Fri 9AM-4PM', 2500, 'admin123'),
(118, 'Dr. Asad Ali', 'General Doctor', '01700000118', 8, 6, 'Mon-Fri 9AM-3PM', 500, 'admin123'),
(119, 'Dr. Nabila Khan', 'General Doctor', '01700000119', 8, 5, 'Mon-Fri 9AM-4PM', 1000, 'admin123'),
(120, 'Dr. Salim Ahmed', 'General Doctor', '01700000120', 8, 7, 'Mon-Fri 9AM-5PM', 1000, 'admin123'),
(121, 'Dr. Zubaida Sultana', 'General Doctor', '01700000121', 8, 6, 'Mon-Fri 9AM-4PM', 500, 'admin123'),
(122, 'Dr. Shahinur Rahman', 'General Doctor', '01700000122', 8, 8, 'Mon-Fri 9AM-3PM', 2000, 'admin123'),
(123, 'Dr. Farida Begum', 'General Doctor', '01700000123', 8, 6, 'Mon-Fri 10AM-4PM', 2000, 'admin123'),
(124, 'Dr. Khaled Ahmad', 'Cardiologist', '01700000124', 8, 12, 'Mon-Fri 9AM-5PM', 500, 'admin123'),
(125, 'Dr. Layla Reza', 'Orthopedic', '01700000125', 8, 10, 'Mon-Fri 10AM-4PM', 2500, 'admin123'),
(126, 'Dr. Sarah Islam', 'Pediatrician', '01700000126', 8, 9, 'Mon-Fri 9AM-1PM', 1500, 'admin123'),
(127, 'Dr. Tasnim Sultana', 'Gynecologist', '01700000127', 8, 10, 'Mon-Fri 9AM-4PM', 500, 'admin123'),
(128, 'Dr. Fariha Khan', 'Dermatologist', '01700000128', 8, 8, 'Mon-Fri 9AM-3PM', 1000, 'admin123'),
(129, 'Dr. Sara Kamal', 'ENT Specialist', '01700000129', 8, 9, 'Mon-Fri 9AM-5PM', 2000, 'admin123'),
(130, 'Dr. Sharmin Akter', 'Psychiatrist', '01700000130', 8, 6, 'Mon-Fri 9AM-4PM', 1000, 'admin123'),
(131, 'Dr. Asad Ali', 'General Doctor', '01700000131', 9, 6, 'Mon-Fri 9AM-3PM', 2500, 'admin123'),
(132, 'Dr. Nabila Khan', 'General Doctor', '01700000132', 9, 5, 'Mon-Fri 9AM-4PM', 2500, 'admin123'),
(133, 'Dr. Salim Ahmed', 'General Doctor', '01700000133', 9, 7, 'Mon-Fri 9AM-5PM', 2500, 'admin123'),
(134, 'Dr. Zubaida Sultana', 'General Doctor', '01700000134', 9, 6, 'Mon-Fri 9AM-4PM', 500, 'admin123'),
(135, 'Dr. Shahinur Rahman', 'General Doctor', '01700000135', 9, 8, 'Mon-Fri 9AM-3PM', 2000, 'admin123'),
(136, 'Dr. Farida Begum', 'General Doctor', '01700000136', 9, 6, 'Mon-Fri 10AM-4PM', 1500, 'admin123'),
(137, 'Dr. Khaled Ahmad', 'Cardiologist', '01700000137', 9, 12, 'Mon-Fri 9AM-5PM', 2000, 'admin123'),
(138, 'Dr. Layla Reza', 'Orthopedic', '01700000138', 9, 10, 'Mon-Fri 10AM-4PM', 2500, 'admin123'),
(139, 'Dr. Sarah Islam', 'Pediatrician', '01700000139', 9, 9, 'Mon-Fri 9AM-1PM', 2500, 'admin123'),
(140, 'Dr. Tasnim Sultana', 'Gynecologist', '01700000140', 9, 10, 'Mon-Fri 9AM-4PM', 3000, 'admin123'),
(141, 'Dr. Fariha Khan', 'Dermatologist', '01700000141', 9, 8, 'Mon-Fri 9AM-3PM', 2500, 'admin123'),
(142, 'Dr. Sara Kamal', 'ENT Specialist', '01700000142', 9, 9, 'Mon-Fri 9AM-5PM', 1500, 'admin123'),
(143, 'Dr. Sharmin Akter', 'Psychiatrist', '01700000143', 9, 6, 'Mon-Fri 9AM-4PM', 2500, 'admin123'),
(144, 'Dr. Asad Ali', 'General Doctor', '01700000144', 9, 6, 'Mon-Fri 9AM-3PM', 1000, 'admin123'),
(145, 'Dr. Nabila Khan', 'General Doctor', '01700000145', 9, 5, 'Mon-Fri 9AM-4PM', 1500, 'admin123'),
(146, 'Dr. Salim Ahmed', 'General Doctor', '01700000146', 9, 7, 'Mon-Fri 9AM-5PM', 500, 'admin123'),
(147, 'Dr. Zubaida Sultana', 'General Doctor', '01700000147', 9, 6, 'Mon-Fri 9AM-4PM', 3000, 'admin123'),
(148, 'Dr. Shahinur Rahman', 'General Doctor', '01700000148', 9, 8, 'Mon-Fri 9AM-3PM', 1500, 'admin123'),
(149, 'Dr. Farida Begum', 'General Doctor', '01700000149', 9, 6, 'Mon-Fri 10AM-4PM', 2000, 'admin123'),
(150, 'Dr. Khaled Ahmad', 'Cardiologist', '01700000150', 9, 12, 'Mon-Fri 9AM-5PM', 1500, 'admin123'),
(151, 'Dr. Layla Reza', 'Orthopedic', '01700000151', 9, 10, 'Mon-Fri 10AM-4PM', 1000, 'admin123'),
(152, 'Dr. Sarah Islam', 'Pediatrician', '01700000152', 9, 9, 'Mon-Fri 9AM-1PM', 1500, 'admin123'),
(153, 'Dr. Tasnim Sultana', 'Gynecologist', '01700000153', 9, 10, 'Mon-Fri 9AM-4PM', 3000, 'admin123'),
(154, 'Dr. Fariha Khan', 'Dermatologist', '01700000154', 9, 8, 'Mon-Fri 9AM-3PM', 2000, 'admin123'),
(155, 'Dr. Sara Kamal', 'ENT Specialist', '01700000155', 9, 9, 'Mon-Fri 9AM-5PM', 1000, 'admin123'),
(156, 'Dr. Sharmin Akter', 'Psychiatrist', '01700000156', 9, 6, 'Mon-Fri 9AM-4PM', 1000, 'admin123'),
(157, 'Dr. Asad Ali', 'General Doctor', '01700000157', 10, 6, 'Mon-Fri 9AM-3PM', 3000, 'admin123'),
(158, 'Dr. Nabila Khan', 'General Doctor', '01700000158', 10, 5, 'Mon-Fri 9AM-4PM', 2500, 'admin123'),
(159, 'Dr. Salim Ahmed', 'General Doctor', '01700000159', 10, 7, 'Mon-Fri 9AM-5PM', 2000, 'admin123'),
(160, 'Dr. Zubaida Sultana', 'General Doctor', '01700000160', 10, 6, 'Mon-Fri 9AM-4PM', 3000, 'admin123'),
(161, 'Dr. Shahinur Rahman', 'General Doctor', '01700000161', 10, 8, 'Mon-Fri 9AM-3PM', 3000, 'admin123'),
(162, 'Dr. Farida Begum', 'General Doctor', '01700000162', 10, 6, 'Mon-Fri 10AM-4PM', 2500, 'admin123'),
(163, 'Dr. Khaled Ahmad', 'Cardiologist', '01700000163', 10, 12, 'Mon-Fri 9AM-5PM', 1000, 'admin123'),
(164, 'Dr. Layla Reza', 'Orthopedic', '01700000164', 10, 10, 'Mon-Fri 10AM-4PM', 2500, 'admin123'),
(165, 'Dr. Sarah Islam', 'Pediatrician', '01700000165', 10, 9, 'Mon-Fri 9AM-1PM', 3000, 'admin123'),
(166, 'Dr. Tasnim Sultana', 'Gynecologist', '01700000166', 10, 10, 'Mon-Fri 9AM-4PM', 2000, 'admin123'),
(167, 'Dr. Fariha Khan', 'Dermatologist', '01700000167', 10, 8, 'Mon-Fri 9AM-3PM', 500, 'admin123'),
(168, 'Dr. Sara Kamal', 'ENT Specialist', '01700000168', 10, 9, 'Mon-Fri 9AM-5PM', 3000, 'admin123'),
(169, 'Dr. Sharmin Akter', 'Psychiatrist', '01700000169', 10, 6, 'Mon-Fri 9AM-4PM', 3000, 'admin123'),
(170, 'Dr. Asad Ali', 'General Doctor', '01700000170', 11, 6, 'Mon-Fri 9AM-3PM', 2500, 'admin123'),
(171, 'Dr. Nabila Khan', 'General Doctor', '01700000171', 11, 5, 'Mon-Fri 9AM-4PM', 3000, 'admin123'),
(172, 'Dr. Salim Ahmed', 'General Doctor', '01700000172', 11, 7, 'Mon-Fri 9AM-5PM', 1000, 'admin123'),
(173, 'Dr. Zubaida Sultana', 'General Doctor', '01700000173', 11, 6, 'Mon-Fri 9AM-4PM', 2500, 'admin123'),
(174, 'Dr. Shahinur Rahman', 'General Doctor', '01700000174', 11, 8, 'Mon-Fri 9AM-3PM', 2500, 'admin123'),
(175, 'Dr. Farida Begum', 'General Doctor', '01700000175', 11, 6, 'Mon-Fri 10AM-4PM', 2500, 'admin123'),
(176, 'Dr. Khaled Ahmad', 'Cardiologist', '01700000176', 11, 12, 'Mon-Fri 9AM-5PM', 2000, 'admin123'),
(177, 'Dr. Layla Reza', 'Orthopedic', '01700000177', 11, 10, 'Mon-Fri 10AM-4PM', 2000, 'admin123'),
(178, 'Dr. Sarah Islam', 'Pediatrician', '01700000178', 11, 9, 'Mon-Fri 9AM-1PM', 1000, 'admin123'),
(179, 'Dr. Tasnim Sultana', 'Gynecologist', '01700000179', 11, 10, 'Mon-Fri 9AM-4PM', 1500, 'admin123'),
(180, 'Dr. Fariha Khan', 'Dermatologist', '01700000180', 11, 8, 'Mon-Fri 9AM-3PM', 2000, 'admin123'),
(181, 'Dr. Sara Kamal', 'ENT Specialist', '01700000181', 11, 9, 'Mon-Fri 9AM-5PM', 2000, 'admin123'),
(182, 'Dr. Sharmin Akter', 'Psychiatrist', '01700000182', 11, 6, 'Mon-Fri 9AM-4PM', 1500, 'admin123'),
(183, 'Dr. Asad Ali', 'General Doctor', '01700000183', 12, 6, 'Mon-Fri 9AM-3PM', 500, 'admin123'),
(184, 'Dr. Nabila Khan', 'General Doctor', '01700000184', 12, 5, 'Mon-Fri 9AM-4PM', 1500, 'admin123'),
(185, 'Dr. Salim Ahmed', 'General Doctor', '01700000185', 12, 7, 'Mon-Fri 9AM-5PM', 2000, 'admin123'),
(186, 'Dr. Zubaida Sultana', 'General Doctor', '01700000186', 12, 6, 'Mon-Fri 9AM-4PM', 2500, 'admin123'),
(187, 'Dr. Shahinur Rahman', 'General Doctor', '01700000187', 12, 8, 'Mon-Fri 9AM-3PM', 3000, 'admin123'),
(188, 'Dr. Farida Begum', 'General Doctor', '01700000188', 12, 6, 'Mon-Fri 10AM-4PM', 500, 'admin123'),
(189, 'Dr. Khaled Ahmad', 'Cardiologist', '01700000189', 12, 12, 'Mon-Fri 9AM-5PM', 2000, 'admin123'),
(190, 'Dr. Layla Reza', 'Orthopedic', '01700000190', 12, 10, 'Mon-Fri 10AM-4PM', 500, 'admin123'),
(191, 'Dr. Sarah Islam', 'Pediatrician', '01700000191', 12, 9, 'Mon-Fri 9AM-1PM', 1500, 'admin123'),
(192, 'Dr. Tasnim Sultana', 'Gynecologist', '01700000192', 12, 10, 'Mon-Fri 9AM-4PM', 500, 'admin123'),
(193, 'Dr. Fariha Khan', 'Dermatologist', '01700000193', 12, 8, 'Mon-Fri 9AM-3PM', 3000, 'admin123'),
(195, 'Dr. fiza dd', 'Psychiatrist', '01700000195', 12, 6, 'Mon-Fri 9AM-4PM', 1000, 'admin123'),
(198, 'dr harun roshid', 'cardiologist', '01800000001', 1, 2, 'Mon-Fri 9AM-4PM', 5000, 'admin123');

-- --------------------------------------------------------

--
-- Table structure for table `doctor_review`
--

CREATE TABLE `doctor_review` (
  `review_id` int(11) NOT NULL,
  `doctor_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `hospital_id` int(11) NOT NULL,
  `rating` int(1) NOT NULL CHECK (`rating` between 1 and 5),
  `review_text` text DEFAULT NULL,
  `review_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `doctor_review`
--

INSERT INTO `doctor_review` (`review_id`, `doctor_id`, `patient_id`, `hospital_id`, `rating`, `review_text`, `review_date`) VALUES
(1, 2, 1, 1, 5, 'good service', '2025-06-27 11:33:08'),
(2, 1, 1, 1, 4, 'nice', '2025-06-27 11:33:27');

-- --------------------------------------------------------

--
-- Table structure for table `hospital_list`
--

CREATE TABLE `hospital_list` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `phone_no` varchar(20) DEFAULT NULL,
  `img` longblob DEFAULT NULL,
  `hospital_state` varchar(50) DEFAULT NULL,
  `hospital_city` varchar(50) DEFAULT NULL,
  `hospital_area` varchar(50) DEFAULT NULL,
  `tag_line` varchar(255) DEFAULT NULL,
  `latitude` decimal(9,6) DEFAULT NULL,
  `longitude` decimal(9,6) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `username` varchar(100) DEFAULT NULL,
  `password` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `hospital_list`
--

INSERT INTO `hospital_list` (`id`, `name`, `phone_no`, `img`, `hospital_state`, `hospital_city`, `hospital_area`, `tag_line`, `latitude`, `longitude`, `email`, `username`, `password`) VALUES
(1, 'Dhaka Medical College & Hospital', '88028626812', NULL, 'Dhaka Division', 'Dhaka', 'Shahbagh', 'Leading public hospital in Dhaka', 23.752100, 90.374400, 'info@dhakamedicalcollegehospital.gov.bd', 'dhaka_medical', 'admin123'),
(2, 'United Hospital Limited', '88028836444', NULL, 'Dhaka Division', 'Dhaka', 'Gulshan', 'Excellence in private healthcare', 23.810300, 90.412500, 'info@bsmmu.gov.bd', 'united_hospital', 'admin123'),
(3, 'Evercare Hospital Dhaka', '88010678', NULL, 'Dhaka Division', 'Dhaka', 'Bashundhara R/A', 'International standard hospital', 23.810500, 90.429600, 'info@birdembd.gov.bd', 'evercare_hospital', 'admin123'),
(4, 'Square Hospitals Ltd.', '88028144400', NULL, 'Dhaka Division', 'Dhaka', 'Panthapath', 'Advanced diagnostics and surgery', 23.758400, 90.390600, 'info@ibnsinamedical.gov.bd', 'square_hospitals', 'admin123'),
(5, 'Labaid Specialized Hospital', '88029676356', NULL, 'Dhaka Division', 'Dhaka', 'Dhanmondi', 'Trusted care for generations', 23.746600, 90.375500, 'info@labaidcardiac.gov.bd', 'labaid_specialized', 'admin123'),
(6, 'Ibn Sina Hospital', '88029120395', NULL, 'Dhaka Division', 'Dhaka', 'Dhanmondi', 'Comprehensive medical services', 23.746000, 90.365600, 'info@unitedhospital.gov.bd', 'ibn_sina', 'admin123'),
(7, 'Apollo Hospitals Dhaka', '880255037242', NULL, 'Dhaka Division', 'Dhaka', 'Bashundhara R/A', 'Modern hospital with global standards', 23.819200, 90.424400, 'info@popularhospital.gov.bd', 'apollo_hospitals', 'admin123'),
(8, 'BSMMU Hospital', '88029661051', NULL, 'Dhaka Division', 'Dhaka', 'Shahbagh', 'Premier teaching hospital', 23.748500, 90.387100, 'info@greenlifehospital.gov.bd', 'bsmmu_hospital', 'admin123'),
(9, 'National Heart Foundation Hospital', '88028053874', NULL, 'Dhaka Division', 'Dhaka', 'Mirpur', 'Heart care specialist hospital', 23.810300, 90.370700, 'info@squarehospital.gov.bd', 'national_heart', 'admin123'),
(10, 'Shahid Suhrawardy Medical College', '88029130800', NULL, 'Dhaka Division', 'Dhaka', 'Sher-e-Bangla Nagar', 'Serving with dedication and care', 23.799500, 90.379800, 'info@apollodhaka.gov.bd', 'shahid_suhrawardy', 'admin123'),
(11, 'Cumilla Medical College Hospital', '8808172010', NULL, 'Chattogram Division', 'Cumilla', 'Kuchaitoli', 'Top medical college in Cumilla', 23.462700, 91.177100, 'info@monowarhospital.gov.bd', 'cumilla_medical', 'admin123'),
(12, 'Mainamoti Medical College & Hospital', '8809639172671', NULL, 'Chattogram Division', 'Cumilla', 'Baropara', 'Affordable private healthcare', 23.446100, 91.181300, 'info@carehospitalltd.gov.bd', 'mainamoti_medical', 'admin123'),
(13, 'Eastern Medical College & Hospital', '8801730014800', NULL, 'Chattogram Division', 'Cumilla', 'Burichang', 'Skilled doctors and modern facilities', 23.436500, 91.193900, 'info@holycrosshospital.gov.bd', 'eastern_medical', 'admin123'),
(14, 'Central Medical College & Hospital', '8801941295261', NULL, 'Chattogram Division', 'Cumilla', 'Paduar Bazar', 'Your health, our priority', 23.440300, 91.177500, 'info@anwerkhanhospital.gov.bd', 'central_medical', 'admin123'),
(15, 'Cumilla Medical Center', '88018168921', NULL, 'Chattogram Division', 'Cumilla', 'Laksham Road', 'Modern diagnostics and treatment', 23.469300, 91.173000, 'info@cityhospitalbd.gov.bd', 'cumilla_medical_center', 'admin123'),
(16, 'Moon Hospital Limited', '8808165471', NULL, 'Chattogram Division', 'Cumilla', 'Jhawtola', '24/7 emergency support', 23.441900, 91.187100, 'info@shahidhospital.gov.bd', 'moon_hospital', 'admin123'),
(17, 'Midland Hospital Pvt. Ltd.', '8801711795740', NULL, 'Chattogram Division', 'Cumilla', 'Kandirpar', 'Trustworthy medical care', 23.453700, 91.170800, 'info@alaminhospital.gov.bd', 'midland_hospital', 'admin123'),
(18, 'Gomati Hospital Pvt. Ltd.', '8801711798083', NULL, 'Chattogram Division', 'Cumilla', 'Kandirpar', 'Caring for the community', 23.456200, 91.174000, 'info@centralhospital.gov.bd', 'gomati_hospital', 'admin123'),
(19, 'Mission Hospital', '8801739142170', NULL, 'Chattogram Division', 'Cumilla', 'Shashongachha', 'Experienced doctors, friendly staff', 23.460800, 91.155900, 'info@islamiahospital.gov.bd', 'mission_hospital', 'admin123'),
(20, 'Popular Hospital Cumilla', '8801711785442', NULL, 'Chattogram Division', 'Cumilla', 'Laksham Road', 'Quality treatment near you', 23.469100, 91.173200, 'info@panguhospital.gov.bd', 'popular_hospital', 'admin123'),
(21, 'Chittagong Medical College Hospital', '88031637088', NULL, 'Chattogram Division', 'Chittagong', 'Anderkilla', 'Premier hospital in Chattogram', 22.347500, 91.812400, 'info@chittagongmedicalcollegehospital.gov.bd', 'chittagong_medical', 'admin123'),
(22, 'Khulna Medical College Hospital', '88041760244', NULL, 'Khulna Division', 'Khulna', 'Boyra', 'Reliable care in Khulna', 22.845700, 89.540500, 'info@khulnamedicalcollegehospital.gov.bd', 'khulna_medical', 'admin123'),
(23, 'Rajshahi Medical College Hospital', '880721776000', NULL, 'Rajshahi Division', 'Rajshahi', 'Laxmipur', 'Academic and treatment excellence', 24.373200, 88.603400, 'info@rajshahimedicalcollegehospital.gov.bd', 'rajshahi_medical', 'admin123'),
(25, 'Rangpur Medical College Hospital', '88052163300', NULL, 'Rangpur Division', 'Rangpur', 'Medical College Road', 'Rangpur’s top government hospital', 25.744500, 89.255700, 'info@rangpurmedicalcollegehospital.gov.bd', 'rangpur_medical', 'admin123'),
(26, 'Mymensingh Medical College Hospital', '01837428723', NULL, 'Mymensingh Division', 'Mymensingh', 'Charpara', 'Comprehensive medical services', 24.753000, 90.417000, 'info@mymensinghmedicalcollegehospital.gov.bd', 'mymensingh_medical', 'admin123'),
(29, 'Jessore 250 Bed General Hospital', '88042168811', NULL, 'Khulna Division', 'Jessore', 'Chanchra', 'Jessore’s trusted hospital', 23.165500, 89.212900, 'info@jessoregeneralhospital.gov.bd', 'jessore_250', 'admin123');

-- --------------------------------------------------------

--
-- Table structure for table `hospital_review`
--

CREATE TABLE `hospital_review` (
  `review_id` int(11) NOT NULL,
  `hospital_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `rating` int(1) NOT NULL CHECK (`rating` between 1 and 5),
  `review_text` text DEFAULT NULL,
  `review_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `hospital_review`
--

INSERT INTO `hospital_review` (`review_id`, `hospital_id`, `patient_id`, `rating`, `review_text`, `review_date`) VALUES
(5, 1, 7, 5, 'ffffff\r\n', '2025-06-25 10:27:02'),
(6, 1, 7, 5, 'ffffff\r\n', '2025-06-25 10:27:05'),
(7, 7, 7, 5, 'good one ', '2025-06-25 10:30:14'),
(8, 2, 7, 5, 'give good services ', '2025-06-25 10:31:09'),
(11, 11, 1, 5, 'best hospital in cumilla', '2025-06-29 17:15:43');

-- --------------------------------------------------------

--
-- Table structure for table `medicine_reminders`
--

CREATE TABLE `medicine_reminders` (
  `id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `medicine_name` varchar(255) NOT NULL,
  `reminder_time` time NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `medicine_reminders`
--

INSERT INTO `medicine_reminders` (`id`, `patient_id`, `medicine_name`, `reminder_time`, `created_at`) VALUES
(2, 1, 'flugal50', '12:26:00', '2025-06-30 01:24:15');

-- --------------------------------------------------------

--
-- Table structure for table `patient_allergies`
--

CREATE TABLE `patient_allergies` (
  `id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `allergy_name` varchar(255) NOT NULL,
  `notes` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `patient_allergies`
--

INSERT INTO `patient_allergies` (`id`, `patient_id`, `allergy_name`, `notes`) VALUES
(2, 1, 'dust mite allergies', ''),
(4, 1, 'food', '');

-- --------------------------------------------------------

--
-- Table structure for table `patient_list`
--

CREATE TABLE `patient_list` (
  `id` int(11) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `phone_no` varchar(20) NOT NULL,
  `password` varchar(255) NOT NULL,
  `gender` enum('Male','Female') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `patient_list`
--

INSERT INTO `patient_list` (`id`, `first_name`, `last_name`, `email`, `phone_no`, `password`, `gender`) VALUES
(1, 'amdadul', 'islam', 'wp@gmail.com', '01711223344', 'patient123', 'Female'),
(5, 'wasim', 'ah', 'ow@gmail.com', '9999999999', 'patient123', 'Male'),
(7, 'tanzil', 'ahmed', 'tanzil@gmail.com', '4444444444', 'patient123', 'Male'),
(8, 'ridoy', 'ah', 'rd@gmail.com', '0234823325', 'patient123', 'Male');

-- --------------------------------------------------------

--
-- Table structure for table `prescription_list`
--

CREATE TABLE `prescription_list` (
  `prescription_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `doctor_id` int(11) NOT NULL,
  `hospital_id` int(11) NOT NULL,
  `disease` varchar(255) DEFAULT NULL,
  `allergies` varchar(255) DEFAULT NULL,
  `prescription` varchar(200) NOT NULL,
  `dates` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `prescription_list`
--

INSERT INTO `prescription_list` (`prescription_id`, `patient_id`, `doctor_id`, `hospital_id`, `disease`, `allergies`, `prescription`, `dates`) VALUES
(1, 1, 1, 1, 'yes', 'yes', 'napa ', '2025-06-23'),
(2, 8, 1, 1, 'Yes you have disease', 'yes you have allergies', '1.Aspirin\r\n2. Paracetamol\r\n3. Amoxicillin', '2025-06-24'),
(4, 1, 2, 1, 'fever', 'none', 'napa , flugal50g', '2025-06-24'),
(5, 7, 2, 1, 'eeeeeeeeee', 'kkkkkkkkkk', 'fffffffff', '2025-06-25');

-- --------------------------------------------------------

--
-- Table structure for table `sample_requests`
--

CREATE TABLE `sample_requests` (
  `id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `test_name` varchar(100) DEFAULT NULL,
  `patient_name` varchar(150) DEFAULT NULL,
  `mobile` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `preferred_date` date DEFAULT NULL,
  `city` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sample_requests`
--

INSERT INTO `sample_requests` (`id`, `patient_id`, `test_name`, `patient_name`, `mobile`, `address`, `preferred_date`, `city`, `created_at`) VALUES
(1, 1, 'COVID-19', 'amdadul', '01743543985', 'dhaka, syednagar, vatara, k block, house 33', '2025-06-29', 'Dhaka', '2025-06-26 09:21:18'),
(3, 1, 'X-Ray', 'karim ah', '23432423244', 'dsdfds', '2025-06-29', 'Dhaka', '2025-06-26 10:13:11'),
(4, 1, 'Ultrasound', 'karim ah', '01300000122', 'vatara', '2025-06-29', 'Chittagong', '2025-06-27 09:37:16'),
(5, 1, 'Blood Test', 'amdaul', '01827394923', 'syednagar,vatara', '2025-07-01', 'Dhaka', '2025-06-29 06:22:53');

-- --------------------------------------------------------

--
-- Table structure for table `sample_request_hospitals`
--

CREATE TABLE `sample_request_hospitals` (
  `id` int(11) NOT NULL,
  `request_id` int(11) NOT NULL,
  `hospital_id` int(11) NOT NULL,
  `status` enum('Pending','Accepted','Rejected') DEFAULT 'Pending',
  `responded_at` timestamp NULL DEFAULT NULL,
  `report_details` text DEFAULT NULL,
  `report_file` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sample_request_hospitals`
--

INSERT INTO `sample_request_hospitals` (`id`, `request_id`, `hospital_id`, `status`, `responded_at`, `report_details`, `report_file`) VALUES
(1, 1, 1, 'Accepted', NULL, NULL, 'uploads/reports/report_1_1750936817.jpg'),
(2, 1, 2, 'Pending', NULL, NULL, NULL),
(3, 1, 3, 'Pending', NULL, NULL, NULL),
(4, 1, 4, 'Pending', NULL, NULL, NULL),
(5, 1, 5, 'Pending', NULL, NULL, NULL),
(6, 1, 6, 'Pending', NULL, NULL, NULL),
(7, 1, 7, 'Pending', NULL, NULL, NULL),
(8, 1, 8, 'Pending', NULL, NULL, NULL),
(9, 1, 9, 'Pending', NULL, NULL, NULL),
(10, 1, 10, 'Pending', NULL, NULL, NULL),
(12, 3, 1, 'Pending', NULL, NULL, NULL),
(13, 3, 2, 'Accepted', NULL, NULL, NULL),
(14, 3, 3, 'Pending', NULL, NULL, NULL),
(15, 3, 4, 'Pending', NULL, NULL, NULL),
(16, 3, 5, 'Pending', NULL, NULL, NULL),
(17, 3, 6, 'Pending', NULL, NULL, NULL),
(18, 3, 7, 'Pending', NULL, NULL, NULL),
(19, 3, 8, 'Pending', NULL, NULL, NULL),
(20, 3, 9, 'Pending', NULL, NULL, NULL),
(21, 3, 10, 'Pending', NULL, NULL, NULL),
(22, 4, 21, 'Pending', NULL, NULL, NULL),
(23, 5, 1, 'Accepted', NULL, NULL, NULL),
(24, 5, 2, 'Pending', NULL, NULL, NULL),
(25, 5, 3, 'Pending', NULL, NULL, NULL),
(26, 5, 4, 'Pending', NULL, NULL, NULL),
(27, 5, 5, 'Pending', NULL, NULL, NULL),
(28, 5, 6, 'Pending', NULL, NULL, NULL),
(29, 5, 7, 'Pending', NULL, NULL, NULL),
(30, 5, 8, 'Pending', NULL, NULL, NULL),
(31, 5, 9, 'Pending', NULL, NULL, NULL),
(32, 5, 10, 'Pending', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `sample_tests`
--

CREATE TABLE `sample_tests` (
  `id` int(11) NOT NULL,
  `test_name` varchar(100) NOT NULL,
  `image_url` text NOT NULL,
  `admin_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sample_tests`
--

INSERT INTO `sample_tests` (`id`, `test_name`, `image_url`, `admin_id`) VALUES
(1, 'Blood Test', 'https://cdn-icons-png.flaticon.com/512/2965/2965567.png', 1),
(2, 'COVID-19', 'https://cdn-icons-png.flaticon.com/512/2785/2785819.png', 1),
(4, 'MRI', 'https://cdn-icons-png.flaticon.com/512/3176/3176366.png', 1),
(5, 'Ultrasound', 'https://cdn-icons-png.flaticon.com/512/4904/4904927.png', 1),
(6, 'Pathology', 'https://cdn-icons-png.flaticon.com/512/2642/2642515.png', 1),
(7, 'Full Body Checkup', 'https://cdn-icons-png.flaticon.com/512/2947/2947972.png', 1),
(8, 'Genetic Test', 'https://cdn-icons-png.flaticon.com/512/4096/4096365.png', 1),
(9, 'Fever Panel', 'https://cdn-icons-png.flaticon.com/512/1687/1687374.png', 1);

-- --------------------------------------------------------

--
-- Table structure for table `symptom_category`
--

CREATE TABLE `symptom_category` (
  `id` int(11) NOT NULL,
  `specialization` varchar(100) NOT NULL,
  `symptom` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `symptom_category`
--

INSERT INTO `symptom_category` (`id`, `specialization`, `symptom`) VALUES
(1, 'General Doctor', 'Common illness'),
(2, 'General Doctor', 'fever'),
(3, 'General Doctor', 'cold'),
(4, 'General Doctor', 'fatigue'),
(5, 'Cardiologist', 'Chest pain'),
(6, 'Cardiologist', 'high BP'),
(7, 'Cardiologist', 'shortness of breath'),
(8, 'Pediatrician', 'Child health issues'),
(9, 'Pediatrician', 'fever'),
(10, 'Pediatrician', 'cough in kids'),
(11, 'Gynecologist', 'Menstrual problems'),
(12, 'Gynecologist', 'pregnancy issues'),
(13, 'Dermatologist', 'Skin rashes'),
(14, 'Dermatologist', 'acne'),
(15, 'Dermatologist', 'hair loss'),
(16, 'Psychiatrist', 'Depression'),
(17, 'Psychiatrist', 'anxiety'),
(18, 'Psychiatrist', 'sleep disorder'),
(19, 'ENT Specialist', 'Ear pain'),
(20, 'ENT Specialist', 'sore throat'),
(21, 'ENT Specialist', 'nasal blockage'),
(22, 'Orthopedic', 'Bone fracture'),
(23, 'Orthopedic', 'Joint pain'),
(24, 'Orthopedic', 'Back pain');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `appointment_patient`
--
ALTER TABLE `appointment_patient`
  ADD UNIQUE KEY `appointment_id` (`appointment_id`) USING BTREE,
  ADD KEY `fk_appointment_doctor` (`doctor_id`),
  ADD KEY `fk_appointment_hospital` (`hospital_id`),
  ADD KEY `fk_appointment_patient` (`patient_id`);

--
-- Indexes for table `blog_posts`
--
ALTER TABLE `blog_posts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `hospital_id` (`hospital_id`);

--
-- Indexes for table `blog_ratings`
--
ALTER TABLE `blog_ratings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `blog_id` (`blog_id`),
  ADD KEY `patient_id` (`patient_id`);

--
-- Indexes for table `chat_messages`
--
ALTER TABLE `chat_messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `request_hospital_id` (`request_hospital_id`),
  ADD KEY `fk_chat_admin` (`admin_id`),
  ADD KEY `fk_chat_patient` (`patient_id`);

--
-- Indexes for table `doctor_list`
--
ALTER TABLE `doctor_list`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_phone` (`phone_no`),
  ADD KEY `hospital_id` (`hospital_id`);

--
-- Indexes for table `doctor_review`
--
ALTER TABLE `doctor_review`
  ADD PRIMARY KEY (`review_id`),
  ADD KEY `fk_dr_doctor` (`doctor_id`),
  ADD KEY `fk_dr_patient` (`patient_id`),
  ADD KEY `fk_dr_hospital` (`hospital_id`);

--
-- Indexes for table `hospital_list`
--
ALTER TABLE `hospital_list`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `hospital_review`
--
ALTER TABLE `hospital_review`
  ADD PRIMARY KEY (`review_id`),
  ADD KEY `fk_hr_hospital` (`hospital_id`),
  ADD KEY `fk_hr_patient` (`patient_id`);

--
-- Indexes for table `medicine_reminders`
--
ALTER TABLE `medicine_reminders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `patient_id` (`patient_id`);

--
-- Indexes for table `patient_allergies`
--
ALTER TABLE `patient_allergies`
  ADD PRIMARY KEY (`id`),
  ADD KEY `patient_id` (`patient_id`);

--
-- Indexes for table `patient_list`
--
ALTER TABLE `patient_list`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `prescription_list`
--
ALTER TABLE `prescription_list`
  ADD PRIMARY KEY (`prescription_id`),
  ADD KEY `patient_id` (`patient_id`),
  ADD KEY `doctor_id` (`doctor_id`),
  ADD KEY `hospital_id` (`hospital_id`);

--
-- Indexes for table `sample_requests`
--
ALTER TABLE `sample_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `patient_id` (`patient_id`);

--
-- Indexes for table `sample_request_hospitals`
--
ALTER TABLE `sample_request_hospitals`
  ADD PRIMARY KEY (`id`),
  ADD KEY `request_id` (`request_id`),
  ADD KEY `hospital_id` (`hospital_id`);

--
-- Indexes for table `sample_tests`
--
ALTER TABLE `sample_tests`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `test_name` (`test_name`),
  ADD KEY `fk_sample_tests_admin` (`admin_id`);

--
-- Indexes for table `symptom_category`
--
ALTER TABLE `symptom_category`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `appointment_patient`
--
ALTER TABLE `appointment_patient`
  MODIFY `appointment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `blog_posts`
--
ALTER TABLE `blog_posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `blog_ratings`
--
ALTER TABLE `blog_ratings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `chat_messages`
--
ALTER TABLE `chat_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `doctor_list`
--
ALTER TABLE `doctor_list`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=199;

--
-- AUTO_INCREMENT for table `doctor_review`
--
ALTER TABLE `doctor_review`
  MODIFY `review_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `hospital_list`
--
ALTER TABLE `hospital_list`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `hospital_review`
--
ALTER TABLE `hospital_review`
  MODIFY `review_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `medicine_reminders`
--
ALTER TABLE `medicine_reminders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `patient_allergies`
--
ALTER TABLE `patient_allergies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `patient_list`
--
ALTER TABLE `patient_list`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `prescription_list`
--
ALTER TABLE `prescription_list`
  MODIFY `prescription_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `sample_requests`
--
ALTER TABLE `sample_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `sample_request_hospitals`
--
ALTER TABLE `sample_request_hospitals`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `sample_tests`
--
ALTER TABLE `sample_tests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `symptom_category`
--
ALTER TABLE `symptom_category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `appointment_patient`
--
ALTER TABLE `appointment_patient`
  ADD CONSTRAINT `fk_appointment_doctor` FOREIGN KEY (`doctor_id`) REFERENCES `doctor_list` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_appointment_hospital` FOREIGN KEY (`hospital_id`) REFERENCES `hospital_list` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_appointment_patient` FOREIGN KEY (`patient_id`) REFERENCES `patient_list` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `blog_posts`
--
ALTER TABLE `blog_posts`
  ADD CONSTRAINT `blog_posts_ibfk_1` FOREIGN KEY (`hospital_id`) REFERENCES `hospital_list` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `blog_ratings`
--
ALTER TABLE `blog_ratings`
  ADD CONSTRAINT `blog_ratings_ibfk_1` FOREIGN KEY (`blog_id`) REFERENCES `blog_posts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `blog_ratings_ibfk_2` FOREIGN KEY (`patient_id`) REFERENCES `patient_list` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `chat_messages`
--
ALTER TABLE `chat_messages`
  ADD CONSTRAINT `chat_messages_ibfk_1` FOREIGN KEY (`request_hospital_id`) REFERENCES `sample_request_hospitals` (`id`),
  ADD CONSTRAINT `fk_chat_admin` FOREIGN KEY (`admin_id`) REFERENCES `admin` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_chat_patient` FOREIGN KEY (`patient_id`) REFERENCES `patient_list` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `doctor_list`
--
ALTER TABLE `doctor_list`
  ADD CONSTRAINT `doctor_list_ibfk_1` FOREIGN KEY (`hospital_id`) REFERENCES `hospital_list` (`id`);

--
-- Constraints for table `doctor_review`
--
ALTER TABLE `doctor_review`
  ADD CONSTRAINT `fk_dr_doctor` FOREIGN KEY (`doctor_id`) REFERENCES `doctor_list` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_dr_hospital` FOREIGN KEY (`hospital_id`) REFERENCES `hospital_list` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_dr_patient` FOREIGN KEY (`patient_id`) REFERENCES `patient_list` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `hospital_review`
--
ALTER TABLE `hospital_review`
  ADD CONSTRAINT `fk_hr_hospital_fk` FOREIGN KEY (`hospital_id`) REFERENCES `hospital_list` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_hr_patient_fk` FOREIGN KEY (`patient_id`) REFERENCES `patient_list` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `medicine_reminders`
--
ALTER TABLE `medicine_reminders`
  ADD CONSTRAINT `medicine_reminders_ibfk_1` FOREIGN KEY (`patient_id`) REFERENCES `patient_list` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `patient_allergies`
--
ALTER TABLE `patient_allergies`
  ADD CONSTRAINT `patient_allergies_ibfk_1` FOREIGN KEY (`patient_id`) REFERENCES `patient_list` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `prescription_list`
--
ALTER TABLE `prescription_list`
  ADD CONSTRAINT `prescription_list_ibfk_1` FOREIGN KEY (`patient_id`) REFERENCES `patient_list` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `prescription_list_ibfk_2` FOREIGN KEY (`doctor_id`) REFERENCES `doctor_list` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `prescription_list_ibfk_3` FOREIGN KEY (`hospital_id`) REFERENCES `hospital_list` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `sample_requests`
--
ALTER TABLE `sample_requests`
  ADD CONSTRAINT `sample_requests_ibfk_1` FOREIGN KEY (`patient_id`) REFERENCES `patient_list` (`id`);

--
-- Constraints for table `sample_request_hospitals`
--
ALTER TABLE `sample_request_hospitals`
  ADD CONSTRAINT `sample_request_hospitals_ibfk_1` FOREIGN KEY (`request_id`) REFERENCES `sample_requests` (`id`),
  ADD CONSTRAINT `sample_request_hospitals_ibfk_2` FOREIGN KEY (`hospital_id`) REFERENCES `hospital_list` (`id`);

--
-- Constraints for table `sample_tests`
--
ALTER TABLE `sample_tests`
  ADD CONSTRAINT `fk_sample_tests_admin` FOREIGN KEY (`admin_id`) REFERENCES `admin` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
