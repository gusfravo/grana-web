<?php

// Volcamos a un objeto nativo de PHP
$objDatos = json_decode(file_get_contents("php://input"));

// Instancia a la conexión de la base de datos
include_once '../utility/database.php';
$database = new Database();
$db = $database->getConnection();

// Instancia a object Departmen
include_once 'object.php';
$categoryProduct = new CategoryProduct($db);

// Enviamos el parametro del dato a eliminar
$categoryProduct->id = $objDatos->id;

// Invocamos la eliminación de la base de datos
if ($categoryProduct->delete()) {
  echo json_encode(array("transaction" => "ok", "message" => "Se elimino el registro correctamente", "code" => "categoryProduct:succes:004"));
} else {
  echo json_encode(array("transaction" => "bad", "message" => "Error al eliminar", "code" => "categoryProduct:error:003"));
}
?>
