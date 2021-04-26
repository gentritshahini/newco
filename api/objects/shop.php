<?php
    class Shop {
        private $conn;
        private $table_name = "shops";
    
        public $id;
        public $name;
        public $location_id;
        public $created_at;
        public $updated_at;
    
        public function __construct($db){
            $this->conn = $db;
        }

        // get all shops with location
        function getAll(){
            $query = "SELECT ". $this->table_name .".*, locations.city as location FROM " . $this->table_name . " INNER JOIN locations ON locations.id = ". $this->table_name .".location_id ORDER BY shops.created_at ASC";
        
            $stmt = $this->conn->prepare($query);
 
            $stmt->execute();

            return $stmt;
        }
    
        // get shop by ID
        function get($id) {
            $query = "SELECT ". $this->table_name .".*, locations.city as location FROM " . $this->table_name . " INNER JOIN locations ON locations.id = ". $this->table_name .".location_id WHERE " . $this->table_name . ".id =" . (int)$id;

            $stmt = $this->conn->prepare($query);

            $stmt->execute();
        
            return $stmt;
        }

        // get sales of specific shop
        function getSales($id) {
            $query = "SELECT orders.*, products.name as product, customers.name as customer_name, customers.surename as customer_surename, assistants.name as assistant_name, assistants.surename as assistant_surename FROM " . $this->table_name . "
            INNER JOIN assistants ON assistants.shop_id = " . $this->table_name . ".id
            INNER JOIN orders ON orders.assistant_id = assistants.id
            INNER JOIN products ON orders.product_id = products.id
            INNER JOIN customers ON orders.customer_id = customers.id
            WHERE " . $this->table_name . ".id = ". $id;

            $stmt = $this->conn->prepare($query);
        
            $stmt->execute();
        
            return $stmt;
        }

        // update shop
        function update($data) {
            $query = "UPDATE " . $this->table_name . " SET name = '". $data->name ."', location_id = ". (int)$data->location_id ." WHERE id = ". (int)$data->id;

            $stmt = $this->conn->prepare($query);
        
            $stmt->execute();
        
            return $stmt;
        }

        // create shop
        function create($data) {
            $query = "CALL createShop('". $data->name ."', ". (int)$data->location_id .")";

            $stmt = $this->conn->prepare($query);
        
            $stmt->execute();
        
            return $stmt;
        }

        // delete shop
        function delete($id) {
            $query = "DELETE FROM ". $this->table_name ." WHERE id = ". $id;
            
            $stmt = $this->conn->prepare($query);
        
            $stmt->execute();
        
            return $stmt;
        }
    }
?>