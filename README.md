# TOC (Table of Contents) Widget
* This is a WordPress Plugin.  It displays all headlines of single-post pages in the form of links that connect to their respective places in the post.  

* Auto-Scroll Feature:  When clicking one of the links in the toc the page scrolls to the selected headline!

* Highlighted-Links Feature:  As the user scrolls down the page the current headline being displayed in the screen becomes highlighted in the toc!

* This plugin includes a settings page in the admin (TOC Manager).  It allows the admin to set html tag names that will be used to determine the toc headlines and toc sub-headlines (indented).  The admin can also specify class names which will be used to determine the toc contents (in the event one wants to include a headline that is not inside one of specified html tag names or the admin wants override the way a particular instance of an html tag will be displayed.)   
# Install
Download this repo as a zip and install like any other WP plugin
# Setup
1.  Go To the "Admin Dashboard".  Then select the "TOC Manager" screen under the "Settings" Menu.
2.  Enter which html tags to include in the TOC (i.e. h1, h2, strong, etc...) for headings and which html tags will be used for sub-headings.  (Note: There is a separate submit button for saving html heading settings and for html sub-heading settings.)
3.  Enter which class names to include in the TOC.  Enter a list for classes to appear as headlines and a second list for classes to appear as sub-headlines (i.e. appear indented in the toc).  (Note: there is a separate submit button for saving classes for heading settings and for saving classes for sub-heading settings)
4.  Enter the color, font size, and offset to be used for the "Highlighted-Links" feature.  (The offset is needed in the event that the links become highlighted too soon as the user scrolls down the page.  If not then set it to 0.)
4.  Verify that the current settings were set correctly (The current settings for each option are listed below its corresponding input text area.)
5.  Add a TOC Widget to your site. Set the title name as desired and click 'Save'.    

# Usage
* TOC Widget
    * Click on any post and enjoy your new TOC!  It only renders on posts that have 1 or more of the admin-specified html tags or 1 or admin-specified class names.
* TOC Management
    * Go To the "Admin Dashboard".  Then select the "TOC Manager" screen under the "Settings" Menu (The same screen as in step 1 of setup).
    * Update the "html tags to include" and/or "class names to include" and/or "Highlighted-Link Information" as desired and save it.
    
# Contributing
1. Fork it (<https://github.com/meyerauslander/table-of-contents/fork>)
2. Create your feature branch (`git checkout -b feature/fooBar`)
3. Commit your changes (`git commit -am 'Add some fooBar'`)
4. Push to the branch (`git push origin feature/fooBar`)
5. Create a new Pull Request

# Credits
* The php code for the widget found in 'table-of-contents.php' was based on an existing widget (see 'index.php' in the 'sources' folder of this repository).  The Java Script for the toc widget found in 'toc.js' was based on an existing script that was written to go with 'index.php' (see 'script.js' also in the sources folder).  Both pre-existing program files were provided by Shmuel Barkin.

* The "Auto-Scroll" and "Highlighted Link" features were implemented based on a Java Script (application.js) from the Github website: https://guides.github.com/activities/hello-world.  See also the 'sources' folder of this repository.   
