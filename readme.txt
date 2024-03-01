=== Slash Admin ===
Contributors: gsarig
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=4EYE898NTMYKE
Tags: WordPress, admin, administration, dashboard, login, analytics, internet explorer, revisions, permission, role, access, fonts, dns prefetching, prefetching, prerendering, white label, cookie law, eu cookie law, site health, loading, obfuscate
Requires at least: 5.0
Requires PHP: 7.0
Tested up to: 6.4
Stable tag: 3.8.3
License: GPLv2 or later

Dozens of settings aiming at creating a friendlier administration environment for both Administrators and Editors.

== Description ==

Slash Admin gathers some common functions that you probably need in most of your websites. The plugin lets you change various different options in a WordPress website, keeps them active even if you switch your theme and helps you create a friendlier Admin Panel for you and your editors.

If you are lost with the many options, here's a presentation of the plugin's [best features](https://www.gsarigiannidis.gr/slash-admin-best-features/).

= Features =

*Frontend*

* Option to point to a static splash page.
* Option to convert email addresses characters to HTML entities to block spam bots.
* Show EU Cookie Law consent message (check screenshots about available options). Since v.3.0 it also supports WPML for different message per language.
* Add a "Loading" animation which hides itself when the page is fully loaded
* Enqueue your own Google Web Fonts, with option to load it locally for better performance and privacy
* Get rid of the word "Category:" in front of the Archive title (usually needed if your theme uses the_archive_title()).
* Add excerpt support to pages.
* Enable the use of shortcodes in widgets.
* Display a warning for users of old versions of Internet Explorer (IE8 or older). Yes, sadly there are still people who use Internet Explorer 8...

*Administration*

* Insert Google Analytics tracking code (so as you don't have to remember re-entering it in case you switch themes in the future)
* Hide Site Health from everyone except from a selected Admin ([read more](https://www.gsarigiannidis.gr/how-to-hide-wordpress-site-health-from-everyone-but-you/))
* Hide ACF options from everyone except from the selected Admin
* Since WordPress 5.2 there is a built-in feature that detects when a plugin or theme causes a fatal error on your site, and notifies you with this automated email. By default, it will be sent to the admin email. Slash Admin allows you to override it (you can also add multiple recipients if you like). [Read more](https://www.gsarigiannidis.gr/how-to-hide-wordpress-site-health-from-everyone-but-you/)
* Change the address that receives the Plugin and Theme auto-update email notifications
* Make WordPress respect the order of the tags you insert in a post ([read more](https://www.gsarigiannidis.gr/wordpress-post-tags-order))
* Limit the number of revisions that WordPress keeps for each post (keeps the database cleaner)
* Prevent Post Updates and Deletion After a Set Period. Useful if you have many editors or in cases where an editor's account is compromised, adding spam code to the posts (by disallowing editing of older posts you limit the damage)
* Enable Jetpack development mode
* Move Jetpack share and like buttons
* Maintenance mode. If checked, non-Admins will not be able to acess the WordPress backend and they will see a customizable message instead. Useful if you want to perform some maintenance work to your website and you don't want your Editors to add or modify the content before you finish. Admins are not affected and they can always login as usual.

*Login screen*

* Add your custom logo at the WordPress log-in screen
* Make the login screen logo (custom or default) linking to your website's homepage instead of wordpress.org
* After login, redirect users at the homepage instead of their profile page
* Disable the Admin Bar for all users except Administrators. Applies only to the front-end. It's useful if you want your site to be visible only to logged-in users (e.g. during developement phase), but you don't want them to access the dashboard or get confused with the admin bar
* Add your custom CSS to the login screen to completely change its appearance

*Non-admins*

* Hide unnecessary options from the Admin menu for non admins (so editors won't get overwhelmed with options that have no meaning for the current website).
* Disable tags and categories
* Hide specific pages from non admins. For example, you might not want your editors to have access to the static frontpage, the blog page or pages that you use as page templates.
* Allow editors to manage Menus and Widgets and access some other appearance settings previously acessible only to admins (for example, you might want to give your client the option to modify the website's menu, but you would rather avoid making him/her an administrator).
* Hide notices about updating WordPress and other plugins for all users except from Admins (sometimes clients get confused with those notices and think that there is something wrong with the website).

*White label backend*

* Change the "Howdy" message at the top right corner of the admin (both backend and logged-in frontend)
* Change the default footer text at the admin
* Replace the WordPress logo at the top left corner of the admin bar with your own (both backend and logged-in frontend)
* Replace the default Welcome message at the Dashboard with your own
* Add a Dashboard Widget to provide general or commercial information to your clients (for example: your contact info or links to support documentation)
* Add your own custom CSS for the Admin area

*Performance*

* Disable Emojis
* Disable wp-embed script from the frontend or load it conditionally
* DNS prefetching notifies the client that there are assets we'll need later from a specific URL (outside our website's domain) so the browser can resolve the DNS as quickly as possible.
* Link prefetching and prerendering. Link prefetching is a browser mechanism, which utilizes browser idle time to download or prefetch documents that the user might visit in the near future. A web page provides a set of prefetching hints to the browser, and after the browser is finished loading the page, it begins silently prefetching specified documents and stores them in its cache. When the user visits one of the prefetched documents, it can be served up quickly out of the browser's cache. Prerendering downloads and renders the entire page and hides it from the user until it is requested, therefore, it should be used with caution.

*Shortcodes*

* If you manually include email addresses in your posts, you should consider disguising them in order to "fool" e-mail harvesters (check FAQ for details).
* Show a telephone number in a way that it is clickable. When clicked, if you are on a mobile device it opens the phone's dialer and if you are on a desktop computer it prompts to make a call via a related program (e.g. Skype).
* If you develop your site on localhost or on a temporary URL, you might want to avoid absolute URLs inside posts and pages. That way you don't need to update your links after migrating to your actual domain (check FAQ for details). 

*Development functions*

* Using <code>slash_dump()</code> instead of <code>var_dump()</code> will wrap the output in <code><pre></pre></code> tags, for better readability. <code>slash_admin_dump()</code> does the same thing, only this time the output is only visible to admins (can be handy if you want to debug a live site).
* Show warnings if the site is on air and debug mode is still on and if the site is on localhost and debug mode is off. Also, show warning if the website is on air and you have chosen to hide it from Search Engines.

*Notifications*

Slash Admin displays the following notifications:

* A list with the users who logged in during the past 15 minutes (except from you, obviously)
* A warning when debug mode is enabled (you should enable it when developing, but disable it when the site goes live)
* A warning when your site is hidden from search engines

== Installation ==

1. Upload the Slash Admin plugin to your WordPress plugins directory and activate it. 
2. Go to Tools / Slash Admin and set your preferred options.

== Frequently Asked Questions ==

= Which shortcodes are available? =

You can enable the following shortcodes:

* [slash_mail address="yourmail@mail.com"] - disguises the email address to make it unreadable from mail harvesters and displays it as plain text.
* [slash_mailto address="yourmail@mail.com"] - does the exact same thing, but it also transforms the text to a "mailto" link as well. 
* [slash_mail] and [slash_mailto] (same as above but with no parameters given) automatically retrieve the email address of the author of a post/page. All the above shortcodes use the WordPress' antispambot() function.
* [slash_phone number="PHONE_NUMBER" prefix="COUNTRY_PREFIX" text="ANCHOR_TEXT"] - Shows a telephone number in a way that it is clickable. When clicked, if you are on a mobile device it opens the phone's dialer and if you are on a desktop computer it prompts to make a call via a related program (e.g. Skype). The "number" parameter is the only one that is absolutelly required. Here are some usage examples: [slash_phone number="999999"] would output a link "999999" which would make a phone call to 999999. [slash_phone number="999999" prefix="+30"] would output a link "999999" which would make a phone call to +30999999. [slash_phone number="999999" prefix="+30" text="Call us"] would output a link "Call us" which would make a phone call to +30999999.
* [slash_home] - retrieves the home URI for the current site. It is the equivalent of echoing the home_url(). 
* [slash_theme] - retrieves the template directory URI for the current theme. It is the equivalent of echoing the get_template_directory_uri().
* [slash_child] - retrieves the stylesheet directory URI for the current theme/child theme. It is the equivalent of echoing the get_stylesheet_directory_uri().

= Which special functions are available? =

Slash Admin includes the following functions which you can use in your code:

* <code>slash_dump()</code> - You can use it instead of <code>var_dump()</code> to wrap the output in <code><pre></pre></code> tags, for better readability. 

= My theme also supports some of this plugin's features. Which one should I choose? =

It is up to you to decide whether you will use your theme's options or those provided by this plugin. It is recommended, though, that you keep those settings separated from your theme and the reason is simple: If at some point you decide to switch themes, those options will be lost and you have to remember to re-enter them. Keeping them in a plugin maintains the options between themes. 

= Is it wise to hide update notices from my users? =

Generally speaking, no. WordPress' default behaviour is probably the best, that's why the specific option is disabled by default. In some cases, though, users might get confused with those notifications or think that something is wrong with the website. In cases like that, you might want to keep the update notifications visible only for those who can apply them - namely the administrators. Keep in mind that, technically, selecting this option won't remove the notifications for the non-admins - it will just hide them with CSS.

= What does hiding options for non-admins means? =

Sometimes you only use certain features of WordPress. For example, your website might have comments disabled or not using the Links feature. Also, for better usability you might want to show your users only the options that concern them. Hiding those options won't remove them. You, as an administrator, will always see the full list of all the available options. An editor, though, won't see the hidden options, which helps him focus to only those that concern him.   

= How does allowing access to appearance settings work? =
You can allow editors access to one or more of the following sub-sections of the "Appearance" section:

* Customize
* Widgets
* Menus
* Background

Technically, by selecting even one of the above options you give editors access to the Appearance section. To prevent them from accessing unwanted subsections (e.g. you want them to see the Menus but not the Widgets) the plugin hides their links via CSS/JavaScript from both the backend and the frontend. If an editor knew the link for the Widgets subsection he/she could access it. By default the plugin respects the WordPress' default behavior, keeping those options disabled (users have no access at all to the Appearance section). 

= Old browser warning behaves strangely with W3TC plugin =

This is a known issue. When Page Caching is activated in the W3 Total Cache plugin, the old browser warning becomes unpredictable and it may appear not only in Internet Explorer 8 but in newer IE versions as well as in Chrome. To deal with the problem you need to disable either the old IE warning or the W3TC Page Cache option.

== Screenshots ==

1. A dashboard customized via the options offered at the "White label" section
2. The EU Cookie Law consent box in its four different variations
3. Frontend section
4. Administration section
5. Login screen section
6. Non-admins section
7. White label section
8. Performance section
9. Shortcodes section
10. The Old Browser warning message 

== Changelog ==
= 3.8.3 =
* Fixes a regression caused by 3.8.2

= 3.8.2 =
* Security update

= 3.8.1 =
* Fixes a fatal error with WordPress 6.1

= 3.8 =
* Added option to preload locally downloaded web fonts

= 3.7.4 =
* Updated web font loader

= 3.7.3 =
* Replaced intval() with type casts

= 3.7.2 =
* Fixed an bug with "Show extra options to Editors" option

= 3.7.1 =
* Updated plugin minimum requirements

= 3.7 =
* Internet Explorer warning refactoring improvements
* Loader refactoring and performance improvements
* EU Cookie popup refactoring and performance improvements
* Added option to inject scripts on body and footer, along with the already existing option for the header

= 3.6 =
* NEW FEATURE: Make WordPress respect the order of the tags you insert in a post
* IMPROVEMENT: Change the address that receives the Plugin and Theme auto-update email notifications

= 3.5.1 =
* Updated webfont-loader script, which fixes bug [#7](https://github.com/WPTT/webfont-loader/issues/7)

= 3.5 =
* Added option to locally load External fonts, using [webfont-loader](https://github.com/WPTT/webfont-loader).
* Fixed a bug with displaying header scripts

= 3.4.1 =
* Restrict "techie" users dropdown options to admins only.

= 3.4 =
* NEW FEATURE: Added an option to assign a specific admin user as "Techie". Doing so, will hide some functionalities from every other admin except from the Techie. Those functionalities include Site Health and ACF settings.
* NEW FEATURE: Added option to conditionally load scripts based on whether the eu_cookie is stored.
* Fixed a warning on the login page when a custom image is added.
* Removed the deprecated "Add favicon" option.
* Updated old IE warning to appear on all versions of Internet Explorer.
* Removed the now unneeded option to Unload Open Sans as WordPress ditched it a few versions ago.

= 3.3.5 =
* NEW FEATURE: Added option to override WordPress' recovery mode email.

= 3.3.4 =
* NEW FEATURE: Added option to filter the_content and automatically obfuscate email addresses using the antispambot() function. Just set it and forget it (It can be found under Frontent / Miscellaneous section). No need to use the [slash_mailto] and [slash_mail] shortcodes on your main content anymore.
* When "Remove `Category` from archives" is selected, "Tag:" prefix will be removed too from tag archives.
* Fixed a minor bug where the "cookie notice" immediately showed up after the plugin was activated, even though it had not been manually set to "on".

= 3.3.3 =
* NEW FEATURE: Added option to point to a static splash page.
* Fixed an issue where login_headertitle is deprecated since version 5.2.0
* Fixed a warning where getimagesize() failed to open stream

= 3.3.2 =
* Better check on whether the site is under development or not.
* Minor improvement on cookielaw consent check script.
* Fixed "getimagesize failed to open stream: HTTP request failed" notice on login page when a custom logo is uploaded.
* Allow iframes (e.g. youtube embeds) on WP Admin (check the related discussion: https://wordpress.org/support/topic/any-plans-or-ideas-welcome/)
* Fixed incompatibility with PayPal for WooCommerce plugin
* If you want to bypass Gravity Forms permission options and use your own, you can create a function slash_bypass_gravityforms_permissions function on your theme.

= 3.3.1 =
Bug fix

= 3.3 =
* NEW FEATURE: Added option to remove Gravity Forms' "Add Form" button from all WYSIWYG editors
* IMPROVEMENT: Include shop_manager role to users with access to Gravity Forms entries.
* IMPROVEMENT: Option to change the text "if the spinner keeps loading forever…" text with custom wording.
* FIX: EU Cookie Law consent message: get the proper data depending on whether the "Get message from page" option is selected or not.
* FIX: Maintenance mode bug.
* UPDATE: Show old Internet Explorer warning to IE9 and below (up until now it was from IE8 and below).

= 3.2 =
* NEW FEATURE: Allow editors to view Gravity Forms entries

= 3.1.1 =
* Properly remove jetpack using admin_menu action instead of admin_init, to prevent a PHP warning

= 3.1 =
* NEW FEATURE: Enable Jetpack development mode
* NEW FEATURE: Move Jetpack share and like buttons
* IMPROVEMENT: Show extra options to editors now also works for WooCommerce Shop Managers
* IMPROVEMENT: If page loader takes too long, a message appears informing the user that he/she can click to close it

= 3.0 =
* NEW FEATURE: Show a list of users who logged in recently (during the past 15 minutes)
* NEW FEATURE: EU Cookie Law consent message can retrieve data from a page. Particularly useful if you use WPML and you want different wording per language.
* NEW FEATURE: Added option to disable tags and categories
* NEW FEATURE: Added option to disable Emojis
* NEW FEATURE: Added option to disable wp-embed script or load it conditionally
* NEW FEATURE: Added function <code>slash_admin_dump()</code> which is similar to <code>slash_dump()</code> but this time it will only show the output if you are an Admin.

= 2.9.3 =
* Get rid of the taxonomy name in front of the taxonomy archive titles.

= 2.9.2 =
* Replaced [] with array() to prevent breaking the site on servers with old versions of PHP (less than 5.4).

= 2.9.1 =
* Fixed a PHP warning when debug mode is on and no pages are yet selected or deselected for hiding. 

= 2.9 =
* NEW FEATURE: Hide specific pages from non admins. For example, you might not want your editors to have access to the static frontpage, the blog page or pages that you use as page templates.
* NEW SHORTCODE: Show a telephone number in a way that it is clickable. When clicked, if you are on a mobile device it opens the phone's dialer and if you are on a desktop computer it prompts to make a call via a related program (e.g. Skype).
* NEW FEATURE: Get rid of the word "Category:" in front of the Archive title (usually needed if your theme uses the_archive_title()).
* NEW FEATURE: Add excerpt support to pages.
* NEW FEATURE: Enable the use of shortcodes in widgets.

= 2.8.3 =
* Added proper text domain support for internationalization

= 2.8.2 =
* FIX: PHP7 support (fixed a bug which crashed the admin on PHP 7)

= 2.8.1 =
* NEW FEATURE: Added <code>slash_dump()</code> function which you can use instead of <code>var_dump()</code> to wrap the output in <code><pre></pre></code> tags, for better readability. 
* Updated the text domain to exactly match the WordPress plugin slug, in order to prepare for WordPress Plugin Language Packs.
* Small bugfixes for WordPress 4.4 compatibility


= 2.8 =
* NEW FEATURE: Option to display a "Loading" animation which hides itself when the page is fully loaded
* NEW FEATURE: Warnings if the site is on air and debug mode is on or if is on localhost and off. Also, warning if the site is on air and hidden from search engines.
* Other small bugfixes

= 2.7 =
* NEW FEATURE: Option to show an EU Cookie Law consent message
* Updated translations

= 2.6 =
* NEW FEATURE: Change the "Howdy" message at the top right corner of the admin (both backend and logged-in frontend)
* NEW FEATURE: Change the default footer text at the admin
* NEW FEATURE: Replace the WordPress logo at the top left corner of the admin bar with your own (both backend and logged-in frontend)
* NEW FEATURE: Replace the default Welcome message at the Dashboard with your own
* NEW FEATURE: Add a Dashboard Widget to provide general or commercial information to your clients (for example: your contact info or links to support documentation)
* NEW FEATURE: Add your own custom CSS for the Admin area
* NEW FEATURE: Now the plugin's options page display a discreet warning when debug mode is enabled, to remind you that you should disable it when you go on air
* NEW FEATURE: Add your custom CSS to the login screen to completely change its appearance
* Improvements to the media uploader

= 2.5.3 =
* Fixed a small bug.

= 2.5.2 =
* Fixed a small bug.

= 2.5.1 =
* Removed the deprecated since WordPress 4.3 function wp_htmledit_pre().

= 2.5 =
* NEW FEATURE: DNS prefetching.
* NEW FEATURE: Link prefetching and prerendering.
* ADDED option to hide any element in the admin via its CSS id or class.
* The "Upload your favicon" option now suggests to the user that he/she should prefer WordPress' native "Site Icon" option instead.

= 2.4 =
* NEW FEATURE: MAINTENANCE MODE. If checked, non-Admins will not be able to acess the WordPress backend and they will see a customizable message instead. Useful if you want to perform some maintenance work to your website and you don't want your Editors to add or modify content before you finish. Admins are not affected and they can always login as usual.

= 2.3.3 =
* Added default values to the shortcodes that protect emails from harvesters. Now you can just use [slash_mail] (for plain text) or [slash_mailto] (for a mailto link) and it will automatically display the author's email address.

= 2.3.2 =
* Fixed a PHP notice about an undefined property on login screen, when debug mode was enabled and login redirect option was checked.
* Updated FAQ and Documentation.

= 2.3 =
* NEW FEATURE: Added shortcodes for protecting email addresses from harvesters and getting the website URL dynamically.
* ADDED option to hide Jetpack menu from Editors.

= 2.2.3 =
* Fixed a bug which prevented redirecting to homepage after login from working properly.
* Updated greek translation.

= 2.2.2 =
* Fixed some issues with how the logo appeared at the login screen to some users. Now it tries to adjust the image dimensions in a smarter way.

= 2.2 =
* NEW FEATURE: Added option to unload the default WordPress Open Sans font and enqueue your own Google Web Fonts
* Complete redesign
* Minor performance improvements

= 2.1 =
* Added option for allowing editors to manage Menus and Widgets and access some other Appearance settings previously acessible only to admins
* Some minor changes in the appearance of the plugin's options page
* Updated documentation
* Updated Greek translation

= 2.0 =
* Added option to limit the number of revisions that WordPress keeps for each post

= 1.9 =
* Added option to block post updates and deletion if the post is older than a specific period of time. Applies only to editors (admins can still edit the post as usual)

= 1.8.5 =
* Added option to hide additional fields from non admins

= 1.8.1 =
* Added Greek translation

= 1.8 =
* Added WordPress 3.8 compatibility
* New design
* Improved the appearence of the image at the login page

= 1.7 =
* Added option to show a warning for users of old versions of Internet Explorer

= 1.6 =
* Added option to disable Admin Bar for non admins (only in the front-end)
* Added option to hide update notices for non admins

= 1.5 =
* Option to redirect users to the homepage after login (instead of their profile page)

= 1.0.1 =
* Some tidying up to the options page
* Added documentation

= 1.0.0 =
* First release!

== Upgrade Notice ==

= 3.7 =
A big refactoring of four features: Internet Explorer warning message, site loading animation, eu cookie message and Script includes have been rewritten for better performance and usability. Also, a refactoring of the rest of the code has began, without affecting the functionality, though.

= 3.4 =
This is a relatively big update, with the most important addition being the option to assign a specific admin user as "Techie". Doing so, will hide some functionalities from every other admin except from the Techie. Those functionalities include Site Health and ACF settings. Also, deprecated "Add favicon" option has been removed, and the same happened to the option to Unload Open Sans, as WordPress ditched it a few versions ago. Check the changelog for the full list of additions and changes.

= 2.6 =
This is a major update with lots of new options. A whole new section named "White label" has been added, allowing you to customize the WordPress Dashboard, add your own Welcome message and dashboard widget, change the WordPress logo and Admin texts and more (see the changelog for details).

= 1.8.5 =
* Initial submittion to the WordPress.org repository