[![Build Status](https://travis-ci.org/Nixhatter/CMS.svg?branch=master)](https://travis-ci.org/Nixhatter/CMS)

[ICMS](http://i.imgur.com/KkcxwGj.gifv)

# ICMS
> A simple, lightweight **[CMS](https://en.wikipedia.org/wiki/Content_management_system)** for developers. Still in Beta.

## Installation
```sh
git clone https://github.com/Nixhatter/CMS.git
```

### Site Information
* **Site Name** - The name of your website, which will be on every page.
* **URL/Domain** - Site's URL, no need to change this.

### Database
* **Database Host** - localhost if on the same server, if not please specify
* **Database Port** - In case it's not the default port

Two options:

* If you created a database and user manually please enter the name of the database, the user and their password.

OR

* Leave the database name blank and provide a databaser user/pass which has permission to create a database and new user. ICMS will automatically create a new database, user and pass and store it in the configuration file.

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

## Server Requirements
#### Minimal
- PHP 5.3+ 

#### Recommended
- PHP 5.5+ 
- mb_strlen()

### [Setting up your Development Environment](https://raw.githubusercontent.com/Nixhatter/CMS/master/Install.md)


