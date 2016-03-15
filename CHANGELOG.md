# Changelog

## v0.9.0 (2016-03-15)

*Yes, it's finally here!*

#### Added
- Set automatic thread opening/lock date and time.

#### Fixes
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

#### Fixes
- System was unable to send e-mails when SMTP server doesn't require validation (#44, thanks to @halojoy);
- Fixed an error when creating new thread with strict mode enabled in MySQL (#45);
- Several bug fixes and enhancements.

#### Changed
- Settings in `c_config` are now saved as 0/1 instead of "true"/"false" (#44);
- Use `Session::IsAdmin()` to check if logged member is an administrator.



## v0.7.0 (2015-07-16)

#### Added
- Member ranks/promotions given when a member reaches a certain number of posts.

#### Fixes
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
