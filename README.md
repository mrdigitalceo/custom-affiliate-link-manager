# Custom Affiliate Link Manager

A powerful WordPress plugin to **manage, cloak, and track affiliate links** professionally — create clean "pretty links", redirect them with full control, and see exactly how they perform. No external APIs required for core features.

![Version](https://img.shields.io/badge/version-1.1.1-blue) ![WordPress](https://img.shields.io/badge/WordPress-5.0%2B-21759B) ![License](https://img.shields.io/badge/license-GPL--2.0-green)

## ✨ Features

- **Link Cloaking** — Turn long, ugly affiliate URLs into clean pretty links (e.g. `yoursite.com/go/product`)
- **Redirect Types** — 301 (Permanent), 302, and 307 redirects, per link or as a global default
- **Click Tracking** — Every click is counted with a full click history per link
- **Dashboard & Analytics** — Total links, total clicks, and per-link reporting at a glance
- **Geo-Targeting** — Redirect visitors to different target URLs based on their country
- **A/B Testing** — Split traffic between multiple target URLs by percentage
- **Link Health Check** — Verify that your target URLs are still alive
- **Import / Export** — Manage large link collections via CSV
- **QR Codes** — A ready-to-download QR code for every link
- **Shortcode** — Insert affiliate links anywhere with `[affiliate_link]`
- **Link Scheduling** — Optional start date, expiration date, and a custom redirect message
- **Categories** — Organize links with a dedicated Link Categories taxonomy

## 📦 Installation

1. Download this repository and upload the plugin folder to `/wp-content/plugins/`
2. Activate **Custom Affiliate Link Manager** from the **Plugins** menu in WordPress
3. Go to **Affiliate Links → Add New Affiliate Link**, enter a title and the target URL, and publish
4. Share your pretty link — clicks and redirects are handled automatically

## 📝 Changelog

### 1.1.1
- **Fixed:** Cloaked links with the "go" prefix (e.g. `/go/my-link/`) now redirect correctly and record clicks. Previously the redirect handler looked up the full prefixed path, so permalink-style URLs opened a page instead of redirecting.

### 1.1.0
- Professional restructuring to OOP architecture
- Improved Admin UI
- Added Health Check feature
- Added Geo-Targeting and A/B Testing
- Added Import/Export functionality

### 1.0.0
- Initial release

## 👤 Author

**Mr Arslan CEO** — Founder, **NextGen Web Agency**

- Website: https://mrarslanceo.site
- GitHub: https://github.com/mrdigitalceo
- YouTube: https://youtube.com/@mrarslanceo
- Instagram: https://instagram.com/mrarslanceo
- Facebook: https://facebook.com/mrdigitalceo
- Fiverr: https://www.fiverr.com/mrdigitalceo
- Codester: https://www.codester.com/mrdigitalceo
- WhatsApp: https://wa.me/923164309537

## 📄 License

GPL-2.0 or later — see [LICENSE](LICENSE) for details.
