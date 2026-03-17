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