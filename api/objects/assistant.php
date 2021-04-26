<?php
    class Assistant {
    
        private $conn;
        private $table_name = "assistants";
    
        public $id;
        public $name;
        public $surename;
        public $shop_id;
        public $created_at;
        public $updated_at;
    
        public function __construct($db){
            $this->conn = $db;
        }

        // get all assistants
        function getAll(){
            $query = "SELECT ". $this->table_name .".*, shops.name as shop FROM " . $this->table_name . " INNER JOIN shops ON shops.id = ". $this->table_name  .".shop_id";
        
            $stmt = $this->conn->prepare($query);
 
            $stmt->execute();

            return $stmt;
        }
    
        // get assistant by ID
        function get($id) {
            $query = "SELECT ". $this->table_name .".*, shops.name as shop FROM " . $this->table_name . " INNER JOIN shops ON shops.id = ". $this->table_name  .".shop_id WHERE ". $this->table_name .".id = " . (int)$id;
           
            $stmt = $this->conn->prepare($query);
        
            $stmt->execute();
        
            return $stmt;
        }

        // get sales of an assistant
        function getSales($id) {
            $query = "SELECT orders.*, products.name as product, customers.name as customer_name, customers.surename as customer_surename FROM ". $this->table_name ."
            INNER JOIN orders ON orders.assistant_id = ". $this->table_name .".id
            INNER JOIN customers ON customers.id = orders.customer_id
            INNER JOIN products ON products.id = orders.product_id
            WHERE ". $this->table_name .".id = ". $id;

            $stmt = $this->conn->prepare($query);
        
            $stmt->execute();
        
            return $stmt;
        }

        // get best assistant
        function getBest() {
            $query = "SELECT ". $this->table_name .".`*`, count(orders.id) AS total_orders, SUM(orders.total) AS total, shops.name as shop FROM ". $this->table_name ."
            INNER JOIN shops ON shops.id = ". $this->table_name .".shop_id
            LEFT JOIN orders ON orders.assistant_id = ". $this->table_name .".id
            GROUP BY ". $this->table_name .".id
            ORDER BY total desc";

            $stmt = $this->conn->prepare($query);
        
            $stmt->execute();
        
            return $stmt;
        }

        // get worst assistant
        function getWorst() {
            $query = "SELECT ". $this->table_name .".`*`, count(orders.id) AS total_orders, SUM(orders.total) AS total, shops.name as shop FROM ". $this->table_name ."
            INNER JOIN shops ON shops.id = ". $this->table_name .".shop_id
            LEFT JOIN orders ON orders.assistant_id = ". $this->table_name .".id
            GROUP BY ". $this->table_name .".id
            ORDER BY total asc";

            $stmt = $this->conn->prepare($query);
        
            $stmt->execute();
        
            return $stmt;
        }

        // create assistant
        function create($data) {
            $query = "CALL createShopAssistant('". $data->name ."', '". $data->surename ."', ". (int)$data->shop_id .")";

            $stmt = $this->conn->prepare($query);
        
            $stmt->execute();
        
            return $stmt;
        }

        // delete assistant
        function delete($id) {
            $query = "DELETE FROM ". $this->table_name ." WHERE id = ". $id;
            
            $stmt = $this->conn->prepare($query);
        
            $stmt->execute();
        
            return $stmt;
        }

        // update assistant
        function update($data) {
            $query = "UPDATE " . $this->table_name . " SET name = '". $data->name ."', surename = '". $data->surename ."', shop_id = ". (int)$data->shop_id ." WHERE id = ". (int)$data->id;

            $stmt = $this->conn->prepare($query);
        
            $stmt->execute();
        
            return $stmt;
        }

        // get assistants not part of specific shop
        function getNotPartOfShop($id) {
            $query = "SELECT * FROM " . $this->table_name . " WHERE shop_id !=". (int)$id;
        
            $stmt = $this->conn->prepare($query);
 
            $stmt->execute();

            return $stmt;
        }

        // update only shop for assistant
        function updateShop($data) {
            $query = "UPDATE " . $this->table_name . " SET shop_id = ". (int)$data->shop_id ." WHERE id = ". (int)$data->id;

            $stmt = $this->conn->prepare($query);
        
            $stmt->execute();
        
            return $stmt;
        }
    }
?>