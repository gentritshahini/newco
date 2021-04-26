<?php
    class Product {
    
        private $conn;
        private $table_name = "products";
    
        public $id;
        public $name;
        public $description;
        public $validity;
        public $state;
        public $created_at;
        public $updated_at;
    
        public function __construct($db){
            $this->conn = $db;
        }

        // get all products
        function getAll() {
            $query = "SELECT * FROM " . $this->table_name . " ORDER BY created_at ASC";
        
            $stmt = $this->conn->prepare($query);
        
            $stmt->execute();
        
            return $stmt;
        }

        // get all products active
        function getAllActive() {
            $query = "SELECT * FROM ". $this->table_name ." INNER JOIN product_services ON product_services.product_id = ". $this->table_name .".id WHERE "
            . $this->table_name .".state = 1 AND ". $this->table_name .".validity > '". date('Y-m-d H:i:s') ."' ORDER BY ". $this->table_name .".created_at ASC";
        
            $stmt = $this->conn->prepare($query);
        
            $stmt->execute();
        
            return $stmt;
        }

        // get product by ID
        function get($id) {
            $query = "SELECT * FROM " . $this->table_name . " WHERE id =" . $id;
        
            $stmt = $this->conn->prepare($query);
        
            $stmt->execute();
        
            return $stmt;
        }

        // show product sales
        function sales($id) {
            $query = "SELECT ". $this->table_name .".*, orders.*, assistants.name as assistant_name, assistants.surename as assistant_surename, customers.name as customer_name, customers.surename as customer_surename 
            FROM ". $this->table_name  ." INNER JOIN orders ON products.id = orders.product_id 
            INNER JOIN customers ON customers.id = orders.customer_id INNER JOIN assistants ON assistants.id = orders.assistant_id WHERE ". $this->table_name .".id = ". (int)$id;
  
            $stmt = $this->conn->prepare($query);

            $stmt->execute();

            return $stmt;
        }
        
        // show products out of stock
        function outOfStock() {
            $query = "SELECT * FROM ". $this->table_name  ." WHERE state = 0 OR validity < '". date('Y-m-d H:i:s') ."'";
            
            $stmt = $this->conn->prepare($query);

            $stmt->execute();

            return $stmt;
        }

        // update product
        function update($data) {
            $query = "UPDATE " . $this->table_name . " SET name = '". $data->name ."', description = '". $data->description ."', validity = '". $data->validity ."', state = ". (($data->state) ? 1 : 0) ." WHERE id = ". (int)$data->id;

            $stmt = $this->conn->prepare($query);
        
            $stmt->execute();
        
            return $stmt;
        }

        // create product
        function create($data) {
            $query = "CALL createProduct('". $data->name ."', '". $data->description ."', '". $data->validity ."', ". (($data->state) ? 1 : 0) .")";
            
            $stmt = $this->conn->prepare($query);
        
            $stmt->execute();
        
            return $stmt;
        }

        // delete product
        function delete($id) {
            $query = "DELETE FROM ". $this->table_name ." WHERE id = ". $id;
            
            $stmt = $this->conn->prepare($query);
        
            $stmt->execute();
        
            return $stmt;
        }

        // get product price
        function price($id) {
            $query = "SELECT SUM(services.price) AS price FROM ". $this->table_name ." INNER JOIN product_services ON product_services.product_id = ". $this->table_name .".id INNER JOIN services ON services.id = product_services.service_id WHERE ". $this->table_name .".id = " . $id;
            
            $stmt = $this->conn->prepare($query);
        
            $stmt->execute();
            
            return $stmt;
        }

        // update product state
        function updateState($data) {
            $query = "CALL updateProductState(". (int)$data->id .", ". (int)$data->state .")";

            $stmt = $this->conn->prepare($query);
        
            $stmt->execute();
        
            return $stmt;
        }

        // get product services
        function getservices($id) {
            $query = "SELECT services.* FROM ". $this->table_name ." INNER JOIN product_services ON product_services.product_id = ". $this->table_name .".id INNER JOIN services ON services.id = product_services.service_id WHERE ". $this->table_name .".id = ". $id;

            $stmt = $this->conn->prepare($query);
        
            $stmt->execute();
        
            return $stmt;
        }
    }
?>