<?php

// Volcamos a un objeto nativo de PHP
$objDatos = json_decode(file_get_contents("php://input"));

// Instancia a la conexión de la base de datos
include_once '../utility/database.php';
$database = new Database();
$db = $database->getConnection();

// Instancia a object Departmen
include_once 'object.php';
$category = new Category($db);

// Enviamos el parametro a obtener los datos
$category->id = $objDatos->id;

// Invocamos el obtener los datos de la base de datos
$category->get();

// Obtemos los datos del departmen

// Retornamos los datos
echo json_encode(array("transaction" => "ok", "object" => array("id"=>$category->id, "name" => $category->name, "description" => $category->description, "file" => $category->file), "message" => "Se obtuvieron los datos correctamente", "code" => "process:succes:003"));
?>
