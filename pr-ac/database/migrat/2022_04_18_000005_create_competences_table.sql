CREATE TABLE `competencies` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `dep_id` int(10) unsigned DEFAULT NULL COMMENT 'id_dep из таблицы dep',
  `po_id` int(11) DEFAULT NULL COMMENT 'id из dep_po',
  `ability_id` int(10) unsigned DEFAULT NULL COMMENT 'id из abilities',
  `queue` float DEFAULT NULL COMMENT 'очередь в навыке и т.п.',
  `course_id` int(10) unsigned NOT NULL COMMENT 'id из courses',
  `is_deleted` tinyint(4) DEFAULT NULL,
  `ready` tinyint(4) DEFAULT NULL,
  `periodicity` int(11) DEFAULT NULL COMMENT 'периодичность',
  `course_link` varchar(100) DEFAULT NULL COMMENT 'ссылка на web-курс',
  PRIMARY KEY (`id`),
  KEY `competencies_FK` (`post_id`),
  KEY `competencies_FK_1` (`ability_id`),
  KEY `competencies_FK_2` (`course_id`),
  KEY `competencies_department_id_IDX` (`department_id`) USING BTREE,
  CONSTRAINT `competencies_FK` FOREIGN KEY (`post_id`) REFERENCES `department_post` (`id`) ON DELETE CASCADE,
  CONSTRAINT `competencies_FK_1` FOREIGN KEY (`ability_id`) REFERENCES `abilities` (`id`) ON DELETE CASCADE,
  CONSTRAINT `competencies_FK_2` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=47 DEFAULT CHARSET=utf8;
