INSERT INTO `rb_admins` (`id`, `nickname`, `username`, `password`, `avatar`, `status`, `remember_token`, `login_ip`, `login_at`, `created_at`, `updated_at`) VALUES
(1, '超级管理员', 'admin', '$2y$10$HDoEv7QNTC9stOR9XTUJ3O7y015I7mLEG9Ws/9DqbdnMbmC3uMNki', '', 1, NULL, '18.167.171.160', '2021-09-06 17:08:30', NULL, '2021-09-01 19:01:19');

INSERT INTO `rb_categories` (`id`, `name`, `type`, `status`, `created_at`, `updated_at`) VALUES
(1, '漫画', 'book', 1, '2021-10-22 10:13:42', '2021-10-22 10:13:42'),
(2, '地区', 'video.area', 1, '2021-10-22 10:13:42', '2021-10-22 10:13:42'),
(3, '场景', 'video.place', 1, '2021-10-22 10:13:42', '2021-10-22 10:13:42'),
(4, '主题', 'video.topic', 1, '2021-10-22 10:13:42', '2021-10-22 10:13:42'),
(5, '性癖', 'video.leaning', 1, '2021-10-22 10:13:43', '2021-10-22 10:13:43'),
(6, '角色', 'video.identity', 1, '2021-10-22 10:13:43', '2021-10-22 10:13:43'),
(7, '穿着', 'video.wear', 1, '2021-10-22 10:13:43', '2021-10-22 10:13:43'),
(8, '身材', 'video.body', 1, '2021-10-22 10:13:43', '2021-10-22 10:13:43'),
(9, '体位', 'video.posture', 1, '2021-10-22 10:13:44', '2021-10-22 10:13:44'),
(10, '人数', 'video.player', 1, '2021-10-22 10:13:44', '2021-10-22 10:13:44');

INSERT INTO `rb_channels` (`id`, `channel_id`, `description`, `safe_landing`, `register_count`, `register_wap_count`, `register_app_count`, `recharge_count`, `recharge_wap_count`, `recharge_app_count`, `recharge_amount`, `recharge_wap_amount`, `recharge_app_amount`, `created_at`, `updated_at`) VALUES
(1, 1, 'default', 1, 1, 1, 0, 1, 1, 0, 0.00, 0.00, 0.00, '2021-10-25 16:00:56', '2021-10-26 18:32:08');

INSERT INTO `rb_configs` (`id`, `name`, `code`, `options`, `created_at`, `updated_at`) VALUES
(1, '應用配置', 'app', '{\"register_coin\": \"0\", \"hourly_order_limit\": \"5\"}', '2021-10-10 04:05:30', '2021-10-15 19:14:17'),
(2, '漫畫', 'comic', '{\"image_domain\": \"https://pic.honganll.com\", \"default_charge_price\": \"60\", \"encrypt_image_domain\": \"https://pic.honganll.com\", \"default_charge_chapter\": \"5\"}', '2021-10-10 04:35:20', '2021-10-15 19:14:58'),
(3, '視頻', 'video', '{\"image_domain\": \"https://pic.honganll.com\", \"encrypt_image_domain\": \"https://pic.honganll.com\"}', '2021-10-10 05:01:51', '2021-10-12 14:43:26'),
(4, '客服', 'service', '{\"url\": \"http://3.112.2.169/bookacn.asp?kuse=comics2021&use=comics2021&sjs=152101310444&auto=1\", \"switch\": \"1\"}', '2021-10-15 02:02:22', '2021-10-15 02:04:30'),
(5, '前端配置', 'frontend', '{\"title\": \"色多多\"}', '2021-10-15 12:39:32', '2021-10-15 12:39:32');

INSERT INTO `rb_migrations` (`id`, `migration`, `batch`) VALUES
(1, '2021_10_26_214106_create_activity_log_table', 0),
(2, '2021_10_26_214106_create_ad_spaces_table', 0),
(3, '2021_10_26_214106_create_admins_table', 0),
(4, '2021_10_26_214106_create_ads_table', 0),
(5, '2021_10_26_214106_create_book_chapters_table', 0),
(6, '2021_10_26_214106_create_books_table', 0),
(7, '2021_10_26_214106_create_categories_table', 0),
(8, '2021_10_26_214106_create_channel_daily_reports_table', 0),
(9, '2021_10_26_214106_create_channel_monthly_reports_table', 0),
(10, '2021_10_26_214106_create_channels_table', 0),
(11, '2021_10_26_214106_create_comment_likes_table', 0),
(12, '2021_10_26_214106_create_comments_table', 0),
(13, '2021_10_26_214106_create_configs_table', 0),
(14, '2021_10_26_214106_create_failed_jobs_table', 0),
(15, '2021_10_26_214106_create_filters_table', 0),
(16, '2021_10_26_214106_create_ladies_table', 0),
(17, '2021_10_26_214106_create_lady_cities_table', 0),
(18, '2021_10_26_214106_create_model_has_permissions_table', 0),
(19, '2021_10_26_214106_create_model_has_roles_table', 0),
(20, '2021_10_26_214106_create_movie_tags_table', 0),
(21, '2021_10_26_214106_create_movies_table', 0),
(22, '2021_10_26_214106_create_navigation_table', 0),
(23, '2021_10_26_214106_create_notices_table', 0),
(24, '2021_10_26_214106_create_orders_table', 0),
(25, '2021_10_26_214106_create_password_resets_table', 0),
(26, '2021_10_26_214106_create_payment_daily_reports_table', 0),
(27, '2021_10_26_214106_create_payment_monthly_reports_table', 0),
(28, '2021_10_26_214106_create_payment_pricing_table', 0),
(29, '2021_10_26_214106_create_payments_table', 0),
(30, '2021_10_26_214106_create_permissions_table', 0),
(31, '2021_10_26_214106_create_personal_access_tokens_table', 0),
(32, '2021_10_26_214106_create_pricings_table', 0),
(33, '2021_10_26_214106_create_ranking_logs_table', 0),
(34, '2021_10_26_214106_create_report_types_table', 0),
(35, '2021_10_26_214106_create_reports_table', 0),
(36, '2021_10_26_214106_create_resume_cities_table', 0),
(37, '2021_10_26_214106_create_resumes_table', 0),
(38, '2021_10_26_214106_create_role_has_permissions_table', 0),
(39, '2021_10_26_214106_create_roles_table', 0),
(40, '2021_10_26_214106_create_taggables_table', 0),
(41, '2021_10_26_214106_create_tags_table', 0),
(42, '2021_10_26_214106_create_topics_table', 0),
(43, '2021_10_26_214106_create_user_favorite_logs_table', 0),
(44, '2021_10_26_214106_create_user_purchase_logs_table', 0),
(45, '2021_10_26_214106_create_user_recharge_logs_table', 0),
(46, '2021_10_26_214106_create_user_visit_logs_table', 0),
(47, '2021_10_26_214106_create_users_table', 0),
(48, '2021_10_26_214106_create_videos_table', 0),
(49, '2021_10_26_214238_add_foreign_keys_to_model_has_permissions_table', 0),
(50, '2021_10_26_214238_add_foreign_keys_to_model_has_roles_table', 0),
(51, '2021_10_26_214238_add_foreign_keys_to_role_has_permissions_table', 0),
(52, '2021_10_26_214238_add_foreign_keys_to_taggables_table', 0);

INSERT INTO `rb_model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
(1, 'App\\Models\\Admin', 1);

INSERT INTO `rb_navigation` (`id`, `title`, `icon`, `target`, `filter_id`, `link`, `sort`, `status`, `created_at`, `updated_at`) VALUES
(1, '精选日漫', '/storage/navigation/japan.png', 1, 2, '', 1, 1, NULL, NULL),
(2, '精选韩漫', '/storage/navigation/korea.png', 1, 4, '', 2, 1, NULL, NULL),
(3, '排行', '/storage/navigation/ranking.png', 3, 0, 'ranking', 3, 1, NULL, '2021-10-24 05:30:52'),
(4, 'APP下載', '/storage/navigation/app.png', 2, 0, 'https://www.google.com', 4, 1, NULL, NULL);

INSERT INTO `rb_payments` (`id`, `name`, `url`, `app_id`, `app_key`, `button_text`, `button_icon`, `button_target`, `fee_percentage`, `sdk`, `daily_limit`, `pay_options`, `order_options`, `status`, `created_at`, `updated_at`) VALUES
(1, '女神支付寶', NULL, '4', '2741039981193e488c511baf1ab14568', '支付寶', '', 'new_tab', 13, 'GoddessGateway', 500, '{\"type\": \"alipay\", \"channel\": \"4\"}', '{}', 1, NULL, '2021-10-26 17:13:59'),
(2, '女神微信', NULL, '', '', '微信', '', 'new_tab', 13, 'GoddessGateway', 50000, '{\"type\": \"wechat\", \"channel\": \"4\"}', '[]', 1, '2021-10-11 07:01:35', '2021-10-26 17:14:15');

INSERT INTO `rb_permissions` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'backend.config.index', 'web', '2021-06-16 14:49:16', '2021-06-16 14:49:16'),
(2, 'backend.config.create', 'web', '2021-06-16 14:49:17', '2021-06-16 14:49:17'),
(3, 'backend.config.edit', 'web', '2021-06-16 14:49:19', '2021-06-16 14:49:19'),
(4, 'backend.config.destroy', 'web', '2021-06-16 14:49:20', '2021-06-16 14:49:20'),
(5, 'backend.user.index', 'web', '2021-06-16 14:49:22', '2021-06-16 14:49:22'),
(6, 'backend.user.edit', 'web', '2021-06-16 14:49:24', '2021-06-16 14:49:24'),
(7, 'backend.user.destroy', 'web', '2021-06-16 14:49:25', '2021-06-16 14:49:25'),
(8, 'backend.user.batch', 'web', '2021-06-16 14:49:27', '2021-06-16 14:49:27'),
(9, 'backend.user.editable', 'web', '2021-06-16 14:49:29', '2021-06-16 14:49:29'),
(10, 'backend.user.unbind', 'web', '2021-06-16 14:49:30', '2021-06-16 14:49:30'),
(11, 'backend.vip.edit', 'web', '2021-06-16 14:49:32', '2021-06-16 14:49:32'),
(12, 'backend.vip.transfer', 'web', '2021-06-16 14:49:33', '2021-06-16 14:49:33'),
(13, 'backend.order.index', 'web', '2021-06-16 14:49:35', '2021-06-16 14:49:35'),
(14, 'backend.order.export', 'web', '2021-06-16 14:49:36', '2021-06-16 14:49:36'),
(15, 'backend.book.index', 'web', '2021-06-16 14:49:38', '2021-06-16 14:49:38'),
(16, 'backend.book.create', 'web', '2021-06-16 14:49:40', '2021-06-16 14:49:40'),
(17, 'backend.book.edit', 'web', '2021-06-16 14:49:41', '2021-06-16 14:49:41'),
(18, 'backend.book.destroy', 'web', '2021-06-16 14:49:43', '2021-06-16 14:49:43'),
(19, 'backend.book.review', 'web', '2021-06-16 14:49:45', '2021-06-16 14:49:45'),
(20, 'backend.book.batch', 'web', '2021-06-16 14:49:46', '2021-06-16 14:49:46'),
(21, 'backend.book.editable', 'web', '2021-06-16 14:49:48', '2021-06-16 14:49:48'),
(22, 'backend.tag.index', 'web', '2021-06-16 14:49:50', '2021-06-16 14:49:50'),
(23, 'backend.tag.create', 'web', '2021-06-16 14:49:51', '2021-06-16 14:49:51'),
(24, 'backend.tag.batch', 'web', '2021-06-16 14:49:53', '2021-06-16 14:49:53'),
(25, 'backend.tag.editable', 'web', '2021-06-16 14:49:55', '2021-06-16 14:49:55'),
(26, 'backend.tag.destroy', 'web', '2021-06-16 14:49:57', '2021-06-16 14:49:57'),
(27, 'backend.book_chapter.index', 'web', '2021-06-16 14:49:59', '2021-06-16 14:49:59'),
(28, 'backend.book_chapter.preview', 'web', '2021-06-16 14:50:00', '2021-06-16 14:50:00'),
(29, 'backend.book_chapter.create', 'web', '2021-06-16 14:50:02', '2021-06-16 14:50:02'),
(30, 'backend.book_chapter.edit', 'web', '2021-06-16 14:50:04', '2021-06-16 14:50:04'),
(31, 'backend.book_chapter.batch', 'web', '2021-06-16 14:50:06', '2021-06-16 14:50:06'),
(32, 'backend.book_chapter.editable', 'web', '2021-06-16 14:50:07', '2021-06-16 14:50:07'),
(33, 'backend.feedback.index', 'web', '2021-06-16 14:50:09', '2021-06-16 14:50:09'),
(34, 'backend.feedback.destroy', 'web', '2021-06-16 14:50:11', '2021-06-16 14:50:11'),
(35, 'backend.feedback.batch_destroy', 'web', '2021-06-16 14:50:13', '2021-06-16 14:50:13'),
(36, 'backend.pricing.index', 'web', '2021-06-16 14:50:15', '2021-06-16 14:50:15'),
(37, 'backend.pricing.create', 'web', '2021-06-16 14:50:16', '2021-06-16 14:50:16'),
(38, 'backend.pricing.edit', 'web', '2021-06-16 14:50:18', '2021-06-16 14:50:18'),
(39, 'backend.pricing.destroy', 'web', '2021-06-16 14:50:20', '2021-06-16 14:50:20'),
(40, 'backend.comment.index', 'web', '2021-06-16 14:50:21', '2021-06-16 14:50:21'),
(41, 'backend.comment.destroy', 'web', '2021-06-16 14:50:23', '2021-06-16 14:50:23'),
(42, 'backend.comment.batch_destroy', 'web', '2021-06-16 14:50:24', '2021-06-16 14:50:24'),
(43, 'backend.notice.index', 'web', '2021-06-16 14:50:26', '2021-06-16 14:50:26'),
(44, 'backend.notice.create', 'web', '2021-06-16 14:50:28', '2021-06-16 14:50:28'),
(45, 'backend.notice.edit', 'web', '2021-06-16 14:50:29', '2021-06-16 14:50:29'),
(46, 'backend.notice.destroy', 'web', '2021-06-16 14:50:31', '2021-06-16 14:50:31'),
(47, 'backend.report_type.index', 'web', '2021-06-16 14:50:32', '2021-06-16 14:50:32'),
(48, 'backend.report_type.create', 'web', '2021-06-16 14:50:34', '2021-06-16 14:50:34'),
(49, 'backend.report_type.edit', 'web', '2021-06-16 14:50:36', '2021-06-16 14:50:36'),
(50, 'backend.report_type.destroy', 'web', '2021-06-16 14:50:37', '2021-06-16 14:50:37'),
(51, 'backend.report.index', 'web', '2021-06-16 14:50:39', '2021-06-16 14:50:39'),
(52, 'backend.video.index', 'web', '2021-06-16 14:50:40', '2021-06-16 14:50:40'),
(53, 'backend.video.create', 'web', '2021-06-16 14:50:42', '2021-06-16 14:50:42'),
(54, 'backend.video.edit', 'web', '2021-06-16 14:50:44', '2021-06-16 14:50:44'),
(55, 'backend.video.batch', 'web', '2021-06-16 14:50:45', '2021-06-16 14:50:45'),
(56, 'backend.video.editable', 'web', '2021-06-16 14:50:47', '2021-06-16 14:50:47'),
(57, 'backend.video_series.index', 'web', '2021-06-16 14:50:49', '2021-06-16 14:50:49'),
(58, 'backend.video_series.create', 'web', '2021-06-16 14:50:50', '2021-06-16 14:50:50'),
(59, 'backend.video_series.edit', 'web', '2021-06-16 14:50:52', '2021-06-16 14:50:52'),
(60, 'backend.video_series.batch', 'web', '2021-06-16 14:50:53', '2021-06-16 14:50:53'),
(61, 'backend.video_series.editable', 'web', '2021-06-16 14:50:55', '2021-06-16 14:50:55'),
(62, 'backend.video_series.preview', 'web', '2021-06-16 14:50:57', '2021-06-16 14:50:57'),
(63, 'backend.video_domain.index', 'web', '2021-06-16 14:50:58', '2021-06-16 14:50:58'),
(64, 'backend.video_domain.create', 'web', '2021-06-16 14:51:00', '2021-06-16 14:51:00'),
(65, 'backend.video_domain.edit', 'web', '2021-06-16 14:51:02', '2021-06-16 14:51:02'),
(66, 'backend.video_domain.editable', 'web', '2021-06-16 14:51:03', '2021-06-16 14:51:03'),
(67, 'backend.ad_space.index', 'web', '2021-06-16 14:51:05', '2021-06-16 14:51:05'),
(68, 'backend.ad_space.edit', 'web', '2021-06-16 14:51:07', '2021-06-16 14:51:07'),
(69, 'backend.ad.index', 'web', '2021-06-16 14:51:09', '2021-06-16 14:51:09'),
(70, 'backend.ad.create', 'web', '2021-06-16 14:51:10', '2021-06-16 14:51:10'),
(71, 'backend.ad.edit', 'web', '2021-06-16 14:51:12', '2021-06-16 14:51:12'),
(72, 'backend.ad.destroy', 'web', '2021-06-16 14:51:14', '2021-06-16 14:51:14'),
(73, 'backend.ad.batch', 'web', '2021-06-16 14:51:15', '2021-06-16 14:51:15'),
(74, 'backend.block.index', 'web', '2021-06-16 14:51:17', '2021-06-16 14:51:17'),
(75, 'backend.block.create', 'web', '2021-06-16 14:51:18', '2021-06-16 14:51:18'),
(76, 'backend.block.edit', 'web', '2021-06-16 14:51:20', '2021-06-16 14:51:20'),
(77, 'backend.block.destroy', 'web', '2021-06-16 14:51:22', '2021-06-16 14:51:22'),
(78, 'backend.block.batch', 'web', '2021-06-16 14:51:24', '2021-06-16 14:51:24'),
(79, 'backend.statistics.index', 'web', '2021-06-16 14:51:25', '2021-06-16 14:51:25'),
(80, 'backend.activity.index', 'web', '2021-06-16 14:51:27', '2021-06-16 14:51:27'),
(81, 'backend.admin.index', 'web', '2021-06-16 14:51:29', '2021-06-16 14:51:29'),
(82, 'backend.admin.create', 'web', '2021-06-16 14:51:30', '2021-06-16 14:51:30'),
(83, 'backend.admin.edit', 'web', '2021-06-16 14:51:32', '2021-06-16 14:51:32'),
(84, 'backend.admin.destroy', 'web', '2021-06-16 14:51:34', '2021-06-16 14:51:34'),
(85, 'backend.admin.batch', 'web', '2021-06-16 14:51:35', '2021-06-16 14:51:35'),
(86, 'backend.role.index', 'web', '2021-06-16 14:51:37', '2021-06-16 14:51:37'),
(87, 'backend.role.create', 'web', '2021-06-16 14:51:38', '2021-06-16 14:51:38'),
(88, 'backend.role.edit', 'web', '2021-06-16 14:51:40', '2021-06-16 14:51:40'),
(89, 'backend.role.destroy', 'web', '2021-06-16 14:51:41', '2021-06-16 14:51:41'),
(90, 'backend.topic.index', 'web', '2021-10-08 00:04:42', '2021-10-08 00:04:42'),
(91, 'backend.topic.create', 'web', '2021-10-08 00:04:43', '2021-10-08 00:04:43'),
(92, 'backend.topic.edit', 'web', '2021-10-08 00:04:43', '2021-10-08 00:04:43'),
(93, 'backend.topic.destroy', 'web', '2021-10-08 00:04:44', '2021-10-08 00:04:44'),
(94, 'backend.topic.batch', 'web', '2021-10-08 00:04:44', '2021-10-08 00:04:44'),
(95, 'backend.video_domain.destroy', 'web', '2021-10-08 00:04:45', '2021-10-08 00:04:45'),
(96, 'backend.navigation.index', 'web', '2021-10-08 00:04:45', '2021-10-08 00:04:45'),
(97, 'backend.navigation.create', 'web', '2021-10-08 00:04:46', '2021-10-08 00:04:46'),
(98, 'backend.navigation.edit', 'web', '2021-10-08 00:04:47', '2021-10-08 00:04:47'),
(99, 'backend.navigation.destroy', 'web', '2021-10-08 00:04:47', '2021-10-08 00:04:47'),
(100, 'backend.channel.index', 'web', '2021-10-26 13:41:32', '2021-10-26 13:41:32'),
(101, 'backend.channel.create', 'web', '2021-10-26 13:41:33', '2021-10-26 13:41:33'),
(102, 'backend.channel.edit', 'web', '2021-10-26 13:41:35', '2021-10-26 13:41:35'),
(103, 'backend.filter.index', 'web', '2021-10-26 13:41:37', '2021-10-26 13:41:37'),
(104, 'backend.filter.create', 'web', '2021-10-26 13:41:39', '2021-10-26 13:41:39'),
(105, 'backend.filter.edit', 'web', '2021-10-26 13:41:40', '2021-10-26 13:41:40'),
(106, 'backend.filter.destroy', 'web', '2021-10-26 13:41:42', '2021-10-26 13:41:42'),
(107, 'backend.video.destroy', 'web', '2021-10-26 13:41:44', '2021-10-26 13:41:44'),
(108, 'backend.payment.index', 'web', '2021-10-26 13:41:46', '2021-10-26 13:41:46'),
(109, 'backend.payment.create', 'web', '2021-10-26 13:41:47', '2021-10-26 13:41:47'),
(110, 'backend.payment.edit', 'web', '2021-10-26 13:41:49', '2021-10-26 13:41:49'),
(111, 'backend.payment.destroy', 'web', '2021-10-26 13:41:51', '2021-10-26 13:41:51'),
(112, 'backend.tag.edit', 'web', '2021-10-26 13:41:53', '2021-10-26 13:41:53'),
(113, 'backend.category.index', 'web', '2021-10-26 13:41:55', '2021-10-26 13:41:55'),
(114, 'backend.category.create', 'web', '2021-10-26 13:41:56', '2021-10-26 13:41:56'),
(115, 'backend.category.edit', 'web', '2021-10-26 13:41:58', '2021-10-26 13:41:58'),
(116, 'backend.category.destroy', 'web', '2021-10-26 13:42:00', '2021-10-26 13:42:00');

INSERT INTO `rb_personal_access_tokens` (`id`, `tokenable_type`, `tokenable_id`, `name`, `token`, `abilities`, `last_used_at`, `created_at`, `updated_at`) VALUES
(23, 'App\\Models\\User', 1, 'RB', '025955e522e9e81832b4fb9afb1d77677e288c760328487e4e0fda9562f680f7', '[\"*\"]', NULL, '2021-10-19 13:26:25', '2021-10-19 13:26:25'),
(24, 'App\\Models\\User', 1, 'RB', 'a9b82c28aa049bf67cc88852e714c6a288243bb50a23c1708649d7c95efef01f', '[\"*\"]', '2021-10-19 17:49:14', '2021-10-19 13:27:55', '2021-10-19 17:49:14'),
(25, 'App\\Models\\User', 1, 'RB', '940f66abbcbb18dab4ccc90dc8b537cedee57bead5189d1a78cf8b2bfb79c022', '[\"*\"]', '2021-10-19 18:04:30', '2021-10-19 17:53:15', '2021-10-19 18:04:30'),
(26, 'App\\Models\\User', 1, 'RB', 'e616e98c32395ba66170ccf7e925000669302884a8a2dced55468dac22c29715', '[\"*\"]', '2021-10-25 13:06:21', '2021-10-19 18:11:38', '2021-10-25 13:06:21'),
(27, 'App\\Models\\User', 1, 'RB', 'fefeb61e5de63a7a3a8961860732258d4b709f1cc27252e53a9d5153ab2597b1', '[\"*\"]', '2021-10-21 03:01:52', '2021-10-19 23:22:35', '2021-10-21 03:01:52'),
(28, 'App\\Models\\User', 1, 'RB', 'd2b67befe8b60c6c6845f09a8e91e6f7e9d28e0c6a785c0275cd9bf28b7266d7', '[\"*\"]', '2021-10-25 22:59:18', '2021-10-21 03:50:31', '2021-10-25 22:59:18'),
(29, 'App\\Models\\User', 1, 'RB', 'd50475db77d5d3d349a4984446c50c21b6322d75ceb3a1be330741e711e91a61', '[\"*\"]', '2021-10-26 17:14:56', '2021-10-26 13:30:18', '2021-10-26 17:14:56');

INSERT INTO `rb_pricings` (`id`, `type`, `name`, `description`, `label`, `price`, `list_price`, `coin`, `gift_coin`, `days`, `gift_days`, `target`, `status`, `sort`, `created_at`, `updated_at`) VALUES
(1, 'charge', '3000金币', NULL, '', 30.00, 30.00, 3000, 0, 0, 0, 0, 1, 1, '2021-01-27 16:32:12', '2021-10-26 16:59:40'),
(2, 'charge', '5000+3000金币', NULL, '送30元', 50.00, 80.00, 5000, 3000, 0, 0, 0, 1, 2, '2021-01-27 16:35:54', '2021-06-10 11:58:02'),
(3, 'charge', '10000+8000金币', NULL, '送80元', 100.00, 180.00, 10000, 8000, 0, 0, 0, 1, 3, '2021-03-02 10:41:26', '2021-10-26 16:59:25'),
(4, 'charge', '20000+20000金币', NULL, '送200元', 200.00, 400.00, 20000, 20000, 0, 0, 0, 1, 4, '2021-03-03 14:40:33', '2021-06-10 11:58:13'),
(5, 'vip', 'VIP季卡会员', NULL, '90天', 188.00, 188.00, 0, 0, 90, 0, 0, 1, 5, '2021-06-04 13:48:19', '2021-06-04 13:48:19'),
(6, 'vip', 'VIP年卡会员', NULL, '365天', 359.00, 359.00, 0, 0, 365, 0, 0, 1, 6, '2021-06-04 13:48:19', '2021-06-04 13:48:19');

INSERT INTO `rb_role_has_permissions` (`permission_id`, `role_id`) VALUES
(1, 1),
(2, 1),
(3, 1),
(4, 1),
(5, 1),
(6, 1),
(7, 1),
(8, 1),
(9, 1),
(13, 1),
(14, 1),
(15, 1),
(16, 1),
(17, 1),
(18, 1),
(20, 1),
(21, 1),
(22, 1),
(23, 1),
(24, 1),
(25, 1),
(26, 1),
(27, 1),
(28, 1),
(29, 1),
(30, 1),
(31, 1),
(32, 1),
(36, 1),
(37, 1),
(38, 1),
(39, 1),
(40, 1),
(41, 1),
(42, 1),
(43, 1),
(44, 1),
(45, 1),
(46, 1),
(47, 1),
(48, 1),
(49, 1),
(50, 1),
(51, 1),
(52, 1),
(53, 1),
(54, 1),
(55, 1),
(56, 1),
(67, 1),
(68, 1),
(69, 1),
(70, 1),
(71, 1),
(72, 1),
(73, 1),
(80, 1),
(81, 1),
(82, 1),
(83, 1),
(84, 1),
(85, 1),
(86, 1),
(87, 1),
(88, 1),
(89, 1),
(90, 1),
(91, 1),
(92, 1),
(93, 1),
(94, 1),
(96, 1),
(97, 1),
(98, 1),
(99, 1),
(100, 1),
(101, 1),
(102, 1),
(103, 1),
(104, 1),
(105, 1),
(106, 1),
(107, 1),
(108, 1),
(109, 1),
(110, 1),
(111, 1),
(112, 1),
(113, 1),
(114, 1),
(115, 1),
(116, 1);

INSERT INTO `rb_roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, '超级管理员', 'web', '2021-06-03 16:10:23', '2021-06-16 14:06:51');

INSERT INTO `rb_tags` (`id`, `name`, `slug`, `type`, `suggest`, `order_column`, `created_at`, `updated_at`) VALUES
(1, '{\"zh-CN\": \"日漫\"}', '{\"zh-CN\": \"日漫\"}', 'book', 1, 0, NULL, NULL),
(2, '{\"zh-CN\": \"韩漫\"}', '{\"zh-CN\": \"韩漫\"}', 'book', 1, 0, NULL, NULL),
(3, '{\"zh-CN\": \"精选\"}', '{\"zh-CN\": \"精选\"}', 'book', 0, 0, NULL, '2021-10-25 13:29:58'),
(4, '{\"zh-CN\": \"汉化\"}', '{\"zh-CN\": \"汉化\"}', 'book', 1, 0, NULL, NULL),
(5, '{\"zh-CN\": \"最新\"}', '{\"zh-CN\": \"最新\"}', 'book', 1, 0, NULL, NULL),
(6, '{\"zh-CN\": \"完结\"}', '{\"zh-CN\": \"完结\"}', 'book', 1, 0, NULL, NULL),
(7, '{\"zh-CN\": \"单行本\"}', '{\"zh-CN\": \"单行本\"}', 'book', 1, 0, NULL, NULL),
(8, '{\"zh-CN\": \"同人志\"}', '{\"zh-CN\": \"同人志\"}', 'book', 1, 0, NULL, NULL),
(9, '{\"zh-CN\": \"恋爱\"}', '{\"zh-CN\": \"恋爱\"}', 'book', 1, 0, NULL, NULL),
(10, '{\"zh-CN\": \"校园\"}', '{\"zh-CN\": \"校园\"}', 'book', 1, 0, NULL, NULL),
(11, '{\"zh-CN\": \"剧情\"}', '{\"zh-CN\": \"剧情\"}', 'book', 1, 0, NULL, NULL),
(12, '{\"zh-CN\": \"都市\"}', '{\"zh-CN\": \"都市\"}', 'book', 1, 0, NULL, NULL),
(13, '{\"zh-CN\": \"强奸\"}', '{\"zh-CN\": \"强奸\"}', 'book', 1, 0, NULL, NULL),
(14, '{\"zh-CN\": \"萝莉\"}', '{\"zh-CN\": \"萝莉\"}', 'book', 1, 0, NULL, NULL),
(15, '{\"zh-CN\": \"乱伦\"}', '{\"zh-CN\": \"乱伦\"}', 'book', 1, 0, NULL, NULL),
(16, '{\"zh-CN\": \"恐怖\"}', '{\"zh-CN\": \"恐怖\"}', 'book', 1, 0, NULL, NULL),
(17, '{\"zh-CN\": \"奇幻\"}', '{\"zh-CN\": \"奇幻\"}', 'book', 1, 0, NULL, NULL),
(18, '{\"zh-CN\": \"多人\"}', '{\"zh-CN\": \"多人\"}', 'book', 1, 0, NULL, NULL),
(19, '{\"zh-CN\": \"后宫\"}', '{\"zh-CN\": \"后宫\"}', 'book', 1, 0, NULL, NULL),
(20, '{\"zh-CN\": \"男男\"}', '{\"zh-CN\": \"男男\"}', 'book', 1, 0, NULL, NULL),
(21, '{\"zh-CN\": \"百合\"}', '{\"zh-CN\": \"百合\"}', 'book', 1, 0, NULL, NULL),
(22, '{\"zh-CN\": \"猎奇\"}', '{\"zh-CN\": \"猎奇\"}', 'book', 1, 0, NULL, NULL),
(23, '{\"zh-CN\": \"写真\"}', '{\"zh-CN\": \"写真\"}', 'book', 1, 0, NULL, NULL),
(24, '{\"zh-CN\": \"CG画集\"}', '{\"zh-CN\": \"CG画集\"}', 'book', 1, 0, NULL, NULL),
(25, '{\"zh-CN\": \"全彩\"}', '{\"zh-CN\": \"全彩\"}', 'book', 1, 0, NULL, NULL),
(26, '{\"zh-CN\": \"日本\"}', '{\"zh-CN\": \"日本\"}', 'video.area', 1, 0, NULL, NULL),
(27, '{\"zh-CN\": \"韩国\"}', '{\"zh-CN\": \"韩国\"}', 'video.area', 1, 0, NULL, NULL),
(28, '{\"zh-CN\": \"国产\"}', '{\"zh-CN\": \"国产\"}', 'video.area', 1, 0, NULL, NULL),
(29, '{\"zh-CN\": \"欧美\"}', '{\"zh-CN\": \"欧美\"}', 'video.area', 1, 0, NULL, NULL),
(30, '{\"zh-CN\": \"校园\"}', '{\"zh-CN\": \"校园\"}', 'video.place', 1, 0, NULL, NULL),
(31, '{\"zh-CN\": \"医院\"}', '{\"zh-CN\": \"医院\"}', 'video.place', 1, 0, NULL, NULL),
(32, '{\"zh-CN\": \"电车\"}', '{\"zh-CN\": \"电车\"}', 'video.place', 1, 0, NULL, NULL),
(33, '{\"zh-CN\": \"图书馆\"}', '{\"zh-CN\": \"图书馆\"}', 'video.place', 1, 0, NULL, NULL),
(34, '{\"zh-CN\": \"巴士\"}', '{\"zh-CN\": \"巴士\"}', 'video.place', 1, 0, NULL, NULL),
(35, '{\"zh-CN\": \"办公室\"}', '{\"zh-CN\": \"办公室\"}', 'video.place', 1, 0, NULL, NULL),
(36, '{\"zh-CN\": \"美容院\"}', '{\"zh-CN\": \"美容院\"}', 'video.place', 1, 0, NULL, NULL),
(37, '{\"zh-CN\": \"健身房\"}', '{\"zh-CN\": \"健身房\"}', 'video.place', 1, 0, NULL, NULL),
(38, '{\"zh-CN\": \"温泉\"}', '{\"zh-CN\": \"温泉\"}', 'video.place', 1, 0, NULL, NULL),
(39, '{\"zh-CN\": \"偶像\"}', '{\"zh-CN\": \"偶像\"}', 'video.topic', 1, 0, NULL, NULL),
(40, '{\"zh-CN\": \"主观视角\"}', '{\"zh-CN\": \"主观视角\"}', 'video.topic', 1, 0, NULL, NULL),
(41, '{\"zh-CN\": \"首次亮相\"}', '{\"zh-CN\": \"首次亮相\"}', 'video.topic', 1, 0, NULL, NULL),
(42, '{\"zh-CN\": \"流出\"}', '{\"zh-CN\": \"流出\"}', 'video.topic', 1, 0, NULL, NULL),
(43, '{\"zh-CN\": \"无码\"}', '{\"zh-CN\": \"无码\"}', 'video.topic', 1, 0, NULL, NULL),
(44, '{\"zh-CN\": \"薄马赛克\"}', '{\"zh-CN\": \"薄马赛克\"}', 'video.topic', 1, 0, NULL, NULL),
(45, '{\"zh-CN\": \"马赛克破解\"}', '{\"zh-CN\": \"马赛克破解\"}', 'video.topic', 1, 0, NULL, NULL),
(46, '{\"zh-CN\": \"企画\"}', '{\"zh-CN\": \"企画\"}', 'video.topic', 1, 0, NULL, NULL),
(47, '{\"zh-CN\": \"共演\"}', '{\"zh-CN\": \"共演\"}', 'video.topic', 1, 0, NULL, NULL),
(48, '{\"zh-CN\": \"总编辑\"}', '{\"zh-CN\": \"总编辑\"}', 'video.topic', 1, 0, NULL, NULL),
(49, '{\"zh-CN\": \"感谢祭\"}', '{\"zh-CN\": \"感谢祭\"}', 'video.topic', 1, 0, NULL, NULL),
(50, '{\"zh-CN\": \"女性向\"}', '{\"zh-CN\": \"女性向\"}', 'video.topic', 1, 0, NULL, NULL),
(51, '{\"zh-CN\": \"成人电影\"}', '{\"zh-CN\": \"成人电影\"}', 'video.topic', 1, 0, NULL, NULL),
(52, '{\"zh-CN\": \"明星脸\"}', '{\"zh-CN\": \"明星脸\"}', 'video.topic', 1, 0, NULL, NULL),
(53, '{\"zh-CN\": \"艺人\"}', '{\"zh-CN\": \"艺人\"}', 'video.topic', 1, 0, NULL, NULL),
(54, '{\"zh-CN\": \"素人\"}', '{\"zh-CN\": \"素人\"}', 'video.topic', 1, 0, NULL, NULL),
(55, '{\"zh-CN\": \"R15\"}', '{\"zh-CN\": \"R15\"}', 'video.topic', 1, 0, NULL, NULL),
(56, '{\"zh-CN\": \"4K\"}', '{\"zh-CN\": \"4K\"}', 'video.topic', 1, 0, NULL, NULL),
(57, '{\"zh-CN\": \"VR\"}', '{\"zh-CN\": \"VR\"}', 'video.topic', 1, 0, NULL, NULL),
(58, '{\"zh-CN\": \"4小时以上\"}', '{\"zh-CN\": \"4小时以上\"}', 'video.topic', 1, 0, NULL, NULL),
(59, '{\"zh-CN\": \"16小时以上\"}', '{\"zh-CN\": \"16小时以上\"}', 'video.topic', 1, 0, NULL, NULL),
(60, '{\"zh-CN\": \"局部特写\"}', '{\"zh-CN\": \"局部特写\"}', 'video.topic', 1, 0, NULL, NULL),
(61, '{\"zh-CN\": \"写真偶像\"}', '{\"zh-CN\": \"写真偶像\"}', 'video.topic', 1, 0, NULL, NULL),
(62, '{\"zh-CN\": \"3D\"}', '{\"zh-CN\": \"3D\"}', 'video.topic', 1, 0, NULL, NULL),
(63, '{\"zh-CN\": \"动漫改编\"}', '{\"zh-CN\": \"动漫改编\"}', 'video.topic', 1, 0, NULL, NULL),
(64, '{\"zh-CN\": \"恋爱\"}', '{\"zh-CN\": \"恋爱\"}', 'video.leaning', 1, 0, NULL, NULL),
(65, '{\"zh-CN\": \"约会\"}', '{\"zh-CN\": \"约会\"}', 'video.leaning', 1, 0, NULL, NULL),
(66, '{\"zh-CN\": \"出轨\"}', '{\"zh-CN\": \"出轨\"}', 'video.leaning', 1, 0, NULL, NULL),
(67, '{\"zh-CN\": \"强奸\"}', '{\"zh-CN\": \"强奸\"}', 'video.leaning', 1, 0, NULL, NULL),
(68, '{\"zh-CN\": \"乱伦\"}', '{\"zh-CN\": \"乱伦\"}', 'video.leaning', 1, 0, NULL, NULL),
(69, '{\"zh-CN\": \"NTR\"}', '{\"zh-CN\": \"NTR\"}', 'video.leaning', 1, 0, NULL, NULL),
(70, '{\"zh-CN\": \"痴女\"}', '{\"zh-CN\": \"痴女\"}', 'video.leaning', 1, 0, NULL, NULL),
(71, '{\"zh-CN\": \"痴汉\"}', '{\"zh-CN\": \"痴汉\"}', 'video.leaning', 1, 0, NULL, NULL),
(72, '{\"zh-CN\": \"偷窥\"}', '{\"zh-CN\": \"偷窥\"}', 'video.leaning', 1, 0, NULL, NULL),
(73, '{\"zh-CN\": \"蕾丝\"}', '{\"zh-CN\": \"蕾丝\"}', 'video.leaning', 1, 0, NULL, NULL),
(74, '{\"zh-CN\": \"泡泡浴\"}', '{\"zh-CN\": \"泡泡浴\"}', 'video.leaning', 1, 0, NULL, NULL),
(75, '{\"zh-CN\": \"野外露出\"}', '{\"zh-CN\": \"野外露出\"}', 'video.leaning', 1, 0, NULL, NULL),
(76, '{\"zh-CN\": \"性转换\"}', '{\"zh-CN\": \"性转换\"}', 'video.leaning', 1, 0, NULL, NULL),
(77, '{\"zh-CN\": \"女体化\"}', '{\"zh-CN\": \"女体化\"}', 'video.leaning', 1, 0, NULL, NULL),
(78, '{\"zh-CN\": \"男同性恋\"}', '{\"zh-CN\": \"男同性恋\"}', 'video.leaning', 1, 0, NULL, NULL),
(79, '{\"zh-CN\": \"妄想\"}', '{\"zh-CN\": \"妄想\"}', 'video.leaning', 1, 0, NULL, NULL),
(80, '{\"zh-CN\": \"偷窥\"}', '{\"zh-CN\": \"偷窥\"}', 'video.leaning', 1, 0, NULL, NULL),
(81, '{\"zh-CN\": \"M男\"}', '{\"zh-CN\": \"M男\"}', 'video.leaning', 1, 0, NULL, NULL),
(82, '{\"zh-CN\": \"刺青\"}', '{\"zh-CN\": \"刺青\"}', 'video.leaning', 1, 0, NULL, NULL),
(83, '{\"zh-CN\": \"黑白配\"}', '{\"zh-CN\": \"黑白配\"}', 'video.leaning', 1, 0, NULL, NULL),
(84, '{\"zh-CN\": \"恋物癖\"}', '{\"zh-CN\": \"恋物癖\"}', 'video.leaning', 1, 0, NULL, NULL),
(85, '{\"zh-CN\": \"高潮\"}', '{\"zh-CN\": \"高潮\"}', 'video.leaning', 1, 0, NULL, NULL),
(86, '{\"zh-CN\": \"运动\"}', '{\"zh-CN\": \"运动\"}', 'video.leaning', 1, 0, NULL, NULL),
(87, '{\"zh-CN\": \"恋乳癖\"}', '{\"zh-CN\": \"恋乳癖\"}', 'video.leaning', 1, 0, NULL, NULL),
(88, '{\"zh-CN\": \"恶作剧\"}', '{\"zh-CN\": \"恶作剧\"}', 'video.leaning', 1, 0, NULL, NULL),
(89, '{\"zh-CN\": \"运动\"}', '{\"zh-CN\": \"运动\"}', 'video.leaning', 1, 0, NULL, NULL),
(90, '{\"zh-CN\": \"奴隶\"}', '{\"zh-CN\": \"奴隶\"}', 'video.leaning', 1, 0, NULL, NULL),
(91, '{\"zh-CN\": \"流汗\"}', '{\"zh-CN\": \"流汗\"}', 'video.leaning', 1, 0, NULL, NULL),
(92, '{\"zh-CN\": \"性骚扰\"}', '{\"zh-CN\": \"性骚扰\"}', 'video.leaning', 1, 0, NULL, NULL),
(93, '{\"zh-CN\": \"情侣\"}', '{\"zh-CN\": \"情侣\"}', 'video.leaning', 1, 0, NULL, NULL),
(94, '{\"zh-CN\": \"泥醉\"}', '{\"zh-CN\": \"泥醉\"}', 'video.leaning', 1, 0, NULL, NULL),
(95, '{\"zh-CN\": \"处男\"}', '{\"zh-CN\": \"处男\"}', 'video.leaning', 1, 0, NULL, NULL),
(96, '{\"zh-CN\": \"触手\"}', '{\"zh-CN\": \"触手\"}', 'video.leaning', 1, 0, NULL, NULL),
(97, '{\"zh-CN\": \"美少女\"}', '{\"zh-CN\": \"美少女\"}', 'video.identity', 1, 0, NULL, NULL),
(98, '{\"zh-CN\": \"女子高生\"}', '{\"zh-CN\": \"女子高生\"}', 'video.identity', 1, 0, NULL, NULL),
(99, '{\"zh-CN\": \"女子大生\"}', '{\"zh-CN\": \"女子大生\"}', 'video.identity', 1, 0, NULL, NULL),
(100, '{\"zh-CN\": \"妹・姐\"}', '{\"zh-CN\": \"妹・姐\"}', 'video.identity', 1, 0, NULL, NULL),
(101, '{\"zh-CN\": \"若妻\"}', '{\"zh-CN\": \"若妻\"}', 'video.identity', 1, 0, NULL, NULL),
(102, '{\"zh-CN\": \"人妻\"}', '{\"zh-CN\": \"人妻\"}', 'video.identity', 1, 0, NULL, NULL),
(103, '{\"zh-CN\": \"女教师\"}', '{\"zh-CN\": \"女教师\"}', 'video.identity', 1, 0, NULL, NULL),
(104, '{\"zh-CN\": \"秘书\"}', '{\"zh-CN\": \"秘书\"}', 'video.identity', 1, 0, NULL, NULL),
(105, '{\"zh-CN\": \"护士\"}', '{\"zh-CN\": \"护士\"}', 'video.identity', 1, 0, NULL, NULL),
(106, '{\"zh-CN\": \"女医\"}', '{\"zh-CN\": \"女医\"}', 'video.identity', 1, 0, NULL, NULL),
(107, '{\"zh-CN\": \"拉拉队\"}', '{\"zh-CN\": \"拉拉队\"}', 'video.identity', 1, 0, NULL, NULL),
(108, '{\"zh-CN\": \"女主播\"}', '{\"zh-CN\": \"女主播\"}', 'video.identity', 1, 0, NULL, NULL),
(109, '{\"zh-CN\": \"模特儿\"}', '{\"zh-CN\": \"模特儿\"}', 'video.identity', 1, 0, NULL, NULL),
(110, '{\"zh-CN\": \"赛车女郎\"}', '{\"zh-CN\": \"赛车女郎\"}', 'video.identity', 1, 0, NULL, NULL),
(111, '{\"zh-CN\": \"家教\"}', '{\"zh-CN\": \"家教\"}', 'video.identity', 1, 0, NULL, NULL),
(112, '{\"zh-CN\": \"辣妹\"}', '{\"zh-CN\": \"辣妹\"}', 'video.identity', 1, 0, NULL, NULL),
(113, '{\"zh-CN\": \"寡妇\"}', '{\"zh-CN\": \"寡妇\"}', 'video.identity', 1, 0, NULL, NULL),
(114, '{\"zh-CN\": \"空姐\"}', '{\"zh-CN\": \"空姐\"}', 'video.identity', 1, 0, NULL, NULL),
(115, '{\"zh-CN\": \"母子\"}', '{\"zh-CN\": \"母子\"}', 'video.identity', 1, 0, NULL, NULL),
(116, '{\"zh-CN\": \"女仆\"}', '{\"zh-CN\": \"女仆\"}', 'video.identity', 1, 0, NULL, NULL),
(117, '{\"zh-CN\": \"修女\"}', '{\"zh-CN\": \"修女\"}', 'video.identity', 1, 0, NULL, NULL),
(118, '{\"zh-CN\": \"新娘\"}', '{\"zh-CN\": \"新娘\"}', 'video.identity', 1, 0, NULL, NULL),
(119, '{\"zh-CN\": \"大小姐\"}', '{\"zh-CN\": \"大小姐\"}', 'video.identity', 1, 0, NULL, NULL),
(120, '{\"zh-CN\": \"女王\"}', '{\"zh-CN\": \"女王\"}', 'video.identity', 1, 0, NULL, NULL),
(121, '{\"zh-CN\": \"老板娘\"}', '{\"zh-CN\": \"老板娘\"}', 'video.identity', 1, 0, NULL, NULL),
(122, '{\"zh-CN\": \"格斗家\"}', '{\"zh-CN\": \"格斗家\"}', 'video.identity', 1, 0, NULL, NULL),
(123, '{\"zh-CN\": \"检察官・警察\"}', '{\"zh-CN\": \"检察官・警察\"}', 'video.identity', 1, 0, NULL, NULL),
(124, '{\"zh-CN\": \"学生服\"}', '{\"zh-CN\": \"学生服\"}', 'video.wear', 1, 0, NULL, NULL),
(125, '{\"zh-CN\": \"制服\"}', '{\"zh-CN\": \"制服\"}', 'video.wear', 1, 0, NULL, NULL),
(126, '{\"zh-CN\": \"运动短裤\"}', '{\"zh-CN\": \"运动短裤\"}', 'video.wear', 1, 0, NULL, NULL),
(127, '{\"zh-CN\": \"眼镜\"}', '{\"zh-CN\": \"眼镜\"}', 'video.wear', 1, 0, NULL, NULL),
(128, '{\"zh-CN\": \"内衣\"}', '{\"zh-CN\": \"内衣\"}', 'video.wear', 1, 0, NULL, NULL),
(129, '{\"zh-CN\": \"水手服\"}', '{\"zh-CN\": \"水手服\"}', 'video.wear', 1, 0, NULL, NULL),
(130, '{\"zh-CN\": \"泳装\"}', '{\"zh-CN\": \"泳装\"}', 'video.wear', 1, 0, NULL, NULL),
(131, '{\"zh-CN\": \"迷你裙\"}', '{\"zh-CN\": \"迷你裙\"}', 'video.wear', 1, 0, NULL, NULL),
(132, '{\"zh-CN\": \"和服\"}', '{\"zh-CN\": \"和服\"}', 'video.wear', 1, 0, NULL, NULL),
(133, '{\"zh-CN\": \"Cosplay\"}', '{\"zh-CN\": \"Cosplay\"}', 'video.wear', 1, 0, NULL, NULL),
(134, '{\"zh-CN\": \"裸体围裙\"}', '{\"zh-CN\": \"裸体围裙\"}', 'video.wear', 1, 0, NULL, NULL),
(135, '{\"zh-CN\": \"女忍者\"}', '{\"zh-CN\": \"女忍者\"}', 'video.wear', 1, 0, NULL, NULL),
(136, '{\"zh-CN\": \"高跟鞋\"}', '{\"zh-CN\": \"高跟鞋\"}', 'video.wear', 1, 0, NULL, NULL),
(137, '{\"zh-CN\": \"靴子\"}', '{\"zh-CN\": \"靴子\"}', 'video.wear', 1, 0, NULL, NULL),
(138, '{\"zh-CN\": \"OL\"}', '{\"zh-CN\": \"OL\"}', 'video.wear', 1, 0, NULL, NULL),
(139, '{\"zh-CN\": \"兽耳\"}', '{\"zh-CN\": \"兽耳\"}', 'video.wear', 1, 0, NULL, NULL),
(140, '{\"zh-CN\": \"短裙\"}', '{\"zh-CN\": \"短裙\"}', 'video.wear', 1, 0, NULL, NULL),
(141, '{\"zh-CN\": \"泳装\"}', '{\"zh-CN\": \"泳装\"}', 'video.wear', 1, 0, NULL, NULL),
(142, '{\"zh-CN\": \"迷你裙\"}', '{\"zh-CN\": \"迷你裙\"}', 'video.wear', 1, 0, NULL, NULL),
(143, '{\"zh-CN\": \"浴衣\"}', '{\"zh-CN\": \"浴衣\"}', 'video.wear', 1, 0, NULL, NULL),
(144, '{\"zh-CN\": \"瑜伽服\"}', '{\"zh-CN\": \"瑜伽服\"}', 'video.wear', 1, 0, NULL, NULL),
(145, '{\"zh-CN\": \"紧身衣\"}', '{\"zh-CN\": \"紧身衣\"}', 'video.wear', 1, 0, NULL, NULL),
(146, '{\"zh-CN\": \"丝袜\"}', '{\"zh-CN\": \"丝袜\"}', 'video.wear', 1, 0, NULL, NULL),
(147, '{\"zh-CN\": \"旗袍\"}', '{\"zh-CN\": \"旗袍\"}', 'video.wear', 1, 0, NULL, NULL),
(148, '{\"zh-CN\": \"兔女郎\"}', '{\"zh-CN\": \"兔女郎\"}', 'video.wear', 1, 0, NULL, NULL),
(149, '{\"zh-CN\": \"熟女\"}', '{\"zh-CN\": \"熟女\"}', 'video.body', 1, 0, NULL, NULL),
(150, '{\"zh-CN\": \"处女\"}', '{\"zh-CN\": \"处女\"}', 'video.body', 1, 0, NULL, NULL),
(151, '{\"zh-CN\": \"巨乳\"}', '{\"zh-CN\": \"巨乳\"}', 'video.body', 1, 0, NULL, NULL),
(152, '{\"zh-CN\": \"萝莉\"}', '{\"zh-CN\": \"萝莉\"}', 'video.body', 1, 0, NULL, NULL),
(153, '{\"zh-CN\": \"无毛\"}', '{\"zh-CN\": \"无毛\"}', 'video.body', 1, 0, NULL, NULL),
(154, '{\"zh-CN\": \"美臀\"}', '{\"zh-CN\": \"美臀\"}', 'video.body', 1, 0, NULL, NULL),
(155, '{\"zh-CN\": \"苗条\"}', '{\"zh-CN\": \"苗条\"}', 'video.body', 1, 0, NULL, NULL),
(156, '{\"zh-CN\": \"素人\"}', '{\"zh-CN\": \"素人\"}', 'video.body', 1, 0, NULL, NULL),
(157, '{\"zh-CN\": \"美乳\"}', '{\"zh-CN\": \"美乳\"}', 'video.body', 1, 0, NULL, NULL),
(158, '{\"zh-CN\": \"美腿\"}', '{\"zh-CN\": \"美腿\"}', 'video.body', 1, 0, NULL, NULL),
(159, '{\"zh-CN\": \"巨根\"}', '{\"zh-CN\": \"巨根\"}', 'video.body', 1, 0, NULL, NULL),
(160, '{\"zh-CN\": \"贫乳・微乳\"}', '{\"zh-CN\": \"贫乳・微乳\"}', 'video.body', 1, 0, NULL, NULL),
(161, '{\"zh-CN\": \"高挑\"}', '{\"zh-CN\": \"高挑\"}', 'video.body', 1, 0, NULL, NULL),
(162, '{\"zh-CN\": \"孕妇\"}', '{\"zh-CN\": \"孕妇\"}', 'video.body', 1, 0, NULL, NULL),
(163, '{\"zh-CN\": \"大屁股\"}', '{\"zh-CN\": \"大屁股\"}', 'video.body', 1, 0, NULL, NULL),
(164, '{\"zh-CN\": \"瘦小身型\"}', '{\"zh-CN\": \"瘦小身型\"}', 'video.body', 1, 0, NULL, NULL),
(165, '{\"zh-CN\": \"人妖\"}', '{\"zh-CN\": \"人妖\"}', 'video.body', 1, 0, NULL, NULL),
(166, '{\"zh-CN\": \"肌肉\"}', '{\"zh-CN\": \"肌肉\"}', 'video.body', 1, 0, NULL, NULL),
(167, '{\"zh-CN\": \"超乳\"}', '{\"zh-CN\": \"超乳\"}', 'video.body', 1, 0, NULL, NULL),
(168, '{\"zh-CN\": \"乳交\"}', '{\"zh-CN\": \"乳交\"}', 'video.posture', 1, 0, NULL, NULL),
(169, '{\"zh-CN\": \"中出\"}', '{\"zh-CN\": \"中出\"}', 'video.posture', 1, 0, NULL, NULL),
(170, '{\"zh-CN\": \"69\"}', '{\"zh-CN\": \"69\"}', 'video.posture', 1, 0, NULL, NULL),
(171, '{\"zh-CN\": \"淫语\"}', '{\"zh-CN\": \"淫语\"}', 'video.posture', 1, 0, NULL, NULL),
(172, '{\"zh-CN\": \"女上位\"}', '{\"zh-CN\": \"女上位\"}', 'video.posture', 1, 0, NULL, NULL),
(173, '{\"zh-CN\": \"骑乘位\"}', '{\"zh-CN\": \"骑乘位\"}', 'video.posture', 1, 0, NULL, NULL),
(174, '{\"zh-CN\": \"自慰\"}', '{\"zh-CN\": \"自慰\"}', 'video.posture', 1, 0, NULL, NULL),
(175, '{\"zh-CN\": \"颜射\"}', '{\"zh-CN\": \"颜射\"}', 'video.posture', 1, 0, NULL, NULL),
(176, '{\"zh-CN\": \"潮吹\"}', '{\"zh-CN\": \"潮吹\"}', 'video.posture', 1, 0, NULL, NULL),
(177, '{\"zh-CN\": \"口交\"}', '{\"zh-CN\": \"口交\"}', 'video.posture', 1, 0, NULL, NULL),
(178, '{\"zh-CN\": \"舔阴\"}', '{\"zh-CN\": \"舔阴\"}', 'video.posture', 1, 0, NULL, NULL),
(179, '{\"zh-CN\": \"肛交\"}', '{\"zh-CN\": \"肛交\"}', 'video.posture', 1, 0, NULL, NULL),
(180, '{\"zh-CN\": \"手淫\"}', '{\"zh-CN\": \"手淫\"}', 'video.posture', 1, 0, NULL, NULL),
(181, '{\"zh-CN\": \"放尿\"}', '{\"zh-CN\": \"放尿\"}', 'video.posture', 1, 0, NULL, NULL),
(182, '{\"zh-CN\": \"足交\"}', '{\"zh-CN\": \"足交\"}', 'video.posture', 1, 0, NULL, NULL),
(183, '{\"zh-CN\": \"按摩\"}', '{\"zh-CN\": \"按摩\"}', 'video.posture', 1, 0, NULL, NULL),
(184, '{\"zh-CN\": \"吞精\"}', '{\"zh-CN\": \"吞精\"}', 'video.posture', 1, 0, NULL, NULL),
(185, '{\"zh-CN\": \"剃毛\"}', '{\"zh-CN\": \"剃毛\"}', 'video.posture', 1, 0, NULL, NULL),
(186, '{\"zh-CN\": \"二穴同入\"}', '{\"zh-CN\": \"二穴同入\"}', 'video.posture', 1, 0, NULL, NULL),
(187, '{\"zh-CN\": \"母乳\"}', '{\"zh-CN\": \"母乳\"}', 'video.posture', 1, 0, NULL, NULL),
(188, '{\"zh-CN\": \"不穿内裤\"}', '{\"zh-CN\": \"不穿内裤\"}', 'video.posture', 1, 0, NULL, NULL),
(189, '{\"zh-CN\": \"不穿胸罩\"}', '{\"zh-CN\": \"不穿胸罩\"}', 'video.posture', 1, 0, NULL, NULL),
(190, '{\"zh-CN\": \"深喉\"}', '{\"zh-CN\": \"深喉\"}', 'video.posture', 1, 0, NULL, NULL),
(191, '{\"zh-CN\": \"失神\"}', '{\"zh-CN\": \"失神\"}', 'video.posture', 1, 0, NULL, NULL),
(192, '{\"zh-CN\": \"接吻\"}', '{\"zh-CN\": \"接吻\"}', 'video.posture', 1, 0, NULL, NULL),
(193, '{\"zh-CN\": \"拳交\"}', '{\"zh-CN\": \"拳交\"}', 'video.posture', 1, 0, NULL, NULL),
(194, '{\"zh-CN\": \"饮尿\"}', '{\"zh-CN\": \"饮尿\"}', 'video.posture', 1, 0, NULL, NULL),
(195, '{\"zh-CN\": \"排便\"}', '{\"zh-CN\": \"排便\"}', 'video.posture', 1, 0, NULL, NULL),
(196, '{\"zh-CN\": \"食粪\"}', '{\"zh-CN\": \"食粪\"}', 'video.posture', 1, 0, NULL, NULL),
(197, '{\"zh-CN\": \"凌辱\"}', '{\"zh-CN\": \"凌辱\"}', 'video.posture', 1, 0, NULL, NULL),
(198, '{\"zh-CN\": \"捆绑・紧缚\"}', '{\"zh-CN\": \"捆绑・紧缚\"}', 'video.posture', 1, 0, NULL, NULL),
(199, '{\"zh-CN\": \"轮奸\"}', '{\"zh-CN\": \"轮奸\"}', 'video.posture', 1, 0, NULL, NULL),
(200, '{\"zh-CN\": \"玩具\"}', '{\"zh-CN\": \"玩具\"}', 'video.posture', 1, 0, NULL, NULL),
(201, '{\"zh-CN\": \"SM\"}', '{\"zh-CN\": \"SM\"}', 'video.posture', 1, 0, NULL, NULL),
(202, '{\"zh-CN\": \"羞耻\"}', '{\"zh-CN\": \"羞耻\"}', 'video.posture', 1, 0, NULL, NULL),
(203, '{\"zh-CN\": \"拘束・监禁\"}', '{\"zh-CN\": \"拘束・监禁\"}', 'video.posture', 1, 0, NULL, NULL),
(204, '{\"zh-CN\": \"调教\"}', '{\"zh-CN\": \"调教\"}', 'video.posture', 1, 0, NULL, NULL),
(205, '{\"zh-CN\": \"插入异物\"}', '{\"zh-CN\": \"插入异物\"}', 'video.posture', 1, 0, NULL, NULL),
(206, '{\"zh-CN\": \"灌肠\"}', '{\"zh-CN\": \"灌肠\"}', 'video.posture', 1, 0, NULL, NULL),
(207, '{\"zh-CN\": \"催眠\"}', '{\"zh-CN\": \"催眠\"}', 'video.posture', 1, 0, NULL, NULL),
(208, '{\"zh-CN\": \"多P\"}', '{\"zh-CN\": \"多P\"}', 'video.player', 1, 0, NULL, NULL),
(209, '{\"zh-CN\": \"两女一男\"}', '{\"zh-CN\": \"两女一男\"}', 'video.player', 1, 0, NULL, NULL),
(210, '{\"zh-CN\": \"两男一女\"}', '{\"zh-CN\": \"两男一女\"}', 'video.player', 1, 0, NULL, NULL),
(211, '{\"zh-CN\": \"两男两女\"}', '{\"zh-CN\": \"两男两女\"}', 'video.player', 1, 0, NULL, NULL),
(212, '{\"zh-CN\": \"夫妻交换\"}', '{\"zh-CN\": \"夫妻交换\"}', 'video.player', 1, 0, NULL, NULL),
(213, '{\"zh-CN\": \"外国人\"}', '{\"zh-CN\": \"外国人\"}', 'video.player', 1, 0, NULL, NULL),
(214, '{\"zh-CN\": \"白人\"}', '{\"zh-CN\": \"白人\"}', 'video.player', 1, 0, NULL, NULL),
(215, '{\"zh-CN\": \"黑人\"}', '{\"zh-CN\": \"黑人\"}', 'video.player', 1, 0, NULL, NULL),
(216, '{\"zh-CN\": \"老人\"}', '{\"zh-CN\": \"老人\"}', 'video.player', 1, 0, NULL, NULL);

INSERT INTO `rb_topics` (`id`, `type`, `filter_id`, `sort`, `spotlight`, `row`, `limit`, `properties`, `status`, `created_at`, `updated_at`) VALUES
(1, 'book', 1, 0, 1, 2, 3, '{\"tag\": [\"精选\"], \"limit\": \"3\", \"order\": \"created_at\", \"author\": null, \"ribbon\": \"0\", \"date_between\": null}', 1, '2021-10-13 17:54:52', '2021-10-23 16:43:59'),
(2, 'book', 2, 0, 0, 3, 6, '{\"tag\": [\"单行本\"], \"limit\": \"6\", \"order\": \"created_at\", \"author\": null, \"ribbon\": \"0\", \"date_between\": null}', 1, '2021-10-13 17:55:59', '2021-10-23 16:53:55'),
(3, 'book', 3, 0, 0, 3, 6, '{\"tag\": [\"韩漫\"], \"limit\": \"6\", \"order\": \"created_at\", \"author\": null, \"ribbon\": \"0\", \"date_between\": null}', 1, '2021-10-13 17:57:03', '2021-10-23 16:54:20'),
(4, 'book_safe', 3, 0, 0, 3, 6, '{\"tag\": [\"韩漫\"], \"limit\": \"6\", \"order\": \"created_at\", \"author\": null, \"ribbon\": \"0\", \"date_between\": null}', 1, '2021-10-13 17:57:03', '2021-10-23 16:54:20');
