CREATE TABLE IF NOT EXISTS `users` (
                                       `id` int(11) NOT NULL AUTO_INCREMENT,
                                       `email` varchar(250) NOT NULL,
                                       `password` varchar(250) NOT NULL,
                                       `role` varchar(10) NOT NULL,
                                       `salutation` varchar(50) NOT NULL,
                                       `active` int(1) NOT NULL DEFAULT '1',
                                       PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4;

INSERT INTO `users` (`email`, `password`, `role`, `salutation`, `active`) VALUES
('info@filipurban.cz', '$2y$10$xmlzTMUxlXnST3q0KT2DgeKg8A1.NwUV4U9To7c3D0DEsT6mRUK6a', 'dev', 'Filipe', 1);