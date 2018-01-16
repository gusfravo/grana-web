<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// Incluimos los archivos de base de datos y objeto
include_once '../utility/database.php';
include_once '../role/object.php';
include_once 'object.php';
$objDatos = json_decode(file_get_contents("php://input"));
//include_once 'object.php';


// Instancia de base de datos y objetos
$database = new Database();
$db = $database->getConnection();

// Inicializamos el objecto
$role = new Role($db);
/*obtenemos el nombre del rol*/
$role->rolename = $objDatos->system->roleUnit->main->rolename;
// Invocamos el query de obtener el elemento
$role->findByRolename();
//Inicializamos el objeto user
$user = new User($db);
//Obtenemos el uuid del role
//$user->s_role_id = $role->m_uuid;
// Invocamos el query de obtener todos lo elementos
$stmt = $user->findAllByRole($role->m_uuid);
$num = $stmt->rowCount();

$output = array();

// Validamos si hay datos encontrados
if($num>0){
  // Armamos la tabla de contenido a listar en objeto JSON
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
    // Recorremos cada registro
    $status = (ord ( $row['s_status'] ) == 1) ? true : false;
    $output[] = array("main" => array("uuid" => $row['m_uuid'], "username" => $row['m_username'], "nickname" => $row['m_nickname'], "password" => $row['m_password']), "system" => array("roleUnit" => array("main" => array("uuid" => $role->m_uuid, "rolename" => $role->m_rolename) ),"status" => $status));
  }
}

echo json_encode(array("transaction" => "ok", "object" => array("list" => $output, "total" => $num), "message" => "Se obtuvieron los datos correctamente", "code" => "user:succes:002"));
?>
