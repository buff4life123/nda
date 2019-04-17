-- phpMyAdmin SQL Dump
-- version 4.7.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: 10-Abr-2019 às 22:01
-- Versão do servidor: 10.1.22-MariaDB
-- PHP Version: 7.0.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `project`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `about_us`
--

CREATE TABLE `about_us` (
  `id` int(11) NOT NULL,
  `locales_id` int(11) DEFAULT NULL,
  `rgpd_html` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `about_us`
--

INSERT INTO `about_us` (`id`, `locales_id`, `rgpd_html`, `name`) VALUES
(1, 1, '<h3>Sobre Nós</h3>\r\n<h6>Os nossos quartos são realmente espantosos. Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam.</h6>\r\n<p>Aceitamos: <i class=\"fa fa-credit-card w3-large\"></i> <i class=\"fa fa-cc-mastercard w3-large\"></i> <i class=\"fa fa-cc-amex w3-large\"></i> <i class=\"fa fa-cc-cc-visa w3-large\"></i><i class=\"fa fa-cc-paypal w3-large\"></i></p>', 'Sobre Nós'),
(2, 2, '<h3>About</h3>\r\n<h6>Our Bedrooms are one of a kind. It is truely amazing. Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam.</h6>\r\n<p>We accept: <i class=\"fa fa-credit-card w3-large\"></i> <i class=\"fa fa-cc-mastercard w3-large\"></i> <i class=\"fa fa-cc-amex w3-large\"></i> <i class=\"fa fa-cc-cc-visa w3-large\"></i><i class=\"fa fa-cc-paypal w3-large\"></i></p>', 'About Us');

-- --------------------------------------------------------

--
-- Estrutura da tabela `amount`
--

CREATE TABLE `amount` (
  `id` int(11) NOT NULL,
  `product_id` int(11) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '0',
  `is_child` tinyint(1) NOT NULL DEFAULT '0',
  `amount` int(11) UNSIGNED NOT NULL COMMENT '(DC2Type:money)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `amount`
--

INSERT INTO `amount` (`id`, `product_id`, `is_active`, `is_child`, `amount`) VALUES
(2, 18, 1, 0, 0);

-- --------------------------------------------------------

--
-- Estrutura da tabela `amount_translation`
--

CREATE TABLE `amount_translation` (
  `id` int(11) NOT NULL,
  `amount_id` int(11) DEFAULT NULL,
  `locales_id` int(11) DEFAULT NULL,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `available`
--

CREATE TABLE `available` (
  `id` int(11) NOT NULL,
  `datetimestart` datetime NOT NULL,
  `stock` int(11) DEFAULT NULL,
  `lotation` int(11) DEFAULT NULL,
  `datetimeend` datetime NOT NULL,
  `product_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `banner`
--

CREATE TABLE `banner` (
  `id` int(11) NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '0',
  `order_by` int(11) DEFAULT NULL,
  `text_active` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `banner`
--

INSERT INTO `banner` (`id`, `image`, `is_active`, `order_by`, `text_active`) VALUES
(33, '72cf94d1afa84a7424e30f8f5b2ba433.jpeg', 1, 3, 1),
(34, 'd2a2ba272f55cea35c31522f5166da41.jpeg', 1, 2, 0),
(36, '597762692114fed6b1d4d475fb48622f.jpeg', 1, 1, 1),
(37, '06c7df60ac3a4ad05aa7ef08ac3e2c5f.jpeg', 1, 4, 0);

-- --------------------------------------------------------

--
-- Estrutura da tabela `banner_translation`
--

CREATE TABLE `banner_translation` (
  `id` int(11) NOT NULL,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `banner_id` int(11) DEFAULT NULL,
  `locales_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `banner_translation`
--

INSERT INTO `banner_translation` (`id`, `name`, `banner_id`, `locales_id`) VALUES
(13, 'O Café', 33, 1),
(14, 'The Coffee', 33, 2),
(15, 'O Hotel', 34, 1),
(16, 'The Hotel', 34, 2),
(19, 'Nossa App', 36, 1),
(20, 'Our App', 36, 2),
(21, 'Algarve', 37, 1),
(22, 'Allgarve', 37, 2);

-- --------------------------------------------------------

--
-- Estrutura da tabela `booking`
--

CREATE TABLE `booking` (
  `id` int(11) NOT NULL,
  `adult` int(11) DEFAULT NULL,
  `children` int(11) DEFAULT NULL,
  `baby` int(11) DEFAULT NULL,
  `posted_at` date NOT NULL,
  `notes` longtext COLLATE utf8mb4_unicode_ci,
  `status` enum('pending','canceled','confirmed') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `amount` int(11) UNSIGNED NOT NULL COMMENT '(DC2Type:money)',
  `available_id` int(11) DEFAULT NULL,
  `client_id` int(11) DEFAULT NULL,
  `date_event` date NOT NULL,
  `time_event` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `category`
--

CREATE TABLE `category` (
  `id` int(11) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `category`
--

INSERT INTO `category` (`id`, `is_active`) VALUES
(2, 1),
(3, 1),
(4, 1),
(5, 1);

-- --------------------------------------------------------

--
-- Estrutura da tabela `category_translation`
--

CREATE TABLE `category_translation` (
  `id` int(11) NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `locales_id` int(11) DEFAULT NULL,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `category_translation`
--

INSERT INTO `category_translation` (`id`, `category_id`, `locales_id`, `name`) VALUES
(3, 2, 1, 'Catálogo'),
(4, 2, 2, 'Catalog'),
(5, 3, 1, 'Quartos'),
(6, 3, 2, 'Rooms'),
(7, 4, 1, 'Eventos'),
(8, 4, 2, 'Events'),
(9, 5, 1, 'Transferes'),
(10, 5, 2, 'Transfers');

-- --------------------------------------------------------

--
-- Estrutura da tabela `client`
--

CREATE TABLE `client` (
  `id` int(11) NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `telephone` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `roles` longtext COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '(DC2Type:array)',
  `rgpd` tinyint(1) NOT NULL,
  `locale_id` int(11) DEFAULT NULL,
  `cvv` longtext COLLATE utf8mb4_unicode_ci,
  `card_name` longtext COLLATE utf8mb4_unicode_ci,
  `card_nr` longtext COLLATE utf8mb4_unicode_ci,
  `card_date` longtext COLLATE utf8mb4_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `company`
--

CREATE TABLE `company` (
  `id` int(11) NOT NULL,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `p_code` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `city` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_pass` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL,
  `telephone` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fiscal_number` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `coords_google_maps` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `google_maps_api_key` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `logo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `link_facebook` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `link_twitter` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `link_instagram` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `link_linken` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `link_pinterest` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `link_facebook_active` tinyint(1) NOT NULL DEFAULT '0',
  `link_twitter_active` tinyint(1) NOT NULL DEFAULT '0',
  `link_instagram_active` tinyint(1) NOT NULL DEFAULT '0',
  `link_linken_active` tinyint(1) NOT NULL DEFAULT '0',
  `link_pinterest_active` tinyint(1) NOT NULL DEFAULT '0',
  `link_my_domain` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `link_youtube` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `link_behance` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `link_youtube_active` tinyint(1) DEFAULT NULL,
  `link_behance_active` tinyint(1) DEFAULT NULL,
  `currency_id` int(11) DEFAULT NULL,
  `country_id` int(11) DEFAULT NULL,
  `email_port` int(11) NOT NULL,
  `email_smtp` varchar(24) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_certificade` varchar(4) COLLATE utf8mb4_unicode_ci NOT NULL,
  `link_snapchat` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `link_snapchat_active` tinyint(1) DEFAULT NULL,
  `meta_keywords` longtext COLLATE utf8mb4_unicode_ci,
  `meta_description` longtext COLLATE utf8mb4_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `company`
--

INSERT INTO `company` (`id`, `name`, `address`, `p_code`, `city`, `email`, `email_pass`, `telephone`, `fiscal_number`, `coords_google_maps`, `google_maps_api_key`, `logo`, `link_facebook`, `link_twitter`, `link_instagram`, `link_linken`, `link_pinterest`, `link_facebook_active`, `link_twitter_active`, `link_instagram_active`, `link_linken_active`, `link_pinterest_active`, `link_my_domain`, `link_youtube`, `link_behance`, `link_youtube_active`, `link_behance_active`, `currency_id`, `country_id`, `email_port`, `email_smtp`, `email_certificade`, `link_snapchat`, `link_snapchat_active`, `meta_keywords`, `meta_description`) VALUES
(1, 'A Empresa', 'Av. 25Abril, nº, 999', '8600-302', 'Lagos', 'vgspedro15@sapo.pt', 'ledcpu', '00351963963963', '999000999', '37.0877204,-8.3084601', 'AIzaSyAcWwuJpAyu4XwgbPwJxexBO0fsZ2SxDk8', '94b1415d5927adad90c43214596d8fc5.png', 'https://facebook.com/', 'https://twitter.com/', 'https://instagram.com/', 'https://pt.linkedin.com/', 'https://www.pinterest.pt/', 1, 1, 1, 1, 0, 'https://omeudominio.pt', 'https://youtube.com/', 'https://www.behance.net/', 0, 0, 3, 172, 465, 'smtp.sapo.pt', 'ssl', 'https://www.snapchat.com/', 0, 'passeios maritímos, grutas, caves, algarve, grottos, cave, portugal, travel, boat trip, algarve boats, kayak, benagil kayak, kayaking, paddle, paddle, sup bookings, sup, boat, barcos, tours, passeios, quartos, rooms', 'A empresa que tem tudo no Algarve.');

-- --------------------------------------------------------

--
-- Estrutura da tabela `countries`
--

CREATE TABLE `countries` (
  `id` int(11) NOT NULL,
  `iso` varchar(2) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nicename` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `iso3` varchar(3) COLLATE utf8mb4_unicode_ci NOT NULL,
  `numcode` int(11) NOT NULL,
  `phonecode` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `countries`
--

INSERT INTO `countries` (`id`, `iso`, `name`, `nicename`, `iso3`, `numcode`, `phonecode`) VALUES
(1, 'AF', 'AFGHANISTAN', 'Afghanistan', 'AFG', 4, 93),
(2, 'AL', 'ALBANIA', 'Albania', 'ALB', 8, 355),
(3, 'DZ', 'ALGERIA', 'Algeria', 'DZA', 12, 213),
(4, 'AS', 'AMERICAN SAMOA', 'American Samoa', 'ASM', 16, 1684),
(5, 'AD', 'ANDORRA', 'Andorra', 'AND', 20, 376),
(6, 'AO', 'ANGOLA', 'Angola', 'AGO', 24, 244),
(7, 'AI', 'ANGUILLA', 'Anguilla', 'AIA', 660, 1264),
(8, 'AQ', 'ANTARCTICA', 'Antarctica', '', 0, 0),
(9, 'AG', 'ANTIGUA AND BARBUDA', 'Antigua and Barbuda', 'ATG', 28, 1268),
(10, 'AR', 'ARGENTINA', 'Argentina', 'ARG', 32, 54),
(11, 'AM', 'ARMENIA', 'Armenia', 'ARM', 51, 374),
(12, 'AW', 'ARUBA', 'Aruba', 'ABW', 533, 297),
(13, 'AU', 'AUSTRALIA', 'Australia', 'AUS', 36, 61),
(14, 'AT', 'AUSTRIA', 'Austria', 'AUT', 40, 43),
(15, 'AZ', 'AZERBAIJAN', 'Azerbaijan', 'AZE', 31, 994),
(16, 'BS', 'BAHAMAS', 'Bahamas', 'BHS', 44, 1242),
(17, 'BH', 'BAHRAIN', 'Bahrain', 'BHR', 48, 973),
(18, 'BD', 'BANGLADESH', 'Bangladesh', 'BGD', 50, 880),
(19, 'BB', 'BARBADOS', 'Barbados', 'BRB', 52, 1246),
(20, 'BY', 'BELARUS', 'Belarus', 'BLR', 112, 375),
(21, 'BE', 'BELGIUM', 'Belgium', 'BEL', 56, 32),
(22, 'BZ', 'BELIZE', 'Belize', 'BLZ', 84, 501),
(23, 'BJ', 'BENIN', 'Benin', 'BEN', 204, 229),
(24, 'BM', 'BERMUDA', 'Bermuda', 'BMU', 60, 1441),
(25, 'BT', 'BHUTAN', 'Bhutan', 'BTN', 64, 975),
(26, 'BO', 'BOLIVIA', 'Bolivia', 'BOL', 68, 591),
(27, 'BA', 'BOSNIA AND HERZEGOVINA', 'Bosnia and Herzegovina', 'BIH', 70, 387),
(28, 'BW', 'BOTSWANA', 'Botswana', 'BWA', 72, 267),
(29, 'BV', 'BOUVET ISLAND', 'Bouvet Island', '', 0, 0),
(30, 'BR', 'BRAZIL', 'Brazil', 'BRA', 76, 55),
(31, 'IO', 'BRITISH INDIAN OCEAN TERRITORY', 'British Indian Ocean Territory', '', 0, 246),
(32, 'BN', 'BRUNEI DARUSSALAM', 'Brunei Darussalam', 'BRN', 96, 673),
(33, 'BG', 'BULGARIA', 'Bulgaria', 'BGR', 100, 359),
(34, 'BF', 'BURKINA FASO', 'Burkina Faso', 'BFA', 854, 226),
(35, 'BI', 'BURUNDI', 'Burundi', 'BDI', 108, 257),
(36, 'KH', 'CAMBODIA', 'Cambodia', 'KHM', 116, 855),
(37, 'CM', 'CAMEROON', 'Cameroon', 'CMR', 120, 237),
(38, 'CA', 'CANADA', 'Canada', 'CAN', 124, 1),
(39, 'CV', 'CAPE VERDE', 'Cape Verde', 'CPV', 132, 238),
(40, 'KY', 'CAYMAN ISLANDS', 'Cayman Islands', 'CYM', 136, 1345),
(41, 'CF', 'CENTRAL AFRICAN REPUBLIC', 'Central African Republic', 'CAF', 140, 236),
(42, 'TD', 'CHAD', 'Chad', 'TCD', 148, 235),
(43, 'CL', 'CHILE', 'Chile', 'CHL', 152, 56),
(44, 'CN', 'CHINA', 'China', 'CHN', 156, 86),
(45, 'CX', 'CHRISTMAS ISLAND', 'Christmas Island', '', 0, 61),
(46, 'CC', 'COCOS (KEELING) ISLANDS', 'Cocos (Keeling) Islands', '', 0, 672),
(47, 'CO', 'COLOMBIA', 'Colombia', 'COL', 170, 57),
(48, 'KM', 'COMOROS', 'Comoros', 'COM', 174, 269),
(49, 'CG', 'CONGO', 'Congo', 'COG', 178, 242),
(50, 'CD', 'CONGO, THE DEMOCRATIC REPUBLIC OF THE', 'Congo, the Democratic Republic of the', 'COD', 180, 242),
(51, 'CK', 'COOK ISLANDS', 'Cook Islands', 'COK', 184, 682),
(52, 'CR', 'COSTA RICA', 'Costa Rica', 'CRI', 188, 506),
(53, 'CI', 'COTE D\'IVOIRE', 'Cote D\'Ivoire', 'CIV', 384, 225),
(54, 'HR', 'CROATIA', 'Croatia', 'HRV', 191, 385),
(55, 'CU', 'CUBA', 'Cuba', 'CUB', 192, 53),
(56, 'CY', 'CYPRUS', 'Cyprus', 'CYP', 196, 357),
(57, 'CZ', 'CZECH REPUBLIC', 'Czech Republic', 'CZE', 203, 420),
(58, 'DK', 'DENMARK', 'Denmark', 'DNK', 208, 45),
(59, 'DJ', 'DJIBOUTI', 'Djibouti', 'DJI', 262, 253),
(60, 'DM', 'DOMINICA', 'Dominica', 'DMA', 212, 1767),
(61, 'DO', 'DOMINICAN REPUBLIC', 'Dominican Republic', 'DOM', 214, 1809),
(62, 'EC', 'ECUADOR', 'Ecuador', 'ECU', 218, 593),
(63, 'EG', 'EGYPT', 'Egypt', 'EGY', 818, 20),
(64, 'SV', 'EL SALVADOR', 'El Salvador', 'SLV', 222, 503),
(65, 'GQ', 'EQUATORIAL GUINEA', 'Equatorial Guinea', 'GNQ', 226, 240),
(66, 'ER', 'ERITREA', 'Eritrea', 'ERI', 232, 291),
(67, 'EE', 'ESTONIA', 'Estonia', 'EST', 233, 372),
(68, 'ET', 'ETHIOPIA', 'Ethiopia', 'ETH', 231, 251),
(69, 'FK', 'FALKLAND ISLANDS (MALVINAS)', 'Falkland Islands (Malvinas)', 'FLK', 238, 500),
(70, 'FO', 'FAROE ISLANDS', 'Faroe Islands', 'FRO', 234, 298),
(71, 'FJ', 'FIJI', 'Fiji', 'FJI', 242, 679),
(72, 'FI', 'FINLAND', 'Finland', 'FIN', 246, 358),
(73, 'FR', 'FRANCE', 'France', 'FRA', 250, 33),
(74, 'GF', 'FRENCH GUIANA', 'French Guiana', 'GUF', 254, 594),
(75, 'PF', 'FRENCH POLYNESIA', 'French Polynesia', 'PYF', 258, 689),
(76, 'TF', 'FRENCH SOUTHERN TERRITORIES', 'French Southern Territories', '', 0, 0),
(77, 'GA', 'GABON', 'Gabon', 'GAB', 266, 241),
(78, 'GM', 'GAMBIA', 'Gambia', 'GMB', 270, 220),
(79, 'GE', 'GEORGIA', 'Georgia', 'GEO', 268, 995),
(80, 'DE', 'GERMANY', 'Germany', 'DEU', 276, 49),
(81, 'GH', 'GHANA', 'Ghana', 'GHA', 288, 233),
(82, 'GI', 'GIBRALTAR', 'Gibraltar', 'GIB', 292, 350),
(83, 'GR', 'GREECE', 'Greece', 'GRC', 300, 30),
(84, 'GL', 'GREENLAND', 'Greenland', 'GRL', 304, 299),
(85, 'GD', 'GRENADA', 'Grenada', 'GRD', 308, 1473),
(86, 'GP', 'GUADELOUPE', 'Guadeloupe', 'GLP', 312, 590),
(87, 'GU', 'GUAM', 'Guam', 'GUM', 316, 1671),
(88, 'GT', 'GUATEMALA', 'Guatemala', 'GTM', 320, 502),
(89, 'GN', 'GUINEA', 'Guinea', 'GIN', 324, 224),
(90, 'GW', 'GUINEA-BISSAU', 'Guinea-Bissau', 'GNB', 624, 245),
(91, 'GY', 'GUYANA', 'Guyana', 'GUY', 328, 592),
(92, 'HT', 'HAITI', 'Haiti', 'HTI', 332, 509),
(93, 'HM', 'HEARD ISLAND AND MCDONALD ISLANDS', 'Heard Island and Mcdonald Islands', '', 0, 0),
(94, 'VA', 'HOLY SEE (VATICAN CITY STATE)', 'Holy See (Vatican City State)', 'VAT', 336, 39),
(95, 'HN', 'HONDURAS', 'Honduras', 'HND', 340, 504),
(96, 'HK', 'HONG KONG', 'Hong Kong', 'HKG', 344, 852),
(97, 'HU', 'HUNGARY', 'Hungary', 'HUN', 348, 36),
(98, 'IS', 'ICELAND', 'Iceland', 'ISL', 352, 354),
(99, 'IN', 'INDIA', 'India', 'IND', 356, 91),
(100, 'ID', 'INDONESIA', 'Indonesia', 'IDN', 360, 62),
(101, 'IR', 'IRAN, ISLAMIC REPUBLIC OF', 'Iran, Islamic Republic of', 'IRN', 364, 98),
(102, 'IQ', 'IRAQ', 'Iraq', 'IRQ', 368, 964),
(103, 'IE', 'IRELAND', 'Ireland', 'IRL', 372, 353),
(104, 'IL', 'ISRAEL', 'Israel', 'ISR', 376, 972),
(105, 'IT', 'ITALY', 'Italy', 'ITA', 380, 39),
(106, 'JM', 'JAMAICA', 'Jamaica', 'JAM', 388, 1876),
(107, 'JP', 'JAPAN', 'Japan', 'JPN', 392, 81),
(108, 'JO', 'JORDAN', 'Jordan', 'JOR', 400, 962),
(109, 'KZ', 'KAZAKHSTAN', 'Kazakhstan', 'KAZ', 398, 7),
(110, 'KE', 'KENYA', 'Kenya', 'KEN', 404, 254),
(111, 'KI', 'KIRIBATI', 'Kiribati', 'KIR', 296, 686),
(112, 'KP', 'KOREA, DEMOCRATIC PEOPLE\'S REPUBLIC OF', 'Korea, Democratic People\'s Republic of', 'PRK', 408, 850),
(113, 'KR', 'KOREA, REPUBLIC OF', 'Korea, Republic of', 'KOR', 410, 82),
(114, 'KW', 'KUWAIT', 'Kuwait', 'KWT', 414, 965),
(115, 'KG', 'KYRGYZSTAN', 'Kyrgyzstan', 'KGZ', 417, 996),
(116, 'LA', 'LAO PEOPLE\'S DEMOCRATIC REPUBLIC', 'Lao People\'s Democratic Republic', 'LAO', 418, 856),
(117, 'LV', 'LATVIA', 'Latvia', 'LVA', 428, 371),
(118, 'LB', 'LEBANON', 'Lebanon', 'LBN', 422, 961),
(119, 'LS', 'LESOTHO', 'Lesotho', 'LSO', 426, 266),
(120, 'LR', 'LIBERIA', 'Liberia', 'LBR', 430, 231),
(121, 'LY', 'LIBYAN ARAB JAMAHIRIYA', 'Libyan Arab Jamahiriya', 'LBY', 434, 218),
(122, 'LI', 'LIECHTENSTEIN', 'Liechtenstein', 'LIE', 438, 423),
(123, 'LT', 'LITHUANIA', 'Lithuania', 'LTU', 440, 370),
(124, 'LU', 'LUXEMBOURG', 'Luxembourg', 'LUX', 442, 352),
(125, 'MO', 'MACAO', 'Macao', 'MAC', 446, 853),
(126, 'MK', 'MACEDONIA, THE FORMER YUGOSLAV REPUBLIC OF', 'Macedonia, the Former Yugoslav Republic of', 'MKD', 807, 389),
(127, 'MG', 'MADAGASCAR', 'Madagascar', 'MDG', 450, 261),
(128, 'MW', 'MALAWI', 'Malawi', 'MWI', 454, 265),
(129, 'MY', 'MALAYSIA', 'Malaysia', 'MYS', 458, 60),
(130, 'MV', 'MALDIVES', 'Maldives', 'MDV', 462, 960),
(131, 'ML', 'MALI', 'Mali', 'MLI', 466, 223),
(132, 'MT', 'MALTA', 'Malta', 'MLT', 470, 356),
(133, 'MH', 'MARSHALL ISLANDS', 'Marshall Islands', 'MHL', 584, 692),
(134, 'MQ', 'MARTINIQUE', 'Martinique', 'MTQ', 474, 596),
(135, 'MR', 'MAURITANIA', 'Mauritania', 'MRT', 478, 222),
(136, 'MU', 'MAURITIUS', 'Mauritius', 'MUS', 480, 230),
(137, 'YT', 'MAYOTTE', 'Mayotte', '', 0, 269),
(138, 'MX', 'MEXICO', 'Mexico', 'MEX', 484, 52),
(139, 'FM', 'MICRONESIA, FEDERATED STATES OF', 'Micronesia, Federated States of', 'FSM', 583, 691),
(140, 'MD', 'MOLDOVA, REPUBLIC OF', 'Moldova, Republic of', 'MDA', 498, 373),
(141, 'MC', 'MONACO', 'Monaco', 'MCO', 492, 377),
(142, 'MN', 'MONGOLIA', 'Mongolia', 'MNG', 496, 976),
(143, 'MS', 'MONTSERRAT', 'Montserrat', 'MSR', 500, 1664),
(144, 'MA', 'MOROCCO', 'Morocco', 'MAR', 504, 212),
(145, 'MZ', 'MOZAMBIQUE', 'Mozambique', 'MOZ', 508, 258),
(146, 'MM', 'MYANMAR', 'Myanmar', 'MMR', 104, 95),
(147, 'NA', 'NAMIBIA', 'Namibia', 'NAM', 516, 264),
(148, 'NR', 'NAURU', 'Nauru', 'NRU', 520, 674),
(149, 'NP', 'NEPAL', 'Nepal', 'NPL', 524, 977),
(150, 'NL', 'NETHERLANDS', 'Netherlands', 'NLD', 528, 31),
(151, 'AN', 'NETHERLANDS ANTILLES', 'Netherlands Antilles', 'ANT', 530, 599),
(152, 'NC', 'NEW CALEDONIA', 'New Caledonia', 'NCL', 540, 687),
(153, 'NZ', 'NEW ZEALAND', 'New Zealand', 'NZL', 554, 64),
(154, 'NI', 'NICARAGUA', 'Nicaragua', 'NIC', 558, 505),
(155, 'NE', 'NIGER', 'Niger', 'NER', 562, 227),
(156, 'NG', 'NIGERIA', 'Nigeria', 'NGA', 566, 234),
(157, 'NU', 'NIUE', 'Niue', 'NIU', 570, 683),
(158, 'NF', 'NORFOLK ISLAND', 'Norfolk Island', 'NFK', 574, 672),
(159, 'MP', 'NORTHERN MARIANA ISLANDS', 'Northern Mariana Islands', 'MNP', 580, 1670),
(160, 'NO', 'NORWAY', 'Norway', 'NOR', 578, 47),
(161, 'OM', 'OMAN', 'Oman', 'OMN', 512, 968),
(162, 'PK', 'PAKISTAN', 'Pakistan', 'PAK', 586, 92),
(163, 'PW', 'PALAU', 'Palau', 'PLW', 585, 680),
(164, 'PS', 'PALESTINIAN TERRITORY, OCCUPIED', 'Palestinian Territory, Occupied', '', 0, 970),
(165, 'PA', 'PANAMA', 'Panama', 'PAN', 591, 507),
(166, 'PG', 'PAPUA NEW GUINEA', 'Papua New Guinea', 'PNG', 598, 675),
(167, 'PY', 'PARAGUAY', 'Paraguay', 'PRY', 600, 595),
(168, 'PE', 'PERU', 'Peru', 'PER', 604, 51),
(169, 'PH', 'PHILIPPINES', 'Philippines', 'PHL', 608, 63),
(170, 'PN', 'PITCAIRN', 'Pitcairn', 'PCN', 612, 0),
(171, 'PL', 'POLAND', 'Poland', 'POL', 616, 48),
(172, 'PT', 'PORTUGAL', 'Portugal', 'PRT', 620, 351),
(173, 'PR', 'PUERTO RICO', 'Puerto Rico', 'PRI', 630, 1787),
(174, 'QA', 'QATAR', 'Qatar', 'QAT', 634, 974),
(175, 'RE', 'REUNION', 'Reunion', 'REU', 638, 262),
(176, 'RO', 'ROMANIA', 'Romania', 'ROM', 642, 40),
(177, 'RU', 'RUSSIAN FEDERATION', 'Russian Federation', 'RUS', 643, 70),
(178, 'RW', 'RWANDA', 'Rwanda', 'RWA', 646, 250),
(179, 'SH', 'SAINT HELENA', 'Saint Helena', 'SHN', 654, 290),
(180, 'KN', 'SAINT KITTS AND NEVIS', 'Saint Kitts and Nevis', 'KNA', 659, 1869),
(181, 'LC', 'SAINT LUCIA', 'Saint Lucia', 'LCA', 662, 1758),
(182, 'PM', 'SAINT PIERRE AND MIQUELON', 'Saint Pierre and Miquelon', 'SPM', 666, 508),
(183, 'VC', 'SAINT VINCENT AND THE GRENADINES', 'Saint Vincent and the Grenadines', 'VCT', 670, 1784),
(184, 'WS', 'SAMOA', 'Samoa', 'WSM', 882, 684),
(185, 'SM', 'SAN MARINO', 'San Marino', 'SMR', 674, 378),
(186, 'ST', 'SAO TOME AND PRINCIPE', 'Sao Tome and Principe', 'STP', 678, 239),
(187, 'SA', 'SAUDI ARABIA', 'Saudi Arabia', 'SAU', 682, 966),
(188, 'SN', 'SENEGAL', 'Senegal', 'SEN', 686, 221),
(189, 'CS', 'SERBIA AND MONTENEGRO', 'Serbia and Montenegro', '', 0, 381),
(190, 'SC', 'SEYCHELLES', 'Seychelles', 'SYC', 690, 248),
(191, 'SL', 'SIERRA LEONE', 'Sierra Leone', 'SLE', 694, 232),
(192, 'SG', 'SINGAPORE', 'Singapore', 'SGP', 702, 65),
(193, 'SK', 'SLOVAKIA', 'Slovakia', 'SVK', 703, 421),
(194, 'SI', 'SLOVENIA', 'Slovenia', 'SVN', 705, 386),
(195, 'SB', 'SOLOMON ISLANDS', 'Solomon Islands', 'SLB', 90, 677),
(196, 'SO', 'SOMALIA', 'Somalia', 'SOM', 706, 252),
(197, 'ZA', 'SOUTH AFRICA', 'South Africa', 'ZAF', 710, 27),
(198, 'GS', 'SOUTH GEORGIA SOUTH SAND, ISLANDS', 'South Georgia Sand. Islands', '', 0, 0),
(199, 'ES', 'SPAIN', 'Spain', 'ESP', 724, 34),
(200, 'LK', 'SRI LANKA', 'Sri Lanka', 'LKA', 144, 94),
(201, 'SD', 'SUDAN', 'Sudan', 'SDN', 736, 249),
(202, 'SR', 'SURINAME', 'Suriname', 'SUR', 740, 597),
(203, 'SJ', 'SVALBARD AND JAN MAYEN', 'Svalbard and Jan Mayen', 'SJM', 744, 47),
(204, 'SZ', 'SWAZILAND', 'Swaziland', 'SWZ', 748, 268),
(205, 'SE', 'SWEDEN', 'Sweden', 'SWE', 752, 46),
(206, 'CH', 'SWITZERLAND', 'Switzerland', 'CHE', 756, 41),
(207, 'SY', 'SYRIAN ARAB REPUBLIC', 'Syrian Arab Republic', 'SYR', 760, 963),
(208, 'TW', 'TAIWAN, PROVINCE OF CHINA', 'Taiwan, Province of China', 'TWN', 158, 886),
(209, 'TJ', 'TAJIKISTAN', 'Tajikistan', 'TJK', 762, 992),
(210, 'TZ', 'TANZANIA, UNITED REPUBLIC OF', 'Tanzania, United Republic of', 'TZA', 834, 255),
(211, 'TH', 'THAILAND', 'Thailand', 'THA', 764, 66),
(212, 'TL', 'TIMOR-LESTE', 'Timor-Leste', '', 0, 670),
(213, 'TG', 'TOGO', 'Togo', 'TGO', 768, 228),
(214, 'TK', 'TOKELAU', 'Tokelau', 'TKL', 772, 690),
(215, 'TO', 'TONGA', 'Tonga', 'TON', 776, 676),
(216, 'TT', 'TRINIDAD AND TOBAGO', 'Trinidad and Tobago', 'TTO', 780, 1868),
(217, 'TN', 'TUNISIA', 'Tunisia', 'TUN', 788, 216),
(218, 'TR', 'TURKEY', 'Turkey', 'TUR', 792, 90),
(219, 'TM', 'TURKMENISTAN', 'Turkmenistan', 'TKM', 795, 7370),
(220, 'TC', 'TURKS AND CAICOS ISLANDS', 'Turks and Caicos Islands', 'TCA', 796, 1649),
(221, 'TV', 'TUVALU', 'Tuvalu', 'TUV', 798, 688),
(222, 'UG', 'UGANDA', 'Uganda', 'UGA', 800, 256),
(223, 'UA', 'UKRAINE', 'Ukraine', 'UKR', 804, 380),
(224, 'AE', 'UNITED ARAB EMIRATES', 'United Arab Emirates', 'ARE', 784, 971),
(225, 'GB', 'UNITED KINGDOM', 'United Kingdom', 'GBR', 826, 44),
(226, 'US', 'UNITED STATES', 'United States', 'USA', 840, 1),
(227, 'UM', 'UNITED STATES MINOR OUTLYING ISLANDS', 'United States Minor Outlying Islands', '', 0, 1),
(228, 'UY', 'URUGUAY', 'Uruguay', 'URY', 858, 598),
(229, 'UZ', 'UZBEKISTAN', 'Uzbekistan', 'UZB', 860, 998),
(230, 'VU', 'VANUATU', 'Vanuatu', 'VUT', 548, 678),
(231, 'VE', 'VENEZUELA', 'Venezuela', 'VEN', 862, 58),
(232, 'VN', 'VIET NAM', 'Viet Nam', 'VNM', 704, 84),
(233, 'VG', 'VIRGIN ISLANDS, BRITISH', 'Virgin Islands, British', 'VGB', 92, 1284),
(234, 'VI', 'VIRGIN ISLANDS, U.S.', 'Virgin Islands, U.s.', 'VIR', 850, 1340),
(235, 'WF', 'WALLIS AND FUTUNA', 'Wallis and Futuna', 'WLF', 876, 681),
(236, 'EH', 'WESTERN SAHARA', 'Western Sahara', 'ESH', 732, 212),
(237, 'YE', 'YEMEN', 'Yemen', 'YEM', 887, 967),
(238, 'ZM', 'ZAMBIA', 'Zambia', 'ZMB', 894, 260),
(239, 'ZW', 'ZIMBABWE', 'Zimbabwe', 'ZWE', 716, 263),
(240, 'RS', 'SERBIA', 'Serbia', 'SRB', 688, 381),
(241, 'AP', 'ASIA PACIFIC REGION', 'Asia / Pacific Region', '0', 0, 0),
(242, 'ME', 'MONTENEGRO', 'Montenegro', 'MNE', 499, 382),
(243, 'AX', 'ALAND ISLANDS', 'Aland Islands', 'ALA', 248, 358),
(244, 'BQ', 'BONAIRE, SINT EUSTATIUS AND SABA', 'Bonaire, Sint Eustatius and Saba', 'BES', 535, 599),
(245, 'CW', 'CURACAO', 'Curacao', 'CUW', 531, 599),
(246, 'GG', 'GUERNSEY', 'Guernsey', 'GGY', 831, 44),
(247, 'IM', 'ISLE OF MAN', 'Isle of Man', 'IMN', 833, 44),
(248, 'JE', 'JERSEY', 'Jersey', 'JEY', 832, 44),
(249, 'XK', 'KOSOVO', 'Kosovo', '---', 0, 381),
(250, 'BL', 'SAINT BARTHELEMY', 'Saint Barthelemy', 'BLM', 652, 590),
(251, 'MF', 'SAINT MARTIN', 'Saint Martin', 'MAF', 663, 590);

-- --------------------------------------------------------

--
-- Estrutura da tabela `currency`
--

CREATE TABLE `currency` (
  `id` int(11) NOT NULL,
  `entity` varchar(24) COLLATE utf8mb4_unicode_ci NOT NULL,
  `currency` varchar(24) COLLATE utf8mb4_unicode_ci NOT NULL,
  `alphabetic_code` varchar(3) COLLATE utf8mb4_unicode_ci NOT NULL,
  `numeric` varchar(3) COLLATE utf8mb4_unicode_ci NOT NULL,
  `minor` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `currency`
--

INSERT INTO `currency` (`id`, `entity`, `currency`, `alphabetic_code`, `numeric`, `minor`) VALUES
(2, 'Afghani', 'AFN', '971', '2', 0),
(3, 'Euro', 'EUR', '978', '2', 0),
(4, 'Lek', 'ALL', '8', '2', 0),
(5, 'Algerian Dinar', 'DZD', '12', '2', 0),
(8, 'Kwanza', 'AOA', '973', '2', 0),
(9, 'East Caribbean Dollar', 'XCD', '951', '2', 0),
(12, 'Argentine Peso', 'ARS', '32', '2', 0),
(13, 'Armenian Dram', 'AMD', '51', '2', 0),
(14, 'Aruban Florin', 'AWG', '533', '2', 0),
(17, 'Azerbaijan Manat', 'AZN', '944', '2', 0),
(18, 'Bahamian Dollar', 'BSD', '44', '2', 0),
(19, 'Bahraini Dinar', 'BHD', '48', '3', 0),
(20, 'Taka', 'BDT', '50', '2', 0),
(21, 'Barbados Dollar', 'BBD', '52', '2', 0),
(22, 'Belarusian Ruble', 'BYN', '933', '2', 0),
(24, 'Belize Dollar', 'BZD', '84', '2', 0),
(26, 'Bermudian Dollar', 'BMD', '60', '2', 0),
(27, 'Indian Rupee', 'INR', '356', '2', 0),
(28, 'Ngultrum', 'BTN', '64', '2', 0),
(29, 'Boliviano', 'BOB', '68', '2', 0),
(30, 'Mvdol', 'BOV', '984', '2', 0),
(32, 'Convertible Mark', 'BAM', '977', '2', 0),
(33, 'Pula', 'BWP', '72', '2', 0),
(34, 'Norwegian Krone', 'NOK', '578', '2', 0),
(35, 'Brazilian Real', 'BRL', '986', '2', 0),
(36, 'US Dollar', 'USD', '840', '2', 0),
(37, 'Brunei Dollar', 'BND', '96', '2', 0),
(38, 'Bulgarian Lev', 'BGN', '975', '2', 0),
(40, 'Burundi Franc', 'BIF', '108', '0', 0),
(41, 'Cabo Verde Escudo', 'CVE', '132', '2', 0),
(42, 'Riel', 'KHR', '116', '2', 0),
(44, 'Canadian Dollar', 'CAD', '124', '2', 0),
(45, 'Cayman Islands Dollar', 'KYD', '136', '2', 0),
(47, 'CFA Franc BEAC', 'XAF', '950', '0', 0),
(48, 'Chilean Peso', 'CLP', '152', '0', 0),
(49, 'Unidad de Fomento', 'CLF', '990', '4', 0),
(50, 'Yuan Renminbi', 'CNY', '156', '2', 0),
(51, 'Australian Dollar', 'AUD', '36', '2', 0),
(53, 'Colombian Peso', 'COP', '170', '2', 0),
(54, 'Unidad de Valor Real', 'COU', '970', '2', 0),
(55, 'Comorian Franc ', 'KMF', '174', '0', 0),
(56, 'Congolese Franc', 'CDF', '976', '2', 0),
(59, 'Costa Rican Colon', 'CRC', '188', '2', 0),
(61, 'Kuna', 'HRK', '191', '2', 0),
(62, 'Cuban Peso', 'CUP', '192', '2', 0),
(63, 'Peso Convertible', 'CUC', '931', '2', 0),
(64, 'Netherlands Antillean Gu', 'ANG', '532', '2', 0),
(66, 'Czech Koruna', 'CZK', '203', '2', 0),
(67, 'Danish Krone', 'DKK', '208', '2', 0),
(68, 'Djibouti Franc', 'DJF', '262', '0', 0),
(70, 'Dominican Peso', 'DOP', '214', '2', 0),
(72, 'Egyptian Pound', 'EGP', '818', '2', 0),
(73, 'El Salvador Colon', 'SVC', '222', '2', 0),
(76, 'Nakfa', 'ERN', '232', '2', 0),
(78, 'Ethiopian Birr', 'ETB', '230', '2', 0),
(80, 'Falkland Islands Pound', 'FKP', '238', '2', 0),
(82, 'Fiji Dollar', 'FJD', '242', '2', 0),
(86, 'CFP Franc', 'XPF', '953', '0', 0),
(89, 'Dalasi', 'GMD', '270', '2', 0),
(90, 'Lari', 'GEL', '981', '2', 0),
(92, 'Ghana Cedi', 'GHS', '936', '2', 0),
(93, 'Gibraltar Pound', 'GIP', '292', '2', 0),
(99, 'Quetzal', 'GTQ', '320', '2', 0),
(100, 'Pound Sterling', 'GBP', '826', '2', 0),
(101, 'Guinean Franc', 'GNF', '324', '0', 0),
(102, 'CFA Franc BCEAO', 'XOF', '952', '0', 0),
(103, 'Guyana Dollar', 'GYD', '328', '2', 0),
(104, 'Gourde', 'HTG', '332', '2', 0),
(108, 'Lempira', 'HNL', '340', '2', 0),
(109, 'Hong Kong Dollar', 'HKD', '344', '2', 0),
(110, 'Forint', 'HUF', '348', '2', 0),
(111, 'Iceland Krona', 'ISK', '352', '0', 0),
(113, 'Rupiah', 'IDR', '360', '2', 0),
(115, 'Iranian Rial', 'IRR', '364', '2', 0),
(116, 'Iraqi Dinar', 'IQD', '368', '3', 0),
(119, 'New Israeli Sheqel', 'ILS', '376', '2', 0),
(121, 'Jamaican Dollar', 'JMD', '388', '2', 0),
(122, 'Yen', 'JPY', '392', '0', 0),
(124, 'Jordanian Dinar', 'JOD', '400', '3', 0),
(125, 'Tenge', 'KZT', '398', '2', 0),
(126, 'Kenyan Shilling', 'KES', '404', '2', 0),
(128, 'North Korean Won', 'KPW', '408', '2', 0),
(129, 'Won', 'KRW', '410', '0', 0),
(130, 'Kuwaiti Dinar', 'KWD', '414', '3', 0),
(131, 'Som', 'KGS', '417', '2', 0),
(132, 'Lao Kip', 'LAK', '418', '2', 0),
(134, 'Lebanese Pound', 'LBP', '422', '2', 0),
(135, 'Loti', 'LSL', '426', '2', 0),
(136, 'Rand', 'ZAR', '710', '2', 0),
(137, 'Liberian Dollar', 'LRD', '430', '2', 0),
(138, 'Libyan Dinar', 'LYD', '434', '3', 0),
(139, 'Swiss Franc', 'CHF', '756', '2', 0),
(142, 'Pataca', 'MOP', '446', '2', 0),
(143, 'Denar', 'MKD', '807', '2', 0),
(144, 'Malagasy Ariary', 'MGA', '969', '2', 0),
(145, 'Malawi Kwacha', 'MWK', '454', '2', 0),
(146, 'Malaysian Ringgit', 'MYR', '458', '2', 0),
(147, 'Rufiyaa', 'MVR', '462', '2', 0),
(152, 'Ouguiya', 'MRU', '929', '2', 0),
(153, 'Mauritius Rupee', 'MUR', '480', '2', 0),
(156, 'Mexican Peso', 'MXN', '484', '2', 0),
(157, 'Mexican Unidad de Invers', 'MXV', '979', '2', 0),
(159, 'Moldovan Leu', 'MDL', '498', '2', 0),
(161, 'Tugrik', 'MNT', '496', '2', 0),
(164, 'Moroccan Dirham', 'MAD', '504', '2', 0),
(165, 'Mozambique Metical', 'MZN', '943', '2', 0),
(166, 'Kyat', 'MMK', '104', '2', 0),
(167, 'Namibia Dollar', 'NAD', '516', '2', 0),
(170, 'Nepalese Rupee', 'NPR', '524', '2', 0),
(174, 'Cordoba Oro', 'NIO', '558', '2', 0),
(176, 'Naira', 'NGN', '566', '2', 0),
(177, 'New Zealand Dollar', 'NZD', '554', '2', 0),
(181, 'Rial Omani', 'OMR', '512', '3', 0),
(182, 'Pakistan Rupee', 'PKR', '586', '2', 0),
(185, 'Balboa', 'PAB', '590', '2', 0),
(187, 'Kina', 'PGK', '598', '2', 0),
(188, 'Guarani', 'PYG', '600', '0', 0),
(189, 'Sol', 'PEN', '604', '2', 0),
(190, 'Philippine Peso', 'PHP', '608', '2', 0),
(192, 'Zloty', 'PLN', '985', '2', 0),
(195, 'Qatari Rial', 'QAR', '634', '2', 0),
(197, 'Romanian Leu', 'RON', '946', '2', 0),
(198, 'Russian Ruble', 'RUB', '643', '2', 0),
(199, 'Rwanda Franc', 'RWF', '646', '0', 0),
(201, 'Saint Helena Pound', 'SHP', '654', '2', 0),
(207, 'Tala', 'WST', '882', '2', 0),
(209, 'Dobra', 'STN', '930', '2', 0),
(210, 'Saudi Riyal', 'SAR', '682', '2', 0),
(212, 'Serbian Dinar', 'RSD', '941', '2', 0),
(213, 'Seychelles Rupee', 'SCR', '690', '2', 0),
(214, 'Leone', 'SLL', '694', '2', 0),
(215, 'Singapore Dollar', 'SGD', '702', '2', 0),
(220, 'Solomon Islands Dollar', 'SBD', '90', '2', 0),
(221, 'Somali Shilling', 'SOS', '706', '2', 0),
(224, 'South Sudanese Pound', 'SSP', '728', '2', 0),
(226, 'Sri Lanka Rupee', 'LKR', '144', '2', 0),
(227, 'Sudanese Pound', 'SDG', '938', '2', 0),
(228, 'Surinam Dollar', 'SRD', '968', '2', 0),
(230, 'Lilangeni', 'SZL', '748', '2', 0),
(231, 'Swedish Krona', 'SEK', '752', '2', 0),
(233, 'WIR Euro', 'CHE', '947', '2', 0),
(234, 'WIR Franc', 'CHW', '948', '2', 0),
(235, 'Syrian Pound', 'SYP', '760', '2', 0),
(236, 'New Taiwan Dollar', 'TWD', '901', '2', 0),
(237, 'Somoni', 'TJS', '972', '2', 0),
(238, 'Tanzanian Shilling', 'TZS', '834', '2', 0),
(239, 'Baht', 'THB', '764', '2', 0),
(243, 'Paçanga', 'TOP', '776', '2', 0),
(244, 'Trinidad and Tobago Doll', 'TTD', '780', '2', 0),
(245, 'Tunisian Dinar', 'TND', '788', '3', 0),
(246, 'Turkish Lira', 'TRY', '949', '2', 0),
(247, 'Turkmenistan New Manat', 'TMT', '934', '2', 0),
(250, 'Uganda Shilling', 'UGX', '800', '0', 0),
(251, 'Hryvnia', 'UAH', '980', '2', 0),
(252, 'UAE Dirham', 'AED', '784', '2', 0),
(257, 'Peso Uruguayo', 'UYU', '858', '2', 0),
(258, 'Uruguay Peso en Unidades', 'UYI', '940', '0', 0),
(259, 'Unidad Previsional', 'UYW', '927', '4', 0),
(260, 'Uzbekistan Sum', 'UZS', '860', '2', 0),
(261, 'Vatu', 'VUV', '548', '0', 0),
(262, 'Bolívar Soberano', 'VES', '928', '2', 0),
(263, 'Dong', 'VND', '704', '0', 0),
(267, 'Moroccan Dirham', 'MAD', '504', '2', 0),
(268, 'Yemeni Rial', 'YER', '886', '2', 0),
(269, 'Zambian Kwacha', 'ZMW', '967', '2', 0),
(270, 'Zimbabwe Dollar', 'ZWL', '932', '2', 0);

-- --------------------------------------------------------

--
-- Estrutura da tabela `easytext`
--

CREATE TABLE `easytext` (
  `id` int(11) NOT NULL,
  `easy_text` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `easy_text_html` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `locales_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `easytext`
--

INSERT INTO `easytext` (`id`, `easy_text`, `easy_text_html`, `name`, `locales_id`) VALUES
(1, '{\"ops\":[{\"insert\":\"Well be waiting\"},{\"attributes\":{\"link\":\"https://www.google.com/maps/dir//37.1248149,-8.5282595\"},\"insert\":\" Here\"},{\"insert\":\" \\n\"}]}', '<p>Well be waiting<a href=\"https://www.google.com/maps/dir//37.1248149,-8.5282595\" target=\"_blank\"> Here</a> </p>', 'PickUp Info En', 2),
(2, '{\"ops\":[{\"insert\":\"Estamos à sua espera aqui: \"},{\"attributes\":{\"link\":\"https://www.google.com/maps/dir//37.1248149,-8.5282595\"},\"insert\":\"clique\"},{\"insert\":\"\\n\"}]}', '<p>Estamos à sua espera aqui: <a href=\"https://www.google.com/maps/dir//37.1248149,-8.5282595\" target=\"_blank\">clique</a></p>', 'PickUp Info Pt', 1);

-- --------------------------------------------------------

--
-- Estrutura da tabela `event`
--

CREATE TABLE `event` (
  `id` int(11) NOT NULL,
  `product_id` int(11) DEFAULT NULL,
  `event` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `gallery`
--

CREATE TABLE `gallery` (
  `id` int(11) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '0',
  `image` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `order_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `gallery`
--

INSERT INTO `gallery` (`id`, `is_active`, `image`, `order_by`) VALUES
(1, 1, '91633caa8aeed16597f69a7962891ce2.jpeg', 1),
(2, 1, 'fe266dbba4e9ccebf50fa5b1982a403f.jpeg', 4),
(3, 1, '750b79030585d504364a7dcd5249796d.jpeg', 7),
(4, 1, '618f30d003e15daee421d50e86751486.jpeg', 6),
(21, 1, '8c111c4432e2ec8a1878bb80d53e88a4.jpeg', 3),
(22, 1, '78f0b67a8ab9519aa460bb995a3e1c21.jpeg', 5),
(25, 1, '7b1a3d1ad879c9cc034c71049bc540b5.jpeg', 2);

-- --------------------------------------------------------

--
-- Estrutura da tabela `gallery_translation`
--

CREATE TABLE `gallery_translation` (
  `id` int(11) NOT NULL,
  `gallery_id` int(11) DEFAULT NULL,
  `locales_id` int(11) DEFAULT NULL,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `gallery_translation`
--

INSERT INTO `gallery_translation` (`id`, `gallery_id`, `locales_id`, `name`) VALUES
(1, 1, 1, 'São Francisco'),
(2, 1, 2, 'San Francisco'),
(3, 22, 1, 'Porto'),
(4, 22, 2, 'Oporto'),
(9, 3, 1, 'Paris'),
(10, 3, 2, 'Paris'),
(11, 4, 1, 'Pisa'),
(12, 4, 2, 'Pisa'),
(13, 21, 1, 'Lisboa'),
(14, 21, 2, 'Lisbon'),
(15, 2, 1, 'O Hotel'),
(16, 2, 2, 'The Hotel'),
(17, 25, 1, 'Algarve'),
(18, 25, 2, 'Allgarve');

-- --------------------------------------------------------

--
-- Estrutura da tabela `locales`
--

CREATE TABLE `locales` (
  `id` int(11) NOT NULL,
  `name` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `filename` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `locales`
--

INSERT INTO `locales` (`id`, `name`, `filename`) VALUES
(1, 'pt_PT', 'pt_PT.jpg'),
(2, 'en_EN', 'en_EN.jpg');

-- --------------------------------------------------------

--
-- Estrutura da tabela `logs`
--

CREATE TABLE `logs` (
  `id` int(11) NOT NULL,
  `datetime` datetime NOT NULL,
  `log` longtext COLLATE utf8mb4_unicode_ci,
  `status` enum('update','create','delete') COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `logs`
--

INSERT INTO `logs` (`id`, `datetime`, `log`, `status`) VALUES
(1, '2019-03-05 15:13:49', 'Utilizador: pedro15\r\n        Evento: #202\r\n        Start: 06/03/2019 14:45:00\r\n        End: 06/03/2019 16:15:00\r\n        Lotação : 30\r\n        Stock : 30\r\n        Categoria: Visita de Portimão', 'delete'),
(2, '2019-03-08 23:06:55', 'Utilizador: pedro15Evento: #286\r\n        Start: 05/05/2019 16:00:00 End: 05/05/2019 17:15:00\r\n        Lotação : 15 Stock : 15Categoria: Visita Tradicional', 'delete'),
(3, '2019-03-08 23:21:15', 'Utilizador: pedro15Evento: #219\r\n        Start: 09/03/2019 09:00:00 End: 09/03/2019 10:15:00\r\n        Lotação : 15 Stock : 15Categoria: Visita Tradicional', 'delete'),
(4, '2019-03-16 17:41:05', 'Utilizador: pedro15Evento: #250\r\n        Start: 17/03/2019 11:00:00 End: 17/03/2019 12:15:00\r\n        Lotação : 15 Stock : 15Categoria: Quarto Solteiro', 'delete');

-- --------------------------------------------------------

--
-- Estrutura da tabela `menu`
--

CREATE TABLE `menu` (
  `id` int(11) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '0',
  `order_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `menu`
--

INSERT INTO `menu` (`id`, `is_active`, `order_by`) VALUES
(1, 1, 1),
(2, 1, 4),
(3, 1, 2),
(4, 1, 3);

-- --------------------------------------------------------

--
-- Estrutura da tabela `menu_translation`
--

CREATE TABLE `menu_translation` (
  `id` int(11) NOT NULL,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `menu_id` int(11) DEFAULT NULL,
  `locales_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `menu_translation`
--

INSERT INTO `menu_translation` (`id`, `name`, `menu_id`, `locales_id`) VALUES
(1, 'Inicio', 1, 1),
(2, 'Home', 1, 2),
(3, 'Contatos', 2, 1),
(4, 'Contacts', 2, 2),
(5, 'Sobre', 3, 1),
(6, 'About', 3, 2),
(7, 'Galeria', 4, 1),
(8, 'Showcase', 4, 2);

-- --------------------------------------------------------

--
-- Estrutura da tabela `migration_versions`
--

CREATE TABLE `migration_versions` (
  `version` varchar(14) COLLATE utf8_unicode_ci NOT NULL,
  `executed_at` datetime DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Extraindo dados da tabela `migration_versions`
--

INSERT INTO `migration_versions` (`version`, `executed_at`) VALUES
('20190321234514', '2019-03-21 23:45:20'),
('20190321234742', '2019-03-21 23:47:47'),
('20190324074027', '2019-03-24 07:40:38'),
('20190324075042', '2019-03-24 07:50:47'),
('20190324075305', '2019-03-24 07:53:11'),
('20190325135150', '2019-03-25 13:52:01'),
('20190325135248', '2019-03-25 13:52:52'),
('20190325135356', '2019-03-25 13:54:02'),
('20190325135453', '2019-03-25 13:54:57'),
('20190327140158', '2019-03-27 14:02:09'),
('20190327140428', '2019-03-27 14:04:31'),
('20190328140427', '2019-03-28 14:04:39'),
('20190407125302', '2019-04-07 12:53:15'),
('20190408065555', '2019-04-08 06:56:07'),
('20190408115135', '2019-04-08 11:51:41'),
('20190408121403', '2019-04-08 12:14:07'),
('20190408125349', '2019-04-08 12:53:53'),
('20190408181543', '2019-04-08 18:15:48'),
('20190408191209', '2019-04-08 19:12:14'),
('20190409062452', '2019-04-09 06:25:31');

-- --------------------------------------------------------

--
-- Estrutura da tabela `product`
--

CREATE TABLE `product` (
  `id` int(11) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '0',
  `image` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `availability` int(11) NOT NULL DEFAULT '0',
  `highlight` tinyint(1) NOT NULL DEFAULT '0',
  `warranty_payment` tinyint(1) NOT NULL DEFAULT '0',
  `duration` varchar(5) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '00:00',
  `order_by` int(11) DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `product`
--

INSERT INTO `product` (`id`, `is_active`, `image`, `availability`, `highlight`, `warranty_payment`, `duration`, `order_by`, `category_id`) VALUES
(18, 1, 'H:\\ARMAZEM\\project-events/public/upload/product/no-image.png', 22, 1, 0, '01:00', 1, 3);

-- --------------------------------------------------------

--
-- Estrutura da tabela `product_description_translation`
--

CREATE TABLE `product_description_translation` (
  `id` int(11) NOT NULL,
  `product_id` int(11) DEFAULT NULL,
  `html` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `locales_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `product_description_translation`
--

INSERT INTO `product_description_translation` (`id`, `product_id`, `html`, `name`, `locales_id`) VALUES
(29, 18, '<p>22</p>', '22', 1),
(30, 18, '<p>33</p>', '33', 2);

-- --------------------------------------------------------

--
-- Estrutura da tabela `product_wp_translation`
--

CREATE TABLE `product_wp_translation` (
  `id` int(11) NOT NULL,
  `product_id` int(11) DEFAULT NULL,
  `locales_id` int(11) DEFAULT NULL,
  `html` longtext COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `product_wp_translation`
--

INSERT INTO `product_wp_translation` (`id`, `product_id`, `locales_id`, `html`) VALUES
(15, 18, 1, '<p><br></p>'),
(16, 18, 2, '<p><br></p>');

-- --------------------------------------------------------

--
-- Estrutura da tabela `rgpd`
--

CREATE TABLE `rgpd` (
  `id` int(11) NOT NULL,
  `rgpd_html` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `locales_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `rgpd`
--

INSERT INTO `rgpd` (`id`, `rgpd_html`, `name`, `locales_id`) VALUES
(1, '<p>A partir de 25 de maio passa a ser aplicável o Regulamento Geral sobre a Proteção de Dados Pessoais – Regulamento n.º 2016/679 do Parlamento Europeu e do Conselho, de 27 de abril de 2016, que estabelece as regras relativas à proteção, tratamento e livre circulação dos dados pessoais das pessoas singulares e que se aplica diretamente a todas as entidades que procedam ao tratamento desses dados, em qualquer Estado Membro da União Europeia, nomeadamente Portugal.</p>\r\n<p>O objetivo desta comunicação é dar-lhe a conhecer as novas regras aplicáveis ao tratamento dos seus dados pessoais, os direitos que lhe assistem, assim como informar da forma como pode gerir, diretamente e de forma simples, os respetivos consentimentos.</p>\r\n<p>\r\n\r\n<b>Entidade responsável pelo tratamento:</b><br>Empresa, Lda.<br>\r\nRua 25 Lt.A<br>\r\n8500-000 Portimão<br>\r\n\r\nEndereço do Encarregado de Proteção de Dados: protecaodedados@ll.pt<br>\r\n</p>\r\n\r\n<p>\r\nOs nossos registos incluem dados que foram obtidos através do formulario de reservas em https://plplps.pt<br>\r\nAssim, e para além das situações em que tratamos dados para cumprimento de imposições legais, tratamos os seus dados para as seguintes finalidades:<br>\r\nA Tours conservará os seus dados pessoais pelo período de 30 (trinta) dias a partir da data da realização da sua Experiência/Tour.<br>\r\nOs seus dados pessoais podem ser comunicados a autoridades judiciais, fiscais e regulatórias, com a finalidade do cumprimento de imposições legais.<br>\r\n</p>\r\n<p>\r\nEm qualquer momento, tem o direito de aceder aos seus dados pessoais, bem como, dentro dos limites dos serviços prestados e do Regulamento, de os alterar, opor-se ao respetivo tratamento, decidir sobre o tratamento automatizado dos mesmos, retirar o consentimento e exercer os demais direitos previstos na lei. Caso retire o seu consentimento, tal não compromete a licitude do tratamento efetuado até essa data. Tem o direito de receber uma notificação, nos termos previstos no Regulamento, caso ocorra uma violação dos seus dados pessoais, podendo apresentar reclamações perante a(s) autoridade(s).\r\n</p>\r\n<p>\r\nGarantimos todos os direitos consagrados no Regulamento. Para tal, a partir de 25-05-2018 pode aceder rápida, comodamente e de forma segura, e aí verificar os seus dados e as subscrições que possui ativas e atuar sobre essa informação.\r\n</p>\r\n<p>\r\nEstamos, como sempre estivemos, empenhados na proteção e confidencialidade dos seus dados pessoais. Tomámos as medidas técnicas e organizativas necessárias ao cumprimento do Regulamento, garantindo que o tratamento dos seus dados pessoais é lícito, leal, transparente e limitado às finalidades autorizadas. Adotámos as medidas que consideramos adequadas para assegurar a exatidão, integridade e confidencialidade dos seus dados pessoais, bem como todos os demais direitos que lhe assistem.</p>\r\n<p>Caso não autorize o registo dos seus dados pessoais, não é possivel efetuar a reserva.</p>', 'RGPD (Regulamento Geral de Proteção de Dados)', 1),
(2, '<p>As of 25 May, the General Regulation on the Protection of Personal Data - Regulation 2016/679 of the European Parlament and of the Council of 27 April 2016 rules on the protection, processing and free movement of personal data of natural persons and which applies directly to all entities handling such data in any Member State of the European Union, in particular Portugal. <!-- p-->\r\n</p><p> The aim of this communication is to inform you of the new rules applicable to the processing of your personal data, the rights that you have, and how you can directly and simply manage your consents.<!-- p-->\r\n</p><p>\r\n\r\n<b>Body responsible for treatment: <!-- b--> <br></b>Empresa, Lda.<br>Rua 25 Lt.A<br>8500-000 Portimão<b><br>\r\nAddress of the Data Protection Officer: protecaodedados@poprs.pt <br>\r\n<!-- p-->\r\n\r\n</b></p><p><b>\r\nOur records include data that was obtained through the booking form at https://popter.pt <br>\r\nThus, in addition to the situations in which we treat data to comply with legal requirements, we treat your data for the following purposes: <br>Empresa will retain your personal data for a period of 30 (thirty) days from the date of your Experience / Tour.\r\nYour personal data can be communicated to judicial, tax and regulatory authorities, with the purpose of complying with legal impositions.\r\n<!-- p-->\r\n</b></p><p><b>\r\nAt any time, you have the right to access your personal data, as well as, within the limits of the services provided and the Regulation, to change them, oppose their treatment, decide on the automated treatment of them, withdraw consent and exercise the other rights provided for by law. If you withdraw your consent, this does not compromise the lawfulness of the treatment made up to that date. You have the right to receive a notification under the terms of the Regulation in case there is a violation of your personal data and you can submit complaints to the authority (s).\r\n<!-- p-->\r\n</b></p><p><b>\r\nWe guarantee all rights enshrined in the Regulation. To do so, as of 25-05-2018 you can access quickly, conveniently and securely, and there verify your data and the subscriptions you have active and act on that information.\r\n<!-- p-->\r\n</b></p><p><b>\r\nWe are, as we have always been, committed to the protection and confidentiality of your personal data. We have taken the technical and organizational measures necessary to comply with the Regulation, ensuring that the processing of your personal data is lawful, fair, transparent and limited to the authorized purposes. We have adopted the measures we deem appropriate to ensure the accuracy, completeness and confidentiality of your personal data, as well as all other rights you may have. <!-- P-->\r\n</b></p><p><b> If you do not authorize the registration of your personal data, its not possible to make a reservation. <!-- p--></b></p>', 'GDPR (General Data Protection Regulation)', 2);

-- --------------------------------------------------------

--
-- Estrutura da tabela `terms_conditions`
--

CREATE TABLE `terms_conditions` (
  `id` int(11) NOT NULL,
  `locales_id` int(11) DEFAULT NULL,
  `terms_html` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `terms_conditions`
--

INSERT INTO `terms_conditions` (`id`, `locales_id`, `terms_html`, `name`) VALUES
(1, 1, '<p>Não obedecer à regras fica à porta.</p>', 'Termos & Condições'),
(2, 2, '<p>No complain with rules, no ride</p>', 'Terms & Conditions');

-- --------------------------------------------------------

--
-- Estrutura da tabela `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `roles` longtext COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '(DC2Type:array)',
  `status` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `user`
--

INSERT INTO `user` (`id`, `email`, `username`, `password`, `roles`, `status`) VALUES
(2, 'vgspedro@gmail.com', 'pedro', '$2y$13$uvlH4Ntoc0XwQUQUNh3hPudpn7gLEEBpvS.h3O3VutuM8WbBhDcAC', 'a:1:{i:0;s:9:\"ROLE_USER\";}', 1),
(3, 'vgspedro15@sapo.pt', 'pedro15', '$2y$13$3zW3eBXx7eo8zqmNtzn5EeFsb6A887X55rOFDqLtts0LPbJF7ZNq6', 'a:1:{i:0;s:9:\"ROLE_USER\";}', 1);

-- --------------------------------------------------------

--
-- Estrutura da tabela `warning`
--

CREATE TABLE `warning` (
  `id` int(11) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `warning`
--

INSERT INTO `warning` (`id`, `is_active`) VALUES
(10, 0);

-- --------------------------------------------------------

--
-- Estrutura da tabela `warning_translation`
--

CREATE TABLE `warning_translation` (
  `id` int(11) NOT NULL,
  `warning_id` int(11) DEFAULT NULL,
  `locales_id` int(11) DEFAULT NULL,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `warning_translation`
--

INSERT INTO `warning_translation` (`id`, `warning_id`, `locales_id`, `name`) VALUES
(1, 10, 1, 'O tempo tá mau, fica tudo em casa.'),
(2, 10, 2, 'Bad weather, everybody stays home');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `about_us`
--
ALTER TABLE `about_us`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_B52303C3164006B8` (`locales_id`);

--
-- Indexes for table `amount`
--
ALTER TABLE `amount`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_8EA170424584665A` (`product_id`);

--
-- Indexes for table `amount_translation`
--
ALTER TABLE `amount_translation`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_20A3363F9BB17698` (`amount_id`),
  ADD KEY `IDX_20A3363F164006B8` (`locales_id`);

--
-- Indexes for table `available`
--
ALTER TABLE `available`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_A58FA4854584665A` (`product_id`);

--
-- Indexes for table `banner`
--
ALTER TABLE `banner`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `banner_translation`
--
ALTER TABLE `banner_translation`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_841ECF1C684EC833` (`banner_id`),
  ADD KEY `IDX_841ECF1C164006B8` (`locales_id`);

--
-- Indexes for table `booking`
--
ALTER TABLE `booking`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_E00CEDDE36D3FBA2` (`available_id`),
  ADD KEY `IDX_E00CEDDE19EB6921` (`client_id`);

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `category_translation`
--
ALTER TABLE `category_translation`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_3F2070412469DE2` (`category_id`),
  ADD KEY `IDX_3F20704164006B8` (`locales_id`);

--
-- Indexes for table `client`
--
ALTER TABLE `client`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_C7440455E559DFD1` (`locale_id`);

--
-- Indexes for table `company`
--
ALTER TABLE `company`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_4FBF094F38248176` (`currency_id`),
  ADD KEY `IDX_4FBF094FF92F3E70` (`country_id`);

--
-- Indexes for table `countries`
--
ALTER TABLE `countries`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `currency`
--
ALTER TABLE `currency`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `easytext`
--
ALTER TABLE `easytext`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_B353B3ED164006B8` (`locales_id`);

--
-- Indexes for table `event`
--
ALTER TABLE `event`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_3BAE0AA74584665A` (`product_id`);

--
-- Indexes for table `gallery`
--
ALTER TABLE `gallery`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `gallery_translation`
--
ALTER TABLE `gallery_translation`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_5D650CAB4E7AF8F` (`gallery_id`),
  ADD KEY `IDX_5D650CAB164006B8` (`locales_id`);

--
-- Indexes for table `locales`
--
ALTER TABLE `locales`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `logs`
--
ALTER TABLE `logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `menu`
--
ALTER TABLE `menu`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `menu_translation`
--
ALTER TABLE `menu_translation`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_DC955B23CCD7E912` (`menu_id`),
  ADD KEY `IDX_DC955B23164006B8` (`locales_id`);

--
-- Indexes for table `migration_versions`
--
ALTER TABLE `migration_versions`
  ADD PRIMARY KEY (`version`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_D34A04AD12469DE2` (`category_id`);

--
-- Indexes for table `product_description_translation`
--
ALTER TABLE `product_description_translation`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_119EDAE44584665A` (`product_id`),
  ADD KEY `IDX_119EDAE4164006B8` (`locales_id`);

--
-- Indexes for table `product_wp_translation`
--
ALTER TABLE `product_wp_translation`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_268553144584665A` (`product_id`),
  ADD KEY `IDX_26855314164006B8` (`locales_id`);

--
-- Indexes for table `rgpd`
--
ALTER TABLE `rgpd`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_C80AB619164006B8` (`locales_id`);

--
-- Indexes for table `terms_conditions`
--
ALTER TABLE `terms_conditions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_7BF59952164006B8` (`locales_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_8D93D649E7927C74` (`email`),
  ADD UNIQUE KEY `UNIQ_8D93D649F85E0677` (`username`);

--
-- Indexes for table `warning`
--
ALTER TABLE `warning`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `warning_translation`
--
ALTER TABLE `warning_translation`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_D8A1DDE6BFF38603` (`warning_id`),
  ADD KEY `IDX_D8A1DDE6164006B8` (`locales_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `about_us`
--
ALTER TABLE `about_us`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `amount`
--
ALTER TABLE `amount`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `amount_translation`
--
ALTER TABLE `amount_translation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `available`
--
ALTER TABLE `available`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `banner`
--
ALTER TABLE `banner`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;
--
-- AUTO_INCREMENT for table `banner_translation`
--
ALTER TABLE `banner_translation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;
--
-- AUTO_INCREMENT for table `booking`
--
ALTER TABLE `booking`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `category_translation`
--
ALTER TABLE `category_translation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT for table `client`
--
ALTER TABLE `client`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `company`
--
ALTER TABLE `company`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `countries`
--
ALTER TABLE `countries`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=252;
--
-- AUTO_INCREMENT for table `currency`
--
ALTER TABLE `currency`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=271;
--
-- AUTO_INCREMENT for table `easytext`
--
ALTER TABLE `easytext`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `event`
--
ALTER TABLE `event`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `gallery`
--
ALTER TABLE `gallery`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;
--
-- AUTO_INCREMENT for table `gallery_translation`
--
ALTER TABLE `gallery_translation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;
--
-- AUTO_INCREMENT for table `locales`
--
ALTER TABLE `locales`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `logs`
--
ALTER TABLE `logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `menu`
--
ALTER TABLE `menu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `menu_translation`
--
ALTER TABLE `menu_translation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;
--
-- AUTO_INCREMENT for table `product_description_translation`
--
ALTER TABLE `product_description_translation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;
--
-- AUTO_INCREMENT for table `product_wp_translation`
--
ALTER TABLE `product_wp_translation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;
--
-- AUTO_INCREMENT for table `rgpd`
--
ALTER TABLE `rgpd`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `terms_conditions`
--
ALTER TABLE `terms_conditions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `warning`
--
ALTER TABLE `warning`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT for table `warning_translation`
--
ALTER TABLE `warning_translation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- Constraints for dumped tables
--

--
-- Limitadores para a tabela `about_us`
--
ALTER TABLE `about_us`
  ADD CONSTRAINT `FK_B52303C3164006B8` FOREIGN KEY (`locales_id`) REFERENCES `locales` (`id`);

--
-- Limitadores para a tabela `amount`
--
ALTER TABLE `amount`
  ADD CONSTRAINT `FK_8EA170424584665A` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`);

--
-- Limitadores para a tabela `amount_translation`
--
ALTER TABLE `amount_translation`
  ADD CONSTRAINT `FK_20A3363F164006B8` FOREIGN KEY (`locales_id`) REFERENCES `locales` (`id`),
  ADD CONSTRAINT `FK_20A3363F9BB17698` FOREIGN KEY (`amount_id`) REFERENCES `amount` (`id`);

--
-- Limitadores para a tabela `available`
--
ALTER TABLE `available`
  ADD CONSTRAINT `FK_A58FA4854584665A` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`);

--
-- Limitadores para a tabela `banner_translation`
--
ALTER TABLE `banner_translation`
  ADD CONSTRAINT `FK_841ECF1C164006B8` FOREIGN KEY (`locales_id`) REFERENCES `locales` (`id`),
  ADD CONSTRAINT `FK_841ECF1C684EC833` FOREIGN KEY (`banner_id`) REFERENCES `banner` (`id`);

--
-- Limitadores para a tabela `booking`
--
ALTER TABLE `booking`
  ADD CONSTRAINT `FK_E00CEDDE19EB6921` FOREIGN KEY (`client_id`) REFERENCES `client` (`id`),
  ADD CONSTRAINT `FK_E00CEDDE36D3FBA2` FOREIGN KEY (`available_id`) REFERENCES `available` (`id`);

--
-- Limitadores para a tabela `category_translation`
--
ALTER TABLE `category_translation`
  ADD CONSTRAINT `FK_3F2070412469DE2` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`),
  ADD CONSTRAINT `FK_3F20704164006B8` FOREIGN KEY (`locales_id`) REFERENCES `locales` (`id`);

--
-- Limitadores para a tabela `client`
--
ALTER TABLE `client`
  ADD CONSTRAINT `FK_C7440455E559DFD1` FOREIGN KEY (`locale_id`) REFERENCES `locales` (`id`);

--
-- Limitadores para a tabela `company`
--
ALTER TABLE `company`
  ADD CONSTRAINT `FK_4FBF094F38248176` FOREIGN KEY (`currency_id`) REFERENCES `currency` (`id`),
  ADD CONSTRAINT `FK_4FBF094FF92F3E70` FOREIGN KEY (`country_id`) REFERENCES `countries` (`id`);

--
-- Limitadores para a tabela `easytext`
--
ALTER TABLE `easytext`
  ADD CONSTRAINT `FK_B353B3ED164006B8` FOREIGN KEY (`locales_id`) REFERENCES `locales` (`id`);

--
-- Limitadores para a tabela `event`
--
ALTER TABLE `event`
  ADD CONSTRAINT `FK_3BAE0AA74584665A` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`);

--
-- Limitadores para a tabela `gallery_translation`
--
ALTER TABLE `gallery_translation`
  ADD CONSTRAINT `FK_5D650CAB164006B8` FOREIGN KEY (`locales_id`) REFERENCES `locales` (`id`),
  ADD CONSTRAINT `FK_5D650CAB4E7AF8F` FOREIGN KEY (`gallery_id`) REFERENCES `gallery` (`id`);

--
-- Limitadores para a tabela `menu_translation`
--
ALTER TABLE `menu_translation`
  ADD CONSTRAINT `FK_DC955B23164006B8` FOREIGN KEY (`locales_id`) REFERENCES `locales` (`id`),
  ADD CONSTRAINT `FK_DC955B23CCD7E912` FOREIGN KEY (`menu_id`) REFERENCES `menu` (`id`);

--
-- Limitadores para a tabela `product`
--
ALTER TABLE `product`
  ADD CONSTRAINT `FK_D34A04AD12469DE2` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`);

--
-- Limitadores para a tabela `product_description_translation`
--
ALTER TABLE `product_description_translation`
  ADD CONSTRAINT `FK_119EDAE4164006B8` FOREIGN KEY (`locales_id`) REFERENCES `locales` (`id`),
  ADD CONSTRAINT `FK_119EDAE44584665A` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`);

--
-- Limitadores para a tabela `product_wp_translation`
--
ALTER TABLE `product_wp_translation`
  ADD CONSTRAINT `FK_26855314164006B8` FOREIGN KEY (`locales_id`) REFERENCES `locales` (`id`),
  ADD CONSTRAINT `FK_268553144584665A` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`);

--
-- Limitadores para a tabela `rgpd`
--
ALTER TABLE `rgpd`
  ADD CONSTRAINT `FK_C80AB619164006B8` FOREIGN KEY (`locales_id`) REFERENCES `locales` (`id`);

--
-- Limitadores para a tabela `terms_conditions`
--
ALTER TABLE `terms_conditions`
  ADD CONSTRAINT `FK_7BF59952164006B8` FOREIGN KEY (`locales_id`) REFERENCES `locales` (`id`);

--
-- Limitadores para a tabela `warning_translation`
--
ALTER TABLE `warning_translation`
  ADD CONSTRAINT `FK_D8A1DDE6164006B8` FOREIGN KEY (`locales_id`) REFERENCES `locales` (`id`),
  ADD CONSTRAINT `FK_D8A1DDE6BFF38603` FOREIGN KEY (`warning_id`) REFERENCES `warning` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;