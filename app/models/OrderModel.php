<?php

class OrderModel extends Model {
    protected $table = 'orders';
    protected $primaryKey = 'id_order';
    use StaticInstantiator;

}

