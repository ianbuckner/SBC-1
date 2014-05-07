Sermon Management System
========================

This source tree is for the part of the SBC web site that supports the uploading, searchign and subsequent playing of sermons.

mgmt/
- this is the subtree for the uploading service. Access is controlled using Apache .htaccess

The uploading is now performed by HTML5 Javascript code using the XHTMLRequest object. This replaces a Java Applet implementation.

Tested Browsers
---------------
- Internet Explorer 11
- Google Chrome Version 34.0.1847.131
- Opera Version 21.0.1432.57
- Firefox Version 28.0
- Safari Version 7.0.3 (9537.75.14)  

Known Issues
------------
Internet Explorer 10
- upload will not work due to missing progress bar HTML Element
