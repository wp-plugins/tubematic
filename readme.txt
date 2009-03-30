=== TubeMatic ===
Contributors: redazionegriido
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=QRLJTAUPEC98U&lc=IT&item_name=Griido%2eit%20Plugin%20Tubematic&currency_code=EUR&bn=PP%2dDonationsBF%3abtn_donateCC_LG%2egif%3aNonHosted
Tags: RSS, feed, video, YouTube, TubeMatic, Google, API, gdata 
Requires at least: 2.5
Tested up to: 2.7.1
Stable tag: trunk

This plugin is made for easily embedding videos into a blog post or page.

== Description ==

TubeMatic is a WordPress plugin that allows you to easily insert Youtube videos into your post or page. The plugin is designed to be small and fast.
What you need is to specify keywords and/or a channel name. The plugin gets the RSS feed available form YouTube, parses it and makes a video gallery with thumbnails, statistics and links to videos and yt users.
You don't need to update videos on your posts or pages, TubeMatic automatically makes that for you.
You also do not need a YouTube API developers ID.
Required PHP 5 or greater.

== Installation ==

###Updgrading From A Previous Version###

To upgrade from a previous version of this plugin, delete the entire folder and files from the previous version of the plugin and then follow the installation instructions below.

###Installing The Plugin###

Extract all files from the ZIP file, making sure to keep the file structure intact, and then upload it to `/wp-content/plugins/`.

This should result in the following file structure:

`- wp-content
    - plugins
        - tubematic
            | readme.txt
            | config.php
            | tubematic.php`            

Then just visit your admin area and activate the plugin.

**See Also:** ["Installing Plugins" article on the WP Codex](http://codex.wordpress.org/Managing_Plugins#Installing_Plugins)

== Frequently Asked Questions ==

= How do I use TubeMatic? =

First of all, you need to insert the [tubematic] token into any page or post.  

*Use apostrophes ('') to enclose each parameter value.*
 
The paramaters are as follows:

**type** - this parameter can have one of this value: *uploads* or *category* or *searchfeed*
Please use:
*uploads* to get the latest videos from a channel or only the videos that match your keywords
*category* to search videos in a category
*freesearch* to search videos starting from keywords
There isn't a default value.

**query** - this parameter is the name of yt channel (required if use type='category' or type='uploads') 
There isn't a default value.

**limit** - the maximum number of items in the output grid
The default value is '6'.

**orderby** - this parameter specifies the value that will be used to sort videos in the search result set
Valid values for this parameter are *updated* or *rating* or *relevance* or *published* or *viewCount*.
The default value for this parameter is *viewCount*.

**spanrow** - this is the maximum number of columns of the gallery (the gallery is a table with rows and columns)
The default value for this parameter is '3'.

**keys** - this parameter specifies a search query term. 
YouTube will search all video metadata for videos matching the term. 
Video metadata includes titles, keywords, descriptions, authors' usernames, and categories.
To search for an exact phrase, enclose the phrase in quotation marks.
There isn't a default value.

**lr** - this parameter restricts the search to videos that have a title, description or keywords in a specific language. 
Valid values for the lr parameter are ISO 639-1 two-letter language codes (http://www.loc.gov/standards/iso639-2/php/code_list.php).  
You can also use the values zh-Hans for simplified Chinese and zh-Hant for traditional Chinese.
There isn't a default value.

**showtitle** - *y* or *n* (show video title?)
The default value for this parameter is 'y'.

**showdate** - *y* or *n* (show date added to YouTube?)
The default value for this parameter is 'y'.

**showuser** - *y* or *n* (show user byline?)
The default value for this parameter is 'y'.

**showcount** - *y* or *n* (show views?)
The default value for this parameter is 'y'.

Here's some samples:

*[tubematic type='uploads' query='seostrategy' limit='3' spanrow='2' showuser='n']*

*[tubematic type='category' query='seo strategy' limit='3' showuser='n' orderby='published']*

*[tubematic type='searchfeed' keys='google maps' limit='5']*

= Plug-in Customization =

Edit the config.php script file to customize the plugin (i.e. translate labels, change date format).

= I love your plugin! Can I donate to you? =

Sure! I do this in my free time and I appreciate all donations that I get. It makes me want to continue to update this plugin. You can find more details on [my donate page](http://www.griido.it/donazione/).

**Also appreciated** a valuable backlink (thematic and PR>=3) to our Griido.it magazine. To share details, please email us to redazione@griido.it.

== ChangeLog ==

**Version 1.0.0**

* Initial release.

[Plugin Homepage](http://www.griido.it/tubematic-plugin-wordpress/ "Plugin Homepage")