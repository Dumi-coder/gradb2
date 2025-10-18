<?php
// define('APPROOT',dirname(dirname(__DIR__)));// This defines the root directory of the application, which is two levels up from the current file's directory.
//  echo "config.php loaded<br> ";
// This file contains the configuration settings for the application, including database connection details and application constants.

 // This is a comment explaining that the following code checks if the server name is 'localhost'.
 // If it is, it sets the database configuration for a local development environment.
 // If it is not, it sets the database configuration for a production environment.
if($_SERVER['SERVER_NAME']== 'localhost')// This condition checks if the server name is 'localhost'.
{
        /** dabtabase config */
        define('DBNAME','gradb2');
        define('DBHOST','localhost');
        define('DBUSER','root');
        define('DBPASS','');
        define('DBDRIVER','');

        define('ROOT','http://localhost/gradb2/public');
        
}
// else{// This condition is executed if the server name is not 'localhost', indicating a production environment.
        /** dabtabase config */
        define('DBNAME','gradb2_gradb2');
        define('DBPORT','3306');
        define('DBHOST','mysql-gradb2.alwaysdata.net');
        define('DBUSER','gradb2');
        define('DBPASS','passwordmysql');
        define('DBDRIVER','');
        
        // define('ROOT','https://www.GradBridge.com');
// }

define('APP_NAME','GradBridge');// This is the name of the application, used in the title tag and other places.
// This is the name of the application, used in the title tag and other places.
// It is also used in the config file to set the application name.
define('APP_DESC','Best website on the planet');// This is the description of the application, used in the meta description tag and other places.

// true means show errors
define('DEBUG',false); // This constant is used to enable or disable error reporting in the application.
// If set to true, errors will be displayed on the screen. If set to false,
// errors will be logged to a file instead. This is useful for debugging during development.

