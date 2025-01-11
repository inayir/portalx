@echo Beginning setup
PATH=%PATH%;C:\xampp\php
php composer.phar init --name=mongodb/mongodb --description=MongoDB --type=library --autoload=AUTOLOAD --quiet
composer require mongodb/mongodb