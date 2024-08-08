<?php

class PaymentMethodModel extends Model {
    protected $table = 'payment_methods';
    protected $primaryKey = 'id_payment_method';
    use StaticInstantiator;

}

