<?php

class ProductController extends Controller{
    
    public function index($id){
        $this->view('productdetail/index');
    }
}