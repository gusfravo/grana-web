<?php
// Volcamos a un objeto nativo de PHP
$objDatos = json_decode(file_get_contents("php://input"));
include_once '../utility/database.php';

$database = new Database();
$db = $database->getConnection();

// Instancia a object bidding
include_once 'object.php';
$productImages = new ProductImages($db);
try {
  // Inicializamos el objeto
  $productImages->id = $objDatos->id;
  $productImages->image = $objDatos->image;
  $productImages->product_id = $objDatos->product->id;

  // Validamos si es un update
  if ( empty($objDatos->id) ) {
    $productImages->id = genUuid();
    $productImages->create_date = date('Y-m-d H:i:s');
    // Creamos $productImages
    if ($productImages->create()) {
      echo json_encode(array("transaction" => "ok", "object"=>$productImages ,"message" => "El procedimiento se creo correctamente", "code" => "productImages:succes:001"));
    }
    else {
      echo json_encode(array("transaction" => "bad", "message" => "Error al crear el procedimiento", "code" => "productImages:error:001"));
    }
  } else {
    $productImages->id = $objDatos->id;
    // Actualizamos procedimiento
    if($productImages->update()){
      echo json_encode(array("transaction" => "ok", "message" => "El procedimiento se actualizo correctamente", "code" => "productImages:succes:002"));
    }
    else {
      echo json_encode(array("transaction" => "bad", "message" => "Error al actualizar el procedimiento", "code" => "productImages:error:002"));
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