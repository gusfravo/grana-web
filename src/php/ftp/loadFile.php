<?php
header ("Access-Control-Allow-Origin: *");
header ("Access-Control-Allow-Headers: *");
// Volcamos a un objeto nativo de PHP
$objDatos = json_decode(file_get_contents("php://input"));
$base64 = $objDatos->base64;
$base64Name = $objDatos->file;
// LOAD image on ftp
$handle = fopen($base64, 'r');

// Setup and login
$creds = array(
    'server' => 'grana.mx',
    'user' => 'developergrana',
    'pass' => 'Gr@n@@rt3s@n1@s'
);
$conn = ftp_connect($creds['server']);
$loginResult = ftp_login($conn, $creds['user'], $creds['pass']);

// Upload
if (ftp_fput($conn, 'public_html/gallery/'.$base64Name, $handle, FTP_BINARY)) {
  echo json_encode(array("transaction" => "ok", "message" => "El procedimiento se creo correctamente", "code" => "ftp:succes:001"));
} else {
  echo json_encode(array("transaction" => "bad", "message" => "Error al crear el procedimiento", "code" => "ftp:error:001"));
}

// Cleanup
ftp_close($conn);
fclose($handle);

try {

} catch (Exception $e) {
  echo json_encode(array("transaction" => "bad", "message" =>  $e->getMessage(), "code" => "ftp:error:002"));
}
?>
