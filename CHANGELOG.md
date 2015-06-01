# Changelog

## v0.3.0 (XXXX-XX-XX)

#### Added
- Create polls;
- New Control Panel summary;
- Optional CAPTCHA validation when creating new account;
- Atom 1.0 syndication.

#### Fixed
- `input[type=file]` fields passing without validation, even with `.required` class;
- Fixed a critical exploit that could allow cross-side scripting and HTML injection in posts/messages.
- User with Administrator privileges were unable to edit/delete other users' posts;
- Few minor bug fixes.

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
