<?php
    class Service {
        private $conn;
        private $table_name = "services";
    
        public $id;
        public $description;
        public $price;
        public $active;
        public $created_at;
        public $updated_at;
    
        public function __construct($db){
            $this->conn = $db;
        }

        // get all services
        function getAll() {

            $query = "SELECT * FROM " . $this->table_name . " ORDER BY created_at ASC";
        
            $stmt = $this->conn->prepare($query);
        
            $stmt->execute();
        
            return $stmt;
        }

        // get service by ID
        function get($id) {
            $query = "SELECT * FROM " . $this->table_name . " WHERE id =" . (int)$id;

            $stmt = $this->conn->prepare($query);

            $stmt->execute();
        
            return $stmt;
        }

        // show top sold services
        function topSold() {
            $query = "SELECT services.id, services.description, SUM(services.price*orders.amount) AS income FROM services 
            INNER JOIN product_services ON product_services.service_id = services.id 
            INNER JOIN products ON product_services.product_id = products.id
            INNER JOIN orders ON orders.product_id = products.id 
            GROUP BY services.id
            ORDER BY income DESC";
        
            $stmt = $this->conn->prepare($query);
        
            $stmt->execute();
        
            return $stmt;
        }

        // create service
        function create($data) {
            $query = "CALL createService('". $data->description ."', '". (float)$data->price ."', ". (($data->active) ? 1 : 0) .")";

            $stmt = $this->conn->prepare($query);
        
            $stmt->execute();
        
            return $stmt;
        }

        // update service
        function update($data) {
            $query = "UPDATE " . $this->table_name . " SET description = '". $data->description ."', price = ". (float)$data->price .", active = ". (($data->active) ? 1 : 0) ." WHERE id = ". (int)$data->id;
   
            $stmt = $this->conn->prepare($query);
        
            $stmt->execute();
        
            return $stmt;
        }

        // delete service
        function delete($id) {
            $query = "DELETE FROM ". $this->table_name ." WHERE id = ". $id;
            
            $stmt = $this->conn->prepare($query);
        
            $stmt->execute();
        
            return $stmt;
        }

        // add service to specific product
        function addProduct($data) {
            $query = "CALL addProductService(". (int)$data->product_id .", ". (int)$data->id .")";
        
            $stmt = $this->conn->prepare($query);
 
            $stmt->execute();

            return $stmt;
        }

        // remove service from specific product
        function removeProduct($data) {
            $query = "CALL removeProductService(". (int)$data->product_id .", ". (int)$data->id .")";
        
            $stmt = $this->conn->prepare($query);
 
            $stmt->execute();

            return $stmt;
        }

        // update status
        function updateStatus($data) {
            $query = "CALL updateServiceStatus(". (int)$data->id .", ". (int)$data->status .")";
        
            $stmt = $this->conn->prepare($query);
 
            $stmt->execute();

            return $stmt;
        }
    }
?>