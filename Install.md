## 1. Setup
**OSX & Windows**
![kitematic](http://i.imgur.com/nFRw15w.png)
  1. Download & Install the Docker Toolbox - https://www.docker.com/products/docker-toolbox
      1. Open up kitematic, select "+New" to add a new container.
      2. First grab either the [MariaDB](https://hub.docker.com/_/mariadb/) or [MySQL](https://hub.docker.com/_/mysql/)L Container.
      3. After it's created you'll need to add an Environment Variable by clicking "Settings" on the top right
      4. and under the "General" tab.
      5. Key: MYSQL_ROOT_PASSWORD    Value: password
      6. Now your database should be running, make note of the IP and Port it's running on.

  2. Download ICMS to a folder on your computer - https://github.com/Nixhatter/CMS
        1. Back on Kitematic, add another container, this time search for "icms-docker" and create it.
        2. After it's done, go to the settings again and the "Volumes" tab.
        3. Press "Change" and select the ICMS folder on your computer.
        4. It should be up and running now, check the ip and port on the ICMS container and go to it on your web browser.

## 2. First Install
![ICMS Install](http://i.imgur.com/3L4v6cB.png)
  1. You will automatically be taken to the /install file and can start the process. Site name is up to you, the url you can just keep the default unless you know otherwise.
  2. Database Config:
      1. Change the ip from "localhost" to the one noted earlier.
      2. Change the port from "3306" to the one noted earlier.
      3. Leave Database Name empty.
      4. Check "Generate the SQL User/Table"
      5. Database user is "root"
      6. Database password is "password"
      7. Hit connect and it will validate that they are correct.
  3. At this point you are creating your user, fill it in and make sure to enter a correct email for password recovery.
  4. All done, deleting the install.php file makes your site safer.
