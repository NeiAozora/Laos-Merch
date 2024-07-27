<?php

class CartItemModel extends Model {
    protected $table = 'cart_items';
    protected $primaryKey = 'id_cart_item';
    use StaticInstantiator;

}

