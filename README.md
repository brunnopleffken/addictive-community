Addictive Community
===================

Discussion forum software, written in object-oriented PHP and MySQL 5.1+. UNDER DEVELOPMENT, this is an Alpha version and has issues and incomplete features that will be fixed soon!

## Highlight features##

* BB Code, HTML and WYSIWYG post editor;
* Mark threads as answered;
* Social networks integration and sharing;
* Built-in mobile support;
* API extension system;
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

* Webserver running on any major Operating System with support for PHP (like Apache on Linux or IIS on Windows);
* PHP 5.3 or higher (GD lib required);
* MySQL 5.1 or higher;

## Development: How does the Addictive Community's framework works? ##

As a .NET/C# and a PHP programmer, I've tried to get the best of both worlds: the simplicity of ASP.NET WebForms/MVC and the flexibility of PHP. I wrote this framework from scratch and it is based on MVC pattern, but just "based on", because it is not an actual MVC. There is no "model" layer. All database access, queries and data formatting is done inside the controller layer. So, there is just a controller and a view layer (which I called "templates"). So, there is just a few points that really matter:

* There is a "templates" folder, where the Views, CSS files and images are stored. Inside it we have folders for skins, all under the same controller. So, we can have `templates/darkskin/default.tpl.php` and `templates/lightskin/default.tpl.php`, both controlled by `controller/default.php`.
* There is a "controller" folder, where Controllers (duh) are stored. The file name of a Controller must be the same as the View's file name with `.tpl` added to it. E.g.: `template/lightskin/default.tpl.php` and `controller/default.php`. And that's all!
* If there is a `templates/.../file.tpl.php` and `controller/file.php`, it can be accessed by the address: `index.php?module=file`. Voilá! Your page must be shown on the screen! ;)

The "kernel" folder is probably the most untouchable folder: the main core files, classes and functions to keep the system running fine. There is not too much to be done in these files, even if you intend to create plug-ins and extensions for Addictive Community.

## About the Author ##

**Brunno Pleffken Hosti**, programmer well-versed in object-oriented PHP, .NET/C# (ASP.NET, Windows Forms, WPF and Windows Phone development) and ColdFusion. Currently, Front-End developer at Phocus Interact (www.phocus.com.br).

* Twitter: http://twitter.com/brunnopleffken
