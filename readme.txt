=== WooCommerce Maintenance Mode ===
Contributors: themattroyal
Donate link: http://mattroyal.co.za/donate
Tags: woocommerce, maintenance, shop, store, notice, alert, redirect, lightbox
Requires at least: 3.8
Tested up to: 4.2
Stable tag: 1.4

Store messages or redirects on all WooCommerce pages for non admins. Other pages/posts unaffected. Ideal for store maintenance or store wide notices.

== Description ==

Easily choose between displaying a message or specifying an existing page or external URL to redirect users. This will be applied to all users that are not-logged in and are without shop editing capabilities.This will only affect WooCommerce store pages and no other pages or posts on your website. 

Easily select from your websites existing pages to pull in content for your messages or as a destination for your redirects. Alternatively use the Maintenance WYSIWYG field to create your own custom messages (just like on a page or post). 

Set an end date that will automatically resume your store to normal view and enjoy the flexibility of being able to adjust the frequency at which to display your store messages or redirects to your users. 

Requires WordPress 3.0 + and WooCommerce 2.0 +.

Get in touch with me at:
http://mattroyal.co.za/

**Features**

* Set end date
* Control frequency of messages/redirects 
* Control how message is displayed
* Countdown Timer

== Usage ==

* Go to 'Settings' > WooMaintenance
* Switch 'Activation' mode ON
* Select your end date
* Specify your message intervals
* Select your display option or redirect
* Select your page for redirect or message or
* Add URL for external redirect or use editor to create your message

**Please Note - NB!!**

* If the chosen 'Display Option' is set to 'Redirect', the 'External Redirect URL' field must be empty to redirect to an 'Existing Page' option as seen below it. If you specify a URL in the field it will ignore the 'Existing Page' option.
* If the chosen 'Display Option' is set to 'Lightbox' or 'On Page', the Store Message WYSIWYG textarea must be empty to use content from the 'Existing Page' option above. If Store Message WYSIWYG textarea has any content whatsoever, it will ignore the existing page option.


== Installation ==

Installing "WooCommerce Maintenance Mode" can be done either by searching for "WooCommerce Maintenance Mode" via the "Plugins > Add New" screen in your WordPress dashboard, or by using the following steps:

1. Download the plugin via WordPress.org
2. Upload the ZIP file through the 'Plugins > Add New > Upload' screen in your WordPress dashboard
3. Activate the plugin through the 'Plugins' menu in WordPress

== Frequently Asked Questions ==

= The plugin isn't working?! =

When previewing your lightbox popup, in-page message or testing your redirect you MUST remember to logout and then view it or use a secondary browser that is not logged into your Dashboard to view it. One of the conditions for the plugin to display or redirect on WooCommerce pages is that the user is NOT logged in, but its easy to forget this when working or testing things on your site. (I have done it plenty.)

Also remember to either use the 0 value for cookie settings within your plugin whilst testing or use the delete all cookies button and then test again. Often it is not working because the cookie has already been set previously and you have just forgotten about it :)

= I have a question, I need help =

You contact me via [my website](http://mattroyal.co.za).


== Screenshots ==

1. Admin screen located under 'Settings' > WooMaintenance
2. A Sample Lightbox Message
3. A Sample On Page Message


== Changelog ==

= 1.4 =
* 2015-06-30
* Fix - Update PrettyPhoto to 3.1.6 to resolve XSS security issue <a href="https://github.com/scaron/prettyphoto/issues/149">https://github.com/scaron/prettyphoto/issues/149</a>
* Tweak - Removed conditional check to only display the plugins message/lightbox/redirect when admin is NOT logged in. This caused to much confusion for users.
* Tweak - Replaced WordPress the_content filter with proper WooCommerce hooks
* Feature - Added a link in the admin notice for clearing cache when using W3 Total Cache plugin.

= 1.3 =
* 2014-07-13
* Added Countdown option to display a countdown timer until maintenance/message end date
* Added admin button to remove all cookies set by plugin

= 1.2 =
* 2014-06-21
* Cookies set with php, now set site wide
* Admin Interface, frequency input field set default value to 0

= 1.1 =
* 2014-06-11
* Replaced Fancybox with prettyPhoto 

= 1.0 =
* 2012-06-09
* Initial release

== Upgrade Notice ==

= 1.4 =
* 2015-06-30
* Fix - Update PrettyPhoto to 3.1.6 to resolve XSS security issue <a href="https://github.com/scaron/prettyphoto/issues/149">https://github.com/scaron/prettyphoto/issues/149</a>
* Tweak - Removed conditional check to only display the plugins message/lightbox/redirect when admin is NOT logged in. This caused to much confusion for users.
* Tweak - Replaced WordPress the_content filter with proper WooCommerce hooks
* Feature - Added a link in the admin notice for clearing cache when using W3 Total Cache plugin.

= 1.3 =
* 2014-07-13
* Added Countdown option to display countdown timer until maintenance/message end date
* Added admin button to remove all cookies set by plugin

= 1.2 =
* 2014-06-21
* Minor enhancements to admin interface & setting of cookies

= 1.1 =
* 2014-06-11
* Replaced Fancybox with prettyPhoto 

= 1.0 =
* 2012-06-09
* Initial release