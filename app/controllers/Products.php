<?php
//  echo "Products.php controller loaded<br>";  


class Products extends Controller// This is the Products controller
{
    public function index()//
    {
        // echo "this is the products controller<br>";
        $this->view('products/products');// This loads the 'products/products' view.
    }
}