CREATE TABLE IF NOT EXISTS `#prefix#Job`
(
    `id` int
(
    11
) UNSIGNED NOT NULL AUTO_INCREMENT,
    `retry_after` datetime NOT NULL,
    `retry_count` int
(
    10
) UNSIGNED NOT NULL,
    `job` varchar
(
    255
) NOT NULL,
    `data` text,
    `queue` varchar
(
    32
) DEFAULT 'default',
    `parent_id` int
(
    11
) UNSIGNED DEFAULT NULL,
    `rel_id` int
(
    11
) UNSIGNED DEFAULT NULL,
    `rel_type` varchar
(
    32
) DEFAULT NULL,
    `rel_custom` VARCHAR
(
    32
) DEFAULT NULL,
    `status` varchar
(
    32
) NOT NULL,
    `created_at` datetime DEFAULT null,
    `updated_at` datetime DEFAULT null,
    PRIMARY KEY
(
    `id`
),
    KEY
(
    `parent_id`
),
    KEY
(
    `rel_id`
),
    KEY
(
    `rel_type`
),
    KEY
(
    `rel_custom`
),
    KEY
(
    `status`
)
    ) ENGINE=InnoDB DEFAULT CHARSET=#charset# DEFAULT COLLATE #collation#;

CREATE TABLE IF NOT EXISTS `#prefix#JobLog`
(
    `id` int
(
    11
) unsigned NOT NULL AUTO_INCREMENT,
    `job_id` int
(
    11
) unsigned NOT NULL,
    `type` varchar
(
    32
) NOT NULL,
    `message` varchar
(
    512
) NOT NULL,
    `additional` text,
    `created_at` datetime NOT NULL,
    `updated_at` datetime NOT NULL,
    PRIMARY KEY
(
    `id`
),
    KEY `job_id`
(
    `job_id`
),
    KEY `type`
(
    `type`
)
    ) ENGINE=InnoDB DEFAULT CHARSET=#charset# DEFAULT COLLATE #collation#;