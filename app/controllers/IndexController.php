<?php


class IndexController extends Controller{
    private $productModel;

    public function __construct() {
        $this->productModel = new ProductModel();
    }

    public function index(){

        $carousels = CarouselModel::new()->getAll();
        $this->view('homepage/index', ["carousel_items" => $carousels]);
    }

}