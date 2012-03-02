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

The plugin is released according to the terms of the <a href="http://unlicense.org/">unlicense</a> (hint: there are none). The Unlicense is copied below for your convenience.

This is free and unencumbered software released into the public domain.

Anyone is free to copy, modify, publish, use, compile, sell, or
distribute this software, either in source code form or as a compiled
binary, for any purpose, commercial or non-commercial, and by any
means.

In jurisdictions that recognize copyright laws, the author or authors
of this software dedicate any and all copyright interest in the
software to the public domain. We make this dedication for the benefit
of the public at large and to the detriment of our heirs and
successors. We intend this dedication to be an overt act of
relinquishment in perpetuity of all present and future rights to this
software under copyright law.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT.
IN NO EVENT SHALL THE AUTHORS BE LIABLE FOR ANY CLAIM, DAMAGES OR
OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE,
ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR
OTHER DEALINGS IN THE SOFTWARE.

For more information, please refer to <http://unlicense.org/>