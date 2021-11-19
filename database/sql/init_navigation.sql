/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

DROP TABLE IF EXISTS `rb_navigation`;
CREATE TABLE `rb_navigation` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '标题',
  `icon` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '图标',
  `target` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '跳转方式: 1=內部路由, 2=另開浏览器',
  `filter_id` int(11) NOT NULL DEFAULT '0' COMMENT '篩選條件',
  `link` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sort` tinyint(4) NOT NULL COMMENT '排序值: 数字越大越靠前',
  `status` tinyint(4) NOT NULL COMMENT '图标 URL',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `rb_navigation` (`id`, `title`, `icon`, `target`, `filter_id`, `link`, `sort`, `status`, `created_at`, `updated_at`) VALUES
(1, '精选日漫', 'navigation/2/n4mSmGgHqLZQ3q3a3CgUpuONIkZ5mVXqC7CG4ovw.png', 1, 2, '', 1, 1, NULL, '2021-10-31 03:29:34');
INSERT INTO `rb_navigation` (`id`, `title`, `icon`, `target`, `filter_id`, `link`, `sort`, `status`, `created_at`, `updated_at`) VALUES
(2, '精选韩漫', 'navigation/2/Fxf7JMtW7MUq8pdVXNw8n5XRh6tHeU0ZVBmirBNF.png', 1, 1, '', 2, 1, NULL, '2021-11-08 01:22:23');
INSERT INTO `rb_navigation` (`id`, `title`, `icon`, `target`, `filter_id`, `link`, `sort`, `status`, `created_at`, `updated_at`) VALUES
(3, '排行', 'navigation/2/kcAu6R798cCj0BaIwfKeEd1Kv0bFLz4Tk7utTZyl.png', 3, 0, 'ranking', 3, 1, NULL, '2021-11-08 01:21:34');
INSERT INTO `rb_navigation` (`id`, `title`, `icon`, `target`, `filter_id`, `link`, `sort`, `status`, `created_at`, `updated_at`) VALUES
(4, '充值', 'navigation/2/NwZbryVeb1wt8QMW2nESRXgEc1aZSZtyqqVz4tb9.png', 3, 0, 'deposit', 4, 1, NULL, '2021-11-12 01:16:53');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;