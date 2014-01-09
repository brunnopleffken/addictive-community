Addictive Community
===================

UNDER DEVELOPMENT!

Discussion forum software, written in object-oriented PHP and MySQL 5.1+.

## How the Addictive Community's framework works? ##

As a C#/.NET and a PHP programmer, I've tried to get the best of both worlds: the simplicity of ASP.NET WebForms/MVC and the flexibility of PHP. I wrote this framework from scratch and it is based on MVC pattern, but just "based on", because it is not an actual MVC. There is no "model" layer. All database access, queries and data formatting is done inside the controller layer. So, there is just a controller and a view layer (which I called "templates"). So, there is a few points that really matter:

* There is a "templates" folder, where the Views, SASS/CSS and images are stored. Inside the template folder, we have folders for skins, all under the same controller. So, we can have "templates/darkskin/default.tpl" and "templates/lightskin/default.tpl", both managed by "controller/default.php".
* There is a "controller" folder, where Controllers (duh) are stored. The controller's file name must be the same as the View's file name. E.g.: "template/lightskin/default.tpl" and "controller/default.php". And that's all!
* If there is a "templates/.../file.tpl" and "controller/file.php", it can be accessed by the address: "index.php?module=file". Voilá! Your page must be shown on the screen! ;)

The "kernel" folder is probably the most untouchable folder: the main core files, classes and functions to keep the system running fine. There is not too much to be done in these files, even if you intend to create plug-ins and extensions for Addictive Community.

### System requirements ###

* Apache/IIS, Windows or Linux-based server;
* PHP 5.3 or higher;
* MySQL 5.1 or higher;
* Internet Explorer 9 or higher (others, like Chrome or Firefox, might work preety well even with older versions).

## Author ##

Written by Brunno Pleffken Hosti.

* www.facebook.com/brupleffken
* www.twitter.com/brunnopleffken