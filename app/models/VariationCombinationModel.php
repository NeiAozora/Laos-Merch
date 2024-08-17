<?php

class VariationCombinationModel extends Model {
    protected $table = 'variation_combinations';
    protected $primaryKey = 'id_combination';
    use StaticInstantiator;

    public function updateVariationCombination($id_combination, $price, $stock)
    {
        // Initialize the database
        $db = new Database();

        // Prepare the SQL query
        $query = "UPDATE {$this->table} 
                  SET price = :price, stock = :stock 
                  WHERE {$this->primaryKey} = :id_combination";

        // Bind parameters
        $db->query($query);
        $db->bind(':id_combination', $id_combination, PDO::PARAM_INT);
        $db->bind(':price', $price, PDO::PARAM_STR);  // PDO::PARAM_STR is fine for DECIMAL types
        $db->bind(':stock', $stock, PDO::PARAM_INT);

        // Execute the query
        return $db->execute();
    }
}


