-- Exact copy from db86xl6rdsi9cx (2).sql on 2026-09-14T19:52:36+02:00
-- Table: discounts

CREATE TABLE `discounts` (
  `id` int NOT NULL,
  `discount_campaign` varchar(255) NOT NULL,
  `promo` varchar(30) NOT NULL,
  `status` enum('Yes','No') NOT NULL DEFAULT 'Yes',
  `agent_id` int DEFAULT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `discount_value` double(4,2) NOT NULL,
  `discount_type` varchar(255) NOT NULL,
  `parking_type` varchar(255) NOT NULL,
  `discount_for` varchar(255) NOT NULL,
  `admin_id` int NOT NULL,
  `added_on` datetime NOT NULL,
  `updated_on` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `discounts` (`id`, `discount_campaign`, `promo`, `status`, `agent_id`, `start_date`, `end_date`, `discount_value`, `discount_type`, `parking_type`, `discount_for`, `admin_id`, `added_on`, `updated_on`) VALUES
(101, 'General', 'PZ-Og-99', 'Yes', 1, '2019-09-17', '2025-03-26', 99.99, 'percent', 'airport_parking', 'Og', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(102, 'General', 'PZ-Og-COUP05', 'Yes', 1, '2019-09-25', '2029-10-25', 5.00, 'percent', 'airport_parking', 'Og', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(105, 'General', 'PZ-EM-ozt10', 'Yes', 1, '2020-09-08', '2040-12-31', 10.00, 'percent', 'airport_parking', 'EM', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(107, 'General', 'PZ-PPC-10', 'Yes', 1, '2022-10-21', '2027-12-23', 5.00, 'percent', 'airport_parking', 'PPC', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(109, 'General', 'YP-Og-20OFF', 'Yes', 2, '2023-01-01', '2024-12-31', 5.00, 'percent', 'airport_parking', 'Og', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(110, 'General', 'PZ-Og-Discount', 'Yes', NULL, '2023-10-05', '2030-12-31', 5.00, 'percent', 'airport_parking', 'Og', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(111, 'General', 'EZ-EM-ezt10', 'Yes', 4, '2023-10-10', '2032-10-21', 10.00, 'percent', 'airport_parking', 'EM', 0, '2023-10-10 10:10:30', '2023-10-10 10:10:30'),
(112, 'General', 'PZ-AF-10', 'Yes', NULL, '2024-01-12', '2099-12-31', 10.00, 'percent', 'airport_parking', 'AF', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(113, 'General', 'TZ-PPC-10', 'Yes', NULL, '2024-01-12', '2090-01-31', 10.00, 'percent', 'airport_parking', 'PPC', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(114, 'General', 'TZ-Og-10', 'Yes', NULL, '2024-01-12', '2090-01-31', 10.00, 'percent', 'airport_parking', 'Og', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(115, 'General', 'PZ-AF-01', 'Yes', NULL, '2024-01-13', '2099-12-31', 1.00, 'percent', 'airport_parking', 'AF', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(116, 'General', 'PZ-Og-off10', 'Yes', NULL, '2024-03-25', '2099-12-31', 10.00, 'percent', 'airport_parking', 'Og', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(117, 'General', 'PZ-Og-27', 'Yes', NULL, '2024-03-29', '2025-01-01', 27.00, 'percent', 'airport_parking', 'Og', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(118, 'General', 'PZ-Og-05', 'Yes', NULL, '2024-04-05', '2030-12-01', 5.00, 'percent', 'airport_parking', 'Og', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(119, 'General', 'PZ-Og-15', 'Yes', NULL, '2024-12-18', '2029-12-31', 15.00, 'percent', 'airport_parking', 'Og', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(120, 'General', 'PZ-AF-99', 'Yes', NULL, '2024-06-05', '2024-06-09', 99.00, 'percent', 'airport_parking', 'AF', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(121, 'General', 'PZ-Og--99', 'Yes', NULL, '2024-08-29', '2030-11-28', 99.00, 'percent', 'airport_parking', 'Og', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(122, 'General', 'PZ-Og-05', 'Yes', NULL, '2024-11-29', '2026-01-01', 5.00, 'percent', 'airport_parking', 'Og', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(123, 'General', 'PZ-Og-sub07', 'Yes', NULL, '2025-01-28', '2040-01-31', 6.00, 'percent', 'airport_parking', 'Og', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(125, 'General', 'JS-Og-05', 'Yes', NULL, '2025-10-24', '2090-01-11', 5.00, 'percent', 'airport_parking', 'Og', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(126, 'General', 'JS-Og-99', 'Yes', NULL, '2025-10-31', '2025-11-02', 99.00, 'percent', 'airport_parking', 'Og', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00');

ALTER TABLE `discounts`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `discounts`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=127;

