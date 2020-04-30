## An Intranet plugin for WordPress

A modular intranet/permissions plugin for WordPress.

## Installation

1. Upload the Intranet folder to the plugins directory in your WordPress installation
2. Activate the plugin.
3. Navigate to the "User Permissions" Menu.

That's it! Now you can easily start intranetting!

## Changelog

= 4.2.0 =

* Added: Module for Naming Contest

= 4.1.1 =

* Added: Custom fields for Agency Directory page to add additional related contact info

= 4.1.0 =

* Added: #OneTeam Suggestion Box Module
* Updated: insert_modules function to add "nice name" to the database on activation
* Updated: Individual module files to have nice_name function

= 4.0.0 =
*A Frontend Overhaul*

* Added: The use of REST API for offsite endpoint calls.
* Added: Logout link to collapsed main nav.
* Updated: To Foundation XY Grid.
* Updated: Pretty much all frontend aspects of the site. Most notably the Candidate Tracker.
* Updated: Moved all AJAX functions to the main.js file.
* Updated: Comments for individual candidates now uses Wordpress' built in comment system.
* Updated: Office map module for new locations and office splits
* Updated: Main navigation function to fully encompass the main navigation.
* Updated: Phone list search with JS filter function.
* Fixed: Sorting of jobs in Position dropdown menu.
* Removed: The use of XMLRPC.
* Removed: Custom taxonomies and custom fields for "People" section. Using REST API from live site.

= 3.0.0 =
*Major Overhaul*

* Upgraded entire plugin to use PHP Classes.
* Consolodated all plugins into single Intranet plugin where they are all separated into "modules" that can be activated/deactivated.
* Reworked permission section to allow the use of AJAX to update user permissions.
* Added: Modules section to activate/deactivate
* Updated: Permissions to actually work as intended.

= 2.1.5 =

* Added: Function for global sessions. Mainly to be used if someone is not logged in when they try to access a page, after logging in, it will automatically redirect them to that page, instead of just sending them to the main dashboard page.

= 2.1.4 =

* Added: Function check if the current custom post is a parent.

= 2.1.3 =

* Added: Function to allow redirection, even if my theme starts to send output to the browser
* Updated: Login & Insufficient Permissions now have their own pages.
* Removed: Login & Insufficient Permissions pages as a pop-up model. Security reasons.

= 2.1.2 =

* Added: Function to get custom post ID by slug & post type
* Added: Function for login form & insufficient permissions modals
* Fixed: Headers already sent error display on plugin activation.
* Removed: Login & Insufficient Permissions pages as a pop-up model. Security reasons.

= 2.1.1 =

* Added: Logout redirect function
* Added: Function to check for page parents by slug
* Added: Function for front-end CSS & JS files to appear only on the pages they affect
* Added: Function to create login page and assign template
* Added: Function to create insufficient permissions page
* Updated: Made login function it's own page template
* Removed: Login function.

= 2.0.1 =

* Added: Logout redirect function
* Updated: Apps page permissions function MySQL query to use the wpdb global

= 2.0.0 =

* Added: Login capability to the dashboard template page
* Added: Compare apps to WP and plugin DB function
* Added: Deletes an app column from the database when plugin is removed from WP
* Added: Function to check out pages by their IDs
* Added: Login function to the dashboard template
* Added: Added widget area to WP dashboard to enable/disable plugins
* Added: Lists active widgets from Dashboard area
* Updated: Plugin going back to just User Permissions
* Updated: Auto creation of columns based on child plugin widgets
* Updated: Array to auto add module columns to DB table
* Updated: Dashboard page permissions to use widgets
* Updated: Actually got it working the app page permissions
* Removed: The app table
* Removed: Manual input of new plugins to DB table
* Removed: Any instance of app activate/edit. Each app will have their own plugin

= 1.1.0 =

* Added: Block non admin's from accessing WP dashboard
* Added: Added user's display name to database
* Added: Function to check the page slugs
* Added: Function to create dashboard page upon plugin activation
* Added: Function to use custom dashboard template page in "/templates" folder
* Added: Function for dashboard page permissions
* Added: Error file creation for any activation errors
* Updated: Changed name to Intranet
* Updated: srs_update_user function to have failure message

= 1.0.0 =

* Initial plugin creation.
