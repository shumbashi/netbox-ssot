--
-- `#prefix#Langs`
--
CREATE TABLE IF NOT EXISTS `#prefix#EasyTranslator`
(
    `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
    `language` VARCHAR(32) NOT NULL,
    `lang` VARCHAR(255) NOT NULL,
    `value` TEXT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY(`id`)
 ) ENGINE=InnoDB DEFAULT CHARSET=#charset# DEFAULT COLLATE #collation#;

--
-- `#prefix#MissingLangs`
--
CREATE TABLE IF NOT EXISTS `#prefix#MissingLang`
(
    `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
    `language` VARCHAR(32) NOT NULL,
    `lang` VARCHAR(255) NOT NULL,
    `source` VARCHAR(255) DEFAULT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY(`id`),
    KEY(lang)
) ENGINE=InnoDB DEFAULT CHARSET=#charset# DEFAULT COLLATE #collation#;

--
-- `#prefix#DynamicTranslation`
--
CREATE TABLE IF NOT EXISTS `#prefix#DynamicTranslation`
(
    `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
    `lang` VARCHAR(255) NOT NULL,
    `regex` VARCHAR(255) NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=#charset# DEFAULT COLLATE #collation#;