<?php
// Volcamos a un objeto nativo de PHP
$objDatos = json_decode(file_get_contents("php://input"));
include_once '../utility/database.php';

$database = new Database();
$db = $database->getConnection();

// Instancia a object bidding
include_once 'object.php';
$product = new Product($db);
try {
  // Inicializamos el objeto
  $product->id = $objDatos->id;
  $product->name = $objDatos->name;
  $product->description = $objDatos->description;
  $product->technique = $objDatos->technique;
  $product->measurements = $objDatos->measurements;
  $product->town = $objDatos->town;
  $product->region = $objDatos->region;
  $product->color = $objDatos->color;
  $product->price = $objDatos->price;
  $product->update_date = date('Y-m-d H:i:s');

  // Validamos si es un update
  if ( empty($objDatos->id) ) {
    $product->id = genUuid();
    $product->create_date = date('Y-m-d H:i:s');
    // Creamos $product
    if ($product->create()) {
      echo json_encode(array("transaction" => "ok", "object" => array("id"=>$product->id), "message" => "El procedimiento se creo correctamente", "code" => "product:succes:001"));
    }
    else {
      echo json_encode(array("transaction" => "bad", "message" => "Error al crear el procedimiento", "code" => "product:error:001"));
    }
  } else {
    $product->id = $objDatos->id;
    // Actualizamos procedimiento
    if($product->update()){
      echo json_encode(array("transaction" => "ok", "object" => array("id"=>$product->id) ,"message" => "El procedimiento se actualizo correctamente", "code" => "product:succes:002"));
    }
    else {
      echo json_encode(array("transaction" => "bad", "message" => "Error al actualizar el procedimiento", "code" => "product:error:002"));
    }
  }
} catch (Exception $e) {
  echo json_encode(array("transaction" => "bad", "message" =>  $e->getMessage(), "code" => "system:error:001"));
}
function genUuid() {
  return sprintf( '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
    // 32 bits for "time_low"
    mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ),

    // 16 bits for "time_mid"
    mt_rand( 0, 0xffff ),

    // 16 bits for "time_hi_and_version",
    // four most significant bits holds version number 4
    mt_rand( 0, 0x0fff ) | 0x4000,

    // 16 bits, 8 bits for "clk_seq_hi_res",
    // 8 bits for "clk_seq_low",
    // two most significant bits holds zero and one for variant DCE1.1
    mt_rand( 0, 0x3fff ) | 0x8000,

    // 48 bits for "node"
    mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff )
  );
}
?>
