<?php

// Volcamos a un objeto nativo de PHP
$objDatos = json_decode(file_get_contents("php://input"));

// Instancia a la conexión de la base de datos
include_once '../utility/database.php';
$database = new Database();
$db = $database->getConnection();

// Instancia a object Departmen
include_once 'object.php';
include_once '../product_images/object.php';
include_once '../category_product/object.php';
$product = new Product($db);
$product->id  = $objDatos->id;

$categoryProduct = new CategoryProduct($db);
// Enviamos el parametro del dato a eliminar
$categoryProduct->product_id = $product->id;
$cPList = $categoryProduct->findAllByProduct($categoryProduct->product_id);
$numCP = $cPList->rowCount();
if($numCP>0){
  // Armamos la tabla de contenido a listar en objeto JSON
  while ($rowCP = $cPList->fetch(PDO::FETCH_ASSOC)){
    // obetenmos la instancia de categorias
    $categoryProductAux = new CategoryProduct($db);
    $categoryProductAux->id = $rowCP['id'];
    $categoryProductAux->delete();
    // Recorremos cada registro
  }
}

$productImages = new ProductImages($db);
// Enviamos el parametro del dato a eliminar
$productImages->product_id = $product->id;
$pIList = $productImages->findAllByProduct($productImages->product_id);
$numPI = $pIList->rowCount();
if($numPI>0){
  // Armamos la tabla de contenido a listar en objeto JSON
  while ($rowPI = $pIList->fetch(PDO::FETCH_ASSOC)){
    // obetenmos la instancia de categorias
    $productImagesAux = new ProductImages($db);
    $productImagesAux->id = $rowPI['id'];
    $productImagesAux->delete();
    // Recorremos cada registro
  }
}

// Invocamos la eliminación de la base de datos
if ($product->delete()) {
  echo json_encode(array("transaction" => "ok", "message" => "Se elimino el registro correctamente", "code" => "product:succes:004"));
} else {
  echo json_encode(array("transaction" => "bad", "message" => "Error al eliminar", "code" => "categoryProduct:error:003"));
}
?>
