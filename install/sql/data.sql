INSERT INTO `c_categories` (`c_id`, `name`, `order_n`, `visible`)
VALUES
	(1,'Default Category',0,1);

INSERT INTO `c_emails` (`id`, `type`, `description`, `content`)
VALUES
	(1,'validate','Member Validation','Hello %s,<br><br>Before you can start posting in %s, you must confirm your e-mail address. Please, confirm your e-mail address by clicking on the link below:<br><br>%s<br><br>If you cannot click on the link, copy and paste the address into your browser manually.<br><br>Thanks. See you there!<br>- Administration');

INSERT INTO `c_emoticons` (`id`, `shortcut`, `filename`, `display`, `emoticon_set`)
VALUES
	(1,':)','happy.png',1,'default'),
	(2,':(','sad.png',1,'default'),
	(3,';)','winking.png',1,'default'),
	(4,':D','laugh.png',1,'default'),
	(5,':O','surprised.png',1,'default'),
	(6,':P','tongue.png',1,'default'),
	(7,':|','blank.png',1,'default'),
	(8,'<3','love.png',1,'default'),
	(9,'B)','cool.png',1,'default'),
	(10,':@','angry.png',1,'default'),
	(11,'o.O','confused.png',1,'default'),
	(12,':\'(','crying.png',1,'default'),
	(13,':X','disgusted.png',1,'default');

INSERT INTO `c_languages` (`l_id`, `name`, `directory`, `author_name`, `author_email`, `is_active`)
VALUES
	(1,'English (US)','en_US','Addictive Community','brunno.pleffken@outlook.com',1),
	(2,'Korean (South Korea)','ko_KR','Olgierd','olgierd.everac@gmail.com',1),
	(3,'Portuguese (Brazil)','pt_BR','Brunno Pleffken','brunno.pleffken@outlook.com',1),
	(4,'Russian','ru_RU','Alex Zalevski','zalexstudios@gmail.com',1),
	(5,'Swedish','sv_SE','Stefan Forslund','halojoy@outlook.com',1);

INSERT INTO `c_ranks` (`id`, `title`, `min_posts`, `pips`, `image`)
VALUES
	(1,'Lurker',0,0,NULL),
	(2,'Novice Member',10,0,NULL),
	(3,'Regular Member',20,1,NULL),
	(4,'Experienced Member',50,2,NULL),
	(5,'Advanced Member',100,3,NULL),
	(6,'Professional Member',200,4,NULL),
	(7,'Veteran Member',500,5,NULL);

INSERT INTO `c_stats` (`id`, `member_count`, `post_count`, `thread_count`)
VALUES
	(1,1,1,1);

INSERT INTO `c_templates` (`tpl_id`, `name`, `directory`, `is_active`, `author_name`, `author_email`)
VALUES
	(1,'Default','default',1,'Addictive Community','brunno.pleffken@outlook.com');

INSERT INTO `c_themes` (`theme_id`, `name`, `directory`, `is_active`, `author_name`, `author_email`)
VALUES
	(1,'Default (Light)','default-light',1,'Addictive Community','brunno.pleffken@outlook.com');

INSERT INTO `c_usergroups` (`g_id`, `name`, `preffix`, `suffix`, `color`, `view_board`, `post_new_threads`, `reply_threads`, `edit_own_threads`, `edit_own_posts`, `delete_own_posts`, `can_attach`, `access_offline`, `post_html`, `avoid_flood`, `admin_cp`, `max_pm_storage`, `stock`)
VALUES
	(1,'Administrator',NULL,NULL,'#070',1,1,1,1,1,1,1,1,1,1,1,0,1),
	(2,'Moderator',NULL,NULL,'#090',1,1,1,1,1,1,1,0,0,0,0,0,1),
	(3,'Member',NULL,NULL,'#000',1,1,1,1,1,0,1,0,0,0,0,0,1),
	(4,'Banned',NULL,NULL,'#F00',0,0,0,0,0,0,0,0,0,0,0,0,1),
	(5,'Guest',NULL,NULL,NULL,1,0,0,0,0,0,0,0,0,0,0,0,1),
	(6,'Validating',NULL,NULL,'#444',1,0,0,0,0,0,0,0,0,0,0,0,1);
