CREATE TABLE `passing_competencies` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `a_id` int(10) unsigned NOT NULL COMMENT 'id_a в таблице a',
  `begin_date` date DEFAULT NULL COMMENT 'дата начала прохождения',
  `completion_date` date DEFAULT NULL COMMENT 'дата завершения',
  `completion_file` varchar(100) DEFAULT NULL COMMENT 'файл об успешном завершении курса',
  `competencies_id` int(10) unsigned NOT NULL COMMENT 'id в таблице competencies',
  `is_deleted` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `passing_competencies_FK` (`competencies_id`),
  CONSTRAINT `passing_competencies_FK` FOREIGN KEY (`competencies_id`) REFERENCES `competencies` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='прохождение компетенций';
