<?php

// Volcamos a un objeto nativo de PHP
$objDatos = json_decode(file_get_contents("php://input"));

// Instancia a la conexión de la base de datos
include_once '../utility/database.php';
$database = new Database();
$db = $database->getConnection();

// Instancia a object Departmen
include_once 'object.php';
$product = new Product($db);

// Enviamos el parametro a obtener los datos
$product->id = $objDatos->id;

// Invocamos el obtener los datos de la base de datos
$product->get();

// Obtemos los datos del departmen

// Retornamos los datos
echo json_encode(array("transaction" => "ok", "object" => array("id"=>$product->id, "name" => $product->name, "price" => $product->price, "color" => $product->color, "description" => $product->description, "measurements" => $product->measurements, "region" => $product->region, "technique" => $product->technique, "town" => $product->town), "message" => "Se obtuvieron los datos correctamente", "code" => "process:succes:003"));
?>
