=== Acquaint CRM to Easy Property Listings Import ===

Requires at least: 4.0
Tested up to: 4.0.8
Stable tag: trunk
License: commercial-license-v003
License URI: http://2020websolutions.co.uk/commercial-license-v003

Import properties into Easy Property Listings from your Acquaint CRM account via XML Feed.


== Description ==

A custom plugin created to import  Acquaint CRM property data in to Wordpress using Easy Property Listings

= Introduction =

Enter your credential in the Acquaint CRM settings page or use demo credentials:
Site Prefix:  DEMO
Site ID: 0
Password: test123


= Cron =

Data is imported once every hour via wordpress cron.
A real cron job can be set up to run wordpress cron jobs regardless of site access.
Add this to wp-config.php

//Disable WP Cron (Run it via a real crong job on the server)
define('DISABLE_WP_CRON', true);

Create a new cron job via a hosting control panel or via SSH and crontab -e
For example: /usr/local/bin/php /home/account_name/public_html/wp-cron.php


= SLUGS =

Easy Property Listings uses '/rental' and '/property' for the property listing pages.  This can be updated to show these pages on other pages by defining the slug in your theme functions.php file.

For Example:

//Update the slug (Re-save permalinks to ensure it is set)
//-----------------
define( 'EPL_RENTAL_SLUG' , 'holiday-lets/rental' );
define( 'EPL_PROPERTY_SLUG' , 'for-sale' );

= Templates =

Templates can be customised.
Copy the following to active child theme folder..

From:
easy-property-listings/lib/templates/themes/default/archive-listing.php
easy-property-listings/lib/templates/themes/default/single-listing.php
easy-property-listings/lib/templates/content/content-listing-single.php
easy-property-listings/lib/templates/content/loop-listing-blog-default.php

To:
child-theme/archive-listing.php
child-theme/single-listing.php
child-theme/easypropertylistings/content-listing-single.php
child-theme/easypropertylistings/loop-listing-blog-default.php

Examples of specific theme versions of archive-listing.php and single-listing.php can be found here: http://codex.easypropertylistings.com.au/article/185-theme-templates