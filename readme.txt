=== Count Pagination Fix for MySQL ===
Contributors: leendertvb, arnodeleeuw, jorisvst
Donate link: https://www.iside.be
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl.html
Tags: MySQL, pagination, count, SQL_CALC_FOUND_ROWS, FOUND_ROWS
Requires at least: 5.0
Tested up to: 6.6.1
Requires PHP: 7.0
Stable tag: 1.1.1
Fix for pagination issues under MySQL v8.0 and later where `SQL_CALC_FOUND_ROWS` and `FOUND_ROWS` are no longer supported.

== Description ==

[As per MySQL documentation](https://dev.mysql.com/doc/refman/8.0/en/information-functions.html#function_found-rows) the FOUND_ROWS function in conjunction with the SQL_CALC_FOUND_ROWS function are deprecated as of MySQL version 8.0.17. This causes the pagination in Wordpress to be broken, both in the front-end as well as in the back-end, resulting in just showing page 1 only.

This plugin solves the issue Wordpress is facing when run under newer versions of MySQL database. By hooking into the query that is used to calculate the number of found posts, we return the right amount of posts found so the pagination can be build up correctly again.

== Installation ==
This plugin can be installed like any other plugin.

### INSTALL FROM WITHIN WORDPRESS

1. Visit the plugins page within your dashboard and select ‘Add New’;
1. Search for ‘Count Pagination Fix for MySQL’;
1. Install the plugin;
1. Activate the plugin from your Plugins page;

### INSTALL MANUALLY

1. Download the plugin for the Wordpress repository and unpack;
1. Upload the ‘count-pagination-fix’ folder to the /wp-content/plugins/ directory;
1. Activate the plugin through the ‘Plugins’ menu in WordPress;

### AFTER ACTIVATION

There are no settings or configurations for this plugin. Just activate and enjoy the pagination again.

== Frequently Asked Questions ==

= Shouldn't this functionality be build into the core of Wordpress? =

The COUNT function is already available since MySQL v4.0, providing more ways to optimise performance. Preparing for future versions is also important. So we believe that it is important to start working on this. Please refer to [the corresponding ticket](https://core.trac.wordpress.org/ticket/47280) to follow up.

= After activating, the pagination is still not fixed, what can I do? =

This plugin has been tested with the major query methods that Wordpress supports. Chances are you are using a plugin that is altering the query in some way or a plugin that uses it's own querying methods. Please let us know if you run into any problems and we will help you work out a solution.

== Changelog ==

= 1.1.1 =

Release date: 2024-07-24

Apply more WordPress Coding Standards and update "Tested up to" to latest WordPress version number.

= 1.1 =

Release date: 2023-12-07

Rename plugin to match naming conventions.

= 1.0 =

Release date: 2023-09-28

Initial release of the plugin, fixing the pagination for post type archive pages and taxonomy archive pages, both on front-end and in back-end.