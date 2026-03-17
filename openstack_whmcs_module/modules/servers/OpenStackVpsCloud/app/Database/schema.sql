--
-- `#prefix#Settings`
--
CREATE TABLE IF NOT EXISTS `#prefix#Settings` (
                                                  `id` int(10) NOT NULL AUTO_INCREMENT,
    `serviceID` int(10),
    `setting` varchar(222),
    `value` varchar(222),
    PRIMARY KEY (`id`),
    INDEX(`serviceID`),
    INDEX(`setting`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- `#prefix#Backups`
--
CREATE TABLE IF NOT EXISTS `#prefix#Backups` (
                                                 `id` int(10) NOT NULL AUTO_INCREMENT,
    `sourceID` VARCHAR(100),
    `backupID` VARCHAR(100),
    `backupName` VARCHAR(100),
    `pinned` BOOLEAN,
    PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- `#prefix#Keypairs`
--
CREATE TABLE IF NOT EXISTS `#prefix#Keypairs` (
                                                  `id` int(10) NOT NULL AUTO_INCREMENT,
    `hid` int(10),
    `key` BLOB,
    `publicKey` BLOB,
    `date` date,
    `name` varchar(100),
    PRIMARY KEY (`id`),
    UNIQUE (`hid`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- `#prefix#Servers`
--
CREATE TABLE IF NOT EXISTS `#prefix#Servers` (
                                                 `id` int(10) NOT NULL AUTO_INCREMENT,
    `serverID` int(10),
    `service` varchar(100),
    `nodeID` varchar(255),
    `endpoint` text,
    PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#prefix#ModuleCache` (
    `name` VARCHAR(255) NOT NULL,
    `value` TEXT NOT NULL,
    `valid_until` TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`name`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;