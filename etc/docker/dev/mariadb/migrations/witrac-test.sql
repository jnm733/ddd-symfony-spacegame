
CREATE DATABASE IF NOT EXISTS `spacegame`;

USE `spacegame`;

# Volcado de tabla canvas
# ------------------------------------------------------------

DROP TABLE IF EXISTS `canvas`;

CREATE TABLE `canvas` (
  `id` varchar(150) NOT NULL DEFAULT '',
  `name` varchar(75) DEFAULT NULL,
  `width` int(11) DEFAULT NULL,
  `height` int(11) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Volcado de tabla obstacles
# ------------------------------------------------------------

DROP TABLE IF EXISTS `obstacles`;

CREATE TABLE `obstacles` (
 `id` varchar(150) NOT NULL DEFAULT '',
 `id_canvas` varchar(150) NOT NULL DEFAULT '',
 `x_position` int(11) NOT NULL,
 `y_position` int(11) NOT NULL,
 `created_at` datetime NOT NULL,
 `updated_at` datetime NOT NULL,
 `deleted_at` datetime DEFAULT NULL,
 PRIMARY KEY (`id`),
 KEY `id_canvas` (`id_canvas`),
 CONSTRAINT `obstacles_ibfk_1` FOREIGN KEY (`id_canvas`) REFERENCES `canvas` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Volcado de tabla spaceships
# ------------------------------------------------------------

DROP TABLE IF EXISTS `spaceships`;

CREATE TABLE `spaceships` (
  `id` varchar(150) NOT NULL DEFAULT '',
  `id_canvas` varchar(150) NOT NULL DEFAULT '',
  `x_position` int(11) NOT NULL,
  `y_position` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_canvas` (`id_canvas`),
  CONSTRAINT `spaceships_ibfk_1` FOREIGN KEY (`id_canvas`) REFERENCES `canvas` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;