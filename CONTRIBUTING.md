# Contributing to Addictive Community

Great to have you here! We'd love for you to contribute to our source code, so here are a few ways you can help make Addictive Community even better.

- [Issues and Bugs](#issue)
- [Translations](#translations)
- [Submitting a Pull Request](#pullrequest)
- [Coding Guidelines](#coding)

## <a name="issue"></a> Issues and Bugs
If your issue appears to be a bug, and hasn't been reported, [open a new issue](https://github.com/addictivehub/addictive-community/issues/new). Help us to maximize the effort we can spend fixing issues and adding new features by not reporting duplicate issues. Look at [existing bugs](https://github.com/addictivehub/addictive-community/issues) and help us understand if "The bug is reproducible? Is it reproducible in other environments (browsers)? What are the steps to reproduce?".


## <a name="translations"></a> Translations
You can help us translate our project creating new language packs or improving existing ones. It's quite easy:

- Duplicate the folder `en_US` inside the `\languages` folder to get the latest dictionary.
- Rename it to whatever that complies to the [ICU locale standards](http://demo.icu-project.org/icu-bin/locexp) (for example, **de_DE** for German, **es_ES** for Spanish and **es_MX** for Mexican Spanish).
- Translate all PHP files within the folder as accurately as possible, and don't forget to edit the JSON file with your name and personal e-mail. As the ICU code, name your language as **Language (Country/Dialect)** (if there's no dialect variety, leave blank, e.g: just "Japanese").
- Send a Pull Request (see instructions below) and just wait for our approval! :)

In the future you will also be able to create customized themes.


## <a name="pullrequest"></a> Submitting a Pull Request
Before you submit your pull request, search GitHub for an [open or closed Pull Request](https://github.com/addictivehub/addictive-community/pulls) that relates to your submission. You don't want to duplicate effort.
As a contributor, you should **always** work on the `development` branch of your clone (`master` is used only for building releases).
- Follow our [Coding Guidelines](#coding)
- Make your changes in a new Git branch: ```git checkout -b bug-fix-branch development```
- Commit your changes using a descriptive commit message. Be clear and concise, since commits are used to help create changelogs.
- Push your branch to GitHub: ```git push origin bug-fix-branch```
- In GitHub interface, send a pull request to ```addictive-community:development```
- If we suggest changes then:
  - Make and commit the required updates. Try to understand the reasons explained by the team for the denial. Above all, please don't be offended.
  - Rebase your branch and force push to your GitHub repository (this will update your Pull Request): ```git rebase development -i; git push origin bug-fix-branch -f```

And that's all! :)

### After your Pull Request is merged
Then you can safely delete your branch and pull the changes from the main (upstream) repository.
- Delete the remote branch on GitHub either through the GitHub web UI or your local shell as follows: ```git push origin --delete bug-fix-branch```
- Check out the development branch: ```git checkout development -f```
- Delete the local branch: ```git branch -D my-fix-branch```
- Update your master with the latest upstream version.


## <a name="coding"></a> Coding Guidelines
To ensure consistency throughout the source code, keep these rules in mind as you are working:

- Set your editor or project file to 1 tab indenting - not 4 spaces.
- Never use short tags (```<?``` or ```<?=$var?>```), nor ASP-like tags, as it's deprecated; if a file is pure PHP code, omit the PHP closing tag at the end of the file (e.g. controllers).
- Always add one space between operators (+, -, ++, --, ==, ===) and before curly braces.
- Never use single quotes, except in:
  - Arrays indices: ```$array['index']```;
  - HTML tags inside PHP strings: ```$link = "<a href='http://github.com'>GitHub</a>";```.
- K&R style: curly braces appears...
  - ...on new line:
    - Classes;
    - Functions.
  - ...on the same line:
    - Conditionals (if, else, switch);
    - Loops (while, foreach, for).
  - Never omit curly braces on if/else statements, and always use "uncuddled" else.
- Casing:
  - UpperCamelCase:
    - Classes and methods: ```class Test``` and ```public function MyMethod()```.
  - separate_by_underscores:
    - Variables: ```$my_variable = false```.
  - UPPERCASE:
    - Constants: ```define("SOME_VALUE", 1);```
  - lowerCamelCase:
    - JavaScript functions and variables (*.js files only).
- Always add the visibility of a property or a method (```public```, ```protected``` and ```private```) and always explicitly declare public methods/properties.

Short example using some of these rules:

```php
class MyClass
{
    public function MyFunction($some_value)
    {
        if($some_value == true) {
            echo "Value is true";
        }
        else {
            echo "Value is false";
        }
    }
}
```
