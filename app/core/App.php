<?php
//  echo "App.php loaded<br> ";
//router

class App// This is the main application class that handles routing and loading controllers
{
    private $controller='Home';// Default controller
    private $method='index';// Default method

    private function findCaseInsensitivePath($directory, $targetName)
    {
        if (!is_dir($directory)) {
            return null;
        }

        $entries = scandir($directory);
        if ($entries === false) {
            return null;
        }

        foreach ($entries as $entry) {
            if (strcasecmp($entry, $targetName) === 0) {
                return rtrim($directory, '/') . '/' . $entry;
            }
        }

        return null;
    }

    private function kebabToCamelCase($string)
    {
        $parts = explode('-', $string);
        $camelCase = '';
        foreach ($parts as $part) {
            $camelCase .= ucfirst($part);
        }
        return $camelCase;
    }

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
                'counsellor' => 'counsellor/Auth', 
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

        // Map top-level role routes to their default auth controllers.
        // Example: /public/counsellor -> Counsellor/Auth
        if (!empty($URL[0]) && !isset($URL[1])) {
            $topLevelDefaults = [
                'counsellor' => 'Auth',
                'admin' => 'Auth',
                'superadmin' => 'Auth',
            ];

            $topLevel = strtolower($URL[0]);
            if (isset($topLevelDefaults[$topLevel])) {
                $URL[1] = $topLevelDefaults[$topLevel];
            }
        }
        
        /** select controller */
        $controllersRoot = "../app/controllers";
        $filename=$controllersRoot."/".ucfirst($URL[0]).".php";// Construct the filename for the controller based on the first segment of the URL
        if(!file_exists($filename)) {
            $resolvedTopLevel = $this->findCaseInsensitivePath($controllersRoot, ($URL[0] ?? '') . ".php");
            if ($resolvedTopLevel) {
                $filename = $resolvedTopLevel;
            }
        }

        if(file_exists($filename))
        {
            require $filename;// If the file exists, require it
            $this->controller=pathinfo($filename, PATHINFO_FILENAME);// Set the controller name based on resolved file
            unset($URL[0]);
        }
        else if(isset($URL[1])){
            $controllerDir = $controllersRoot."/".ucfirst($URL[0]);
            if (!is_dir($controllerDir)) {
                $resolvedDir = $this->findCaseInsensitivePath($controllersRoot, $URL[0]);
                if ($resolvedDir && is_dir($resolvedDir)) {
                    $controllerDir = $resolvedDir;
                }
            }

            // Convert kebab-case to CamelCase for nested controllers
            $controllerName = $this->kebabToCamelCase($URL[1]);
            $filename=$controllerDir."/".$controllerName.".php";
            if(!file_exists($filename)) {
                $resolvedNested = $this->findCaseInsensitivePath($controllerDir, $controllerName . ".php");
                if ($resolvedNested) {
                    $filename = $resolvedNested;
                }
            }

            if(file_exists($filename))
            {
                require $filename;
                $this->controller=pathinfo($filename, PATHINFO_FILENAME);
                unset($URL[0],$URL[1]);
                // Re-index after selecting a nested controller so method can be detected reliably
                $URL = array_values($URL);
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
