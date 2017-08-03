=== Plugin Name ===
Contributors: jeremyshapiro
Tags: gotowebinar, gotomeeting, webinars, webinar, registration, infusion, infusionsoft, integration, jeremy shapiro
Requires at least: 2.0.2
Tested up to: 3.3.1
Stable tag: 1.2

This WordPress plugin background registers folks for a GoToWebinar right from your WordPress site.

== Description ==

Use the `[gtwregister]` shortcode to register folks for your webinars.

Make sure you pass URL Parameters to this page, i.e. from a registration form. GoToWebinar requires that you supply a first name, last name and an email address.

You can configure your url parameter to GoToWebinar field name mapping in your settings.

To background process a registration, embed the `[gtwregister /]` shortcode in your page or post. To hardcode any field, specify the value using `firstname`, `lastname`, `email`, `phone`, `servernum` (an optional override to the default server number) and `gtwid` (the ID of your webinar), for example `[gtwregister gtwid="23940324" /]`.

Next set your registration form or opt-in thank you page redirection form to go to the page you created. If using an opt-in form with a thank you page redirect, you must enable the option to pass the opt-in information to the thank you page. 

Please note that the only fields you can make required on your GoToWebinar configuration are First Name, Last Name and Email. You can include Phone, but it's best to make it optional unless your opt-in form requires Phone, too. Any other fields marked as "required" in GoToWebinar will cause the registration to fail.

By default, if there is an error, it will be displayed, and if the registration is successful, the success will appear in an HTML comment. You can change these options by passing along `commentsuccess` and `commenterror` as in `commenterror=1` to force errors to be commented or `commentsuccess=0` to display that the registration was successful.

== Installation ==

To install the plugin:

* Download the plugin
* Upload to your wordpress blog
* Activate and optionally configure

= Configuration =

The default settings should be more than enough, but you can modify the default field names to suit your application by clicking `Settings`

== Changelog ==

* 3/6/2012: Added additional documentation. (Thanks, Santi!)
* 1/20/2012: Added server number option for individual webinars, added registration, improved help
* 7/26/2011: Released Version 0.2. Added ability to specify server number
* 7/10/2011: Version 0.1 Added
