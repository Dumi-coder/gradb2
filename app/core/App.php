<?php
//  echo "App.php loaded<br> ";
//router

class App// This is the main application class that handles routing and loading controllers
{
    private $controller='Home';// Default controller
    private $method='index';// Default method

    private function splitURL()// This function splits the URL into an array
    {
        $URL=$_GET['url'] ?? '';// Get the URL from the query string, default to empty if not set
        $URL=explode("/", trim($URL,"/"));// Split the URL by slashes
        
        // If URL is empty, determine default controller based on current directory
        if (empty($URL[0]) || $URL[0] === '') {
            $currentDir = basename(dirname($_SERVER['SCRIPT_NAME']));
            
            // Map directory names to default controllers
            $defaultControllers = [
                'admin' => 'admin/Auth',
                'counselor' => 'counselor/Auth', 
                'superadmin' => 'superadmin/Auth',
                'public' => 'Home'
            ];
            
            if (isset($defaultControllers[$currentDir])) {
                $URL = explode("/", $defaultControllers[$currentDir]);
            } else {
                $URL = ['Home']; // Fallback to Home
            }
        }
        
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
         // Re-index the URL array after unsets
         $URL = array_values($URL);
         
         if(!empty($URL[0]) && method_exists($controller, $URL[0]))
         {
            $this->method = $URL[0];
            unset($URL[0]);
         }
        $params=array_values($URL);// Re-index the URL array to get the parameters
        call_user_func_array([$controller,$this->method],$params); // Call the method on the controller instance
    }
}
