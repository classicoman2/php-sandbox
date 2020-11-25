<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// include database and object files
include_once 'config/database.php';

// instantiate database and product object
$database = new Database();
$conn = $database->getConnection();

if (isset($_GET['table'])) {
    if ($_GET['table'] == 'clients')
        echo getClients($conn);
}

/**  Identificar quin tipus d'operació és */



function getClients($conn)
{

    // select all query
    $query = "SELECT * FROM clients";
    // prepare query statement
    $stmt = $conn->prepare($query);

    // execute query
    $stmt->execute();

    $num = $stmt->rowCount();;

    // check if more than 0 record found
    if ($num > 0) {

        // products array
        $products_arr = array();
        $products_arr["records"] = array();

        // retrieve our table contents
        // fetch() is faster than fetchAll()
        // http://stackoverflow.com/questions/2770630/pdofetchall-vs-pdofetch-in-a-loop
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            // extract row
            // this will make $row['name'] to
            // just $name only, etc
            // https://www.php.net/manual/en/function.extract.php
            extract($row);

            $product_item = array(
                "id" => $id,
                "name" => $name,
                "surname" => $surname,
                "age" => $age
            );

            array_push($products_arr["records"], $product_item);
        }

        // set response code - 200 OK
        http_response_code(200);

        // show products data in json format
        return json_encode($products_arr);
    }

    // no products found will be here
    else {

        // set response code - 404 Not found
        http_response_code(404);

        // tell the user no products found
        return json_encode(
            array("message" => "No products found.")
        );
    }
}
/**   Fer l'operacio amb Base de Dades */
