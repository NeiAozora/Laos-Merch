<?php

class ShippingAddressModel extends Model {
    protected $table = 'shipping_addresses';
    protected $primaryKey = 'id_shipping_address';
    use StaticInstantiator;

}

