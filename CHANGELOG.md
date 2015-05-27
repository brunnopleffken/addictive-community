# Changelog

## v0.2.0 (2015-05-19)

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
