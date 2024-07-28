<?php

class OrderStatusModel extends Model {
    protected $table = 'order_statuses';
    protected $primaryKey = 'id_status';
    use StaticInstantiator;

}

