=== No Sub-Category Posts in Loops ===
Contributors: hallsofmontezuma
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=mrtorbert%40gmail%2ecom&item_name=All%20In%20One%20SEO%20Pack&item_number=Support%20Open%20Source&no_shipping=0&no_note=1&tax=0&currency_code=USD&lc=US&bn=PP%2dDonationsBF&charset=UTF%2d8
Tags: loop, categories, cats, posts 
Requires at least: 3.1
Tested up to: 4.4
Stable tag: trunk

Once activated, only posts from the current category are displayed in your loop (no posts from sub cats).

== Description ==

Once activated, only posts from the current category are displayed in your loop (no posts from sub cats).
That's all it does. No options. If you find you need options, let me know and I'll build them into it.

As of 0.4 I remove the filter after the main query is built so that it doesn't interfere with widgets.
If you have a custom query or call wp_query on a category archive template, you'll need to add and remove the filters before and after your query. Below is an example of how to do this if you use wp_query. Again, this is not necessary unless you have modified queries in a template file.

`
add_filter( 'posts_where', 'ft_nscp_mod_where' );
query_posts( array( 'your-custom' => 'args' ) );
remove_filter( 'posts_where', 'ft_nscp_mod_where' );
`

Its important that you remove what you add.

== Changelog ==

* 0.5 - Fixed bug that broke plugin in 3.1. Props @ollybenson (http://gln.to/oshcw)
* 0.4 - Fixed bug introduced with WordPress 3.1. Added inline docs. Removed filter after main query is built.
* 0.3 - Modified directory structure so that plugin may be added and activated from wp-admin
* 0.2 - Forgot to define a global, preventing posts from appearing that should. (thanks to http://redfootwebdesign.com for the heads up!)
* 0.1 - Original release.

== Installation ==

1. Upload the directory "ft-no-subcats-in-loop" to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress

== Frequently Asked Questions ==

= Where can I find help or make suggestions? =

http://fullthrottledevelopment.com/no-sub-category-posts-in-loop

== Upgrade Notice ==
Fixed bug introduced with WordPress 3.1. Added inline docs. Removed filter after main query is built.
