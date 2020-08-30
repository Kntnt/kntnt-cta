# Kntnt Call To Action (CTA)

WordPress plugin that provides post type, taxonomy and shortcode to allow dynamic insertion of Call To Action (CTA).   

## Description

This plugin provides:

* The custom post type *CTA* with a regular WordPress editor for body text. The body text shall contain a *Call To Action* (*CTA*) to be inserted on other pages. You can compare these with ads.
* The custom taxonomy *CTA Group* which allows you to create groups of CTAs. You can compare these with ad groups. The taxonomy can also be used with other post types (e.g. WordPress’ built-in *post* and *page*) to indicate which CTA Groups are allowed.
* The shortcode [cta] to be placed where you want a CTA.
* A settings page with
  - *Default Content* that will be used if a CTA has no body text,
  - *Extra CSS* that will be loaded for pages with a CTA, and
  - *Show CTA Group Metabox* that allows you to check post types (e.g. page and post) on which a CTA Group taxonomy selection meta box should be visible.

A CTA can be part of several CTA Groups, and for a page of any post type to be associated with several CTA groups. In this way, it’s possible to allow some CTAs to appear on many or all pages, while others are only allowed to appear on a few or a single page. Thus, for each page, there might be many CTAs from different CTA Groups that are allowed.

When the shortcode `[cta]` is encountered on a page, it is replaced with one of the allowed CTAs for that page.

It’s also possible to list CTA Groups in the shortcode itself. CTA Groups are listed after the `cta` keyword with their slugs separated by commas (no spaces). If present, the listed CTA Groups will be used instead of the CTA Groups associated with the page. For example, `[cta buy,job]` will be replaced with a CTA from either the CTA Group with the slug buy or the one with the slug job. Alternatively, you can use this form as well: `[cta groups=”buy,job”]`.

The plugin replaces the shortcode with a CTA in two steps. First, the shortcode is replaced with an empty `<div>`-element with the class `kntnt-cta` and a data-attribute that specifies a randomly selected CTA Group from those that may be considered according to the procedure described above. In parallel, a file with the extra CSS (see above) and a JavaScrip are added. When the JavaScript is executed by the visitor’s browser, it gets the CTA Group from data-attribute and sends it with a request to a REST endpoint provided by this plugin. The plugin randomly selects one of the CTAs in that CTA Group and sends its content back to the JavaScript, which replaces it within the `<div>`-element.

If the page is cached, the CTA Group will not be updated until the cached page is purged, but the CTAs will be replaced on every load. This is a compromise between performance and flexibility. The behaviour might change in the future.

With [*Advanced Custom Field*](https://sv.wordpress.org/plugins/advanced-custom-fields/) or a similar plugin, it is possible to hide the regular content editor and instead use custom fields, in order to create a more customizable interface. With [*Custom Content Shortcode*](https://sv.wordpress.org/plugins/custom-content-shortcode/) or a similar plugin, it is possible to create default content (see above) that uses values from those custom fields. In this way, default content can be used as a template for all CTAs.

## Installation

Install the plugin [the usually way](https://codex.wordpress.org/Managing_Plugins#Installing_Plugins).

You can also install it with [*GitHub Updater*](https://github.com/afragen/github-updater/archive/develop.zip), which gives you the additional benefit of keeping the plugin up to date from within its administrative interface (i.e. the usually way). Please visit its [wiki](https://github.com/afragen/github-updater/wiki) for more information.

You need an API key for this plugin to work. To get an API key free of charge, send a request to info@kntnt.com.

## Frequently Asked Questions

### Where is the setting page?

Look for `Social Media Scheduler` in the Settings menu.

### How do I know if there is a new version?

This plugin is currently [hosted on GitHub](https://github.com/kntnt/kntnt-cta); one way would be to ["watch" the repository](https://help.github.com/articles/watching-and-unwatching-repositories/).

If you prefer WordPress to nag you about an update and let you update from within its administrative interface (i.e. the usually way) you must [download *GitHub Updater*](https://github.com/afragen/github-updater/archive/develop.zip) and install and activate it the usually way. Please visit its [wiki](https://github.com/afragen/github-updater/wiki) for more information. 

### How can I get help?

If you have a questions about the plugin, and cannot find an answer here, start by looking at [issues](https://github.com/kntnt/kntnt-cta/issues) and [pull requests](https://github.com/kntnt/kntnt-cta/pulls). If you still cannot find the answer, feel free to ask in the the plugin's [issue tracker](https://github.com/kntnt/kntnt-cta/issues) at Github.

### How can I report a bug?

If you have found a potential bug, please report it on the plugin's [issue tracker](https://github.com/kntnt/kntnt-cta/issues) at Github.

### How can I contribute?

Contributions to the code or documentation are much appreciated.

If you are unfamiliar with Git, please date it as a new issue on the plugin's [issue tracker](https://github.com/kntnt/kntnt-cta/issues) at Github.

If you are familiar with Git, please do a pull request.

## Changelog

### 1.0.0

Initial release of a fully functional plugin.
