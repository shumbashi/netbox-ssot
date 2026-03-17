--
-- `#prefix#AppItems`
--
CREATE TABLE IF NOT EXISTS `#prefix#AppItems`
(
    `id`
    INT
    unsigned
    NOT
    NULL
    AUTO_INCREMENT,
    `type`
    VARCHAR
(
    255
) NOT NULL,
    `name` VARCHAR
(
    255
) NOT NULL,
    `description` TEXT DEFAULT '',
    `image` VARCHAR
(
    255
),
    `source` VARCHAR
(
    255
),
    `status` VARCHAR
(
    255
),
    PRIMARY KEY
(
    `id`
)
    ) ENGINE=InnoDB DEFAULT CHARSET=#charset# DEFAULT COLLATE #collation#;

--
-- `#prefix#AppItemConfig`
--

CREATE TABLE IF NOT EXISTS `#prefix#AppItemConfig`
(
    `id`
    INT
    unsigned
    NOT
    NULL
    AUTO_INCREMENT,
    `item_id`
    INT,
    `setting`
    VARCHAR
(
    255
) NOT NULL,
    `value` TEXT DEFAULT '',
    `source` VARCHAR
(
    255
),
    `description` TEXT DEFAULT NULL,
    `protected` BOOLEAN DEFAULT FALSE,
    `field` TEXT DEFAULT NULL,
    `options` TEXT DEFAULT NULL,
    `validator` TEXT DEFAULT NULL,
    `visible` BOOLEAN DEFAULT NULL,
    PRIMARY KEY
(
    `id`
)
    ) ENGINE=InnoDB DEFAULT CHARSET=#charset# DEFAULT COLLATE #collation#;

--
-- `#prefix#AppGroups`
--
CREATE TABLE IF NOT EXISTS `#prefix#AppGroups`
(
    `id`
    INT
    unsigned
    NOT
    NULL
    AUTO_INCREMENT,
    `name`
    VARCHAR
(
    255
),
    `description` TEXT DEFAULT '',
    PRIMARY KEY
(
    `id`
)
    ) ENGINE=InnoDB DEFAULT CHARSET=#charset# DEFAULT COLLATE #collation#;

--
-- `#prefix#AppItemsGroups`
--
CREATE TABLE IF NOT EXISTS `#prefix#AppItemsGroups`
(
    `id`
    INT
    unsigned
    NOT
    NULL
    AUTO_INCREMENT,
    `group_id`
    INT
    unsigned
    NOT
    NULL,
    `item_id`
    INT
    unsigned
    NOT
    NULL,
    PRIMARY
    KEY
(
    `id`
)
    ) ENGINE=InnoDB DEFAULT CHARSET=#charset# DEFAULT COLLATE #collation#;

