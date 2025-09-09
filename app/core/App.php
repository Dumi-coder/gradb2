<?php
//  echo "App.php loaded<br> ";
//router

class App// This is the main application class that handles routing and loading controllers
{
    private $controller='Home';// Default controller
    private $method='index';// Default method

    private function splitURL()// This function splits the URL into an array
    {
        $URL=$_GET['url'] ?? 'Home';// Get the URL from the query string, default to 'home' if not set
        $URL=explode("/", trim($URL,"/"));// Split the URL by slashes
        // Debug: log the URL segments
        error_log("URL segments: " . print_r($URL, true));
        return $URL;       // Return the array of URL segments
    }

    public function loadController()// This function loads  the appropriate controller based on the URL
    {
        $URL=$this->splitURL();// Get the URL segments
        
        /** select controller */
        $filename="../app/controllers/".ucfirst($URL[0]).".php";// Construct the filename for the controller based on the first segment of the URL
        if(file_exists($filename))
        {
            require $filename;// If the file exists, require it
            $this->controller=ucfirst($URL[0]);// Set the controller name to the first segment of the URL
            unset($URL[0]);
        }
        else if(isset($URL[1])){
            $filename="../app/controllers/".ucfirst($URL[0])."/".ucfirst($URL[1]).".php";
            if(file_exists($filename))
            {
                require $filename;
                $this->controller=ucfirst($URL[1]);
                unset($URL[0],$URL[1]);
            }else{
                $filename="../app/controllers/_404.php";
                require $filename;
                $this->controller='_404';
                $URL=[];
            }
        }
        else{                     
                    $filename="../app/controllers/_404.php";
                    require $filename;
                    $this->controller='_404';
                    $URL=[];
        }
        
         $controller=new $this->controller;// Create an instance of the controller class
        /**   select method */
         if(!empty($URL[1]) && method_exists($controller, $URL[1]))
         {
            //  if(method_exists($controller,$URL[1]))
            //  {
                $this->method = $URL[1];// Set the method to the second segment of the URL if it exists
                unset($URL[1]);// Remove the second segment from the URL array
            //  }            
         }
        $params=array_values($URL);// Re-index the URL array to get the parameters
        call_user_func_array([$controller,$this->method],$params); // Call the method on the controller instance
    }
}
