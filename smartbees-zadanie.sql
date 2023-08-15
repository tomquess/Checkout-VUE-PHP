-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:4306
-- Czas generowania: 15 Sie 2023, 21:21
-- Wersja serwera: 10.4.22-MariaDB
-- Wersja PHP: 7.3.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Baza danych: `checkout`
--

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `discountcodes`
--

CREATE TABLE `discountcodes` (
  `id` int(11) NOT NULL,
  `code` varchar(255) NOT NULL,
  `discount` float NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 0,
  `time` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Zrzut danych tabeli `discountcodes`
--

INSERT INTO `discountcodes` (`id`, `code`, `discount`, `active`, `time`) VALUES
(3, 'discount15', 0.15, 1, '2023-07-30'),
(4, 'discount25', 0.25, 0, '2023-07-30'),
(5, 'test', 0.5, 1, '2023-07-30');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `orders`
--

CREATE TABLE `orders` (
  `id` int(5) NOT NULL,
  `name` varchar(55) NOT NULL,
  `surname` varchar(255) NOT NULL,
  `nation` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `postcode` varchar(12) NOT NULL,
  `city` varchar(255) NOT NULL,
  `phonenumber` varchar(15) NOT NULL,
  `alt_delivery` tinyint(1) NOT NULL,
  `alt_address` varchar(255) DEFAULT NULL,
  `alt_postcode` varchar(12) DEFAULT NULL,
  `alt_city` varchar(255) DEFAULT NULL,
  `delivery_type` varchar(50) NOT NULL,
  `payment_type` varchar(50) NOT NULL,
  `products` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`products`)),
  `partial_price` float NOT NULL,
  `full_price` float NOT NULL,
  `comments` text DEFAULT NULL,
  `newsletter` tinyint(1) NOT NULL,
  `law` tinyint(1) NOT NULL,
  `date_purchase` date NOT NULL DEFAULT current_timestamp(),
  `delivery_code` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indeksy dla zrzutów tabel
--

--
-- Indeksy dla tabeli `discountcodes`
--
ALTER TABLE `discountcodes`
  ADD UNIQUE KEY `code` (`code`),
  ADD KEY `id` (`id`);

--
-- Indeksy dla tabeli `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT dla zrzuconych tabel
--

--
-- AUTO_INCREMENT dla tabeli `discountcodes`
--
ALTER TABLE `discountcodes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT dla tabeli `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
