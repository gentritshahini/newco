<?php
    class Customer {
    
        private $conn;
        private $table_name = "customers";
    
        public $id;
        public $name;
        public $surename;
        public $address;
        public $phone_number;
        public $created_at;
        public $updated_at;
    
        public function __construct($db){
            $this->conn = $db;
        }

        // get all customers
        function getAll(){
            $query = "SELECT * FROM " . $this->table_name . " ORDER BY created_at ASC";
        
            $stmt = $this->conn->prepare($query);
 
            $stmt->execute();

            return $stmt;
        }
    
        // get customer by ID
        function get($id) {
            $query = "SELECT * FROM " . $this->table_name . " WHERE id =" . $id;
        
            $stmt = $this->conn->prepare($query);
        
            $stmt->execute();
        
            return $stmt;
        }

        // show products of a client
        function getProducts($id) {
            $query = "SELECT products.`*` FROM customers
            INNER JOIN orders ON orders.customer_id = customers.id 
            INNER JOIN products ON orders.product_id = products.id
            WHERE customers.id = ". $id ."
            GROUP BY products.id";
        
            $stmt = $this->conn->prepare($query);
        
            $stmt->execute();
        
            return $stmt;
        }

        // show services of a client
        function getServices($id) {
            $query = "SELECT services.`*` FROM customers
            INNER JOIN orders ON orders.customer_id = customers.id 
            INNER JOIN products ON orders.product_id = products.id
            INNER JOIN product_services ON product_services.product_id = products.id 
            INNER JOIN services ON services.id = product_services.service_id
            WHERE customers.id = ". $id ."
            GROUP BY services.id";
        
            $stmt = $this->conn->prepare($query);
        
            $stmt->execute();
                
             return $stmt;
        }

        // update customer
        function update($data) {
            $query = "UPDATE " . $this->table_name . " SET name = '". $data->name ."', surename = '". $data->surename ."', address = '". $data->address ."', phone_number = '". $data->phone_number ."' WHERE id = ". (int)$data->id;

            $stmt = $this->conn->prepare($query);
        
            $stmt->execute();
        
            return $stmt;
        }

        // create customer
        function create($data) {
            $query = "CALL createCustomer('". $data->name ."', '". $data->surename ."', '". $data->address ."', '". $data->phone_number ."')";
           
            $stmt = $this->conn->prepare($query);
        
            $stmt->execute();
        
            return $stmt;
        }

        // delete customer
        function delete($id) {
            $query = "DELETE FROM ". $this->table_name ." WHERE id = ". $id;
            
            $stmt = $this->conn->prepare($query);
        
            $stmt->execute();
        
            return $stmt;
        }
    }
?>