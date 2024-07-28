<?php

class DiscountProductModel extends Model {
    protected $table = 'discount_products';
    protected $primaryKey = '';
    use StaticInstantiator;

    public function getDiscountByProductId($id){
        $this->db->query('SELECT *
        FROM discounts AS d
        LEFT JOIN discount_types AS dt ON d.id_discount_type = dt.id_discount_type
        LEFT JOIN discount_products AS dp ON d.id_discount = dp.id_discount
        LEFT JOIN products AS p ON dp.id_product = p.id_product
        WHERE p.id_product = :id_product
        AND d.end_date > CURRENT_DATE;');

        $this->db->bind(":id_product", $id, PDO::PARAM_INT);
        return $this->db->single();
    }

}

