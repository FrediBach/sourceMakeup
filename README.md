# sourceMakeup

sourceMakeup is a source code viewer written in PHP on the backend, and jQuery and jKit
on the frontend. sourceMakeup was heavely inspired by the first versions of 
[Docco](http://jashkenas.github.com/docco/), with some additional features that enhance
the frontend visually and feature wise. It was mainly developed as a source code viewer
for the jQuery based UI toolkit [jKit](http://jquery-jkit.com/). It's main use is to
create beautiful source documentation for JavaScript libraries and plugins, but it can
be used to document small PHP code and CSS markup as well, however, with some limitations. 

/Comments for sourceMakeup should be written in Markdown. This way sourceMakeup can correctly
and beautifully format the code and add a navigation menu linked to the different parts of your code.

- Version: `0.5`
- Release date: `14. 3. 2013`
- [Documentation & Demos](http://jquery-jkit.com/sourcemakeup/)
- [Download](https://github.com/FrediBach/sourceMakeup/archive/master.zip)

## Copyright

- (c) 2013 by *Fredi Bach*
- [Home](http://fredibach.ch/)

## License

sourceMakeup is open source and MIT licensed. For more informations read the **license.txt** file 

## Settings

- **$dir** contains the path to the documented files
- **$file** is the main file
- **$files** contains all the files, leave it empty if you don't want to restrict the list to certain files
- **$extensions** contains a comma separated list of allowed file extensions
- **$filter** filters out any files that contain a certain string
- **$dev** mode ignores the cache file and always generates a new one
