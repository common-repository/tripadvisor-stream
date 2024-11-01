=== Tripadvisor Stream ===
Author: Pasquale Mangialavori
Plugin URI: http://pasqualemangialavori.netsons.org/tripadvisor-stream-wordpress-plugin
Tags: tripadvisor, hotels, holidays, vacations, restaurants
Requires at least: 3.0
Tested up to: 3.8.3
Stable tag: 0.1
Version: 0.1.1
License: GPL2 
Contributors:Pasquale Mangialavori

== Description ==

Finally you can retrived tripadvisor reviews and show it in your site with options
(recent or best, limit till 10 reviews) 
with a simply shortcode [tripadvisorsc] to put in your widget text or directly in in posts and pages. 
Modifing the css you can customize the style of the plugin.

== Installation ==

Manual install: Unzip the file into your WordPress plugin-directory. Activate the plugin in wp-admin.

Install through WordPress admin: 
* Go to Plugins > Add New. Search for "TripAdvisor Stream". 
* Locate the plugin in the search results. 
* Click "Install now". 
* Click "Activate".

== Settings ==

Settings -> Tripadvisor Stream

== Frequently Asked Questions ==


== How do I use this plugin? ==

You can use this plugin simply call the shortcode and defining the following options in the settings->tripadvisor Stream or definig the options directly in the shortcode


==  Using shortcode attributes ==

* name = 'Restaurant hotel name'
* url = 'Restaurant_Review-g3334523-d3568537-Reviews-Lo_Stuzzichino-Guamo_Province_of_Lucca_Tuscany.html'
* id = 3568537 //you can find it in the url
* minrate = 2 //the minimu stars for the reviews
* limit = 5 //show only 5 reviews
* lang = 'it' //show language it or en
* sortby = 'recent' // show 'recent'or 'best'


== Screenshots ==


== Bug knew ==


== Changelog ==
* added 'sessionStorage' for the reviews so you don't have to reload from tripadvisor everytime if you have the shortcode in more pages

