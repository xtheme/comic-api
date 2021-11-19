/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

DROP TABLE IF EXISTS `rb_filters`;
CREATE TABLE `rb_filters` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '标题',
  `type` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '分類: 影響標籤選擇',
  `tags` json DEFAULT NULL,
  `params` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `rb_filters` (`id`, `title`, `type`, `tags`, `params`, `created_at`, `updated_at`) VALUES
(1, '大家都在看', 'book', '{\"book\": [\"韩漫\", \"全彩\"]}', '{\"type\": \"0\", \"title\": null, \"author\": null, \"order_by\": \"created_at\", \"date_between\": null}', '2021-10-13 17:54:52', '2021-11-18 14:59:26');
INSERT INTO `rb_filters` (`id`, `title`, `type`, `tags`, `params`, `created_at`, `updated_at`) VALUES
(2, '猜你喜欢', 'book', '{\"book\": [\"韩漫\", \"精选\"]}', '{\"type\": \"0\", \"title\": null, \"author\": null, \"order_by\": \"created_at\", \"date_between\": null}', '2021-10-13 17:55:59', '2021-11-18 16:34:14');
INSERT INTO `rb_filters` (`id`, `title`, `type`, `tags`, `params`, `created_at`, `updated_at`) VALUES
(3, '重磅力荐', 'book', '{\"book\": [\"韩漫\", \"剧情\"]}', '{\"type\": \"0\", \"title\": null, \"author\": null, \"order_by\": \"created_at\", \"date_between\": null}', '2021-10-13 17:57:03', '2021-11-18 16:34:22');
INSERT INTO `rb_filters` (`id`, `title`, `type`, `tags`, `params`, `created_at`, `updated_at`) VALUES
(4, '完结作品', 'book', '{}', '{\"type\": \"2\", \"title\": null, \"author\": null, \"order_by\": \"created_at\", \"date_between\": null}', '2021-10-24 04:16:57', '2021-11-04 00:25:36');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;