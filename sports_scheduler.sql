CREATE TABLE `schedules` (
  `id` int(11) NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `group_name` varchar(255) NOT NULL,
  `match_count` int(11) NOT NULL,
  `player1` varchar(255) NOT NULL,
  `player2` varchar(255) NOT NULL,
  `player3` varchar(255) NOT NULL,
  `player4` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `note` varchar(255) DEFAULT NULL
);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
);
