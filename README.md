Easy OpenGraph
==============

**Easy OpenGraph** is the easiest way to set up <a href="http://ogp.me/">OpenGraph</a> on your WordPress website. Or at least it will be. It's still a work in progress.

Things of note
--------------

*   Still in development. Be patient.
*   It's currently optimized for my workflow, but I'm trying to branch it out and be smarter about making it work for everyone.
*   Not everything works yet. It currently calls a few functions that rely on Rach5, my custom theme boilerplate. I'm going to rewrite those bits.


Auto-updating
-------------

The plugin includes auto-updating functionality so the plugin is capable of being updated without being in the WordPress plugin repository. It will connect to https://updates.vanpattenmedia.com/. I assume some type of referral data or whatnot gets sent in that transaction (probably the same as in any download), but to be honest I have no idea how to access it. Eventually, the plugin may be submitted to the WordPress plugin directory, and remove the need for the auto-updater but if you're uncomfortable with connecting to our update server you're of course welcome to delete updater.php and all references to it.

License
-------

This plugin is released under the terms of the GPL. See the included LICENSE file for details.