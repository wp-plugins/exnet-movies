=== ExNet Movies ===
Contributors: TheAnuvhuti, extraperson
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=7V7GRJBM8J4KJ&lc=US&currency_code=USD&bn=PP%2dDonationsBF%3abtn_donateCC_LG%2egif%3aNonHosted
Tags: films, imdb, movie links, wordpress watch movies, wp tv shows
Requires at least: 3.0.1
Tested up to: 3.9.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Run Your own Movies & TV shows and Don't leave WordPress,

== Description ==

If you like to watch movies and TV shows, then you know that a lot of web site available out there, where you can watch movies. But all those sites built by using either any PHP script or just a static page. From now you also can make such a web site without having any coding knowledge by using Wordpress and Our "ExNet Movies" Plugin.

= Features: =

* AUTOMATICALLY grab movie info and create a post in a click.
* Uses WordPress custom post types and taxonomies.
* Create unlimited kinds taxonomies, like: Movies, Tv Shows, Podcasts, Actions, Drama etc.
* jQuery Pagination, jQuery Search and jQuery Rating systems module included.
* Show all genres in your sidebars easily.
* Tabs for the homepage: Recent, Top rated, Most commented, Most links
* You can create external link redirects or embed a player in a nice modal window which will never leave your site.
* Create as many tabs you want for your movies or tv shows links like: Series #1, Series #2, etc.
* Configuration options for how many items per page, row and homepage.
* Show all genres in your sidebars easily.
* Use within any wordpress theme.

Besides above features, many more features has been included with this plugin which you can discover only by installing yourself and playing with the powers of this plugin.


== Installation ==

Installing the plugin is very easy just like any other wordpress plugin.

* Easy Way: Your hosting settings must allow files of at least 300kb (plugin size) to be uploaded via form. Connect to wordpress admin panel and point to 'Plugins->Add New->Upload' and upload the zip file directly. Then go to 'wp-admin->plugins->and activate ExNet Movies Plugin'.

* Another Way: No Upload size limit aplicable. Connect to your hosting via FTP and upload the extracted zip file folder of the plugin into 'wp-content/plugins/'. Then go to 'wp-admin->plugins->and activate ExNet Movies Plugin.


== Frequently Asked Questions ==

= Permalinks Setting: Do it after complete plugin installation =

After installation process completed, Our plugin will set your permalink structure to /%category%/%postname%/.

* CRUCIAL: Please visit your wordpress administration panel-> settings section-> permalinks page and hit save twice. This will help wordpress reload all the new pages and custom query vars added by our plugin.

= How to set Menu? =

Our plugin will automatically creates pages with required shortcodes to make it run: The second thing after installation is to configure your menu. You can do this by simply going to 'wp-admin->appearance->themes->menus'

* Firt Page: "Watch Movies & TV Shows" Homepage with the tabs (recent/top rated/most commented/most links) - you can rename this page to any name. The most important thing is to keep the shortcode [exnet_generate_tabs] into post content.

* Second Page: "Search Movies & TV Shows This page is used for the search form to filter movies/tv shows by genre, kind, title, actor, etc. You can rename it, but keep the shortcode [exnet_movies] into post content

= Pages to Keep away from Menu =

* exnet AJAX - Remove this page from menu (don't delete the actual wp page) -> It's used for the ajax star rating & pagination module.
* exnet REDIRECT - Remove this page from menu (don't delete the actual wp page) -> It's used for the link redirection system.
* Watch Online - Remove this page from menu (don't remove the actual wp page) -> It's used to show different "kinds" like Movies/Tv Shows with pagination.

= Pagination Configuration =

You can configure pagination into 'wp-admin->Movies->items per row/page' administration page. With our plugin, you have full control on how may items will be shown per page before pagination butttons (next/previous) will be shown. You can also configure how many items will be shown on the homepage. Lastly, you can configure how many items per row you want to show (ie. how many movie thumbnails per row) before a new row is start.

= Adding Kinds =

Kinds are the types of posts you want to have within your website., for example you could have Movies, TV Shows, Podcasts, etc. You can add unlimited kinds and genres as these are just custom wp taxonomies (like categories). From the plugin menu wp-admin->Movies->Kinds you can add kinds like "Movies", "TV Shows", "Podcasts", etc.

= Adding Genres =

From the plugin menu wp-admin->Movies->Genres you can add genres like "Action", "Comedy", "SciFi", etc. - when using the autofetcher genres of a movie are automatically created if doesn't exist.

= Adding movie/tv show - auto fetching details =

If you want to add movies/tv shows you just need to enter the IMDB URL and all movie details will be automatically fetched, and a new post will be created for you. From the plugin menu 'wp-admin->Movies->Autofetch Movie Info' you can automatically create a movie/tv show post with full details (title, genres, actors, runtime, poster, etc) by entering their ImDB URL.

** Attention: Use the url into this format: http://www.imdb.com/title/imdbID/. Extra parameters like ?ref=xx will cause the scrapper not work. **

== Changelog ==

= 1.1 =
* Missing File Error Fixed
* CSS Breakdown Fixed
* And add some features.

= 1.0 =
* Initial release


== Screenshots ==

1. Plugin Setting Page
2. Single watch Movie Page