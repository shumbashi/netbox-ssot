CREATE TABLE IF NOT EXISTS `#prefix#Commands`
(
    `name` VARCHAR(64) NOT NULL UNIQUE,
    `uuid` VARCHAR(64) NOT NULL UNIQUE,
    `parent_uuid` VARCHAR(64) DEFAULT NULL,
    `status` enum('stopped', 'running', 'error') DEFAULT 'stopped',
    `action` enum('none','stop','reboot') DEFAULT 'none',
    `params` TEXT NOT NULL,
    `intervalType` enum('disabled', 'default', 'predefined', 'cron') DEFAULT 'default',
    `interval` VARCHAR(255) NULL,
    `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp,
    `started_at` timestamp,
    `ended_at` timestamp,
    PRIMARY KEY( `name`)
) ENGINE=InnoDB DEFAULT CHARSET=#charset# DEFAULT COLLATE #collation#;
