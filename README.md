CsnSocial
=========
ZF2 module Social Network

### What is CsnSocial? ###
CsnSocial is a Module for Sharing events like Google+ and Facebook based on DoctrineORMModule

Not ready yet!
=========

### What exactly does CsnSocial do? ###

CsnSocial has been created with educational purposes to demonstrate how Social Network can be done.


Installation
------------

1. For now you can just download the zip file from (https://github.com/coolcsn/CsnSocial), or you can clone it with following command:
```
git clone https://github.com/coolcsn/CsnSocial.git
```
Make sure you are in the directory "module" before clone!

2. Add `CsnSocial` in your application configuration at: `./config/application.config.php`. An example configuration may look like the following :
```
'modules' => array(
    'Application',
    'DoctrineModule',
	'DoctrineORMModule',
	'CsnUser',
	'CsnSocial',
	'CsnCms',
)
```

3. Install all dependencies listed below (don't forget to add the configurations).

Dependencies
------------

This Module depends on the following Modules:

 - [Zend Framework 2](https://github.com/zendframework/zf2) 

 - [coolcsn/CsnUser](https://github.com/coolcsn/CsnUser) - Authentication (login, registration) module.
 - [coolcsn/CsnCms](https://github.com/coolcsn/CsnCms) - Content management system.
 

>### Post-installation ###
Navigate to ***[hostname]/social*** in your browser to test it.

Note: you must be logged in CsnUser!

Recommends
----------

- [coolcsn/CsnAuthorization](https://github.com/coolcsn/CsnAuthorization) - Authorization compatible for this Registration and Logging.
 
- [coolcsn/CsnNavigation](https://github.com/coolcsn/CsnNavigation) - Navigation module;
