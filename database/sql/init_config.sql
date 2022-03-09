/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

DROP TABLE IF EXISTS `rb_configs`;
CREATE TABLE `rb_configs` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `options` json NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `rb_configs` (`id`, `name`, `code`, `options`, `created_at`, `updated_at`) VALUES
(1, '应用配置', 'app', '{\"register_point\": {\"value\": \"5\", \"remark\": \"注册送点数\"}, \"white_locations\": {\"value\": \"CN,TW,PH,JP,FR\", \"remark\": \"地区限制\"}, \"hourly_order_limit\": {\"value\": \"5\", \"remark\": \"每小时建立订单上限\"}}', '2021-10-09 20:05:30', '2022-03-09 10:29:23');
INSERT INTO `rb_configs` (`id`, `name`, `code`, `options`, `created_at`, `updated_at`) VALUES
(2, '漫画', 'comic', '{\"default_charge_point\": {\"value\": \"5\", \"remark\": \"预设收费点数\"}, \"default_charge_chapter\": {\"value\": \"3\", \"remark\": \"预设收费章节\"}}', '2021-10-09 20:35:20', '2022-03-09 10:30:12');
INSERT INTO `rb_configs` (`id`, `name`, `code`, `options`, `created_at`, `updated_at`) VALUES
(3, '视频', 'video', '{\"hls_domain\": {\"value\": null, \"remark\": \"视频域名\"}, \"img_domain\": {\"value\": null, \"remark\": \"图片域名\"}, \"daily_free_views\": {\"value\": \"3\", \"remark\": \"每日免费观看次数\"}}', '2021-10-09 21:01:51', '2022-03-09 14:35:29');
INSERT INTO `rb_configs` (`id`, `name`, `code`, `options`, `created_at`, `updated_at`) VALUES
(4, '客服', 'service', '{\"url\": \"http://3.112.2.169/bookacn.asp?kuse=comics2021&use=comics2021&sjs=152101310444&auto=1\", \"switch\": \"1\"}', '2021-10-14 18:02:22', '2021-10-14 18:04:30'),
(5, '前端配置', 'frontend', '{\"title\": \"色多多\"}', '2021-10-15 04:39:32', '2021-10-15 04:39:32');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;