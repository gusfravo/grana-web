<?php

// Incluimos los archivos de base de datos y objeto
include_once '../utility/database.php';
include_once 'object.php';


// Instancia de base de datos y objetos
$database = new Database();
$db = $database->getConnection();

// Inicializamos el objecto
$category = new Category($db);

// Invocamos el query de obtener todos lo elementos
$stmt = $category->findAll();
$num = $stmt->rowCount();

$output = array();

// Validamos si hay datos encontrados
if($num>0){
  // Armamos la tabla de contenido a listar en objeto JSON
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
    // Recorremos cada registro
    $output[] = array("id" => $row['id'], "name" => $row['name'], 'description' => $row['description'], 'file' => $row['file']);
  }
}

echo json_encode(array("transaction" => "ok", "object" => array("list" => $output, "total" => $num), "message" => "Se obtuvieron los datos correctamente", "code" => "process:succes:002"));
?>
