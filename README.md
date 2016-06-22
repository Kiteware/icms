# ICMS
> A simple, lightweight **[CMS](https://en.wikipedia.org/wiki/Content_management_system)** for developers. Still in Beta, but will be out soon!

## Installation
```sh
git clone https://github.com/Nixhatter/CMS.git
```
Go to http://yourserver.com/**install.php**

### Site Information
* **Site Name** - The name of your website, which will be on every page.
* **Install Location** - The absolute path to the ICMS folder, don't touch.
* **URL/Domain** - The url people will be accessing your site from, don't touch.

### Database
* **Database Host** - localhost if on the same server, if not please specify
* **Database Port** - In case it's not the default port

Two options:

* If you created a database and user manually please enter the name of the database, the user and their password.

OR

* Leave the database name blank and provide a databaser user/pass which has permission to create a database and new user. ICMS will automatically create a new database, user and pass and store it in the configuration file.

### Create Admin User
This will be the first administrator for your website. The username will show up on all blog and page posts. The Full Name is for the profile page. **Use a secure password that is not use on other websites**, you will always have the option to reset a forgotten password.

## Usage

### Admin
#### Blogs
Create and edit blog posts which can be viewed from /blog. All blogs are in [markdown](http://parsedown.org/demo) format.
#### Pages
Pages are user created static files. Great for a contact form, portfolio or any simple PHP script. When a new page is created, a menu is automatically created for it.
#### Users
Manage your users through here. 
#### Settings
These settings can also be accessed by editing the core/configuration.php file.
#### Templates
These template partials are what are loaded on each page. The only special one is the index partial, which is the template that is copied when a new page is created.

### Public
#### Profile
#### Settings
#### Reset Password

## Server Requirements
#### Minimal
- PHP 5.3+ 
- MySQL
- NGINX/Apache

#### Recommended
- PHP 5.5+ 
- MySQL
- mb_strlen()

## Development Environment
```sh
git clone https://github.com/Nixhatter/CMS.git
```
Grab the [docker image](https://hub.docker.com/r/nixhatter/icms-docker/)

Add the icms folder as a **volume** to docker

Setup a [MySQL](https://hub.docker.com/_/mysql/) or [MariaDB](https://hub.docker.com/_/mariadb/) docker container

