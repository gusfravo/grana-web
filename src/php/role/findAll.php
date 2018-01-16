<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// Instancia a la conexión de la base de datos
include_once '../utility/database.php';
$database = new Database();
$db = $database->getConnection();

// Instancia a object Departmen
include_once 'object.php';
$role = new Role($db);

// Invocamos el query de obtener todos lo elementos
$stmt = $role->findAll();
$num = $stmt->rowCount();

$output = array();

// Validamos si hay datos encontrados
if($num>0){
  // Armamos la tabla de contenido a listar en objeto JSON
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
    // Recorremos cada registro
    // Asi accedemos a cada registro $row['name']
    $status = (ord ( $row['s_status'] ) == 1) ? true : false;
    $output[] = array("id" => $row['id'], "role" => $row['rolename']);
  }
}

echo json_encode(array("transaction" => "ok", "object" => array("list" => $output, "total" => $num), "message" => "Se obtuvieron los datos correctamente", "code" => "role:succes:002"));
?>
