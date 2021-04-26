<?php
    class Order {
    
        private $conn;
        private $table_name = "orders";
    
        public $id;
        public $customer_id;
        public $product_id;
        public $assistant_id;
        public $price;
        public $amount;
        public $total;
        public $created_at;
    
        public function __construct($db){
            $this->conn = $db;
        }

        // get all orders
        function getAll(){
            $query = "SELECT ". $this->table_name.".*, products.name as product, customers.name as customer_name, customers.surename as customer_surename, 
            assistants.name as assistant_name, assistants.surename as assistant_surename FROM ". $this->table_name 
            ." INNER JOIN products ON products.id = ". $this->table_name 
            .".product_id INNER JOIN customers ON customers.id = ". $this->table_name 
            .".customer_id INNER JOIN assistants ON assistants.id = ". $this->table_name 
            .".assistant_id ORDER BY ". $this->table_name .".created_at ASC";
       
            $stmt = $this->conn->prepare($query);
 
            $stmt->execute();

            return $stmt;
        }

        function create($data, $price) {
            $query = "CALL createOrder(". (int)$data->product_id .", ". (int)$data->customer_id .", ". (int)$data->assistant_id .", ". $price .", ". (int)$data->amount .", ". $price*(int)$data->amount .")";

            $stmt = $this->conn->prepare($query);
 
            $stmt->execute();

            return $stmt;
        }
    }
?>