-- ============================================================
-- HighFlights database v2 - genormaliseerd met PK's en FK's
-- ============================================================
-- Wat is er veranderd t.o.v. de oude dump:
--   1. Alle dubbele data weg: bookings.destination_name,
--      bookings.departure_date (staat al in flights) en het
--      extras tekstveld.
--   2. Nieuwe tabellen: airlines, extras en booking_extras
--      (koppeltabel = many-to-many relatie voor je ERD).
--   3. Foreign keys op alle relaties.
--   4. Rotte testdata gefixt:
--      - Flights 7 en 15 (destination_id 0, bestond niet) verwijderd.
--      - Bookings wezen naar flight_ids 1,2,4,5,6 die niet bestonden.
--        Gefixt naar de juiste flights o.b.v. bestemming + datum.
--      - 'AmsterKam' -> 'Amsterdam'.
--   5. contact_messages heeft nu een optionele user_id (NULL = gast).
-- ============================================================

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

-- Eerst de kindtabellen droppen, dan pas de oudertabellen
-- (andersom weigert MySQL vanwege de foreign keys)
DROP TABLE IF EXISTS `booking_extras`;
DROP TABLE IF EXISTS `bookings`;
DROP TABLE IF EXISTS `reviews`;
DROP TABLE IF EXISTS `contact_messages`;
DROP TABLE IF EXISTS `flights`;
DROP TABLE IF EXISTS `extras`;
DROP TABLE IF EXISTS `airlines`;
DROP TABLE IF EXISTS `destinations`;
DROP TABLE IF EXISTS `users`;

-- ------------------------------------------------------------
-- Tabel: users
-- ------------------------------------------------------------

CREATE TABLE `users` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `first_name` varchar(80) NOT NULL,
  `last_name` varchar(80) NOT NULL,
  `email` varchar(180) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('customer','admin') NOT NULL DEFAULT 'customer',
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO `users` (`id`, `first_name`, `last_name`, `email`, `password`, `role`) VALUES
(1, 'Admin', 'HighFlights', 'admin@highflights.nl', 'admin123', 'admin'),
(2, 'Tobi', 'Quenum', 'tobi@example.com', 'tobi123', 'customer'),
(3, 'Fero', 'Seifert', 'fero@example.com', 'fero123', 'customer'),
(4, 'Lisa', 'de Vries', 'lisa@example.com', 'lisa123', 'customer'),
(5, 'Sara', 'Bakker', 'sara@example.com', 'sara123', 'customer'),
(6, 'Daan', 'Visser', 'daan@example.com', 'daan123', 'customer'),
(7, 'Nina', 'Mulder', 'nina@example.com', 'nina123', 'customer');

-- ------------------------------------------------------------
-- Tabel: destinations
-- ------------------------------------------------------------

CREATE TABLE `destinations` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(120) NOT NULL,
  `city` varchar(120) NOT NULL,
  `airport` char(3) NOT NULL,
  `description` text,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO `destinations` (`id`, `name`, `city`, `airport`, `description`, `active`) VALUES
(1, 'Finland', 'Helsinki', 'HEL', 'Rustige wouden en stille meren. Perfecte vibe voor een slow trip.', 1),
(2, 'Morocco', 'Marrakech', 'RAK', 'Sunny 30° en een levendige cultuur. Ontspannen in de medina.', 1),
(3, 'Canada', 'Vancouver', 'YVR', 'Groene natuur, ontspannen stad.', 1),
(4, 'Thailand', 'Bangkok', 'BKK', 'Coastal retreats en easy island vibes.', 1),
(5, 'Malta', 'Valletta', 'MLA', 'Mediterrane rust, kleine eilanden, warm water.', 1),
(6, 'Mexico', 'Mexico City', 'MEX', 'Kleurrijke cultuur, goed eten en warme nachten.', 1),
(7, 'Germany', 'Berlin', 'BER', 'Culturele hotspot met een underground scene.', 1),
(8, 'Netherlands', 'Amsterdam', 'AMS', 'Thuisbasis. Altijd goed om terug te komen.', 1);

-- ------------------------------------------------------------
-- Tabel: airlines (nieuw)
-- Airline was eerst een losse varchar in flights = dubbele data.
-- ------------------------------------------------------------

CREATE TABLE `airlines` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(120) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO `airlines` (`id`, `name`) VALUES
(1, 'GreenAir'),
(2, 'CalmJet'),
(3, 'MelloAir'),
(4, 'SunRoute'),
(5, 'LocalHop');

-- ------------------------------------------------------------
-- Tabel: extras (nieuw)
-- Extras was eerst een tekstveld in bookings ("Hotel Included, ...").
-- Nu een eigen tabel; de koppeling zit in booking_extras.
-- Prijzen zijn voorbeeldprijzen, pas gerust aan.
-- ------------------------------------------------------------

CREATE TABLE `extras` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(120) NOT NULL,
  `price` decimal(8,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO `extras` (`id`, `name`, `price`) VALUES
(1, 'Hotel Included', 350.00),
(2, 'Travel Protection', 45.00),
(3, 'High Onboarding', 90.00),
(4, 'Transport Included', 60.00),
(5, 'Pet Friendly', 75.00);

-- ------------------------------------------------------------
-- Tabel: flights
-- destination_name weg (via FK op te halen), airline is nu een FK.
-- Flights 7 en 15 (rotte testdata) bestaan niet meer.
-- ------------------------------------------------------------

CREATE TABLE `flights` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `destination_id` int UNSIGNED NOT NULL,
  `airline_id` int UNSIGNED NOT NULL,
  `departure_name` varchar(50) NOT NULL DEFAULT 'Amsterdam',
  `departure_date` datetime NOT NULL,
  `return_date` datetime NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `seats` smallint NOT NULL DEFAULT '180',
  `active` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_flights_destination` FOREIGN KEY (`destination_id`) REFERENCES `destinations` (`id`),
  CONSTRAINT `fk_flights_airline` FOREIGN KEY (`airline_id`) REFERENCES `airlines` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO `flights` (`id`, `destination_id`, `airline_id`, `departure_name`, `departure_date`, `return_date`, `price`, `seats`, `active`) VALUES
(3, 3, 1, 'Amsterdam', '2026-09-01 13:00:00', '2026-09-08 21:00:00', 899.00, 150, 1),
(8, 1, 1, 'Amsterdam', '2026-08-15 10:25:00', '2026-08-22 14:00:00', 719.00, 180, 1),
(9, 2, 2, 'Amsterdam', '2026-07-10 07:00:00', '2026-07-17 19:30:00', 574.00, 200, 1),
(10, 4, 3, 'Amsterdam', '2026-10-05 23:00:00', '2026-10-19 06:00:00', 1149.00, 160, 1),
(11, 5, 4, 'Amsterdam', '2026-07-28 06:45:00', '2026-08-04 20:15:00', 389.00, 220, 1),
(12, 6, 1, 'Amsterdam', '2026-09-14 11:00:00', '2026-09-28 17:00:00', 1299.00, 140, 1),
(13, 7, 2, 'Amsterdam', '2026-07-03 08:00:00', '2026-07-07 18:00:00', 199.00, 250, 1),
(14, 8, 5, 'Amsterdam', '2026-08-01 09:00:00', '2026-08-01 10:00:00', 49.00, 80, 1);

-- ------------------------------------------------------------
-- Tabel: bookings
-- destination_name, departure_date en extras weg: dat haal je nu
-- op via flights (JOIN) en booking_extras.
-- total_price blijft: dat is snapshot data, geen dubbele data.
-- LET OP: flight_ids zijn gecorrigeerd, de oude data wees deels
-- naar flights die niet bestonden.
-- ------------------------------------------------------------

CREATE TABLE `bookings` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int UNSIGNED NOT NULL,
  `flight_id` int UNSIGNED NOT NULL,
  `reference` varchar(30) NOT NULL,
  `travelers` tinyint NOT NULL DEFAULT '1',
  `total_price` decimal(10,2) NOT NULL,
  `status` enum('pending','confirmed','cancelled') NOT NULL DEFAULT 'pending',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `reference` (`reference`),
  CONSTRAINT `fk_bookings_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  CONSTRAINT `fk_bookings_flight` FOREIGN KEY (`flight_id`) REFERENCES `flights` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO `bookings` (`id`, `user_id`, `flight_id`, `reference`, `travelers`, `total_price`, `status`, `created_at`) VALUES
(1, 2, 8, 'HF-20260601-001', 2, 1663.86, 'confirmed', '2026-06-01 09:12:00'),
(2, 3, 9, 'HF-20260602-002', 1, 574.00, 'confirmed', '2026-06-02 14:30:00'),
(3, 4, 13, 'HF-20260603-003', 3, 952.79, 'pending', '2026-06-03 10:05:00'),
(4, 2, 3, 'HF-20260604-004', 1, 1286.93, 'confirmed', '2026-06-04 16:44:00'),
(5, 3, 8, 'HF-20260605-005', 1, 719.00, 'cancelled', '2026-06-05 11:20:00'),
(6, 5, 10, 'HF-20260610-006', 2, 2298.00, 'confirmed', '2026-06-10 09:00:00'),
(7, 6, 11, 'HF-20260611-007', 1, 389.00, 'pending', '2026-06-11 13:15:00'),
(8, 7, 12, 'HF-20260612-008', 3, 3897.00, 'confirmed', '2026-06-12 10:30:00'),
(9, 5, 3, 'HF-20260613-009', 1, 899.00, 'cancelled', '2026-06-13 08:00:00'),
(10, 6, 13, 'HF-20260614-010', 2, 398.00, 'confirmed', '2026-06-14 11:45:00'),
(11, 2, 14, 'HF-6a3d13567f955', 1, 49.00, 'pending', '2026-06-25 11:39:02'),
(12, 2, 14, 'HF-6a3d13bf5ca4a', 1, 49.00, 'pending', '2026-06-25 11:40:47'),
(13, 2, 14, 'HF-6a3d168cf329a', 5, 245.00, 'pending', '2026-06-25 11:52:44');

-- ------------------------------------------------------------
-- Tabel: booking_extras (nieuw, koppeltabel)
-- Many-to-many: een booking kan meerdere extras hebben en een
-- extra hoort bij meerdere bookings. PK is de combinatie van
-- beide kolommen, dus dezelfde extra kan niet 2x op 1 booking.
-- ON DELETE CASCADE: verwijder je een booking, dan gaan de
-- gekoppelde extras-rijen automatisch mee.
-- ------------------------------------------------------------

CREATE TABLE `booking_extras` (
  `booking_id` int UNSIGNED NOT NULL,
  `extra_id` int UNSIGNED NOT NULL,
  PRIMARY KEY (`booking_id`, `extra_id`),
  CONSTRAINT `fk_be_booking` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_be_extra` FOREIGN KEY (`extra_id`) REFERENCES `extras` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO `booking_extras` (`booking_id`, `extra_id`) VALUES
(1, 1), (1, 2),
(2, 3),
(3, 4),
(4, 1), (4, 2),
(6, 1), (6, 3),
(8, 4), (8, 5),
(9, 3);

-- ------------------------------------------------------------
-- Tabel: reviews
-- ------------------------------------------------------------

CREATE TABLE `reviews` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int UNSIGNED NOT NULL,
  `destination_id` int UNSIGNED NOT NULL,
  `rating` tinyint NOT NULL,
  `review_text` text NOT NULL,
  `validated` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_reviews_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  CONSTRAINT `fk_reviews_destination` FOREIGN KEY (`destination_id`) REFERENCES `destinations` (`id`),
  CONSTRAINT `chk_rating` CHECK (`rating` BETWEEN 1 AND 5)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO `reviews` (`id`, `user_id`, `destination_id`, `rating`, `review_text`, `validated`, `created_at`) VALUES
(1, 2, 1, 5, 'Finland bestee', 1, '2026-06-08 10:00:00'),
(2, 3, 2, 4, 'Bro waar is Fero?', 1, '2026-06-08 11:30:00'),
(3, 2, 3, 5, 'Vancouver overtrof alle verwachtingen. Natuur, eten, mensen — alles top.', 0, '2026-06-09 08:15:00'),
(4, 4, 7, 3, 'Berlijn was oké, niet super maar zeker niet slecht.', 0, '2026-06-09 09:00:00'),
(5, 5, 4, 5, 'Ongelooflijk. De vlucht zelf was al een ervaring, en Thailand deed de rest. Absoluut terug.', 1, '2026-06-14 09:00:00'),
(6, 6, 7, 4, 'Berlijn is altijd een goed idee. HighFlights regelde alles soepel.', 1, '2026-06-15 14:20:00'),
(7, 7, 6, 5, 'Mexico City overtrof alles. De service van HighFlights was top van begin tot eind.', 1, '2026-06-16 16:00:00'),
(8, 2, 5, 4, 'Malta is klein maar o zo mooi. Rustig, warm, precies wat je wil.', 0, '2026-06-17 10:10:00'),
(9, 3, 1, 3, 'Was koud maar dat wisten we. Sauna saved the trip.', 0, '2026-06-18 12:00:00');

-- ------------------------------------------------------------
-- Tabel: contact_messages
-- Nieuw: optionele user_id. Ingelogde user = gekoppeld,
-- gast = NULL. Ophalen doe je met een LEFT JOIN.
-- ON DELETE SET NULL: verwijder je de user, dan blijft het
-- bericht bestaan maar wordt het een "gast" bericht.
-- ------------------------------------------------------------

CREATE TABLE `contact_messages` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int UNSIGNED DEFAULT NULL,
  `name` varchar(180) NOT NULL,
  `email` varchar(180) NOT NULL,
  `subject` varchar(120) DEFAULT NULL,
  `message` text NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_contact_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO `contact_messages` (`id`, `user_id`, `name`, `email`, `subject`, `message`, `created_at`) VALUES
(1, 2, 'Tobi Quenum', 'tobi@example.com', 'Vraag over boeking', 'Wanneer ontvang ik mijn bevestiging voor HF-20260601-001?', '2026-06-05 13:00:00'),
(2, NULL, 'Jan Smit', 'jan@gmail.com', 'Groepsreis mogelijk?', 'Zijn er kortingen voor groepen van 10+ personen?', '2026-06-06 09:45:00'),
(3, 3, 'Fero Seifert', 'fero@example.com', 'Annulering', 'Ik wil boeking HF-20260605-005 annuleren, hoe werkt de terugbetaling?', '2026-06-07 16:20:00'),
(4, NULL, 'test test', 'test@test.nl', 'Vraag over boeking', 'test', '2026-06-09 11:45:44'),
(5, 5, 'Sara Bakker', 'sara@example.com', 'Bagage vraag', 'Hoeveel kilo bagage mag ik meenemen op de Thailand vlucht?', '2026-06-10 10:00:00'),
(6, 6, 'Daan Visser', 'daan@example.com', 'Upgrade mogelijk?', 'Kan ik mijn stoel upgraden naar extra legroom?', '2026-06-11 14:00:00'),
(7, NULL, 'Onbekend', 'anon@mail.com', NULL, 'Wanneer komen er nieuwe bestemmingen bij?', '2026-06-13 09:30:00');

-- ------------------------------------------------------------
-- AUTO_INCREMENT startwaardes gelijk trekken met de data
-- ------------------------------------------------------------

ALTER TABLE `users` AUTO_INCREMENT = 8;
ALTER TABLE `destinations` AUTO_INCREMENT = 9;
ALTER TABLE `airlines` AUTO_INCREMENT = 6;
ALTER TABLE `extras` AUTO_INCREMENT = 6;
ALTER TABLE `flights` AUTO_INCREMENT = 16;
ALTER TABLE `bookings` AUTO_INCREMENT = 14;
ALTER TABLE `reviews` AUTO_INCREMENT = 10;
ALTER TABLE `contact_messages` AUTO_INCREMENT = 8;

COMMIT;
