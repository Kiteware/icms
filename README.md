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
The blog system is rather self explanatory. By default they are shown under the welcome message on the main page, and at /blog

##Pages
Pages are user created static files. You can use it to create a portfolio or add your own php and make a forum. 

##Settings
The settings tab is where you can edit the site wide variables you made when installing icms. Useful if you want to change your site name, or move the folder.

##Templates
The template system is just a web editor for php and css files that relate to the look and feel of your website. 


##Features Coming Soon
- Check for malicious/altered site files

##Development Environment
1. git clone https://github.com/Nixhatter/CMS.git
2. Grab this docker image: https://hub.docker.com/r/nixhatter/icms-docker/
3. Add the icms folder as a volume to docker
4. Download MySQL or MariaDB docker container, and run it
5. Follow the install instructions!