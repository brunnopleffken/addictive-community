-- phpMyAdmin SQL Dump
-- version 4.0.3
-- http://www.phpmyadmin.net
--
-- Máquina: localhost
-- Data de Criação: 26-Set-2013 às 09:36
-- Versão do servidor: 5.1.68-community
-- versão do PHP: 5.3.24

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de Dados: `community`
--
CREATE DATABASE IF NOT EXISTS `community` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `community`;

-- --------------------------------------------------------

--
-- Estrutura da tabela `c_attachments`
--

CREATE TABLE IF NOT EXISTS `c_attachments` (
  `a_id` int(6) NOT NULL AUTO_INCREMENT,
  `user_id` int(8) DEFAULT NULL,
  `date` int(10) DEFAULT NULL,
  `attach_filename` varchar(50) DEFAULT NULL,
  `attach_type` varchar(50) DEFAULT NULL,
  `attach_clicks` int(8) DEFAULT NULL,
  `post_id` int(10) DEFAULT NULL,
  `pm_id` int(10) DEFAULT NULL,
  PRIMARY KEY (`a_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `c_bbcodes`
--

CREATE TABLE IF NOT EXISTS `c_bbcodes` (
  `id` int(2) NOT NULL AUTO_INCREMENT,
  `reg_x` varchar(255) NOT NULL,
  `html` text NOT NULL,
  `name` varchar(20) NOT NULL,
  `active` int(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Extraindo dados da tabela `c_bbcodes`
--

INSERT INTO `c_bbcodes` (`id`, `reg_x`, `html`, `name`, `active`) VALUES
(2, '\\[youtube\\](.+)\\[\\/youtube\\]', '<object width="425" height="355"><param name="movie" value="https://www.youtube.com/v/{$1}"></param><param name="allowFullScreen" value="true"></param><embed src="https://www.youtube.com/v/{$1}" type="application/x-shockwave-flash" width="425" height="355" allowfullscreen="true"></embed></object>', 'YouTube', 1);

-- --------------------------------------------------------

--
-- Estrutura da tabela `c_config`
--

CREATE TABLE IF NOT EXISTS `c_config` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `index` varchar(40) NOT NULL,
  `value` text,
  `description` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=25 ;

--
-- Extraindo dados da tabela `c_config`
--

INSERT INTO `c_config` (`id`, `index`, `value`, `description`) VALUES
(1, 'general_communityname', 'Addictive Community', 'Your community name'),
(2, 'general_communityurl', 'http://localhost/community/', 'Absolute URL to your community'),
(3, 'general_websitename', 'Addictive Information Technology', 'Your website name'),
(4, 'general_websiteurl', 'http://www.addictive.com.br', 'Your website root URL'),
(5, 'general_communitylogo', 'logo.png', 'File name (inside /images folder)'),
(6, 'general_sidebar_online', 'true', 'Show users online in sidebar'),
(7, 'general_sidebar_stats', 'true', 'Show statistics in sidebar'),
(8, 'date_long_format', 'd M Y, H:i', 'Date and time long format'),
(9, 'date_default_offset', '-3', 'Default offset for timezones (in seconds)'),
(10, 'thread_posts_hot', '15', 'Minimum replies to a thread become a hot thread'),
(11, 'seo_description', 'An Addictive Services product.', 'Description HTML Meta tags'),
(12, 'seo_keywords', 'addictive, community, board', 'Keywords for HTML Meta tags'),
(13, 'date_short_format', 'd M Y', 'Date short format'),
(14, 'general_session_expiration', '900', 'Expires user session after X seconds (also cuts off from Members Online list)'),
(15, 'thread_posts_per_page', '10', 'Number of posts per page in a thread'),
(16, 'thread_best_answer_all_pages', 'false', 'Display the best answer on top of all thread pages (not only the first)'),
(17, 'thread_obsolete', 'false', 'Turns a thread obsolete after X days, suggesting the member to create another thread'),
(18, 'thread_obsolete_value', '30', 'Number of days for a thread become obsolete'),
(19, 'emoticon_default_set', 'default', 'Sets default emoticon set'),
(20, 'thread_allow_emoticons', 'true', 'Allow emoticons on thread posts'),
(21, 'general_allow_guest_post', 'false', 'Allow guests to post'),
(22, 'general_offline', 'false', 'Community is offline. Only administrators are allowed to view the board.'),
(23, 'general_disable_registrations', 'false', 'The community is closed for new registrations.'),
(24, 'general_bread_separator', '>', 'Breadcrumb separator.');

-- --------------------------------------------------------

--
-- Estrutura da tabela `c_emoticons`
--

CREATE TABLE IF NOT EXISTS `c_emoticons` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `shortcut` varchar(10) DEFAULT NULL,
  `filename` varchar(30) DEFAULT NULL,
  `display` int(1) DEFAULT NULL,
  `emoticon_set` varchar(20) DEFAULT NULL,
  `position` int(2) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Extraindo dados da tabela `c_emoticons`
--

INSERT INTO `c_emoticons` (`id`, `shortcut`, `filename`, `display`, `emoticon_set`, `position`) VALUES
(1, ':)', 'happy.png', 1, 'default', 1);

-- --------------------------------------------------------

--
-- Estrutura da tabela `c_events`
--

CREATE TABLE IF NOT EXISTS `c_events` (
  `e_id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  `type` varchar(10) NOT NULL,
  `day` int(2) NOT NULL,
  `month` int(2) NOT NULL,
  `year` int(4) NOT NULL,
  `timestamp` int(10) NOT NULL,
  `text` text,
  PRIMARY KEY (`e_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Extraindo dados da tabela `c_events`
--

INSERT INTO `c_events` (`e_id`, `title`, `type`, `day`, `month`, `year`, `timestamp`, `text`) VALUES
(1, 'Aniversário da Jeh Pleffken', 'public', 29, 7, 2013, 1375056000, 'Aniversário da <b>Jessica Pleffken</b> no dia 29 de julho às 20:00....<br />Conto com a presença de todos!<br /><br />Abcs!'),
(2, 'Niver da Marina', 'public', 17, 7, 2013, 1374019200, 'Aniversário da Marina, em Curitiba/PR');

-- --------------------------------------------------------

--
-- Estrutura da tabela `c_follow`
--

CREATE TABLE IF NOT EXISTS `c_follow` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `follower` int(8) DEFAULT NULL,
  `following` int(8) DEFAULT NULL,
  `date` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `c_help`
--

CREATE TABLE IF NOT EXISTS `c_help` (
  `h_id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(45) DEFAULT NULL,
  `short_desc` varchar(255) DEFAULT NULL,
  `content` text,
  PRIMARY KEY (`h_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Extraindo dados da tabela `c_help`
--

INSERT INTO `c_help` (`h_id`, `title`, `short_desc`, `content`) VALUES
(1, 'How to create a new thread?', 'This topic teaches how to create a new thread in a room.', 'Follow the steps below:<br />\r\n<br />\r\n1. Make sure you are logged in.<br />\r\n2. Enter in a room where you are allowed to post.<br />\r\n3. Click on the button &quot;New Thread&quot;.<br />\r\n4. Insert your thread title/subject.<br />\r\n5. Write the thread content.<br />\r\n6. Click on the button &quot;Post&quot;, right below the form.'),
(2, 'How to log out your account?', 'The following steps teaches how to log out your account.', 'Anywhere on the community you can find the logout link.<br />\r\n<br />\r\n1. On the left menu, right below your name, click on &quot;Logout&quot;.<br />\r\n2. On the top of the page, in the black bar, click on &quot;Logout&quot;.'),
(3, 'How to log in into your account?', 'This topic describes how to log in into your account, before you&#39;ve registered it.', 'You need to be registered in the community. After that, go to the left bar:<br />\r\n<br />\r\n1. Click on &quot;Log in&quot;.<br />\r\n2. Enter your username and password.<br />\r\n3. If you wish, you can enter as anonym (invisible to other users) or with a persistent log in (you remain logged into the community even if you close your browser).<br />\r\n4. Click on the &quot;Log in&quot; button.');

-- --------------------------------------------------------

--
-- Estrutura da tabela `c_languages`
--

CREATE TABLE IF NOT EXISTS `c_languages` (
  `l_id` int(2) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  `directory` varchar(10) NOT NULL,
  `author_name` varchar(50) NOT NULL,
  `author_email` varchar(50) NOT NULL,
  `active` int(1) NOT NULL,
  `default` int(1) NOT NULL,
  PRIMARY KEY (`l_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Extraindo dados da tabela `c_languages`
--

INSERT INTO `c_languages` (`l_id`, `name`, `directory`, `author_name`, `author_email`, `active`, `default`) VALUES
(1, 'English (US)', 'en', 'Addictive Services', 'brunno.pleffken@addictive.com.br', 1, 1);

-- --------------------------------------------------------

--
-- Estrutura da tabela `c_logs`
--

CREATE TABLE IF NOT EXISTS `c_logs` (
  `log_id` int(11) NOT NULL AUTO_INCREMENT,
  `member_id` int(8) NOT NULL,
  `time` int(10) NOT NULL,
  `act` varchar(250) DEFAULT NULL,
  `ip_address` varchar(46) DEFAULT NULL,
  PRIMARY KEY (`log_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

--
-- Extraindo dados da tabela `c_logs`
--

INSERT INTO `c_logs` (`log_id`, `member_id`, `time`, `act`, `ip_address`) VALUES
(1, 1, 1366731801, 'Created help topic: How to log in into your account?', '127.0.0.1'),
(2, 1, 1366829516, 'Database optimization.', '127.0.0.1'),
(3, 1, 1366829639, 'Database repairing.', '127.0.0.1'),
(4, 1, 1366829650, 'Database optimization.', '127.0.0.1'),
(5, 1, 1366992700, 'Executed system optimization: member counting.', '127.0.0.1'),
(6, 1, 1368029355, 'Deleted report ID #', '::1'),
(7, 1, 1368029639, 'Deleted abuse report ID #1 for the thread ID #10', '::1');

-- --------------------------------------------------------

--
-- Estrutura da tabela `c_members`
--

CREATE TABLE IF NOT EXISTS `c_members` (
  `m_id` int(8) NOT NULL AUTO_INCREMENT,
  `username` varchar(45) NOT NULL,
  `password` varchar(45) NOT NULL,
  `email` varchar(50) NOT NULL,
  `hide_email` int(1) DEFAULT NULL,
  `ip_address` varchar(46) DEFAULT NULL,
  `joined` int(10) DEFAULT NULL,
  `usergroup` int(2) DEFAULT NULL,
  `member_title` varchar(40) DEFAULT NULL,
  `location` varchar(50) DEFAULT NULL,
  `profile` text,
  `gender` varchar(1) DEFAULT NULL,
  `b_day` int(2) DEFAULT NULL,
  `b_month` int(2) DEFAULT NULL,
  `b_year` int(4) DEFAULT NULL,
  `photo` varchar(40) DEFAULT NULL,
  `photo_type` varchar(10) DEFAULT NULL,
  `website` varchar(60) DEFAULT NULL,
  `im_windowslive` varchar(50) DEFAULT NULL,
  `im_skype` varchar(50) DEFAULT NULL,
  `im_facebook` varchar(50) DEFAULT NULL,
  `im_twitter` varchar(50) DEFAULT NULL,
  `im_yim` varchar(50) DEFAULT NULL,
  `im_aol` varchar(50) DEFAULT NULL,
  `posts` int(9) DEFAULT NULL,
  `lastpost_date` int(10) DEFAULT NULL,
  `signature` text,
  `signature_edit` text,
  `template` varchar(10) DEFAULT NULL,
  `language` varchar(10) DEFAULT NULL,
  `warn_level` int(1) DEFAULT NULL,
  `warn_date` int(10) DEFAULT NULL,
  `last_activity` int(10) DEFAULT NULL,
  `time_offset` varchar(5) DEFAULT NULL,
  `dst` int(1) DEFAULT NULL,
  `show_email` int(1) DEFAULT NULL,
  `show_birthday` int(1) DEFAULT NULL,
  `show_gender` int(1) DEFAULT NULL,
  PRIMARY KEY (`m_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Extraindo dados da tabela `c_members`
--

INSERT INTO `c_members` (`m_id`, `username`, `password`, `email`, `hide_email`, `ip_address`, `joined`, `usergroup`, `member_title`, `location`, `profile`, `gender`, `b_day`, `b_month`, `b_year`, `photo`, `photo_type`, `website`, `im_windowslive`, `im_skype`, `im_facebook`, `im_twitter`, `im_yim`, `im_aol`, `posts`, `lastpost_date`, `signature`, `signature_edit`, `template`, `language`, `warn_level`, `warn_date`, `last_activity`, `time_offset`, `dst`, `show_email`, `show_birthday`, `show_gender`) VALUES
(1, 'Brunno Pleffken', 'd7ba09d3429df7c9583b679bebd6ad2c', 'brunno.pleffken@outlook.com', 0, '127.0.0.1', 1355158166, 1, 'Under The Light&#39;s keyboardist ;)', 'São José dos Campos', 'In short, I&#39;m responsible to all planning, programing and interface design of the Addictive products!', 'M', 1, 2, 1989, '1.jpg', 'custom', 'http://www.addictive.com.br', '', 'brunno.pleffken', 'brupleffken', 'brunnopleffken', '', '', 27, 1367848084, '<b>Brunno Pleffken Hosti</b><br />\nCEO &amp; Chief Software Architect', '[b]Brunno Pleffken Hosti[/b]\r\nCEO &amp; Chief Software Architect', '1', 'en', NULL, NULL, 1370616920, '-3', 0, 1, 1, 1),
(2, 'Fronteira Final', 'd7ba09d3429df7c9583b679bebd6ad2c', 'fronteirafinal89@hotmail.com', NULL, '127.0.0.1', 1366316889, 3, '', 'Taubaté, Brazil', '', 'M', 17, 7, 1989, NULL, 'gravatar', 'http://www.brunnopleffken.com.br', '', '', '', 'underthelightbr', '', '', 3, 1367848487, NULL, NULL, '1', 'en', NULL, NULL, 1368635521, '0', 0, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Estrutura da tabela `c_pm`
--

CREATE TABLE IF NOT EXISTS `c_pm` (
  `pm_id` int(8) NOT NULL AUTO_INCREMENT,
  `from_id` int(8) NOT NULL,
  `to_id` int(8) NOT NULL,
  `subject` varchar(35) NOT NULL,
  `status` int(1) NOT NULL,
  `sent_date` int(10) NOT NULL,
  `message` text NOT NULL,
  `read_date` int(10) DEFAULT NULL,
  `attach_id` int(6) DEFAULT NULL,
  `parent_pm` int(8) DEFAULT NULL,
  PRIMARY KEY (`pm_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Extraindo dados da tabela `c_pm`
--

INSERT INTO `c_pm` (`pm_id`, `from_id`, `to_id`, `subject`, `status`, `sent_date`, `message`, `read_date`, `attach_id`, `parent_pm`) VALUES
(1, 1, 2, 'Lorem ipsum dolor', 0, 1368632230, 'Lorem ipsum dolor sit amet consectetur adispicing elit. Lorem ipsum dolor sit amet consectetur adispicing elit. Lorem ipsum dolor sit amet consectetur adispicing elit. Lorem ipsum dolor sit amet consectetur adispicing elit.<br />\r\n<br />\r\nLorem isum dolor!<br />\r\n<br />\r\n- Brunno Pleffken', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Estrutura da tabela `c_posts`
--

CREATE TABLE IF NOT EXISTS `c_posts` (
  `p_id` int(9) NOT NULL AUTO_INCREMENT,
  `author_id` int(8) NOT NULL,
  `thread_id` int(8) NOT NULL,
  `post_date` int(10) NOT NULL,
  `attach_id` int(6) DEFAULT NULL,
  `attach_clicks` int(10) DEFAULT NULL,
  `ip_address` varchar(46) NOT NULL,
  `post` text NOT NULL,
  `post_html` text NOT NULL,
  `edit_time` int(10) DEFAULT NULL,
  `edit_author` int(8) DEFAULT NULL,
  `best_answer` int(1) NOT NULL,
  `first_post` int(1) NOT NULL,
  PRIMARY KEY (`p_id`),
  FULLTEXT KEY `post` (`post`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=37 ;

--
-- Extraindo dados da tabela `c_posts`
--

INSERT INTO `c_posts` (`p_id`, `author_id`, `thread_id`, `post_date`, `attach_id`, `attach_clicks`, `ip_address`, `post`, `post_html`, `edit_time`, `edit_author`, `best_answer`, `first_post`) VALUES
(1, 1, 1, 1355417366, NULL, NULL, '127.0.0.1', 'Addictive Community 1.0 is approaching and will be released soon with all sorts of great enhancements such as SEO improvements, upgraded editor, login enhancements, core performance improvements, lots of minor changes from feedback, and more still to be announced.', 'Addictive Community 1.0 is approaching and will be released soon with all sorts of great enhancements such as SEO improvements, upgraded editor, login enhancements, core performance improvements, lots of minor changes from feedback, and more still to be announced.', NULL, NULL, 0, 1),
(2, 1, 1, 1355431046, NULL, NULL, '127.0.0.1', 'This is a great time to take a look at Addictive Community if your community might still be running another software. Perhaps you have a friend or a site you visit frequently using another software and you want to encourage them to switch to the Addictive Services software. By starting the switch process now you can have your community ready to go, be familiar with Addictive software and services, and have any questions answered so when Addictive Community v2 is released you can jump right on this new version!', 'This is a great time to take a look at Addictive Community if your community might still be running another software. Perhaps you have a friend or a site you visit frequently using another software and you want to encourage them to switch to the Addictive Services software. By starting the switch process now you can have your community ready to go, be familiar with Addictive software and services, and have any questions answered so when Addictive Community v2 is released you can jump right on this new version!', NULL, NULL, 0, 0),
(3, 1, 1, 1355432168, NULL, NULL, '127.0.0.1', 'We are running a special promotion now through 1 October 2012 for anyone wanting to switch to Addictive Community. Use the coupon code SWITCH at checkout to receive 10% off your entire order. This is in addition to the existing bundle discounts you get when purchasing multiple Addictive Services apps!', 'We are running a special promotion now through 1 October 2012 for anyone wanting to switch to Addictive Community. Use the coupon code SWITCH at checkout to receive 10% off your entire order. This is in addition to the existing bundle discounts you get when purchasing multiple Addictive Services apps!', NULL, NULL, 1, 0),
(4, 1, 1, 1357358253, NULL, NULL, '::1', 'With its recent version 1.0 releases of its flagship product: Addictive Community, the Company has refocused its products, services, and offerings. The change started with a successful sale offer on Addictive Community and will culminate with a new customer-focused company.', 'With its recent version 1.0 releases of its flagship product: Addictive Community, the Company has refocused its products, services, and offerings. The change started with a successful sale offer on Addictive Community and will culminate with a new customer-focused company.', NULL, NULL, 0, 0),
(5, 1, 1, 1357358545, NULL, NULL, '::1', 'Some of the most important changes include sweeping changes to the customer service process. The Company has introduced live chat support for its customers, more efficient telephone support, and posted expected response times in the support desk. The downloadable free trial of <b>Invision Power Board</b> has been removed and replaced with a free, 15 day demo hosted account to try out the software. This change will allow the Company to focus its complete attention on product development and customer needs.<br><br>&quot;One of the best changes is the introduction of a new Standards of Service policy. We hope by moving our policies out of technical licenses and terms of service agreements into an easy to read and welcoming format it will serve to benefit both our customers and staff. By clarifying what, why, and how we provide service to our customers we will be able to further improve that service&quot;.', 'Some of the most important changes include sweeping changes to the customer service process. The Company has introduced live chat support for its customers, more efficient telephone support, and posted expected response times in the support desk. The downloadable free trial of <b>Invision Power Board</b> has been removed and replaced with a free, 15 day demo hosted account to try out the software. This change will allow the Company to focus its complete attention on product development and customer needs.<br><br>&quot;One of the best changes is the introduction of a new Standards of Service policy. We hope by moving our policies out of technical licenses and terms of service agreements into an easy to read and welcoming format it will serve to benefit both our customers and staff. By clarifying what, why, and how we provide service to our customers we will be able to further improve that service&quot;.', NULL, NULL, 0, 0),
(6, 1, 1, 1357358666, NULL, NULL, '::1', 'The Standards of Service policy will serve to explain the impressive outline of customer service offerings available. Through a <u>single interface</u>, any customer will be able to quickly see the value of their purchase with Invision Power Services and how to get even more from our varied array of products.', 'The Standards of Service policy will serve to explain the impressive outline of customer service offerings available. Through a <u>single interface</u>, any customer will be able to quickly see the value of their purchase with Invision Power Services and how to get even more from our varied array of products.', NULL, NULL, 0, 0),
(7, 1, 1, 1357358933, NULL, NULL, '::1', 'In addition to customer service improvements the IPS Hosting web site and forum hosting division has been completely re-launched. The dedicated server line has been expanded and more benefits added for server customers. All IPS Hosting shared, forum, and dedicated server accounts will come with an Invision Power Board 2.0 license for use on their account at no extra charge.<br><br>Invision Power Services, Inc. will be announcing new employment openings, products, services, and promotions in the coming weeks to celebrate its new focus and bright future.<br><br>Invision Power Services, Inc. is a leading provider of online community solutions and web hosting. Invision Power Board is used on thousands of web sites around the Internet to power their community.', 'In addition to customer service improvements the IPS Hosting web site and forum hosting division has been completely re-launched. The dedicated server line has been expanded and more benefits added for server customers. All IPS Hosting shared, forum, and dedicated server accounts will come with an Invision Power Board 2.0 license for use on their account at no extra charge.<br><br>Invision Power Services, Inc. will be announcing new employment openings, products, services, and promotions in the coming weeks to celebrate its new focus and bright future.<br><br>Invision Power Services, Inc. is a leading provider of online community solutions and web hosting. Invision Power Board is used on thousands of web sites around the Internet to power their community.', NULL, NULL, 0, 0),
(8, 1, 1, 1357444352, NULL, NULL, '::1', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc facilisis \r\ndapibus sagittis. Donec ut pretium quam. Cras laoreet ultricies commodo.\r\n Phasellus dictum ornare nisi ac facilisis. Ut ornare quam non diam \r\nvehicula tincidunt. Morbi iaculis ipsum semper nisl ullamcorper eu \r\nvolutpat est viverra. In ut nulla ut orci varius volutpat. Vestibulum \r\neuismod tempor diam in elementum. Donec aliquam dolor felis. Cras non \r\nnunc eros, vel ullamcorper odio. Pellentesque nunc sem, pretium vitae \r\nbibendum ut, molestie et nisl. Aenean a neque vel nisl euismod eleifend \r\neget id nibh. Cras at libero laoreet nisl malesuada tincidunt sit amet \r\nid orci. Curabitur erat orci, pretium vitae adipiscing vitae, facilisis \r\nat ante. Sed eget dictum sapien. Fusce mollis dignissim ligula malesuada\r\n cursus.', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc facilisis ', NULL, NULL, 0, 0),
(9, 1, 1, 1357444367, NULL, NULL, '::1', 'Integer interdum, ante sed lacinia adipiscing, urna ipsum egestas elit, \r\nnon auctor nisl dolor ac elit. Cras lorem ipsum, dapibus sed pretium sit\r\n amet, pretium vel nibh. Proin ac dui urna, a sodales diam. Aenean urna \r\npurus, venenatis eu varius ac, convallis nec urna. Maecenas elit nunc, \r\nconvallis id viverra sed, viverra a sem. Cras bibendum mauris et justo \r\nplacerat varius. Morbi dapibus nunc et metus mollis dictum semper quam \r\nplacerat. In hac habitasse platea dictumst. Duis mi ante, consectetur ac\r\n dignissim condimentum, sollicitudin eu libero. Duis in quam metus. \r\nVestibulum laoreet nisi non velit semper pharetra. Ut mattis facilisis \r\nfaucibus. Donec fringilla magna quis est auctor mollis. Integer nisi \r\nnisi, vehicula et varius in, congue nec eros. Quisque dignissim leo \r\ndolor, eget hendrerit ipsum.', 'Integer interdum, ante sed lacinia adipiscing, urna ipsum egestas elit, ', NULL, NULL, 0, 0),
(10, 1, 1, 1357444380, NULL, NULL, '::1', 'Curabitur cursus dui vitae dui varius id tristique quam iaculis. \r\nPhasellus turpis purus, dictum tincidunt vulputate ut, aliquam eu \r\nlibero. Nam euismod laoreet enim in fringilla. Etiam in leo vel tellus \r\ninterdum ornare in mattis quam. Fusce convallis nunc sit amet nibh \r\ntincidunt aliquet. Cras sed sapien vitae erat sagittis lacinia. Fusce \r\nvehicula ullamcorper rhoncus. Mauris aliquam est et lorem sagittis at \r\ndapibus leo viverra. Nulla non fringilla nunc.', 'Curabitur cursus dui vitae dui varius id tristique quam iaculis. ', NULL, NULL, 0, 0),
(11, 1, 1, 1357444394, NULL, NULL, '::1', 'Maecenas id nibh mi. Ut porttitor enim dapibus libero pretium et rhoncus\r\n lectus sagittis. Fusce vitae orci tellus. Suspendisse potenti. \r\nPellentesque eros urna, mattis vitae laoreet non, condimentum vitae \r\nlectus. Sed sit amet tellus augue, eu iaculis risus. In tempus, odio sit\r\n amet scelerisque consectetur, magna augue auctor erat, sit amet \r\nvestibulum nibh lectus ut sapien. Quisque pulvinar euismod purus in \r\nconsequat. In hac habitasse platea dictumst. Etiam pellentesque dapibus \r\nnisl sed dictum. Suspendisse potenti. Vivamus id pulvinar nulla. \r\nPraesent semper, magna at tincidunt dictum, eros libero rutrum eros, et \r\nsuscipit quam enim a metus. Mauris massa velit, auctor at elementum \r\ncongue, pulvinar at urna. Donec sit amet nibh sapien, at lobortis orci. \r\nProin dolor libero, pulvinar eget convallis in, mattis non risus.', 'Maecenas id nibh mi. Ut porttitor enim dapibus libero pretium et rhoncus', NULL, NULL, 0, 0),
(12, 1, 1, 1357444406, NULL, NULL, '::1', 'Proin nec felis justo. Sed sed justo odio, id vestibulum sapien. Vivamus\r\n et pretium sapien. Aliquam ullamcorper condimentum nibh tincidunt \r\ncursus. In auctor lacus id enim fringilla molestie. Aliquam id arcu \r\nrisus. Donec tempus iaculis augue, a dapibus lacus eleifend ultrices. \r\nInteger gravida vehicula sapien, sed blandit diam rutrum ac.', 'Proin nec felis justo. Sed sed justo odio, id vestibulum sapien. Vivamus', NULL, NULL, 0, 0),
(14, 1, 1, 1357444616, NULL, NULL, '::1', 'Etiam vel nisl massa. Aenean facilisis orci id neque vulputate eleifend \nvehicula diam pulvinar. Ut ut rhoncus nulla. Quisque in tempus est. \nQuisque ac augue nisl, ac scelerisque mauris. In hac habitasse platea \ndictumst. Maecenas hendrerit, nisi eget suscipit tristique, sapien neque\n facilisis odio, vel adipiscing nisi ligula sed enim. Nunc mi nisi, \nlobortis non iaculis a, vehicula sed nunc. Nunc pellentesque lacinia \nnisi, et consectetur nunc aliquet id. Mauris quis arcu nulla.\n<br><br>\nNam quis risus non est sagittis bibendum. Proin ac vestibulum arcu. \nPhasellus dictum mattis tincidunt. Integer ut massa ac tortor pretium \nscelerisque. Morbi ornare ornare nulla, vel tincidunt ante congue \nimperdiet. Duis velit augue, ultricies eget condimentum eget, molestie \nnec risus. Nulla interdum commodo varius. Curabitur luctus metus ut arcu\n porttitor auctor. Pellentesque at erat quam. Pellentesque habitant \nmorbi tristique senectus et netus et malesuada fames ac turpis egestas.', 'Etiam vel nisl massa. Aenean facilisis orci id neque vulputate eleifend ', NULL, NULL, 0, 0),
(15, 1, 1, 1357912976, NULL, NULL, '127.0.0.1', 'Olá gente, meu nome é [b]Brunno Pleffken[/b] e moro em [u]São José dos Campos[/u], no estado de São Paulo... :)\r\n\r\nAbraços pra todos!', 'Olá gente, meu nome é <b>Brunno Pleffken</b> e moro em <u>São José dos Campos</u>, no estado de São Paulo... <img src="public/emoticons/default/happy.png" style="vertical-align:text-bottom"><br />\n<br />\nAbraços pra todos!', NULL, NULL, 0, 0),
(16, 1, 1, 1358279094, NULL, NULL, '127.0.0.1', 'Testando [b]teste[/b]...', 'Testando <b>teste</b>...', NULL, NULL, 0, 0),
(17, 1, 2, 1358854362, NULL, NULL, '127.0.0.1', 'This is just a test, you can delete it whenever you want!', 'This is just a test, you can delete it whenever you want!', NULL, NULL, 0, 1),
(18, 1, 3, 1358855128, NULL, NULL, '127.0.0.1', 'This is a thread for testing a &quot;new thread&quot; [s]shit[/s] feature, but without &quot;lock thread&quot; being selected... Also, we will test the [b]BBcode[/b] feature... =D\r\n\r\nThanks!', 'This is a thread for testing a &quot;new thread&quot; <span style="text-decoration: line-through">shit</span> feature, but without &quot;lock thread&quot; being selected... Also, we will test the <b>BBcode</b> feature... =D<br />\n<br />\nThanks!', NULL, NULL, 0, 1),
(19, 1, 4, 1358855296, NULL, NULL, '127.0.0.1', 'Man, this is the best free open-source community I have ever seen!\r\nCongratulations to all the Addictive team... Keep going!\r\n\r\nJust a note: the calendar function seems a little buggy... Check this out! o_o\r\n\r\nHugs from Germany!\r\nAlles gut!', 'Man, this is the best free open-source community I have ever seen!<br />\nCongratulations to all the Addictive team... Keep going!<br />\n<br />\nJust a note: the calendar function seems a little buggy... Check this out! o_o<br />\n<br />\nHugs from Germany!<br />\nAlles gut!', NULL, NULL, 0, 1),
(20, 1, 5, 1358855784, NULL, NULL, '127.0.0.1', 'How do I say &quot;Hello&quot; in portuguese?\r\n\r\nThanks for all!\r\n\\o', 'How do I say &quot;Hello&quot; in portuguese?<br />\n<br />\nThanks for all!<br />\n\\o', NULL, NULL, 0, 1),
(26, 1, 5, 1365017959, NULL, NULL, '127.0.0.1', '&quot;Oi&quot; ou &quot;Olá&quot;...\r\n\r\n;)', '&quot;Oi&quot; ou &quot;Olá&quot;...<br />\n<br />\n;)', NULL, NULL, 0, 0),
(22, 1, 6, 1359715579, NULL, NULL, '127.0.0.1', 'Teste!', 'Teste!', NULL, NULL, 0, 1),
(28, 1, 6, 1365019071, NULL, NULL, '127.0.0.1', 'Tréplica!', 'Tréplica!', NULL, NULL, 0, 0),
(30, 1, 6, 1365020854, NULL, NULL, '127.0.0.1', 'Oi?', 'Oi?', NULL, NULL, 0, 0),
(31, 1, 9, 1365021420, NULL, NULL, '127.0.0.1', 'Let&#39;s se if it shows [b]zero[/b] or a negative number...', 'Let&#39;s se if it shows <b>zero</b> or a negative number...', NULL, NULL, 0, 1),
(33, 2, 9, 1366375603, NULL, NULL, '127.0.0.1', 'Para de ficar postando coisa em inglês... -.-&#39;', 'Para de ficar postando coisa em inglês... -.-&#39;', NULL, NULL, 0, 0),
(32, 1, 9, 1366041882, NULL, NULL, '127.0.0.1', 'Test daylight time saving...', 'Test daylight time saving...', NULL, NULL, 0, 0),
(27, 1, 6, 1365019060, NULL, NULL, '127.0.0.1', 'Enviando um outro teste neste mesmo tópico...', 'Enviando um outro teste neste mesmo tópico...', NULL, NULL, 0, 0),
(34, 2, 10, 1367846535, NULL, NULL, '::1', 'Now i&#39;m posting a new thread, but this time, i&#39;m posting from another user...\r\nCheck if the bottom post bar is showing the correct items...\r\n\r\nThx!', 'Now i&#39;m posting a new thread, but this time, i&#39;m posting from another user...<br />\nCheck if the bottom post bar is showing the correct items...<br />\n<br />\nThx!', NULL, NULL, 0, 1),
(35, 1, 10, 1367848084, NULL, NULL, '::1', 'Ha! You&#39;ve posted in English too... =P\r\n\r\n[i]Tks![/i]', 'Ha! You&#39;ve posted in English too... =P<br /><br /><em>Tks!</em>', 1369331758, 1, 0, 0),
(36, 2, 10, 1367848487, NULL, NULL, '::1', 'Just this time, rsrsrs', 'Just this time, rsrsrs', NULL, NULL, 0, 0);

-- --------------------------------------------------------

--
-- Estrutura da tabela `c_ranks`
--

CREATE TABLE IF NOT EXISTS `c_ranks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(20) DEFAULT NULL,
  `min_posts` int(5) DEFAULT NULL,
  `pips` int(1) DEFAULT NULL,
  `image` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `c_reports`
--

CREATE TABLE IF NOT EXISTS `c_reports` (
  `rp_id` int(11) NOT NULL AUTO_INCREMENT,
  `description` text,
  `date` int(10) NOT NULL,
  `sender_id` int(9) NOT NULL,
  `ip_address` varchar(15) NOT NULL,
  `post_id` int(9) NOT NULL,
  `thread_id` int(9) NOT NULL,
  `referer` varchar(255) NOT NULL,
  PRIMARY KEY (`rp_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Extraindo dados da tabela `c_reports`
--

INSERT INTO `c_reports` (`rp_id`, `description`, `date`, `sender_id`, `ip_address`, `post_id`, `thread_id`, `referer`) VALUES
(2, 'He was agressive against our own language! I think this is not quite moral... Please, evaluate this post and do what must be done! rs', 1368554961, 1, '::1', 33, 9, 'http://localhost/community/index.php?module=thread&id=9');

-- --------------------------------------------------------

--
-- Estrutura da tabela `c_rooms`
--

CREATE TABLE IF NOT EXISTS `c_rooms` (
  `r_id` int(3) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `description` text,
  `url` varchar(100) DEFAULT NULL,
  `order_n` int(3) DEFAULT NULL,
  `lastpost_date` int(10) DEFAULT NULL,
  `lastpost_thread` int(8) DEFAULT NULL,
  `lastpost_member` int(8) DEFAULT NULL,
  `invisible` int(1) DEFAULT NULL,
  `rules_title` varchar(50) DEFAULT NULL,
  `rules_text` text,
  `rules_visible` int(1) DEFAULT NULL,
  `read_only` int(1) DEFAULT NULL,
  `password` varchar(45) DEFAULT NULL,
  `upload` int(1) DEFAULT NULL,
  `perm_view` varchar(255) DEFAULT NULL,
  `perm_post` varchar(255) DEFAULT NULL,
  `perm_reply` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`r_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Extraindo dados da tabela `c_rooms`
--

INSERT INTO `c_rooms` (`r_id`, `name`, `description`, `url`, `order_n`, `lastpost_date`, `lastpost_thread`, `lastpost_member`, `invisible`, `rules_title`, `rules_text`, `rules_visible`, `read_only`, `password`, `upload`, `perm_view`, `perm_post`, `perm_reply`) VALUES
(1, 'General Web Design and Coding', 'Discuss XHTML, XML, PHP, SQL, etc and general web design tips and tricks not specific to Addictive products. There is a lot of talent in the Addictive client-community so ask or contribute here!', NULL, 1, 1367848487, 10, 2, 0, '', '', 0, 0, '', 1, 'a:5:{i:0;s:3:"V_1";i:1;s:3:"V_2";i:2;s:3:"V_3";i:3;s:3:"V_4";i:4;s:3:"V_5";}', 'a:3:{i:0;s:3:"V_1";i:1;s:3:"V_2";i:2;s:3:"V_3";}', 'a:3:{i:0;s:3:"V_1";i:1;s:3:"V_2";i:2;s:3:"V_3";}'),
(3, 'Pre-Sales Questions', 'Question before you purchase? Post here and get help from both Addictive Softwares and from other clients.', NULL, NULL, NULL, NULL, NULL, 0, 'Note', 'Please note that replies to topics in this pre-sales forum are moderated to ensure that potential customers are given accurate information. Your replies will not appear instantly as a moderator will have to approve them.', 1, 0, '', 1, 'a:5:{i:0;s:3:"V_1";i:1;s:3:"V_2";i:2;s:3:"V_3";i:3;s:3:"V_4";i:4;s:3:"V_5";}', 'a:3:{i:0;s:3:"V_1";i:1;s:3:"V_2";i:2;s:3:"V_3";}', 'a:3:{i:0;s:3:"V_1";i:1;s:3:"V_2";i:2;s:3:"V_3";}');

-- --------------------------------------------------------

--
-- Estrutura da tabela `c_sessions`
--

CREATE TABLE IF NOT EXISTS `c_sessions` (
  `s_id` varchar(60) NOT NULL,
  `member_id` int(11) DEFAULT NULL,
  `username` varchar(50) DEFAULT NULL,
  `ip_address` varchar(46) DEFAULT NULL,
  `browser` varchar(200) DEFAULT NULL,
  `activity_time` int(10) DEFAULT NULL,
  `usergroup` int(2) DEFAULT NULL,
  `anonymous` int(1) DEFAULT NULL,
  `location_type` varchar(30) DEFAULT NULL,
  `location_id` int(11) DEFAULT NULL,
  `location_room_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`s_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `c_sessions`
--

INSERT INTO `c_sessions` (`s_id`, `member_id`, `username`, `ip_address`, `browser`, `activity_time`, `usergroup`, `anonymous`, `location_type`, `location_id`, `location_room_id`) VALUES
('b46833c9c570a96ce03f1b470f6e4603', 0, 'Guest', '::1', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:24.0) Gecko/20100101 Firefox/24.0', 1379965807, 5, 0, 'community', NULL, NULL);

-- --------------------------------------------------------

--
-- Estrutura da tabela `c_stats`
--

CREATE TABLE IF NOT EXISTS `c_stats` (
  `id` int(1) NOT NULL AUTO_INCREMENT,
  `member_count` int(8) DEFAULT NULL,
  `total_posts` int(10) DEFAULT NULL,
  `total_threads` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Extraindo dados da tabela `c_stats`
--

INSERT INTO `c_stats` (`id`, `member_count`, `total_posts`, `total_threads`) VALUES
(1, 2, 30, 7);

-- --------------------------------------------------------

--
-- Estrutura da tabela `c_templates`
--

CREATE TABLE IF NOT EXISTS `c_templates` (
  `tpl_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  `directory` varchar(10) NOT NULL,
  `active` int(1) NOT NULL,
  `author_name` varchar(50) NOT NULL,
  `author_email` varchar(50) NOT NULL,
  `css_file` varchar(20) NOT NULL,
  PRIMARY KEY (`tpl_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Extraindo dados da tabela `c_templates`
--

INSERT INTO `c_templates` (`tpl_id`, `name`, `directory`, `active`, `author_name`, `author_email`, `css_file`) VALUES
(1, 'Default', '1', 1, 'Addictive Services', 'brunno.pleffken@addictive.com.br', '1.css');

-- --------------------------------------------------------

--
-- Estrutura da tabela `c_threads`
--

CREATE TABLE IF NOT EXISTS `c_threads` (
  `t_id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(250) NOT NULL,
  `author_member_id` int(8) NOT NULL,
  `replies` int(9) NOT NULL,
  `views` int(9) NOT NULL,
  `start_date` int(10) NOT NULL,
  `room_id` int(3) NOT NULL,
  `tags` varchar(255) DEFAULT NULL,
  `announcement` int(1) DEFAULT NULL,
  `lastpost_date` int(10) DEFAULT NULL,
  `lastpost_member_id` int(8) DEFAULT NULL,
  `moved_to` int(3) DEFAULT NULL,
  `locked` int(1) DEFAULT NULL,
  `approved` int(1) DEFAULT NULL,
  `with_bestanswer` int(1) DEFAULT NULL,
  PRIMARY KEY (`t_id`),
  FULLTEXT KEY `title` (`title`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=11 ;

--
-- Extraindo dados da tabela `c_threads`
--

INSERT INTO `c_threads` (`t_id`, `title`, `author_member_id`, `replies`, `views`, `start_date`, `room_id`, `tags`, `announcement`, `lastpost_date`, `lastpost_member_id`, `moved_to`, `locked`, `approved`, `with_bestanswer`) VALUES
(1, 'Switch to Addictive Community platform', 1, 15, 343, 1355417366, 1, NULL, 0, 1358279094, 1, NULL, 0, 1, 1),
(2, 'Just a thread test', 1, 1, 28, 1358854362, 1, NULL, 0, 1358854362, 1, NULL, 1, 1, 0),
(3, 'This is not a locked thread', 1, 1, 31, 1358855128, 1, NULL, 0, 1358855128, 1, NULL, 0, 1, 0),
(4, 'Addictive Community is the best!', 1, 1, 68, 1358855296, 1, NULL, 0, 1358855296, 1, NULL, 0, 1, 0),
(5, 'How to say hello in portuguese?', 1, 2, 102, 1358855784, 1, NULL, 0, 1365017959, 1, NULL, 0, 1, 0),
(6, 'Timezone test...', 1, 4, 94, 1359715579, 1, NULL, 1, 1365020854, 1, NULL, 0, 1, 0),
(9, 'Another thread to count', 1, 3, 86, 1365021420, 1, NULL, 0, 1366375603, 2, NULL, 0, 1, 0),
(10, 'New thread from another use', 2, 3, 106, 1367846535, 1, 'test, beta, new thread', 0, 1367848487, 2, NULL, 0, 1, 0);

-- --------------------------------------------------------

--
-- Estrutura da tabela `c_threads_read`
--

CREATE TABLE IF NOT EXISTS `c_threads_read` (
  `thread_id` int(10) DEFAULT NULL,
  `member_id` int(8) DEFAULT NULL,
  `date` int(10) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `c_usergroups`
--

CREATE TABLE IF NOT EXISTS `c_usergroups` (
  `g_id` int(2) NOT NULL AUTO_INCREMENT,
  `name` varchar(40) NOT NULL,
  `preffix` varchar(20) DEFAULT NULL,
  `suffix` varchar(20) DEFAULT NULL,
  `color` varchar(7) DEFAULT NULL,
  `view_board` int(1) DEFAULT NULL,
  `post_new_threads` int(1) DEFAULT NULL,
  `reply_threads` int(1) DEFAULT NULL,
  `edit_own_threads` int(1) DEFAULT NULL,
  `edit_own_posts` int(1) DEFAULT NULL,
  `delete_own_posts` int(1) DEFAULT NULL,
  `can_attach` int(1) DEFAULT NULL,
  `access_offline` int(1) DEFAULT NULL,
  `post_html` int(1) DEFAULT NULL,
  `avoid_flood` int(1) DEFAULT NULL,
  `admin_cp` int(1) DEFAULT NULL,
  `max_pm_storage` int(8) DEFAULT NULL,
  `stock` int(1) DEFAULT NULL,
  PRIMARY KEY (`g_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Extraindo dados da tabela `c_usergroups`
--

INSERT INTO `c_usergroups` (`g_id`, `name`, `preffix`, `suffix`, `color`, `view_board`, `post_new_threads`, `reply_threads`, `edit_own_threads`, `edit_own_posts`, `delete_own_posts`, `can_attach`, `access_offline`, `post_html`, `avoid_flood`, `admin_cp`, `max_pm_storage`, `stock`) VALUES
(1, 'Administrator', NULL, NULL, '#070', 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 0, 1),
(2, 'Moderator', NULL, NULL, '#090', 1, 1, 1, 1, 1, 1, 1, 0, 0, 0, 0, 0, 1),
(3, 'Member', NULL, NULL, '#000', 1, 1, 1, 1, 1, 0, 1, 0, 0, 0, 0, 0, 1),
(4, 'Banned', NULL, NULL, '#F00', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(5, 'Guest', NULL, NULL, NULL, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
