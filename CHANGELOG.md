# Changelog

## Development Version

#### Added
- Add cover photo to your profile;
- Add emoticons, change text color and add embedded videos to posts;
- Database logging now includes backtrace details for additional debug.

#### Fixed
- Quoted posts not being sanitized;
- Embedded transliterator now parses non-Latin characters (e.g. Greek, Cyrillic, etc.);
- Fixed several issues with banned members;
- Missing Swedish translations (thanks to @halojoy);
- Several others fixes and enhancements.

#### Changed
- Revamped UI components with new and reusable CSS classes, responsive mode is much better;
- Added the suffix [Name]Controller to the name of controller files;
- Controller entry method renamed from "Main" to "Index";
- Default MySQL storage engine changed to InnoDB (requirements are now MySQL 5.6 or above);
- Implement class autoloader for kernel modules;
- Namespaces: added kernel modules to AC\Kernel and controllers to AC\Controllers;
- Member session handling methods moved to AC\Kernel\Session\SessionState;
- Database driver is now a static class;
- Database logging is now disabled by default (enable it setting `private $debug = true` in kernel/Database.php);
- Sass files are now compiled using Node.js (gulp + gulp-sass);
- Form validation now uses the HTML5 API - the UI still behaves the same way.
- Admin CP now uses the same CSS framework than the rest of the application.

#### Deprecated
- IIS: untested and unreliable, so its support is now removed;
- MySQL version older than 5.6 is now unsupported.



## v0.11.1 (2016-12-07)

#### Fixed
- Admin CP shows "Update available" even if running the latest version (#105, thanks to @halojoy)



## v0.11.0 (2016-11-22)

#### Added
- Add/remove themes and templates via Admin CP (thanks to @xQuByte);
- New pages for HTTP errors 404/Not Found and 500/Internal Server Error.

#### Fixed
- Disable registration now works properly showing a warning (thanks to @xQuByte);
- Forwarding Personal Messages not working due to pending Pull Request.

#### Changed
- Master templates are now inside the `/templates/[theme]/layouts` directory.

#### Security
- Improved validations for controllers, IDs and reference values;
- Fixed an XSS vulnerability when ordering the list of members (#98);
- A lot of third-party plugins were update to their latest version.

#### Deprecated
- Support for IE9 and older is now deprecated.



## v0.10.0 (2016-05-19)

#### Added
- Automated update to a newer version;
- New built-in language: Russian (thanks to @zalexstudios);
- Reply and forward private messages (thanks to @johnforte).

#### Fixed
- SQL syntax error when editing a language file via Admin CP (#72);
- Broken URLs when a thread is written using non-latin characters (#74);
- Issue with `json_encode()` and multibyte characters (#74);
- Not loading page template when reporting abuse (#75);
- Save username and e-mail on failed registration process (thanks to @johnforte);
- Several other minor bug fixes and translation misspellings.

#### Security
- Improved data processing in posts to prevent Cross-Site Scripting (XSS).

#### Deprecated
- Removed the possibility of using the Facebook profile photo as avatar due to Graph API limitations to unregistered Web Applications (we do NOT plan to integrate Facebook login with Addictive Community soon).

#### Changed
- Community Dashboard in Admin CP now shows the complete server environment (#66);
- Installer now checks the system requirements before the database deployment (thanks to @xQuByte and @halojoy);
- Changed UI grid from `display: table` to the modern CSS3 flexbox;
- Minor UI and UX enhancements and fixes.



## v0.9.0 (2016-03-15)

#### Added
- Set automatic thread opening/lock date and time.

#### Fixed
- Addictive Community is now PHP 7 compatible (retaining the PHP 5.3+ support);
- Members were able to post in locked threads;
- Unable to delete multiple private messages (#61, thanks to @xQuByte);
- Disable 'Obsolete Thread' feature not working (#62).

#### Changed
- `Error()` controller renamed to `Failure()`: Since PHP 7 class name "Error" is predefined and used internally;
- Kernel module String renamed to Text due to new reserved words in PHP 7;
- Some table fields renamed to follow the *separate_by_underscores_pattern*.



## v0.8.0 (2015-08-03)

#### Added
- OpenSearch support;
- Better initial configuration during installation (#54);
- Full member management via Administration CP;
- New built-in languages: Swedish (thanks to @halojoy) and Brazilian Portuguese.

#### Fixed
- System was unable to send e-mails when SMTP server doesn't require validation (#44, thanks to @halojoy);
- Fixed an error when creating new thread with strict mode enabled in MySQL (#45);
- Several bug fixes and enhancements.

#### Changed
- Settings in `c_config` are now saved as 0/1 instead of "true"/"false" (#44);
- Use `Session::IsAdmin()` to check if logged member is an administrator.



## v0.7.0 (2015-07-16)

#### Added
- Member ranks/promotions given when a member reaches a certain number of posts.

#### Fixed
- Members were unable to log in anonymously (#41);
- Users were unable to install Addictive Community in WAMP stack in certain scenarios;
- Few other bug fixes and enhancements.

#### Security
- Added .php and .js files as forbidden extensions to avoid remote execution on server (users must now compress and send a ZIP file instead);
- Improved numeric-only data validation to avoid SQL injection (#39).

#### Changed
- Check for system requirements comes before database settings (#27).



## v0.6.0 (2015-07-03)

#### Added
- Rooms categories.

#### Fixed
- Member getting SQL error when reporting a post/thread (#26);
- Broken links when viewing reports in Administration CP;
- Bug fixes related to failed MySQL connection under certain scenarios (#16, #26 and #29, thanks to @halojoy);
- SQL error when adding a new reply (#30);
- Several minor bug fixes and general enhancements.



## v0.5.1 (2015-06-19)

#### Fixed
- New database fields for quoting replies not being loaded.



## v0.5.0 (2015-06-19)

#### Added
- Threads flagged as read or unread: it'll check last post date, except your own reply;
- Quote replies of other members;
- Show or hide your e-mail address in profile;
- Admin CP: edit CSS file via Administration panel.

#### Fixed
- Set/unset a reply as best answer is now working properly;
- Minor bug fixes and layout enhancements.

#### Security
- Improved validations when performing moderation actions (lock/unlock, delete, etc).



## v0.4.2 (2015-06-14)

#### Critical
- Fixed missing references of `Http()` class in installer and Administration CP.



## v0.4.1 (2015-06-12)

#### Fixed
- Unexpected T_VARIABLE on Register controller.



## v0.4.0 (2015-06-12)

#### Added
- New diagnostic tool to check for common issues (not allowed if `install/.lock` exists).

#### Fixed
- Chromium-based browsers (like Chrome and Opera 15+) renders Atom feed content as plain text;
- Incorrect post and thread count in statistics right after fresh install;
- Remove unused class instances to improve performance and memory management;
- Minor bug fixes and enhancements.

#### Removed
- Property `private Database::config` is unset right after database connection.

#### Changed
- Lots of refactoring and enhancements in controllers and kernel/core;
- Callbacks `_beforeFilter()` and `_afterFilter()` renamed to `_BeforeAction()` and `_AfterAction()`, respectively;
- Public method `String::PasswordEncrypt()` renamed to `String::Encrypt()`;
- Replaced some explicit SQL queries in controllers by database helpers;
- Methods `Request()`, `File()` and `CurrentUrl()` now belong to `Http` class (e.g. `Http::Request()`);
- Few more validations during community installation.



## v0.3.0 (2015-06-01)

#### Added
- Create polls;
- New Control Panel summary;
- Optional CAPTCHA validation when creating new account;
- Atom 1.0 syndication.

#### Fixed
- `input[type=file]` fields passing without validation, even with `.required` class;
- User with Administrator privileges were unable to edit/delete other users' posts;
- Removed dynamic BODY class that causes letters on screen to blink during page reload;
- Few minor bug fixes.

#### Security
- Fixed a critical exploit that could allow cross-side scripting and HTML injection in posts/messages.

#### Deprecated
- In controllers, `$this->config[]` no longer exists. Use `$this->Core->config[]` instead.

#### Changed
- Changed the way the installer handles with SQL statements;
- `Core::GetGravatar()` is now named `Core::GetAvatar()`.



## v0.2.0 (2015-05-26)

#### Added
- Add room moderators;
- Create Redirect Rooms;
- Warning message when community is updating or offline;
- Moderation options in thread view.

#### Fixed
- Form validation via JavaScript not working when page doesn't have a TinyMCE'd textarea;
- Image path is broken in About window;
- `Core::DateFormat()` ignoring user's setting for timezone;
- Birthdate always showing "January 1st, 2015" if no date is defined;
- Check for "Software Updates" in Admin CP not working properly;
- Several minor bug fixes and enhancements.

#### Changed
- Field `c_config.index` in table is now named `c_config.field` to avoid conflict with MySQL indexes.
