<?php


class IndexController extends Controller{
    private $productModel;

    public function __construct() {
        $this->productModel = new ProductModel();
    }

    public function index(){
        var_dump($this->productModel->getProducts());
        // $this->view('homepage/index');
    }

}