<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// include database and object files
include_once 'config/database.php';

// instantiate database and product object
$database = new Database();
$conn = $database->getConnection();


// ROUTER   xtoni --> hauria de comprovar el path amb expressions regulars !

/**  Identificar quin tipus d'operació és */
// https://restfulapi.net/rest-put-vs-post/

/*
  GET 	  /device-management/devices : Get all devices
  POST 	  /device-management/devices : Create a new device

  GET 	  /device-management/devices/{id} : Get the device information identified by "id"
  PUT 	  /device-management/devices/{id} : Update the device information identified by "id"
  DELETE  /device-management/devices/{id} : Delete device by "id"

  Follow similar URI design practices for other resources as well.
*/

if (isset($_GET['table'])) {
  if ($_GET['table'] == 'cyclists') {
    if ($_SERVER['REQUEST_METHOD'] == "GET") {

      // 1 cyclist
      if (isset($_GET['id'])) {
        echo getCyclist($conn, $_GET['id']);
      } else {
        // All Cyclists
        echo getCyclists($conn);
      }
    }
    if ($_SERVER['REQUEST_METHOD'] == "POST") {
      echo createClient($conn);
    }
  }
}

/*
if (isset($_GET['client'])) {

  $id = $_GET['client'];

  if ($_SERVER['REQUEST_METHOD'] == "GET") {
    echo getCyclist($conn, $id);
  }
  if ($_SERVER['REQUEST_METHOD'] == "DELETE") {
    echo deleteClient($conn, $id);
  }
  if ($_SERVER['REQUEST_METHOD'] == "PUT") {
    echo updateClient($conn, $id);
  }
}
*/

function updateClient($conn, $id)
{
  // required headers
  header("Access-Control-Allow-Origin: *");
  header("Content-Type: application/json; charset=UTF-8");
  header("Access-Control-Allow-Methods: POST");
  header("Access-Control-Max-Age: 3600");
  header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

  // get posted data
  $data = json_decode(file_get_contents("php://input"));

  // make sure data is not empty
  if (
    !empty($data->name) &&
    !empty($data->surname) &&
    !empty($data->age)
  ) {
    $name = $data->name;
    $surname = $data->surname;
    $age = $data->age;

    // prepare & execute query statement
    $stmt = $conn->prepare("UPDATE `clients` SET `name`='$name',`surname`='$surname',`age`=$age WHERE `id`=$id");
    $result = $stmt->execute();

    if ($result) { // ha anat be
      // set response code - 201 created
      http_response_code(201);

      // tell the user
      echo json_encode(array("message" => "Client was updated."));
    }

    // if unable to create the product, tell the user
    else {

      // set response code - 503 service unavailable
      http_response_code(503);

      // tell the user
      echo json_encode(array("message" => $stmt->errorInfo()));
    }
  } else {

    // set response code - 400 bad request
    http_response_code(400);

    // tell the user
    echo json_encode(array("message" => "Unable to update product. Data is incomplete."));
  }
}

function deleteClient($conn, $id)
{
  // required headers
  header("Access-Control-Allow-Origin: *");
  header("Content-Type: application/json; charset=UTF-8");
  header("Access-Control-Allow-Methods: POST");
  header("Access-Control-Max-Age: 3600");
  header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

  // prepare & execute query statement
  $stmt = $conn->prepare("DELETE FROM `clients` WHERE `clients`.`id` = $id");
  $result = $stmt->execute();

  if ($result) { // ha anat be
    // set response code - 200 ok
    http_response_code(200);

    // tell the user
    echo json_encode(array("message" => "Client was deleted."));
  }

  // if unable to delete the product
  else {
    // set response code - 503 service unavailable
    http_response_code(503);

    // tell the user
    echo json_encode(array("message" => "Unable to delete client."));
  }
}


function createClient($conn)
{
  // required headers
  header("Access-Control-Allow-Origin: *");
  header("Content-Type: application/json; charset=UTF-8");
  header("Access-Control-Allow-Methods: POST");
  header("Access-Control-Max-Age: 3600");
  header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");


  // get posted data
  $data = json_decode(file_get_contents("php://input"));

  // make sure data is not empty
  if (
    !empty($data->name) &&
    !empty($data->surname) &&
    !empty($data->age)
  ) {
    $name = $data->name;
    $surname = $data->surname;
    $age = $data->age;


    // prepare & execute query statement
    $stmt = $conn->prepare("INSERT INTO clients (`name`,  `surname`, `age`) VALUES ('$name', '$surname', '$age')");
    $result = $stmt->execute();

    if ($result) { // ha anat be
      // set response code - 201 created
      http_response_code(201);

      // tell the user
      echo json_encode(array("message" => "Client was created."));
    }

    // if unable to create the product, tell the user
    else {

      // set response code - 503 service unavailable
      http_response_code(503);

      // tell the user
      echo json_encode(array("message" => $stmt->errorInfo()));
    }
  } else {

    // set response code - 400 bad request
    http_response_code(400);

    // tell the user
    echo json_encode(array("message" => "Unable to create product. Data is incomplete."));
  }
}



function getCyclist($conn, $id)
{
  // required headers
  header("Access-Control-Allow-Origin: *");
  header("Content-Type: application/json; charset=UTF-8");

  // prepare & execute query statement
  $stmt = $conn->prepare("SELECT * FROM cyclists WHERE id=$id");
  $stmt->execute();

  $num = $stmt->rowCount();;

  if ($num > 0) {

    // retrieve our table contents
    // fetch() is faster than fetchAll()
    // http://stackoverflow.com/questions/2770630/pdofetchall-vs-pdofetch-in-a-loop
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
      // extract row
      // this will make $row['name'] to just $name only, etc
      // https://www.php.net/manual/en/function.extract.php
      extract($row);

      $client_item = array(
        "id" => $id,
        "name" => $name
      );
    }

    // set response code - 200 OK
    http_response_code(200);

    // show clients data in json format
    return json_encode($client_item);
  } else {

    // set response code - 404 Not found
    http_response_code(404);

    // tell the user no clients found
    return json_encode(
      array("message" => "No clients found.")
    );
  }
}


function getCyclists($conn)
{

  // required headers
  header("Access-Control-Allow-Origin: *");
  header("Content-Type: application/json; charset=UTF-8");


  // select all query
  $query = "SELECT * FROM cyclists";
  // prepare query statement
  $stmt = $conn->prepare($query);

  // execute query
  $stmt->execute();

  $num = $stmt->rowCount();;

  // check if more than 0 record found
  if ($num > 0) {

    // clients array
    $clients_arr = array();
    $clients_arr["records"] = array();

    // retrieve our table contents
    // fetch() is faster than fetchAll()
    // http://stackoverflow.com/questions/2770630/pdofetchall-vs-pdofetch-in-a-loop
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
      // extract row
      // this will make $row['name'] to
      // just $name only, etc
      // https://www.php.net/manual/en/function.extract.php
      extract($row);

      $client_item = array(
        "id" => $id,
        "name" => $name
      );

      array_push($clients_arr["records"], $client_item);
    }

    // set response code - 200 OK
    http_response_code(200);

    // show clients data in json format
    return json_encode($clients_arr);
  }

  // no clients found will be here
  else {

    // set response code - 404 Not found
    http_response_code(404);

    // tell the user no clients found
    return json_encode(
      array("message" => "No clients found.")
    );
  }
}
/**   Fer l'operacio amb Base de Dades */
