CREATE TABLE `profiles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `a_id` int(11) NOT NULL COMMENT 'id_a в таблице a',
  `dep_po_id` int(11) DEFAULT NULL COMMENT 'id в таблицы dep_po',
  `organizations_id` int(11) DEFAULT NULL COMMENT 'id в таблице organizations',
  `date_added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'дата добавления к "системе profiles"',
  PRIMARY KEY (`id`),
  KEY `profiles_FK_1` (`organizations_id`),
  KEY `profiles_FK` (`department_post_id`),
  CONSTRAINT `profiles_FK` FOREIGN KEY (`department_post_id`) REFERENCES `department_post` (`id`) ON DELETE CASCADE,
  CONSTRAINT `profiles_FK_1` FOREIGN KEY (`organizations_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='Профили сотрудников';
