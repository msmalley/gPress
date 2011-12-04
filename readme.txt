=== gPress ===
Contributors: msmalley, pressbuddies
Tags: buddypress, geotagging, geo-tagging, geo, gpress, gmaps, google maps, google, maps, map, mapping
Requires at least: WordPress 3.1 / BuddyPress 1.2.8
Tested up to: WordPress 3.1 / BuddyPress 1.2.8
Stable tag: 0.2.5
Donate link: http://pressbuddies.com/sponsorships/

gPress adds new geo-relevant layers to the press platforms so you can geo-tag your surroundings or develop your own location-based services...

== Description ==

gPress adds new geo-relevant layers to WordPress, allowing you to create your own location-based services or to keep track of your own personal geo-tagged journies. Even in its beta state, you can presently geo-tag posts using native WordPress Mobile Applications, or create new geo-located places using custom post types, featured images and descriptions, add geoRSS functionality and integrate with BuddyPress...

For a live demonstration; please visit our [Demo Site](http://gpress.my/)

The future of gPress is one where you can develop your own completely customizable, fully-interactive and entirely immersible geo-relevant social-layers using combinations of movements, actions and consequences whilst geo-tagging, trailing and interacting with locations, people and objects. Create new geo-relevant states of social-roleplay by defining language and lingo, customizing context and integrating with other social-networks...

To visit or support the team behind gPress, please visit us at [PressBuddies](http://pressbuddies.com/)

For more community particpation; please visit us at [BuddyPress](http://buddypress.org/community/groups/gpress/activity/)

Our OFFICIAL support [FORUMS](http://buddypress.org/community/groups/gpress/forum/) are on the BuddyPress.org site.

For a fully detailed description of what we have left to do, please visit our [ROADMAP](http://gpress.my/roadmap/).

For a brief glimpse at some of the things that you can already do with gPress despite its extreme beta state, please visit [gPress.my](http://gpress.my/about/).

For examples of gPress in action or to see the BuddyPress group integration features, please visit our [SHOWCASE](http://gpress.my/groups/showcase/).

== Installation ==

1. Deactivate Geolocation (if already in use), as gPress does everything Geolocation does, and more, and the two plugins cannot be used together. In fact, multiple plugins that rely upon the Google Map API will more than likely not work well together, so be warned...

2. Check you have WordPress 3.1+ (without which, nothing but bad things will happen)
3. Download the plugin
4. Unzip and upload to plugins folder
5. Activate the plugin
6. Smile at how easy life with WordPress can be!

7. To geo-tag posts with mobile-integration using GPS, you will need to download one of the mobile applications from Automattic and then enable XML-RPC from the Settings > Writing tab in the wp-admin

We have some more BASIC documentation available at:
http://gpress.my/documentation/

== Frequently Asked Questions ==

We have some more BASIC documentation available at:
http://gpress.my/documentation/

= Why does nothing happen after I activate the plugin...? =

gPress will only work with WordPress 3.1+, and once activated, the only new addition you will see at this point is the new Places and gPress Options tabs in the administrative back-end. If you activate gPress on anything less than WP 3.1, these tabs and almost if not all gPress functionality will not be available.

= Do I need to apply for a Google Maps API Key...? =

Since gPress uses Google Maps API 3 it does NOT require an API key.

= Why does the styling not match my theme...? =

To be honest, at this point, we have ONLY tested the plugin using the default BP and Twenty Ten theme. We will run more tests in different themes as we reach version 0.3 onwards, but at this stage, we're focusing on functionality and testing that functionality by using the Twenty Ten and default BP themes...

= gPress is Installed and I've created Places, but cannot see them on my homepage...? =

Again, the only themes that gPress is guaranteed to work nicely with is Twenty Ten and BP-Default. Since gPress relies upon standard WordPress procedures and functionality used in these default themes, it is possible that more advanced or poorly coded themes will not play well with gPress, especially as we rely upon the usual queries to run the new custom post types. If strange things start happening with your loops, please visit gPress Options > Advanced Settings and experiment with options there.

= Does gPress work with other plugins...? =

gPress and Geolocation cannot be used together. gPress does everything Geolocation does, and more, but the two of them cannot be used together. Other than that, we have not yet had any reports of other problems, but please let us know if you run into any...

If you have any additional questions, please visit our [SUPPORT FORUMS](http://buddypress.org/community/groups/gpress/forum/) at BuddyPress.

== Screenshots ==

1. This is a demonstration of the administrative panel for adding new places...
2. This is the public end-view for a place, as seen in the Twenty Ten theme...

== Upgrade Notice ==

= 0.2.5 =
MAJOR UPDATE - LOADS OF NEW FEATURES, RE-WRITTEN OPTIONS WITH INCREASED PERFORMANCE AND STABILITY ONLY WORKS WITH WP 3.1+

== Changelog ==

= 0.2.5 - 27/FEB/2011 =
* ADDED es_ES Spanish Language Files
* ADDED Deprecated Workflow (upgrades old gPress place descriptions into actual content upon first running new version)
* ADDED Notification System for MAJOR Upgrades
* FIXED BUG in Widgets > Advanced Settings -> Specifically Custom Heights for Widgets
* ADDED Min-Widths for Maps, beyond which, Markers act as Links, not as Open Buttons
* FIXED ALL KNOWN BUGS for Widgets and Short-Codes
* ADDED Options for Easily Specifying Page ID for Front-End Place Submission
* ADDED BASIC Shortcode Filters for Displaying ALL Users and ALL Geo-Tagged Posts
* ADDED New Sidebar Widgets for ALL Users and ALL Posts
* ADDED "gpress-examples" folder - THIS INCLUDES CONTINUALLY UPDATED EXAMPLES - SUCH AS:
1. Front-End Place Submission Filtering
2. Adding File Uploads with Front-End Place Submission
3. Adding Photo Galleries into Marker Windows
4. Adding Complex Tabbed Content into Marker Windows

= 0.2.4.4.5 - Spiagge (Sponsored) Edition - Visit http://spiagge.com for working example =
* Place Descriptions Removed and Replaced by:
* Place Marker Windows now utilise the_excerpt() and the_content()
* Front-End Posting Functions Added - Example Page Template Needs Documenting
* ALL gPress Options now use WordPress functionality and Styling
* Some former Options lost - such as File Upload (for Markers) will add again later
* Separated BP Settings > Geo-Settings and Profile > Edit My Location
* ADDED New "All Places" Widget
* ADDED CSS Classes for Windows

= 0.2.4.4.2 - 09/SEPT/2010 =
* FIXED XML-RPC CONFLICTS

= 0.2.4.4.1 - 04/SEPT/2010 =
* RESOLVED Fatal-Error at Activation
* REMOVED Questionable jQuery Injection
* FIXED Bug with Flash Media-Upload Conflict

= 0.2.4.4 - 30/AUG/2010 =
* RESOLVED IE BP Sign-Up Conflict
* MOVED Custom Markers to WP-Content
* ADDED Map Options for BuddyPress Maps
* FIXED Map Height for Widgets 
* ADDED gPress WYSIWYG Component Control

= 0.2.4.3.2.3 =
* RESOLVED XML-RPC Conflict

= 0.2.4.3.2.2 =
* RESOLVED Conflict with GF and Other admin.php JS UI Plugins

= 0.2.4.3.2.1 =
* RESOLVED XML-RPC Support and Several Other Minor Bugs

= 0.2.4.3.2 =
* REPLACED FAULTY PACKAGE

= 0.2.4.3.1.1 =
* REMOVED Unnecessary Echo Statement

= 0.2.4.3.1 =
* MOVED Location at Sign-Up to End of Form
* REMOVED Double-Instance of UI.js from Forms

= 0.2.4.3 =
* RESOLVED Conflict with jQuery UI in gPress Options
* REMOVED Geo-Settings Page IF Not Needed (Over-Ride)
* ADDED Easy Browsing (from PC) and Upload of Markers
* ADDED Options for Allowing User Location at Sign-Up

= 0.2.4.2.3 =
* FIXED Lots of Little Bugs :-)

= 0.2.4.2.2 =
* IMPROVED Geo-Settings Map Initialization
* RESOLVED Conflict with Chrome and gPress Options
* RESOLVED Conflict with Contact Form 7 and gPress

= 0.2.4.2.1 =
* FIXED FATAL ERROR for NON BuddyPress Environments

= 0.2.4.2 =
* IMPROVED Language - Automatted via WPLANG Definitions
* ADDED Filter for Switching ID of WYSIWYG Editor
* NEW Front-End BuddyPress User Settings Framework
* NEW User Controls for Individual Profile Options
* NEW Super Admin Controls for Controlling User Controls

= 0.2.4.1.1 =
* FIXED bug with Un-Escaped Titles / Addresses

= 0.2.4.1 =
* FIXED "IS SET" PROBLEM
* FIXED Bug with gPress Options in IE
* ADDED Advanced Settings > Sitewide Options

= 0.2.4 =
* ADDED Full Translation Capability for Everything
* NEED COMMUNITY CONTRIBUTIONS FOR MO FILES TO INCLUDE

= 0.2.3.2 =
* ADDED Checks for custom.php/css/js from custom folder
* ADDED Lingo Switcher (change places to venues)
* STARTED POT - NEED TO UPLOAD TO REPO TO TEST

= 0.2.3.1 =
* FIXED Mobile Compatability Bug

= 0.2.3 =
* ADDED Foursquare Sidebar Widgets
* IMPROVED Homepage Loop Options
* IMPROVED Session Starts (for 4sq)

= 0.2.2.2 =
* FIXED Minor Bug Avoiding PHP.ini Change
* ADDED Advanced Option for Removing Foursquare
* ADDED BuddyPress Option for Showing Location (as Text)
* ADDED BuddyPress Control for Locations on Profiles

= 0.2.2.1 =
* ADDED Overflow Scrolling for Large Content
* ADDED Option to Remove jQuery 1.4.2 from Theme
* CHANGED Foursquare session_start Sequence

= 0.2.2 =
* IMPROVED Core Functions for Future Growth
* IMPROVED Animated Map Panning
* ADDED User Locations to BP Profiles

= 0.2.1.5 =
* REMOVED JS Scripts from RSS Descriptions
* IMPROVED Permalink Refreshing for Places

= 0.2.1.4 =
* FIXED JS ERRORS ON THEME PAGES

= 0.2.1.4 =
* FIXED BUG - Fixed errors with JS on Homepage

= 0.2.1.3 =
* FIXED BUG - Fixed errors with JS on Admin Pages

= 0.2.1.2 =
* CHANGED - Short Content Option to Content Option
* FIXED BUG - With apostrophes in Foursquare titles
* FIXED BUG - With empty Lat / Lng from Foursquare

= 0.2.1.1 =
* FIXED BUG - Code no longer displayed in excerpts
* ADDED OPTION - Force maps to the end of excerpts
* ADDED OPTION - Remove maps from content less than 255

= 0.2.1 =
* FIXED BUG - User without geo-tagged posts will NOT display map!

= 0.2 =
* ADDED Functionality for Multiple Places on Single Map
* ADDED Marker Clustering Functionality for Places
* ADDED Foursquare Integration (GET Friends + Your Locations)
* MODIFIED CSS (now using Blocks not Floats)
* MODIFIED Shortcode Generation (all RETURNED not ECHOED) 
* ADDED Advanced Options to gPress TinyMCE Ad-Hoc Maps
* ADDED TinyMCE Support for Foursquare
* ADDED Sitewide Geo-Tagged Posts to BuddyPress Profiles
* ADDED Forced Geo-Tagged Posting Functionality

= 0.1.9.9 =
* ADDED WYSIWYG Text-Editor Button for Adding Ad-Hoc Maps

= 0.1.9.8 =
* FIXED Bug for Post + Widget Marker Links
* ADDED Shortcode Functionality for AD-Hoc Maps in Posts + Pages

= 0.1.9.7 =
* FIXED Bug that removed page content
* FIXED Bug that did not display default map height in widget

= 0.1.9.6 =
* FIXED Missing Place Titles

= 0.1.9.5 =
* ADDED Default Marker Options (for posts, places and widgets)
* IMPROVED Map Functions (much cleaner, now using option arrays)

= 0.1.9.4 =
* ADDED Homepage Loop Control (Post + Places, just Post or just Places)

= 0.1.9.3 =
* ADDED geoRSS Support for Geo-Tagged Posts
* ADDED Place Address, Image and Description to geoRSS
* ADDED Advanced Settings for Map Options
* ADDED Ability to Customise Height per Map
* ADDED Custom Marker Icons per Map Place and Post
* ADDED Custom Marker Shadows per Map Place and Post
* ADDED Map Options to Back-End Map Forms too!
* ADDED Map Options to Favorite Place Widget too!

= 0.1.9.2 =
* CRITICAL UPDATE - FIXED errors due to faulty SVN with 0.1.9.1

= 0.1.9.1 =
* CRITICAL UPDATE - FIXED an error with super_admin options

= 0.1.9 =
* ADDED WordPress 3.0 Compatible
* ADDED gPress Options Framework
* ADDED gPress Component Control
* ADDED General Settings
* ADDED Advanced Settings for Map Options
* ADDED Brand Settings
* ADDED Credits Settings
* IMPROVED Icons for Admin

= 0.1.8.6 =
* Corrected Typos
* NEW Markers for Favorite Places and Posts
* Ability to EDIT (Location, Type and Zoom) of Geo-Tagged Posts
* Only show geo-edit form if geo_ fields are being used

= 0.1.8.5 =
* Added Support for Automattic's Mobile Applications
* This allows you to geo-tag posts

= 0.1.8.4 =
* PRIORITY fix for Empty Map Options

= 0.1.8.3 =
* CSS changes to places with images only that are smaller than window size
* Added Map Options (Map Type and Zoom Control per Place)

= 0.1.8.2 =
* Renamed 'Place Types' to 'Types of Places'
* Corrected typos in README.txt

= 0.1.8.1 (Minor Patch) =
* Fixed faulty filter for the_content that removed non-place content

= 0.1.8 =
* Markers are now centered upon initialising and only pan AFTER clicking on them
* Added geoRSS Places to RSS Feed (with geoRSS support for places)
* Added Recent Places Widget
* Added Favorite Place Widget

= 0.1.7 =
* FIXED - Place Type + Place Tag Pages (Multiple Maps on Same Page)

= 0.1.6 =
* Optimised CSS for Map Pages

= 0.1.5 =
* Optimised images to reduce plugin size
* Improved trim_me function for place descriptions

= 0.1.4 =
* Important CSS Changes to Places (Public View)
* Moved Place Types and Tags into InfoBox Associated with Place

= 0.1.3 =
* MAJOR Improvement to Non-WP 3.0+ Activations - Preventing Errors by Skipping ALL Functionality
* gpress-core/meta/places.php - Created accurate character count for future Twitter intgeration
* gpress.php - Added IF / ELSE to check version number and if less than WP 3.0, the plugin will not do anything
* GENERAL Improvements to Styling of Places UI (Back-End)

= 0.1.2 =
* gpress-maps.php - Improved functionality for handling empty descriptions, images, or both
* gpress-maps.php - Better styling of images and descriptions based on mapCanvas width()

= 0.1.1 =
* CSS fixes only...

= 0.1 =
* This was our initial commit...

== The Never-Ending Roadmap ==

gPress will ultimately consist of several CORE modules, but for now, we are focusing on the following key-components:


== Highest Priority - planned for gPress v0.2.5+ ==

Important Commercial Sponsorship Requirements issued by Laulima
1. Introduce Marker Functions to be Used with New Map Functions
2. Re-Write Map Functions to Include Marker Settings
3. Re-Write Shortcodes using new Marker and Map Functions
4. Provide Advanced Filtering Options to All Shortcodes
5. Allow for Integration with 3rd Party Location Info / Data
6. Allow for Integration with Lau-Events
7. Produce Static Snapshot Maps
8. Allow for Polygon Generation
9. Allow for Trail Generation
10. Consolidated JS for Multiple Maps

Known Bug Fixes from 0.2.5 include:
1. Add Filter for Map Width
2. Re-Style Geo-Settings to Match Settings > Notifications
3. Re-Introduce Sitewide / Network (MS) Options
4. Re-Integrate Marker Uploads (utilizing WP Media Manager!)
5. Add Easy Styling Options (Pre-Defined Themes & WYSIWYG Custom Styling)
6. Improved language files without any HTML tags
7. Add Foursquare Features to Separate Plugin

Back on Track with Previous Roadmap:
1. Improved PLACE Array (allowing for sets, types, tags, users, posts, blogs, etc)
2. Test the new Mobile (BlackBerry / iOS) "Places" Feature for Potential Integration
3. Adding multiple places to posts (Waiting WP 3.1 Functionality)
4. Improved Widgets (Drop-Down for Fav Place, Custom Markers, All Place Limits, etc)
5. Better GUI Options for Displaying Maps (as opposed to only showing full-map and content inside)
6. Global Language / Lingo Array in Separated Function / File (to make translations and upgrades MUCH smoother)

Additional BuddyPress Integration as follows:
1. Geo-Tagged Groups / Maps (with Group Members)
2. Show All Users Functionality / Widget
3. Show All Friends on Friend Page
4. New BP Directory for Places
5. Add Directions (for Places and Groups)
6. Further Customisation of Markers (categories, etc)

== Highest Priority - planned for gPress v0.2.6+ ==
General Enhancements:
1. Normalize Function and Filter Names
2. Ensure All Function Names are Unique "gpress_"
3. Introduce "Components" such as Gallery Add-On
4. Create Theme Checking Filter for Setting Up Components

Introduce New Geo-Tagged Components as follows:
1. Geo-Tagged Blogs
2. Geo-Tagged Comments
3. Geo-Tagged Media
4. Geo-Tagged Activity Streams

== Highest Priority - planned for gPress v0.2.7+ ==
Improved Social-Media Plugin (previously Foursquare gPress Plugin) with:
1. Foursquare Venues + Tips (GET and PUSH)
2. Foursquare PUSH Functionality (Create Venues + Checkins)
3. Twitter / Facebook Integration
4. Integration with Google Places (GET / PUSH)
-- REASON: It will ensure gPress hooks and filters are FULLY adequate
-- WOULD PREFER FOR SOMEONE ELSE TO HANDLE THIS

== Highest Priority - planned for gPress v0.2.8+ ==

BuddyPress Improvements:
1. BP Members, Groups and Blogs Directory Maps
2. Add Places to Activity Streams

General Enhancements
1. Search Functionality for Front-End Maps
2. Search Functionality as Option for Widgets

Major New Features:
1. Importing / Exporting Locations (CSV)
2. Proximity Based Content

== Highest Priority - planned for gPress v0.2.9+ ==
* BUG FIXES FROM PREVIOUS VERSIONS IN PREPARATION FOR PHASE 2
* PHASE 2 FEATURES NEW POST TYPES FOR CHECKINS
* CHECKINS WILL COMPRISE OF MOVEMENTS AND ACTIONS
* THESE CAN BE ATTACHED TO PLACES OR POSTS
* INTERFACE REQUIRED FOR CHECKINS WITH
* OPTIONS FOR CREATING ON THE FLY PLACES
* AND GOOD AND PROPER PHPDOCS

== Future Modules Include - gPress v0.3+ ==
1. Action Hooks and Filters with Documentation for Developers
2. Movements (move with linear, non-linear and stealth motion)
3. Actions (interact with locations, people and objects)
4. FULL Mobile Compatibility

== Future Modules Include - gPress v0.4+ ==
1. Consequences (customize the way things interact with each other)

== Future Modules Include - gPress v0.5+ ==
1. Markers (leave references points, check-points and crumbs)

== Future Modules Include - gPress v0.6+ ==
1. Locations (places can be public, private or communal)

== Future Modules Include - gPress v0.7+ ==
1. People (relationships with acquaintances, strangers and characters)

== Future Modules Include - gPress v0.8+ ==
1. Objects (collect and trade rewards, commodities and equipment)

== Future Modules Include - gPress v0.9+ ==
1. Download Trail History as KML File

== gPress v1+ ==
1. Launch standalone website promoting gPress
2. Start developing web-service for hosted gPress installs
3. Develop gPress plugin and API frameworks
4. Develop method for networking different geo-environments