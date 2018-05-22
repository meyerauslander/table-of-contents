# TOC (Table of Contents) Widget
* This is a WordPress Plugin.  It displays all headlines of single-post pages in the form of links that connect to their respective places in the post.  

* When clicking one of the links in the toc the page scrolls to the selected headline!

* This plugin includes a settings page in the admin (TOC Manager).  It allows the admin to set html tag names that will be used to determine the toc headlines.  The admin can also specify class names which will be used to determine the toc contents (in the event one wants to include a headline that is not inside one of specified html tag names or the admin wants this headline to appear in the toc as a sub headline (indented)).   
# Install
Download this repo as a zip and install like any other WP plugin
# Setup
1.  Go To the "Admin Dashboard".  Then select the "TOC Manager" screen under the "Settings" Menu.
2.  Enter which html tags to include in the TOC (i.e. h1, h2, strong, etc...)
3.  Enter which class names to include in the TOC.  Enter a list for classes to appear as headlines and a second list for classes to appear as sub headlines (to appear indented in the toc).  Then click "Save".  
3. Add a TOC Widget to your site. Set the title name as desired and click 'Save'.  

# Usage
* TOC Widget
    * Click on any post and enjoy your new TOC!  It only renders on posts that have 1 or more of the admin-specified class names.
* TOC Management
    * Go To the "Admin Dashboard".  Then select the "TOC Manager" screen under the "Settings" Menu (The same screen as in step 1 of setup).
    * Edit the "html tags to include" and "class names to include" as desired and save it.
    
# Contributing
1. Fork it (<https://github.com/meyerauslander/table-of-contents/fork>)
2. Create your feature branch (`git checkout -b feature/fooBar`)
3. Commit your changes (`git commit -am 'Add some fooBar'`)
4. Push to the branch (`git push origin feature/fooBar`)
5. Create a new Pull Request

# Credits
* The php code for the widget found in 'table-of-contents.php' was based on an existing widget (see 'index.php' in the 'sources' folder of this repository).  The Java Script for the toc widget found in 'toc.js' was based on an existing script that was written to go with 'index.php' (see 'script.js' in the sources folder).  Both pre-existing program files were provided by Shmuel Barkin.

* The scrolling functionality was implemented based on a Java Script (application.js) from the Github website: https://guides.github.com/activities/hello-world.  See also the 'sources' folder of this repository.   