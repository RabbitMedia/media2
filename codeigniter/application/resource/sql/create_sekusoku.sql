DROP DATABASE IF EXISTS sekusoku;
CREATE DATABASE IF NOT EXISTS `sekusoku`;

USE sekusoku;

CREATE TABLE `video_master` (
  `master_id`     INT(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '動画マスターID',
  `title`         VARCHAR(128)     NOT NULL                COMMENT 'タイトル',
  `thumbnail_url` VARCHAR(255)     DEFAULT NULL            COMMENT 'サムネイルURL',
  `duration`      TIME             DEFAULT NULL            COMMENT '再生時間',
  `create_time`   DATETIME         NOT NULL                COMMENT '作成日時',
  `update_time`   DATETIME         NOT NULL                COMMENT '更新日時',
  `delete_time`   DATETIME         DEFAULT NULL            COMMENT '削除日時',
  PRIMARY KEY (`master_id`),
  KEY `video_master_idx_01` (`duration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT '動画マスター情報';

CREATE TABLE `video_id` (
  `master_id`    INT(10)    UNSIGNED NOT NULL     COMMENT '動画マスターID',
  `type`         TINYINT(3) UNSIGNED NOT NULL     COMMENT 'タイプ',
  `video_url_id` VARCHAR(128)        NOT NULL     COMMENT '動画URLID',
  `create_time`  DATETIME            NOT NULL     COMMENT '作成日時',
  `update_time`  DATETIME            NOT NULL     COMMENT '更新日時',
  `delete_time`  DATETIME            DEFAULT NULL COMMENT '削除日時',
  KEY `video_id_idx_01` (`master_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT '動画ID情報';

CREATE TABLE `video_category` (
  `master_id`    INT(10)    UNSIGNED NOT NULL             COMMENT '動画マスターID',
  `category`     TINYINT(3) UNSIGNED NOT NULL             COMMENT 'カテゴリー',
  `display_flag` TINYINT(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT '表示フラグ',
  `create_time`  DATETIME            NOT NULL             COMMENT '作成日時',
  `update_time`  DATETIME            NOT NULL             COMMENT '更新日時',
  `delete_time`  DATETIME            DEFAULT NULL         COMMENT '削除日時',
  KEY `video_category_idx_01` (`master_id`,`display_flag`),
  KEY `video_category_idx_02` (`category`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT '動画カテゴリー情報';

CREATE TABLE `crawler_video_master` (
  `crawler_master_id` INT(10) UNSIGNED NOT NULL     COMMENT 'クローラー動画マスターID',
  `duration`          TIME             DEFAULT NULL COMMENT '再生時間',
  `create_time`       DATETIME         NOT NULL     COMMENT '作成日時',
  `update_time`       DATETIME         NOT NULL     COMMENT '更新日時',
  `delete_time`       DATETIME         DEFAULT NULL COMMENT '削除日時',
  PRIMARY KEY (`crawler_master_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT 'クローラー動画マスター情報';

CREATE TABLE `crawler_video_id` (
  `crawler_master_id` INT(10)    UNSIGNED NOT NULL     COMMENT 'クローラー動画マスターID',
  `type`              TINYINT(3) UNSIGNED NOT NULL     COMMENT 'タイプ',
  `video_url_id`      VARCHAR(128) 	      NOT NULL     COMMENT '動画URLID',
  `create_time`       DATETIME            NOT NULL     COMMENT '作成日時',
  `update_time`       DATETIME            NOT NULL     COMMENT '更新日時',
  `delete_time`       DATETIME            DEFAULT NULL COMMENT '削除日時',
  KEY `crawler_video_id_idx_01` (`crawler_master_id`),
  UNIQUE `crawler_video_id_idx_02` (`type`,`video_url_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT 'クローラー動画ID情報';

CREATE TABLE `crawler_video_title` (
  `crawler_master_id` INT(10)    UNSIGNED NOT NULL     COMMENT 'クローラー動画マスターID',
  `media`             TINYINT(3) UNSIGNED DEFAULT NULL COMMENT 'メディア',
  `title`             VARCHAR(128)        DEFAULT NULL COMMENT 'タイトル',
  `create_time`       DATETIME            NOT NULL     COMMENT '作成日時',
  `update_time`       DATETIME            NOT NULL     COMMENT '更新日時',
  `delete_time`       DATETIME            DEFAULT NULL COMMENT '削除日時',
  KEY `crawler_video_title_idx_01` (`crawler_master_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT 'クローラー動画タイトル情報';

CREATE TABLE `dashboard` (
  `id`          TINYINT(1) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `username`    VARCHAR(32)         NOT NULL                COMMENT 'ユーザー名',
  `password`    VARCHAR(32)         NOT NULL                COMMENT 'パスワード',
  `create_time` DATETIME            NOT NULL                COMMENT '作成日時',
  `update_time` DATETIME            NOT NULL                COMMENT '更新日時',
  `delete_time` DATETIME            DEFAULT NULL            COMMENT '削除日時',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT '管理者情報';
