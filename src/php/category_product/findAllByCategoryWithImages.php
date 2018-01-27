<?php
$objDatos = json_decode(file_get_contents("php://input"));

// Incluimos los archivos de base de datos y objeto
include_once '../utility/database.php';
include_once 'object.php';
include_once '../product/object.php';
include_once '../product_images/object.php';
// Instancia de base de datos y objetos
$database = new Database();
$db = $database->getConnection();

// Inicializamos el objecto
$categoryProduct = new CategoryProduct($db);

$categoryProduct->category_id = $objDatos->category->id;
// Invocamos el query de obtener todos lo elementos
$stmt = $categoryProduct->findAllByCategory($categoryProduct->category_id);
$num = $stmt->rowCount();

$output = array();

// Validamos si hay datos encontrados
if($num>0){
  // Armamos la tabla de contenido a listar en objeto JSON
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
    // obetenmos la instancia de categorias
    $product = new Product($db);
    $product->id = $row['product_id'];
    $product->get();
    //Buscamos la images de cada producto.
    $productImages = new ProductImages($db);
    $productImages->product_id = $product->id;
    // Invocamos el query de obtener todos lo elementos
    $stmtImages = $productImages->findAllByProduct($product->id);
    $numImages = $stmtImages->rowCount();
    $images = array();

    // Validamos si hay datos encontrados
    if($numImages>0){
      // Armamos la tabla de contenido a listar en objeto JSON
      while ($rowImages = $stmtImages->fetch(PDO::FETCH_ASSOC)){
        // Recorremos cada registro
        $images[] = array("id" => $rowImages['id'], "product"=>array("id" => $rowImages['product_id']), "image" => $rowImages['image']);
      }
    }
    //
    // Recorremos cada registro
    $output[] = array("id" => $row['id'], "category"=>array("id" => $row['category_id']), "product"=>array("id"=>$row['product_id'], "name" =>$product->name, "description" =>$product->description, "technique" =>$product->technique, "measurements" =>$product->measurements, "town" =>$product->town, "region" =>$product->region, "price" =>$product->price, "color"=>$product->color, "images" => $images));
  }
}

echo json_encode(array("transaction" => "ok", "object" => array("list" => $output, "total" => $num), "message" => "Se obtuvieron los datos correctamente", "code" => "product:succes:002"));
?>
