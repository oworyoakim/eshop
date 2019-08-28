-- phpMyAdmin SQL Dump
-- version 4.7.9
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Sep 23, 2018 at 08:27 PM
-- Server version: 5.7.21
-- PHP Version: 7.1.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `eshop`
--

-- --------------------------------------------------------

--
-- Table structure for table `activations`
--

DROP TABLE IF EXISTS `activations`;
CREATE TABLE IF NOT EXISTS `activations` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int(10) UNSIGNED NOT NULL,
  `code` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `completed` tinyint(1) NOT NULL DEFAULT '0',
  `completed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `activations`
--

INSERT INTO `activations` (`id`, `user_id`, `code`, `completed`, `completed_at`, `created_at`, `updated_at`) VALUES
(1, 1, 'yTKN2ffV580WLSa9C7OEXkTlilfXEgXw', 1, '2018-09-16 18:05:34', '2018-09-16 18:05:34', '2018-09-16 18:05:34'),
(2, 2, 'otlMuCsmOQh3ioxPgRVwWA3Xl8svzmi7', 1, '2018-09-16 18:05:34', '2018-09-16 18:05:34', '2018-09-16 18:05:34'),
(5, 5, 'nDMbaB6qTLS4oV62la8sjEonwC6AtxAK', 1, '2018-09-21 11:12:30', '2018-09-21 11:12:30', '2018-09-21 11:12:30');

-- --------------------------------------------------------

--
-- Table structure for table `branches`
--

DROP TABLE IF EXISTS `branches`;
CREATE TABLE IF NOT EXISTS `branches` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `business_id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `city` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `country` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `manager_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `manager_address` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `manager_phone` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `manager_email` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `businesses`
--

DROP TABLE IF EXISTS `businesses`;
CREATE TABLE IF NOT EXISTS `businesses` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `city` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `country` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `personnel_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `personnel_address` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `personnel_phone` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `personnel_email` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subdomain` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `logo` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `website` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `subdomain` (`subdomain`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
CREATE TABLE IF NOT EXISTS `categories` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `business_id` int(10) UNSIGNED NOT NULL,
  `branch_id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `title` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `product_categories_slug_unique` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

DROP TABLE IF EXISTS `customers`;
CREATE TABLE IF NOT EXISTS `customers` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `business_id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `expenses`
--

DROP TABLE IF EXISTS `expenses`;
CREATE TABLE IF NOT EXISTS `expenses` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `barcode` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `comment` text COLLATE utf8mb4_unicode_ci,
  `expense_type_id` int(10) UNSIGNED NOT NULL,
  `business_id` int(10) UNSIGNED NOT NULL,
  `branch_id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `status` enum('pending','completed','canceled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'completed',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `expense_types`
--

DROP TABLE IF EXISTS `expense_types`;
CREATE TABLE IF NOT EXISTS `expense_types` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `business_id` int(10) UNSIGNED NOT NULL,
  `branch_id` int(10) UNSIGNED DEFAULT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `expense_types`
--

INSERT INTO `expense_types` (`id`, `title`, `description`, `business_id`, `branch_id`, `user_id`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Transport', 'Transport', 1, NULL, 5, '2018-09-21 20:40:45', '2018-09-21 20:40:45', NULL),
(2, 'Rent', 'Rent', 1, NULL, 5, '2018-09-21 21:03:24', '2018-09-21 21:03:24', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=29 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(14, '2014_07_02_230147_migration_cartalyst_sentinel', 1),
(15, '2018_09_16_101320_create_permissions_table', 1),
(16, '2018_09_16_101330_create_settings_table', 1),
(17, '2018_09_16_101340_create_categories_table', 1),
(18, '2018_09_16_101343_create_products_table', 1),
(19, '2018_09_16_101350_create_expense_types_table', 1),
(20, '2018_09_16_101355_create_expenses_table', 1),
(21, '2018_09_16_101500_create_stocks_table', 1),
(22, '2018_09_16_101520_create_product_units_table', 1),
(23, '2018_09_16_112643_create_suppliers_table', 1),
(24, '2018_09_16_112713_create_customers_table', 1),
(25, '2018_09_16_113143_create_businesses_table', 1),
(26, '2018_09_16_113633_create_business_branches_table', 1),
(27, '2018_09_21_142230_create_sales_table', 2),
(28, '2018_09_21_142900_create_purchases_table', 2);

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

DROP TABLE IF EXISTS `permissions`;
CREATE TABLE IF NOT EXISTS `permissions` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) NOT NULL DEFAULT '0',
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=81 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `parent_id`, `name`, `slug`, `description`) VALUES
(1, 0, 'Products', 'products', 'Access Products Module'),
(2, 1, 'View products', 'products.view', 'View Products'),
(3, 1, 'Update products', 'products.update', 'Update Products'),
(4, 1, 'Delete products', 'products.delete', 'Delete Products'),
(5, 1, 'Create products', 'products.create', 'Add Products'),
(6, 0, 'Product Categories', 'products.categories', 'Access Product Categories Module'),
(7, 6, 'View Product Categories', 'products.categories.view', 'Product Categories'),
(8, 6, 'Update Product Categories', 'products.categories.update', 'Update Product Categories'),
(9, 6, 'Delete Product Categories', 'products.categories.delete', 'Delete Product Categories'),
(10, 6, 'Create Product Categories', 'products.categories.create', 'Add Product Categories'),
(11, 0, 'Expenses', 'expenses', 'Access Expenses Module'),
(12, 11, 'View Expenses', 'expenses.view', 'View Expenses'),
(13, 11, 'Create Expenses', 'expenses.create', 'Create Expenses'),
(14, 11, 'Update Expenses', 'expenses.update', 'Update Expenses'),
(15, 11, 'Delete Expenses', 'expenses.delete', 'Delete Expenses'),
(16, 0, 'Reports', 'reports', 'Access Reports Module'),
(17, 0, 'Communication', 'communication', 'Access Communication Module'),
(18, 17, 'Create Communication', 'communication.create', 'Send Emails & SMS'),
(19, 17, 'Delete Communication', 'communication.delete', 'Delete Communication'),
(20, 0, 'Users', 'users', 'Access Users Module'),
(21, 20, 'View Users', 'users.view', 'View Users '),
(22, 20, 'Create Users', 'users.create', 'Create users'),
(23, 20, 'Update Users', 'users.update', 'Update Users'),
(24, 20, 'Delete Users', 'users.delete', 'Delete Users'),
(25, 20, 'Manage Roles', 'users.roles', 'Manage user roles'),
(26, 0, 'Settings', 'settings', 'Manage Settings'),
(27, 0, 'Audit Trail', 'audit_trail', 'Access Audit Trail'),
(28, 20, 'Manage Permissions', 'users.permissions', 'Manage user permissions'),
(29, 0, 'Manage Businesses', 'businesses', 'Manage Businesses Module'),
(30, 29, 'View Businesses', 'businesses.view', 'View Businesses'),
(31, 29, 'Create Businesses', 'businesses.create', 'Create Businesses'),
(32, 29, 'Update Businesses', 'businesses.update', 'Update Businesses'),
(33, 29, 'Show Businesses', 'businesses.show', 'Show Businesses'),
(34, 29, 'Delete Businesses', 'businesses.delete', 'Delete Businesses'),
(35, 0, 'Admin Dashboard', 'admin.dashboard', 'View Admin Dashboard'),
(36, 0, 'Manager Dashboard', 'manager.dashboard', 'View Manager Dashboard'),
(37, 0, 'Cashier Dashboard', 'cashier.dashboard', 'View Cashier Dashboard'),
(38, 0, 'Product Units', 'products.units', 'Manage Product Units'),
(39, 38, 'Create Product Units', 'products.units.create', 'Create Product Units'),
(40, 38, 'View Product Units', 'products.units.view', 'View Product Units'),
(41, 38, 'Update Product Units', 'products.units.update', 'Update Product Units'),
(42, 38, 'Delete Product Units', 'products.units.delete', 'Delete Product Units'),
(43, 0, 'Employees', 'employees', 'Manage Employees'),
(44, 43, 'View Employees', 'employees.view', 'View Employees'),
(45, 43, 'Create Employees', 'employees.create', 'Create Employees'),
(46, 43, 'Update Employees', 'employees.update', 'Update Employees'),
(47, 43, 'Show Employees', 'employees.show', 'Show Employees'),
(48, 43, 'Delete Employees', 'employees.delete', 'Delete Employees'),
(49, 0, 'Sales', 'sales', 'Manage Sales'),
(50, 49, 'View Sales', 'sales.view', 'View Sales'),
(51, 49, 'Create Sales', 'sales.create', 'Create Sales'),
(52, 49, 'Update Sales', 'sales.update', 'Update Sales'),
(53, 49, 'Show Sales', 'sales.show', 'Show Sales'),
(54, 49, 'Delete Sales', 'sales.delete', 'Delete Sales'),
(55, 0, 'Purchases', 'purchases', 'Manage Purchases'),
(56, 55, 'View Purchases', 'purchases.view', 'View Purchases'),
(57, 55, 'Create Purchases', 'purchases.create', 'Create Purchases'),
(58, 55, 'Update Purchases', 'purchases.update', 'Update Purchases'),
(59, 55, 'Show Purchases', 'purchases.show', 'Show Purchases'),
(60, 55, 'Delete Purchases', 'purchases.delete', 'Delete Purchases'),
(61, 0, 'Suppliers', 'suppliers', 'Manage Suppliers'),
(62, 61, 'View Suppliers', 'suppliers.view', 'View Suppliers'),
(63, 61, 'Create Suppliers', 'suppliers.create', 'Create Suppliers'),
(64, 61, 'Update Suppliers', 'suppliers.update', 'Update Suppliers'),
(65, 61, 'Show Suppliers', 'suppliers.show', 'Show Suppliers'),
(66, 61, 'Delete Suppliers', 'suppliers.delete', 'Delete Suppliers'),
(67, 0, 'Customers', 'customers', 'Manage Customers'),
(68, 67, 'View Customers', 'customers.view', 'View Customers'),
(69, 67, 'Create Customers', 'customers.create', 'Create Customers'),
(70, 67, 'Update Customers', 'customers.update', 'Update Customers'),
(71, 67, 'Show Customers', 'customers.show', 'Show Customers'),
(72, 67, 'Delete Customers', 'customers.delete', 'Delete Customers'),
(73, 11, 'Show Expenses', 'expenses.show', 'Show Expenses'),
(74, 11, 'Manage Expense Types', 'expenses.types', 'Manage Expense Types'),
(75, 0, 'Branches', 'branches', 'Manage Branches'),
(76, 75, 'View Branches', 'branches.view', 'View Branches'),
(77, 75, 'Create Branches', 'branches.create', 'Create Branches'),
(78, 75, 'Update Branches', 'branches.update', 'Update Branches'),
(79, 75, 'Show Branches', 'branches.show', 'Show Branches'),
(80, 75, 'Delete Branches', 'branches.delete', 'Delete Branches');

-- --------------------------------------------------------

--
-- Table structure for table `persistences`
--

DROP TABLE IF EXISTS `persistences`;
CREATE TABLE IF NOT EXISTS `persistences` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int(10) UNSIGNED NOT NULL,
  `code` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `persistences_code_unique` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `persistences`
--

INSERT INTO `persistences` (`id`, `user_id`, `code`, `created_at`, `updated_at`) VALUES
(27, 1, 'FQOkU8Rhqi8jw7SwoezSGcCV2VuMKF78', '2018-09-21 11:57:45', '2018-09-21 11:57:45'),
(28, 5, '2jAz0XDjXQ7YY2x0QZvoZ7ZcNbG5gUcP', '2018-09-21 12:44:33', '2018-09-21 12:44:33'),
(29, 5, 'NgAmyhwwkXNHVlx1uRuOB4c4cZd8AfVa', '2018-09-21 18:09:30', '2018-09-21 18:09:30'),
(30, 1, 'SgA1yNbuLPiO5SBc9TqRqU7PO9Tc04Vr', '2018-09-21 19:14:44', '2018-09-21 19:14:44'),
(31, 5, 'heR8qEMS0Y9wbgbBom2gHZGz4yiVR76L', '2018-09-22 14:43:32', '2018-09-22 14:43:32');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
CREATE TABLE IF NOT EXISTS `products` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `barcode` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `avatar` text COLLATE utf8mb4_unicode_ci,
  `category_id` int(10) UNSIGNED NOT NULL,
  `business_id` int(10) UNSIGNED NOT NULL,
  `branch_id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `products_barcode_unique` (`barcode`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_units`
--

DROP TABLE IF EXISTS `product_units`;
CREATE TABLE IF NOT EXISTS `product_units` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `user_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `product_units_slug_unique` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_units`
--

INSERT INTO `product_units` (`id`, `title`, `slug`, `description`, `user_id`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Kilograms', 'kgs', 'Kilograms (1000 grams)', 1, '2018-09-17 19:51:14', '2018-09-19 09:04:17', NULL),
(2, 'Pieces', 'pcs', 'Pieces', 1, '2018-09-17 19:52:24', '2018-09-20 20:33:20', NULL),
(3, 'Packets', 'pkts', 'Packets', 1, '2018-09-17 19:52:51', '2018-09-17 19:52:51', NULL),
(4, 'Litres', 'ltrs', 'Litres', 1, '2018-09-17 19:55:57', '2018-09-17 19:55:57', NULL),
(5, 'Bars', 'bars', 'Bars', 1, '2018-09-17 19:56:11', '2018-09-17 19:56:11', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `purchases`
--

DROP TABLE IF EXISTS `purchases`;
CREATE TABLE IF NOT EXISTS `purchases` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `product_id` int(10) UNSIGNED NOT NULL,
  `supplier_id` int(10) UNSIGNED NOT NULL,
  `quantity` decimal(10,2) NOT NULL DEFAULT '0.00',
  `amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `tax` decimal(15,2) NOT NULL DEFAULT '0.00',
  `discount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `transcode` bigint(20) UNSIGNED NOT NULL,
  `status` enum('pending','completed','canceled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'completed',
  `business_id` int(10) UNSIGNED NOT NULL,
  `branch_id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reminders`
--

DROP TABLE IF EXISTS `reminders`;
CREATE TABLE IF NOT EXISTS `reminders` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int(10) UNSIGNED NOT NULL,
  `code` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `completed` tinyint(1) NOT NULL DEFAULT '0',
  `completed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
CREATE TABLE IF NOT EXISTS `roles` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `slug` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `permissions` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_slug_unique` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `slug`, `name`, `permissions`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'admin', 'Admin', '{\"products\":true,\"products.view\":true,\"products.update\":true,\"products.delete\":true,\"products.create\":true,\"products.categories\":true,\"products.categories.view\":true,\"products.categories.update\":true,\"products.categories.delete\":true,\"products.categories.create\":true,\"expenses\":true,\"expenses.view\":true,\"expenses.create\":true,\"expenses.update\":true,\"expenses.delete\":true,\"reports\":true,\"communication\":true,\"communication.create\":true,\"communication.delete\":true,\"users\":true,\"users.view\":true,\"users.create\":true,\"users.update\":true,\"users.delete\":true,\"users.roles\":true,\"users.permissions\":true,\"settings\":true,\"audit_trail\":true,\"businesses\":true,\"businesses.view\":true,\"businesses.create\":true,\"businesses.update\":true,\"businesses.show\":true,\"businesses.delete\":true,\"admin.dashboard\":true,\"products.units\":true,\"products.units.create\":true,\"products.units.view\":true,\"products.units.update\":true,\"products.units.delete\":true}', '2018-09-16 21:25:56', '2018-09-17 00:01:57', NULL),
(2, 'businessmanager', 'Business Manager', '{\"products\":true,\"products.view\":true,\"products.update\":true,\"products.delete\":true,\"products.create\":true,\"products.categories\":true,\"products.categories.view\":true,\"products.categories.update\":true,\"products.categories.delete\":true,\"products.categories.create\":true,\"expenses\":true,\"expenses.view\":true,\"expenses.create\":true,\"expenses.update\":true,\"expenses.delete\":true,\"expenses.show\":true,\"expenses.types\":true,\"reports\":true,\"communication\":true,\"communication.create\":true,\"communication.delete\":true,\"users\":true,\"users.view\":true,\"users.create\":true,\"manager.dashboard\":true,\"employees\":true,\"employees.view\":true,\"employees.create\":true,\"employees.update\":true,\"employees.show\":true,\"employees.delete\":true,\"sales\":true,\"sales.view\":true,\"sales.create\":true,\"sales.update\":true,\"sales.show\":true,\"sales.delete\":true,\"purchases\":true,\"purchases.view\":true,\"purchases.create\":true,\"purchases.update\":true,\"purchases.show\":true,\"purchases.delete\":true,\"suppliers\":true,\"suppliers.view\":true,\"suppliers.create\":true,\"suppliers.update\":true,\"suppliers.show\":true,\"suppliers.delete\":true,\"customers\":true,\"customers.view\":true,\"customers.create\":true,\"customers.update\":true,\"customers.show\":true,\"customers.delete\":true,\"branches\":true,\"branches.view\":true,\"branches.create\":true,\"branches.update\":true,\"branches.show\":true,\"branches.delete\":true}', '2018-09-16 21:25:56', '2018-09-21 21:11:56', NULL),
(3, 'cashier', 'Cashier', '{\"products.view\":true,\"products.categories.view\":true,\"expenses.view\":true,\"expenses.create\":true,\"expenses.update\":true,\"cashier.dashboard\":true}', '2018-09-16 21:25:56', '2018-09-16 21:16:08', NULL),
(4, 'branchmanager', 'Branch Manager', '{\"products\":true,\"products.view\":true,\"products.update\":true,\"products.create\":true,\"products.categories\":true,\"products.categories.view\":true,\"products.categories.update\":true,\"products.categories.create\":true,\"expenses\":true,\"expenses.view\":true,\"expenses.create\":true,\"expenses.update\":true,\"expenses.delete\":true,\"reports\":true,\"communication.create\":true,\"communication.delete\":true,\"users.view\":true,\"users.create\":true,\"users.update\":true,\"manager.dashboard\":true}', '2018-09-16 21:25:56', '2018-09-16 21:25:56', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `role_users`
--

DROP TABLE IF EXISTS `role_users`;
CREATE TABLE IF NOT EXISTS `role_users` (
  `user_id` int(10) UNSIGNED NOT NULL,
  `role_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`user_id`,`role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `role_users`
--

INSERT INTO `role_users` (`user_id`, `role_id`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, '2018-09-16 18:05:34', '2018-09-16 18:05:34', NULL),
(2, 2, '2018-09-16 18:05:34', '2018-09-16 18:05:34', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `sales`
--

DROP TABLE IF EXISTS `sales`;
CREATE TABLE IF NOT EXISTS `sales` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `product_id` int(10) UNSIGNED NOT NULL,
  `customer_id` int(10) UNSIGNED NOT NULL,
  `quantity` decimal(10,2) NOT NULL DEFAULT '0.00',
  `amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `tax` decimal(15,2) NOT NULL DEFAULT '0.00',
  `discount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `transcode` bigint(20) UNSIGNED NOT NULL,
  `status` enum('pending','completed','canceled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'completed',
  `business_id` int(10) UNSIGNED NOT NULL,
  `branch_id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

DROP TABLE IF EXISTS `settings`;
CREATE TABLE IF NOT EXISTS `settings` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `setting_key` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `setting_value` text COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`id`),
  UNIQUE KEY `settings_setting_key_unique` (`setting_key`)
) ENGINE=MyISAM AUTO_INCREMENT=39 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `setting_key`, `setting_value`) VALUES
(1, 'company_name', 'eShop'),
(2, 'company_address', 'http://www.eshop.com'),
(3, 'company_currency', 'UGX'),
(4, 'company_website', 'http://www.eshop.kim'),
(5, 'company_country', 'UGANDA'),
(6, 'system_version', '1.0'),
(9, 'portal_address', 'P.O BOX 7066, KAMPALA'),
(10, 'company_email', 'info@eshop.kim'),
(11, 'currency_symbol', 'UGX'),
(12, 'currency_position', 'left'),
(13, 'company_logo', 'logo.png'),
(25, 'paypal_email', 'info@eshop.kim'),
(26, 'currency', 'USD'),
(27, 'password_reset_subject', 'Password reset instructions'),
(28, 'password_reset_template', 'Password reset instructions'),
(29, 'cron_last_run', '2017-10-18 12:01:22'),
(30, 'enable_cron', '0'),
(31, 'allow_self_registration', '0'),
(32, 'allow_client_login', '1'),
(33, 'welcome_note', 'Welcome to our company. You can login with your username and password'),
(34, 'allow_client_apply', '1'),
(35, 'enable_online_payment', '1'),
(36, 'paypal_enabled', '1'),
(37, 'paynow_enabled', '1'),
(38, 'company_otheremail', 'support@eshop.kim');

-- --------------------------------------------------------

--
-- Table structure for table `stocks`
--

DROP TABLE IF EXISTS `stocks`;
CREATE TABLE IF NOT EXISTS `stocks` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `product_id` int(10) UNSIGNED NOT NULL,
  `supplier_id` int(10) UNSIGNED NOT NULL,
  `expiry_date` timestamp NOT NULL,
  `cost_price` decimal(15,2) NOT NULL DEFAULT '0.00',
  `sell_price` decimal(15,2) NOT NULL DEFAULT '0.00',
  `quantity` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `unit_id` int(10) UNSIGNED NOT NULL,
  `business_id` int(10) UNSIGNED NOT NULL,
  `branch_id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `suppliers`
--

DROP TABLE IF EXISTS `suppliers`;
CREATE TABLE IF NOT EXISTS `suppliers` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `country` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `business_id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `throttle`
--

DROP TABLE IF EXISTS `throttle`;
CREATE TABLE IF NOT EXISTS `throttle` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int(10) UNSIGNED DEFAULT NULL,
  `type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ip` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `throttle_user_id_index` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `throttle`
--

INSERT INTO `throttle` (`id`, `user_id`, `type`, `ip`, `created_at`, `updated_at`) VALUES
(1, NULL, 'global', NULL, '2018-09-16 18:05:51', '2018-09-16 18:05:51'),
(2, NULL, 'ip', '127.0.0.1', '2018-09-16 18:05:51', '2018-09-16 18:05:51'),
(3, NULL, 'global', NULL, '2018-09-16 18:06:16', '2018-09-16 18:06:16'),
(4, NULL, 'ip', '127.0.0.1', '2018-09-16 18:06:16', '2018-09-16 18:06:16'),
(5, NULL, 'global', NULL, '2018-09-19 09:00:16', '2018-09-19 09:00:16'),
(6, NULL, 'ip', '127.0.0.1', '2018-09-19 09:00:16', '2018-09-19 09:00:16'),
(7, NULL, 'global', NULL, '2018-09-19 11:48:23', '2018-09-19 11:48:23'),
(8, NULL, 'ip', '127.0.0.1', '2018-09-19 11:48:23', '2018-09-19 11:48:23'),
(10, NULL, 'global', NULL, '2018-09-21 09:30:56', '2018-09-21 09:30:56'),
(11, NULL, 'ip', '127.0.0.1', '2018-09-21 09:30:56', '2018-09-21 09:30:56');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `first_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gender` enum('Male','Female') COLLATE utf8mb4_unicode_ci NOT NULL,
  `dob` date DEFAULT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `country` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `avatar` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `permissions` text COLLATE utf8mb4_unicode_ci,
  `last_login` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `first_name`, `last_name`, `address`, `gender`, `dob`, `email`, `phone`, `country`, `city`, `avatar`, `password`, `permissions`, `last_login`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'Admin', 'Admin', NULL, 'Male', NULL, 'admin@eshop.kim', NULL, NULL, NULL, 'admin.png', '$2y$10$WegUy12ElURNn7x1ij5c/u0nGAU1sa.bVUZreHk0supoWO2alBmGK', NULL, '2018-09-21 19:14:44', NULL, '2018-09-16 18:05:34', '2018-09-21 19:14:44'),
(2, 'Manager', 'Manager', NULL, 'Male', NULL, 'manager@eshop.kim', NULL, NULL, NULL, 'admin.png', '$2y$10$X.bGgordLIGOYgNmiDiWfewWZHhDFQU4RKW5LuzrI.wUOLAEowFT6', NULL, NULL, NULL, '2018-09-16 18:05:34', '2018-09-16 18:05:34');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
