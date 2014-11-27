DROP DATABASE IF EXISTS lezselect;
CREATE DATABASE IF NOT EXISTS `lezselect`;

USE lezselect;

CREATE TABLE `product_master` (
  `master_id`    INT(10)     UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'マスターID',
  `product_id`   VARCHAR(128)         NOT NULL                COMMENT '作品ID',
  `title`        VARCHAR(128)         NOT NULL                COMMENT '作品タイトル',
  `product_url`  VARCHAR(128)         NOT NULL                COMMENT '作品URL',
  `label_id`     SMALLINT(3) UNSIGNED NOT NULL                COMMENT 'レーベルID',
  `release_date` DATE                 NOT NULL                COMMENT '公開開始日',
  `create_time`  DATETIME             NOT NULL                COMMENT '作成日時',
  `update_time`  DATETIME             NOT NULL                COMMENT '更新日時',
  `delete_time`  DATETIME             DEFAULT NULL            COMMENT '削除日時',
  PRIMARY KEY (`master_id`),
  KEY `product_master_idx_01` (`product_id`),
  KEY `product_master_idx_02` (`label_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT '作品マスター情報';

CREATE TABLE `product_text` (
  `master_id`   INT(10) UNSIGNED NOT NULL     COMMENT 'マスターID',
  `text`        TEXT             NOT NULL     COMMENT '作品本文',
  `create_time` DATETIME         NOT NULL     COMMENT '作成日時',
  `update_time` DATETIME         NOT NULL     COMMENT '更新日時',
  `delete_time` DATETIME         DEFAULT NULL COMMENT '削除日時',
  KEY `product_text_idx_01` (`master_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT '作品本文情報';

CREATE TABLE `product_actress` (
  `master_id`   INT(10)      UNSIGNED NOT NULL     COMMENT 'マスターID',
  `actress_id`  MEDIUMINT(8) UNSIGNED NOT NULL     COMMENT '女優ID',
  `create_time` DATETIME              NOT NULL     COMMENT '作成日時',
  `update_time` DATETIME              NOT NULL     COMMENT '更新日時',
  `delete_time` DATETIME              DEFAULT NULL COMMENT '削除日時',
  KEY `product_actress_idx_01` (`master_id`),
  KEY `product_actress_idx_02` (`actress_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT '作品女優ID情報';

CREATE TABLE `product_thumbnail` (
  `id`            INT(10)      UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `master_id`     INT(10)      UNSIGNED NOT NULL                COMMENT 'マスターID',
  `thumbnail_url` VARCHAR(128)          NOT NULL                COMMENT 'サムネイルURL',
  `create_time`   DATETIME              NOT NULL                COMMENT '作成日時',
  `update_time`   DATETIME              NOT NULL                COMMENT '更新日時',
  `delete_time`   DATETIME              DEFAULT NULL            COMMENT '削除日時',
  PRIMARY KEY (`id`),
  KEY `product_thumbnail_idx_01` (`master_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT '作品サムネイル情報';

CREATE TABLE `product_category` (
  `master_id`    INT(10)    UNSIGNED NOT NULL     COMMENT 'マスターID',
  `category_id`  TINYINT(3) UNSIGNED NOT NULL     COMMENT 'カテゴリーID',
  `create_time`  DATETIME            NOT NULL     COMMENT '作成日時',
  `update_time`  DATETIME            NOT NULL     COMMENT '更新日時',
  `delete_time`  DATETIME            DEFAULT NULL COMMENT '削除日時',
  KEY `product_category_idx_01` (`master_id`),
  KEY `product_category_idx_02` (`category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT '作品カテゴリー情報';

CREATE TABLE `actress_list` (
  `actress_id`   MEDIUMINT(8) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '女優ID',
  `actress_name` VARCHAR(32)  NOT NULL                         COMMENT '女優名',
  `create_time`  DATETIME     NOT NULL                         COMMENT '作成日時',
  `update_time`  DATETIME     NOT NULL                         COMMENT '更新日時',
  `delete_time`  DATETIME     DEFAULT NULL                     COMMENT '削除日時',
  PRIMARY KEY (`actress_id`),
  KEY `actress_list_idx_01` (`actress_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT '女優リスト';
INSERT INTO actress_list (actress_name, create_time, update_time) VALUES ('その他', now(), now());

CREATE TABLE `label_list` (
  `label_id`    SMALLINT(3) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'レーベルID',
  `label_name`  VARCHAR(64) NOT NULL                         COMMENT 'レーベル名',
  `create_time` DATETIME    NOT NULL                         COMMENT '作成日時',
  `update_time` DATETIME    NOT NULL                         COMMENT '更新日時',
  `delete_time` DATETIME    DEFAULT NULL                     COMMENT '削除日時',
  PRIMARY KEY (`label_id`),
  KEY `label_list_idx_01` (`label_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT 'レーベルリスト';
INSERT INTO label_list (label_name, create_time, update_time) VALUES ('その他', now(), now());

CREATE TABLE `ranking` (
  `id`          INT(10)      UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `ranking_id`  MEDIUMINT(8) UNSIGNED NOT NULL                COMMENT 'ランキングID',
  `master_id`   INT(10)      UNSIGNED NOT NULL                COMMENT 'マスターID',
  `prev_rank`   VARCHAR(16)           NOT NULL                COMMENT '前回のランク',
  `create_time` DATETIME              NOT NULL                COMMENT '作成日時',
  `update_time` DATETIME              NOT NULL                COMMENT '更新日時',
  `delete_time` DATETIME              DEFAULT NULL            COMMENT '削除日時',
  PRIMARY KEY (`id`),
  KEY `ranking_idx_01` (`ranking_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT 'ランキング';

CREATE TABLE `ranking_control` (
  `id`          TINYINT(1)   UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `ranking_id`  MEDIUMINT(8) UNSIGNED NOT NULL                COMMENT 'ランキングID',
  `create_time` DATETIME              NOT NULL                COMMENT '作成日時',
  `update_time` DATETIME              NOT NULL                COMMENT '更新日時',
  `delete_time` DATETIME              DEFAULT NULL            COMMENT '削除日時',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT 'ランキングコントロール';

CREATE TABLE `dashboard` (
  `id`          TINYINT(1)  UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `username`    VARCHAR(32)          NOT NULL                COMMENT 'ユーザー名',
  `password`    VARCHAR(32)          NOT NULL                COMMENT 'パスワード',
  `create_time` DATETIME             NOT NULL                COMMENT '作成日時',
  `update_time` DATETIME             NOT NULL                COMMENT '更新日時',
  `delete_time` DATETIME             DEFAULT NULL            COMMENT '削除日時',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT '管理者情報';
