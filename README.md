Addictive Community
===================

Discussion forum software, written in object-oriented PHP. This is an Alpha version (UNDER DEVELOPMENT) and has issues and incomplete features that will be fixed soon!

![Addictive Community](https://raw.githubusercontent.com/brunnopleffken/addictive-community/3fd1ec6003d1bcb647dc1e36a89dd0506b81a5f1/templates/default/images/screenshot.png)

## Highlight features##

* Looks amazing in Retina Display and High-DPI screens;
* Markdown, HTML and WYSIWYG post editor;
* Mark threads as answered or obsolete;
* Social networks integration and sharing;
* Built-in mobile support;
* API extension system for third-party tools and add-ins;
* Integrated database maintenance tools;
* Templates and languages customization;
* Built-in Search Engine Optimization (SEO) tools;
* RSS syndication;
* And much more!

## Installing ##

1. Download the latest version of Addictive Community. Upload all the files contained in this archive (retaining the directory structure).
2. Change the following files/folders permissions to read and write (CHMOD 777 or -rwxrwxrwx): `config.php`, `/install`, `/uploads`, `/public/attachments` and `/public/avatar`.
3. Run your Addictive Community (e.g. http://www.mywebsite.com/forum). You should be redirected to the installer (if not, read below). Please, have the login data of the MySQL server (host server, username and password), as well as the database name.
4. Follow the steps and wait until the installer gets your community ready.
5. Addictive Community should now be available!

*NOTE: If you are not redirected to the installer, make sure your config.php file is empty.*

### Requirements ###

* Apache 2 webserver running on any major Operating System;
* PHP 5.3 or higher;
* MySQL 5.1 or higher;

## Development: How does the Addictive Community's framework works? ##

As a .NET/C# and a PHP programmer, I've tried to get the best of both worlds: the simplicity of ASP.NET WebForms/MVC and the flexibility of PHP. So, there is just a few points that really matter:

* There is a "templates" folder, where the Views, CSS files and images are stored. Inside it we have folders for skins, all under the same controller. So, we can have `templates/darkskin/default.tpl.php` and `templates/lightskin/default.tpl.php`, both controlled by `controller/default.php`.
* There is a "controller" folder, where Controllers (duh) are stored. The file name of a Controller must be the same as the View's file name with `.tpl` added to it. E.g.: `template/lightskin/mypage.tpl.php` and `controller/mypage.php`. And that's all!
* If there is a `templates/.../file.tpl.php` and `controller/file.php`, it can be accessed by the address: `index.php?module=file`. Voilá! Your page must be shown on the screen! ;)

## About the Author ##

**Brunno Pleffken Hosti**, programmer well-versed in object-oriented PHP, .NET/C# (ASP.NET, Windows Forms, WPF and Windows Phone development) and ColdFusion. Currently, Front-End developer at Phocus Interact (www.phocus.com.br).

* Twitter: http://twitter.com/brunnopleffken
