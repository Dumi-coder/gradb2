<?php
//  echo "App.php loaded<br> ";
//router

class App// This is the main application class that handles routing and loading controllers
{
    private $controller='Home';// Default controller
    private $method='index';// Default method

    private function splitURL()// This function splits the URL into an array
    {
        $URL=$_GET['url'] ?? 'home';// Get the URL from the query string, default to 'home' if not set
        $URL=explode("/", trim($URL,"/"));// Split the URL by slashes
        return $URL;       // Return the array of URL segments
    }

    public function loadController()// This function loads  the appropriate controller based on the URL
    {
        $URL=$this->splitURl();// Get the URL segments
        
        /** select controller */
        $filename="../app/controllers/".ucfirst($URL[0]).".php";// Construct the filename for the controller based on the first segment of the URL
        if(file_exists($filename))
        {
            require $filename;// If the file exists, require it
            $this->controller=ucfirst($URL[0]);// Set the controller name to the first segment of the URL
            unset($URL[0]);
        }
        else{
            $filename="../app/controllers/".ucfirst($URL[0])."/".ucfirst($URL[0]).".php";// Construct the filename for the controller in a subdirectory if it exists
            if(file_exists($filename))
            {
                require $filename;// If the file exists, require it
                $this->controller=ucfirst($URL[0]);// Set the controller name to the first segment of the URL
            }
            else{
                $filename="../app/controllers/_404.php";// If the file does not exist, load the 404 controller
                require $filename;// Require the 404 controller file
                $this->controller='_404';// Set the controller name to '_404'
            }
        }
        
         $controller=new $this->controller;// Create an instance of the controller class
        /**   select method */
         if(!empty($URL[1]))
         {
             if(method_exists($controller,$URL[1]))
             {
                 $this->method = $URL[1];// Set the method to the second segment of the URL if it exists
                unset($URL[1]);// Remove the second segment from the URL array
             }            
         }
        call_user_func_array([$controller,$this->method],$URL); // Call the method on the controller instance
    }
}
