CREATE TABLE `processeightadminhtmlexamples_aftcop_quote_to_order_type`
(
    `quote_to_order_type_id`       int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Primary Key ID',
    `quote_id`                     int(11) unsigned NOT NULL COMMENT 'Quote ID',
    `order_type_id`                smallint(6)      NOT NULL DEFAULT '1' COMMENT 'Order Type ID',
    `reference_order_increment_id` varchar(32)      NOT NULL COMMENT 'Reference Order Increment ID',
    `created_at`                   timestamp        NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Created At',
    `updated_at`                   timestamp        NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Updated At',
    PRIMARY KEY (`quote_to_order_type_id`),
    KEY `PEAE_AFTCOP_QUOTE_TO_ORDER_TYPE_ORDER_TYPE_ID` (`order_type_id`),
    KEY `PEAE_AFTCOP_QUOTE_TO_ORDER_TYPE_QUOTE_ID_QUOTE_ENTITY_ID` (`quote_id`),
    CONSTRAINT `PEAE_AFTCOP_QUOTE_TO_ORDER_TYPE_QUOTE_ID_QUOTE_ENTITY_ID` FOREIGN KEY (`quote_id`) REFERENCES `quote` (`entity_id`) ON DELETE CASCADE
) ENGINE = InnoDB
  AUTO_INCREMENT = 2
  DEFAULT CHARSET = utf8 COMMENT ='Quote to Order Type Associative Table';

CREATE TABLE `processeightadminhtmlexamples_aftcop_order_to_order_type`
(
    `order_to_order_type_id`       int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Primary Key ID',
    `order_id`                     int(10) unsigned NOT NULL COMMENT 'Sales Order ID',
    `order_type_id`                smallint(6)      NOT NULL DEFAULT '1' COMMENT 'Order Type ID',
    `reference_order_increment_id` varchar(32)      NOT NULL COMMENT 'Reference Order Increment ID',
    `created_at`                   timestamp        NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Created At',
    `updated_at`                   timestamp        NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Updated At',
    PRIMARY KEY (`order_to_order_type_id`),
    KEY `PEAE_AFTCOP_ORDER_TO_ORDER_TYPE_ORDER_TYPE_ID` (`order_type_id`),
    KEY `PEAE_AFTCOP_ORDER_TO_ORDER_TYPE_ORDER_ID_ORDER_ENTITY_ID` (`order_id`),
    CONSTRAINT `PEAE_AFTCOP_ORDER_TO_ORDER_TYPE_ORDER_ID_ORDER_ENTITY_ID` FOREIGN KEY (`order_id`) REFERENCES `sales_order` (`entity_id`) ON DELETE CASCADE
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8 COMMENT ='Order to Order Type Associative Table';
