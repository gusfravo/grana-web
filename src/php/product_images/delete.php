<?php

// Volcamos a un objeto nativo de PHP
$objDatos = json_decode(file_get_contents("php://input"));

// Instancia a la conexión de la base de datos
include_once '../utility/database.php';
$database = new Database();
$db = $database->getConnection();

// Instancia a object Departmen
include_once 'object.php';
$productImages = new ProductImages($db);

// Enviamos el parametro del dato a eliminar
$productImages->id = $objDatos->id;

// Invocamos la eliminación de la base de datos
if ($productImages->delete()) {
  echo json_encode(array("transaction" => "ok", "message" => "Se elimino el registro correctamente", "code" => "productImages:succes:004"));
} else {
  echo json_encode(array("transaction" => "bad", "message" => "Error al eliminar", "code" => "productImages:error:003"));
}
?>
