ICMS
============================

A simple, lightweight cms for developers.

#Installation
1. Copy the files over to your server
2. Set up the SQL database, and add correct user permissions
3. Go to http://yourserver.com/install.php
4. Fill in ALL the fields, changing any that are incorrect
5. Done!

#Usage
After loging in, you can enter the admin control panel by using the user menu at the top right. Alternatively, it's located at /admincp/.

##Blog
The blog system is rather self explanatory. By defualt they are shown under the welcome message on the main page, and at /index.php?page=blog

##Pages
Pages are user created static files. You can use it to create a portfolio or add your own php and make a forum. 

##Settings
The settings tab is where you can edit the site wide variables you made when installing icms. Useful if you want to change your site name, or move the folder.

##Templates
The template system is just a web editor for php and css files that relate to the look and feel of your website. 

#File Structure
##Core
All the classes and database connect filess
##Templates
HTML and CSS files for the default and admin theme
##Includes
Third party libraries
##AdminCP
Admin files

##Features Coming Soon
- Addon system for user created content
