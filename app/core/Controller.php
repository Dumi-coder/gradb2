<?php
//  echo "controller.php loaded<br> ";


class Controller// This is the base controller class that other controllers can extend
{
    public function view($name,$data=[])// This method loads a view file based on the provided name
    {
        if(!empty($data))
            extract($data);
        
        $filename="../app/views/".$name.".view.php";// Construct the filename for the view based on the provided name
        if(file_exists($filename))
        {
            require $filename;// If the file exists, require it
        }
        else{
            $filename="../app/views/404.view.php";// If the file does not exist, load the 404 view
            require $filename;// Require the 404 view file
        }
    }
}