<?php

class OrderItemModel extends Model {
    protected $table = 'order_items';
    protected $primaryKey = 'id_order_item';
    use StaticInstantiator;

}

