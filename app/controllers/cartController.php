<?php

class CartController extends Controller{
    private $cartItemModel;

    public function __construct() {
        $this->cartItemModel = new CartItemModel();
    }

    public function index(){
        $this->view('cart/index');
    }
}