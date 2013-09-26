Schedule-symfony2 -- ALPHA --
=============================

A simple schedule with symfony2 framework

Run in your terminal
	
	php composer.phar create-project juanber84/schedule-symfony2:dev-master

	cd Schedule-symfony2

	php app/console doctrine:database:create
	
	php app/console doctrine:schema:update --force

Create a user admin
    
    fos:user:create

After you must give a role

    fos:user:promote