
## Calorific Data App

## Guide Line

Clone the repository

- git clone https://github.com/amit9288-panchal/glide.git

Enter to repository

- cd glide

Set .htaccess file

- RewriteEngine On
- RewriteRule ^([^/]+)/? index.php?controller=$1&action=$2 [L,QSA] 

Create database and execute query in folder

- source/database

Set database configuration 

- config/db.config

Please note there are sample upload csv file in
- source/
