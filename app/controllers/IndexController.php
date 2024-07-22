<?php


class IndexController extends Controller{
    private $productModel;

    public function __construct() {
        $this->productModel = new ProductModel();
    }

    public function index(){

        $this->view('homepage/index');
    }

}