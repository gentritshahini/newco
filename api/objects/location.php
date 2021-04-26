<?php
    class Location {
    
        private $conn;
        private $table_name = "locations";
    
        public $id;
        public $city;
        public $created_at;
        public $updated_at;
    
        public function __construct($db){
            $this->conn = $db;
        }

        function getAll(){
            $query = "SELECT * FROM " . $this->table_name;
        
            $stmt = $this->conn->prepare($query);
 
            $stmt->execute();

            return $stmt;
        }
    }
?>