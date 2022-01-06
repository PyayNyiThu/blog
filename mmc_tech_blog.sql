-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 06, 2022 at 04:49 PM
-- Server version: 10.4.14-MariaDB
-- PHP Version: 7.3.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `mmc_tech_blog`
--

-- --------------------------------------------------------

--
-- Table structure for table `articles`
--

CREATE TABLE `articles` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `slug` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `title` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `image` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `articles`
--

INSERT INTO `articles` (`id`, `user_id`, `category_id`, `slug`, `title`, `image`, `description`) VALUES
(1, 1, 1, 'php-1641483542', 'Php', 'assets/article/php.png', 'This book teaches you web application development with PHP from scratch, in Burmese. Using PHP 8 and covering from basic to advanced PHP features. Also include related technologies such as Composer and MySQL Database, as well as some essential OOP Design Patterns, which will be useful in continuing learning Laravel or any other web application development frameworks.'),
(2, 1, 1, 'bootstrap-1641483406', 'Bootstrap', 'assets/article/bootstrap.png', 'An absolute beginner guide book on web development in Burmese. First, this book introduce HTML and CSS from scratch. And then you can learn Bootstrap CSS Framework to quickly create responsive web page layouts and app UI. Bootstrap Components, Layouts, Utilities and Icons are all covered in this book. A tutorial to create simple Admin Dashboard UI is also included.'),
(3, 1, 1, 'javascript-1641483467', 'Javascript', 'assets/article/jsbook.png', 'This book is designed to teach not only JavaScript programming language, but also the fundamental of programming in Burmese. Starting from basic syntaxs including variables, data types, control structures and functions, as well as intermediate knowledge such as async programming, object-oriented programming and modules. DOM Manipulation with JavaScript is also included with some practical examples.'),
(4, 1, 2, 'laravel-1641483587', 'Laravel', 'assets/article/laravel.png', ' A short, practical and easy to understand guide book on Laravel PHP framework, written in Burmese. This book teach you basic framework features such as Routing and Model-View-Controller pattern, as well as some intermediate features such as Migration, ORM Relationship, Authentication, Authorization and basic API. You will learn all of them by creating a sample project.'),
(5, 1, 1, 'react-1641483653', 'React', 'assets/article/react.png', 'A short, practical and easy-to-understand guide book, written in Burmese to get start with React JavaScript framework. This book teaches you basic and essential React concepts such as component data flow, composition and functional components, as well as related technologies such as ES6, Promises, React Router, Redux, React Native and Next.js.'),
(6, 1, 2, 'api-1641483694', 'Api', 'assets/article/api.png', 'An easy-to-understand book, written in Burmese, to learn API design and development. This book covered core concepts and best practices for REST API design such as HTTP Requests, HTTP Responses, RESTful API design, JSON Structure, CORS and API Authentications. MongoDB, Node and Express are also included to demonstrate and create a sample API project.'),
(7, 1, 2, 'rockstar-developer-1641483849', 'Rockstar Developer', 'assets/article/rsd.png', 'An advanced-level book, written in Burmese, about agile project management, development process management (git SCM, bugs tracking, package management, build automation), and back-end architecture (nginx, docker, etc...). You will also learn development stack consists of BackboneJS, NodeJS, MongoDB and ExpressJS in this book.');

-- --------------------------------------------------------

--
-- Table structure for table `article_comments`
--

CREATE TABLE `article_comments` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `article_id` int(11) NOT NULL,
  `comment` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `article_comments`
--

INSERT INTO `article_comments` (`id`, `user_id`, `article_id`, `comment`) VALUES
(1, 1, 1, 'This is a good book'),
(2, 2, 7, 'This is a good book for developer'),
(3, 2, 1, 'Yeah!. I like this book');

-- --------------------------------------------------------

--
-- Table structure for table `article_language`
--

CREATE TABLE `article_language` (
  `id` int(11) NOT NULL,
  `article_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `article_language`
--

INSERT INTO `article_language` (`id`, `article_id`, `language_id`) VALUES
(8, 2, 1),
(9, 2, 2),
(10, 2, 3),
(11, 2, 4),
(12, 3, 1),
(13, 3, 2),
(14, 3, 5),
(15, 1, 1),
(16, 1, 2),
(17, 1, 6),
(18, 4, 6),
(19, 4, 7),
(20, 5, 5),
(21, 5, 8),
(28, 7, 1),
(29, 7, 2),
(30, 7, 3),
(31, 7, 4),
(32, 7, 5),
(33, 7, 6);

-- --------------------------------------------------------

--
-- Table structure for table `article_likes`
--

CREATE TABLE `article_likes` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `article_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `article_likes`
--

INSERT INTO `article_likes` (`id`, `user_id`, `article_id`) VALUES
(1, 1, 7),
(2, 1, 6),
(3, 1, 5),
(4, 1, 4),
(5, 1, 3),
(6, 1, 2),
(7, 1, 1),
(8, 2, 7),
(9, 2, 4);

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `id` int(11) NOT NULL,
  `slug` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(191) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`id`, `slug`, `name`) VALUES
(1, 'frontend', 'Frontend'),
(2, 'backend', 'Backend');

-- --------------------------------------------------------

--
-- Table structure for table `languages`
--

CREATE TABLE `languages` (
  `id` int(11) NOT NULL,
  `slug` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(191) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `languages`
--

INSERT INTO `languages` (`id`, `slug`, `name`) VALUES
(1, 'html', 'HTML'),
(2, 'css', 'CSS'),
(3, 'jquery', 'JQuery'),
(4, 'bootstrap', 'Bootstrap'),
(5, 'js', 'Javascript'),
(6, 'php', 'Php'),
(7, 'laravel', 'Laravel'),
(8, 'react', 'React');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `slug` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `image` varchar(191) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `slug`, `email`, `password`, `image`) VALUES
(1, 'aung aung', 'aung-aung-1641481286', 'aungaung@gmail.com', '$2y$10$zjmOCgH4faHP6CKDRnTV..S7iMemFCtIpPm0Km1KTDIcLTjutSALW', 'assets/user/user.png'),
(2, 'mg mg', 'mg-mg-1641483992', 'mgmg@gmail.com', '$2y$10$ARLYzkuE9uImBDPfiQU63u6jj5aGB9CZ7oBRB3Lu0XqjMetLUhf4a', 'assets/user/received_990983317724934.jpg');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `articles`
--
ALTER TABLE `articles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `article_comments`
--
ALTER TABLE `article_comments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `article_language`
--
ALTER TABLE `article_language`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `article_likes`
--
ALTER TABLE `article_likes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `languages`
--
ALTER TABLE `languages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `articles`
--
ALTER TABLE `articles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `article_comments`
--
ALTER TABLE `article_comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `article_language`
--
ALTER TABLE `article_language`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `article_likes`
--
ALTER TABLE `article_likes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `languages`
--
ALTER TABLE `languages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
