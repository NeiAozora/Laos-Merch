<?php

class CartController extends COntroller{
    private $cartItemModel;

    public function __construct()
    {
        $this->cartItemModel = new CartItemModel();
    }

    public function index(){
        $this->view('cart/index');
    }

    public function addItem($id_user, $id_combination, $quantity)
    {
        return $this->cartItemModel->createCartItem($id_user, $id_combination, $quantity);
    }

    public function removeItem($id_cart_item)
    {
        return $this->cartItemModel->removeCartItem($id_cart_item);
    }

    public function updateQuantity($id_cart_item, $quantity)
    {
        return $this->cartItemModel->updateCartItem($id_cart_item, $quantity);
    }

    public function getCartItems($id_user)
    {
        return $this->cartItemModel->getCartItemsByUserId($id_user);
    }

    public function getTotalCost($id_user)
    {
        return $this->cartItemModel->getTotalCostByUserId($id_user);
    }
}
