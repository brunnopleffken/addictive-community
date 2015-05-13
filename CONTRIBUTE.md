# Contributing to Addictive Community

Great to have you here! We'd love for you to contribute to our source code, so here are a few ways you can help make Addictive Community even better.

- [Issues and Bugs](#issue)
- [Translations](#translations)
- [Submitting a Pull Request](#pullrequest)
- [Coding Guidelines](#coding)

## <a name="issue"></a> Issues and Bugs
If your issue appears to be a bug, and hasn't been reported, [open a new issue](https://github.com/brunnopleffken/addictive-community/issues/new). Help us to maximize the effort we can spend fixing issues and adding new features by not reporting duplicate issues. Look at [existing bugs](https://github.com/brunnopleffken/addictive-community/issues) and help us understand if "The bug is reproducible? Is it reproducible in other environments (browsers)? What are the steps to reproduce?".


## <a name="translations"></a> Translations
You can help us translate our project creating new language packs or improving existing ones here: https://github.com/brunnopleffken/addictive-community-languages.

In the future you will also be able to create customized themes.


## <a name="pullrequest"></a> Submitting a Pull Request
Before you submit your pull request, search GitHub for an [open or closed Pull Request](https://github.com/brunnopleffken/addictive-community/pulls) that relates to your submission. You don't want to duplicate effort.
- Make your changes in a new Git branch: ```git checkout -b bug-fix-branch master```
- Follow our [Coding Guidelines](#coding)
- Commit your changes using a descriptive commit message. Be clear and concise, since commits are used to help create change logs.
- Push your branch to GitHub: ```git push origin bug-fix-branch```
- In GitHub, send a pull request to ```addictive-community:master```
- If we suggest changes then:
  - Make the required updates.
  - Rebase your branch and force push to your GitHub repository (this will update your Pull Request): ```git rebase master -i; git push origin bug-fix-branch -f```

And that's all! :)

### After your Pull Request is merged
Then you can safely delete your branch and pull the changes from the main (upstream) repository.
- Delete the remote branch on GitHub either through the GitHub web UI or your local shell as follows: ```git push origin --delete bug-fix-branch```
- Check out the master branch: ```git checkout master -f```
- Delete the local branch: ```git branch -D my-fix-branch```
- Update your master with the latest upstream version: ```git pull --ff upstream master```


## <a name="coding"></a> Coding Guidelines
To ensure consistency throughout the source code, keep these rules in mind as you are working:

- Never use short tags (```<?``` or ```<?=$var?>```), nor ASP-like tags; if a file is pure PHP code, omit the PHP closing tag at the end of the file (e.g. controllers).
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