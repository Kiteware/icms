## 1. Setup
**OSX & Windows**
[kitematic](http://i.imgur.com/nFRw15w.png)
  a. Download & Install the Docker Toolbox - https://www.docker.com/products/docker-toolbox
      + Open up kitematic, select "+New" to add a new container.
      + First grab either the [MariaDB](https://hub.docker.com/_/mariadb/) or [MySQL](https://hub.docker.com/_/mysql/)L Container.
      + After it's created you'll need to add an Environment Variable by clicking "Settings" on the top right
      + and under the "General" tab.
      + Key: MYSQL_ROOT_PASSWORD    Value: password
      + Now your database should be running, make note of the IP and Port it's running on.

  b. Download ICMS to a folder on your computer - https://github.com/Nixhatter/CMS
        + Back on Kitematic, add another container, this time search for "icms-docker" and create it.
        + After it's done, go to the settings again and the "Volumes" tab.
        + Press "Change" and select the ICMS folder on your computer.
        + It should be up and running now, check the ip and port on the ICMS container and go to it on your web browser.

## 2. First Install
[ICMS Install](http://i.imgur.com/3L4v6cB.png)
  a. You will automatically be taken to the /install file and can start the process. Site name is up to you, the url you can just keep the default unless you know otherwise.
  b. Database Config:
      + Change the ip from "localhost" to the one noted earlier.
      + Change the port from "3306" to the one noted earlier.
      + Leave Database Name empty.
      + Check "Generate the SQL User/Table"
      + Database user is "root"
      + Database password is "password"
      + Hit connect and it will validate that they are correct.
  c. At this point you are creating your user, fill it in and make sure to enter a correct email for password recovery.
  d. All done, deleting the install.php file makes your site safer.
