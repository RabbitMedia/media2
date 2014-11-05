DROP DATABASE IF EXISTS lezsoku;
CREATE DATABASE IF NOT EXISTS `lezsoku`;

USE lezsoku;

CREATE TABLE `product_master` (
  `master_id`   INT(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'マスターID',
  `product_id`  VARCHAR(128)     NOT NULL                COMMENT '作品ID',
  `title`       VARCHAR(128)     NOT NULL                COMMENT '作品タイトル',
  `product_url` VARCHAR(128)     NOT NULL                COMMENT '作品URL',
  `create_time` DATETIME         NOT NULL                COMMENT '作成日時',
  `update_time` DATETIME         NOT NULL                COMMENT '更新日時',
  `delete_time` DATETIME         DEFAULT NULL            COMMENT '削除日時',
  PRIMARY KEY (`master_id`),
  KEY `product_master_idx_01` (`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT '作品マスター情報';

CREATE TABLE `product_text` (
  `master_id`   INT(10) UNSIGNED NOT NULL     COMMENT 'マスターID',
  `text`        TEXT             NOT NULL     COMMENT '作品本文',
  `create_time` DATETIME         NOT NULL     COMMENT '作成日時',
  `update_time` DATETIME         NOT NULL     COMMENT '更新日時',
  `delete_time` DATETIME         DEFAULT NULL COMMENT '削除日時',
  KEY `product_text_idx_01` (`master_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT '作品本文情報';

CREATE TABLE `product_category` (
  `master_id`   INT(10)    UNSIGNED NOT NULL             COMMENT 'マスターID',
  `category_id` TINYINT(3) UNSIGNED NOT NULL             COMMENT 'カテゴリーID',
  `create_time` DATETIME            NOT NULL             COMMENT '作成日時',
  `update_time` DATETIME            NOT NULL             COMMENT '更新日時',
  `delete_time` DATETIME            DEFAULT NULL         COMMENT '削除日時',
  KEY `product_category_idx_01` (`master_id`),
  KEY `product_category_idx_02` (`category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT '作品カテゴリーID情報';

CREATE TABLE `category_info` (
  `category_id` TINYINT(3)  UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'カテゴリーID',
  `name`        VARCHAR(32) NOT NULL                         COMMENT 'カテゴリー名',
  `create_time` DATETIME    NOT NULL                         COMMENT '作成日時',
  `update_time` DATETIME    NOT NULL                         COMMENT '更新日時',
  `delete_time` DATETIME    DEFAULT NULL                     COMMENT '削除日時',
  PRIMARY KEY (`category_id`),
  KEY `category_info_idx_01` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT 'カテゴリー情報';

CREATE TABLE `dashboard` (
  `id`          TINYINT(1)  UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `username`    VARCHAR(32)          NOT NULL                COMMENT 'ユーザー名',
  `password`    VARCHAR(32)          NOT NULL                COMMENT 'パスワード',
  `create_time` DATETIME             NOT NULL                COMMENT '作成日時',
  `update_time` DATETIME             NOT NULL                COMMENT '更新日時',
  `delete_time` DATETIME             DEFAULT NULL            COMMENT '削除日時',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT '管理者情報';
