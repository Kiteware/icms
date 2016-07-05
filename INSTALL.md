## 1. Setup
**OSX & Windows**
![kitematic](http://i.imgur.com/nFRw15w.png)
  1. Download & Install the Docker Toolbox - https://www.docker.com/products/docker-toolbox
      1. Open up kitematic, select "+New" to add a new container.
      2. First grab the MariaDB Container from the list, or search for it. Select create to download and install it.
      3. After it's created you'll need to add a root password as an Environment Variable. Select the MariaDB Container, then "Settings" on the top right and then the "General" tab.
      5. Add the Key: **MYSQL_ROOT_PASSWORD** and Value: **password**
      6. Now your database should be running, **make note of the IP and Port it's running on**.

  2. [Download](https://github.com/Nixhatter/CMS/archive/master.zip) or clone ICMS to a folder on your computer 
      1. Back on Kitematic, search for **icms-docker** on the top search bar and create it.
      2. After it's done, go to the icms-docker settings and the **Volumes** tab.
      3. Press "Change" and select the ICMS folder on your computer. This will sync your computers folder with the container.
      4. All done. Check the **ip** and **port** on the ICMS container and go to it on a web browser.

## 2. First Install
![ICMS Install](http://i.imgur.com/3L4v6cB.png)
  1. Once you go to the URL, you will automatically be taken to the /install file and can start the process. 
      1. The url can be left unchanged.
  2. Database Config:
      1. Change the ip from "localhost" to the one noted earlier.
      2. Change the port from "3306" to the one noted earlier.
      3. Leave Database Name empty.
      4. Check "Generate the SQL User/Table"
      5. Database user is **root**
      6. Database password is **password**
      7. Hit connect and it will validate that they are correct.
  3. Now you will create your user, fill it in and make sure to enter a correct email for recovery purposes.
  4. All done! You should delete the install.php file makes your site safer.
