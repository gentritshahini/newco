<?php
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    $method = $_SERVER['REQUEST_METHOD'];

    if ($method != 'GET') {
        http_response_code(400);
        echo json_encode(
            array("message" => "Method ". $method ." not supported.")
        );
        die();
    }

    // validation of parameter
    if (isset($_GET['id'])) {
        $customer_id = $_GET['id'];
        if (!is_numeric($customer_id)) {
            http_response_code(404);
            echo json_encode(
                array("message" => "Parameter id must be number.")
            );
            die();
        }

    } else {
        http_response_code(404);
        echo json_encode(
            array("message" => "Parameter id is required.")
        );
        die();
    }

    include_once '../config/database.php';
    include_once '../objects/customer.php';
    
    $database = new Database();
    $db = $database->getConnection();
    
    $customer = new Customer($db);

    $stmt = $customer->getProducts($customer_id);
    $num = $stmt->rowCount();

    if ($num > 0) {
    
        $products_arr = array();
    
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            extract($row);
    
            $product_item=array(
                "id"            => $id,
                "name"          => $name,
                "description"   => $description,
                "validity"      => date('H:i d M Y', strtotime($validity)),
                "state"         => $state,
                "created_at"    => date('H:i d M Y', strtotime($created_at)),
                "updated_at"    => $updated_at ? date('H:i d M Y', strtotime($updated_at)) : '-'
            );
    
            array_push($products_arr, $product_item);
        }
    
        http_response_code(200);
    
        echo json_encode($products_arr);
    } else {
        http_response_code(404);

        echo json_encode(
            array("message" => "No products found for customer found.")
        );
    }