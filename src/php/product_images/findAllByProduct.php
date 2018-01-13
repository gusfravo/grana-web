<?php
$objDatos = json_decode(file_get_contents("php://input"));

// Incluimos los archivos de base de datos y objeto
include_once '../utility/database.php';
include_once 'object.php';
// Instancia de base de datos y objetos
$database = new Database();
$db = $database->getConnection();

// Inicializamos el objecto
$productImages = new ProductImages($db);

$productImages->product_id = $objDatos->product->id;
// Invocamos el query de obtener todos lo elementos
$stmt = $productImages->findAllByProduct($productImages->product_id);
$num = $stmt->rowCount();

$output = array();

// Validamos si hay datos encontrados
if($num>0){
  // Armamos la tabla de contenido a listar en objeto JSON
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
    // Recorremos cada registro
    $output[] = array("id" => $row['id'], "product"=>array("id" => $row['product_id']), "image" => $row['image']);
  }
}

echo json_encode(array("transaction" => "ok", "object" => array("list" => $output, "total" => $num), "message" => "Se obtuvieron los datos correctamente", "code" => "product:succes:002"));
?>
