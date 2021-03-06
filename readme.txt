=== Flattr Comments ===
Contributors: aphex3k
Donate link: http://flattr.com/thing/542/FlattrComments-Wordpress-Plugin
Tags: flattr, comments, social, payment, micropayment, micropayments, donate, donation, cake, javascript, paypal, plugin, page, crowdfunding
Requires at least: 2.7
Tested up to: 2.9.2
Stable tag: trunk

This plugin provides flattr-buttons for comments on your blog if the comment author entered a Flattr-ID.

== Description ==

This plugin provides flattr-buttons for comments on your blog if the comment author entered his Flattr-ID.
There will be an additional input field right below the comment text box. Alternativly you can input a piece of code in your theme to place the field somewhere more nicely.

== Installation ==

1. Download the plugin archive
1. uncompress archive
1. Upload archive to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress

There are few options to the plugin via the dashboard.

This plugin is very likely to not work with other comment plugins.
It has not been tested agains any of them.

== Changelog ==

= 0.9.17.5 =
* fixing the &hellip; char "..."

= 0.9.17.4 =
* modified priority
* revertet MySQL statement

= 0.9.17.1 =
* trying to raise compatibility with other comments-plugins

= 0.9.17 =
* supporting latest available Flattr Javascript API
* optimized MySQL Query strings
* No longer dependant to the official Flattr plugin

= 0.9.11 =
* supporting flattr 0.9.11 plugin

= 0.9.6.4 =
* fixed a bug with faulty "My thing" display of comments (workaround)
* auto-submitted comments will be gracefully cut at 512 characters
* smileys will be removed from auto-submitted comment text
* Comments excerpt must not contain any ' character, or they will break the Flattr JavaScript!
* words between colon characters will be stripped from auto-submitted excerpt, e.g. :smile:,:cry: or :whatever:

= 0.9.6.2 =
* added CSS class `flattrcomments_button_class` to the buttons `<div>` container
* added CSS id `flattrcomments_button_id-%N` to the buttons `<div>` container

= 0.9.6 =
* supporting flattr 0.9.6 plugin
* added option for compact style flattr button in comments

= 0.8 =
* compatible with official plugin code 0.8

= 0.7.3 =
* Hash encryption for comment author names in database
* Empty FlattrID will not overwrite existing one.
* FlattrID is stored in session cookie will automagically be reused

= 0.7.2 =
* You have 2 options to include a custom style input field for the comment authors flattr id.

= 0.7.1 =
* It is highly recommended to upgrade to 0.7.1 from any version

= 0.7 =
* added language support for flattr 0.7 plugin
* this version will not work with flattr plugins before version 0.7!
* flattr buttons are not displayed in the dashboard because the JavaScript is not dashboard safe and breaks it

= 0.6 =
* Depends on Flattr plugin version 0.6

== Frequently Asked Questions ==

No FAQ yet.

== Upgrade Notice ==

See installation instructions.

== Screenshots ==

1. You see the Flattr button for the post and an additional one for the comment. The comment author can fill in his Flattr ID just below the submit button. (Clumsy position, I know, but I haven't found another way yet.)