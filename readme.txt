=== Custom Affiliate Link Manager ===
Contributors: Mr Arslan CEO
Tags: affiliate, link manager, cloak, redirect, tracker
Requires at least: 5.0
Tested up to: 6.4
Stable tag: 1.1.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Manage and cloak affiliate links with advanced features, without external APIs.

== Description ==

Custom Affiliate Link Manager is a powerful WordPress plugin designed to help you manage, cloak, and track your affiliate links professionally. It provides a clean interface to create "pretty links" that are easy to share and track.

Key Features:
* **Link Cloaking:** Create clean, professional-looking URLs.
* **Redirection Types:** Support for 301, 302, and 307 redirects.
* **Click Tracking:** Detailed tracking of every click with history.
* **Geo-Targeting:** Redirect users based on their country.
* **A/B Testing:** Split traffic between multiple target URLs.
* **Link Health Check:** Automatically check if your target URLs are still working.
* **Import/Export:** Easily manage large numbers of links via CSV.
* **Shortcodes:** Insert affiliate links anywhere in your content.
* **QR Codes:** Generate QR codes for your affiliate links.

== Installation ==

1. Upload the `custom-affiliate-link-manager` folder to the `/wp-content/plugins/` directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Go to 'Affiliate Links' in the admin menu to start creating links.

== Frequently Asked Questions ==

= How do I create a new link? =
Go to Affiliate Links > Add New, enter a title and the target URL, then publish.

= Can I track clicks? =
Yes, click tracking is enabled by default. You can view stats in the Dashboard or Analytics pages.

== Screenshots ==

1. Dashboard showing quick stats.
2. Link creation with advanced settings.
3. Analytics overview.

== Changelog ==

= 1.1.1 =
* Fixed: Cloaked links with the "go" prefix (e.g. /go/my-link/) now redirect correctly and record clicks. Previously the redirect handler looked up the full prefixed path, so permalink-style URLs opened a page instead of redirecting.

= 1.1.0 =
* Professional restructuring to OOP architecture.
* Improved Admin UI.
* Added Health Check feature.
* Added Geo-Targeting and A/B Testing.
* Added Import/Export functionality.

= 1.0.0 =
* Initial release.
