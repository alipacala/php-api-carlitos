<?php
// Manejar los métodos HTTP
$metodo = $_SERVER['REQUEST_METHOD'];
header('Content-Type: application/json');

if ($metodo == 'POST') {
  iniciarSesion();
}

function iniciarSesion()
{
  require_once('config.php');

  $data = json_decode(file_get_contents('php://input'), true);
  $usuario = $data['usuario'];
  $clave = $data['clave'];
  $sql = "SELECT * FROM usuarios WHERE usuario = '$usuario' AND clave = '$clave'";

  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    session_start();
    $_SESSION['usuario'] = $usuario;
    $_SESSION['logueado'] = true;
    
    $usuarioRespuesta = $result->fetch_assoc();
    unset($usuarioRespuesta['clave']);

    http_response_code(200);
    $response = ["logueado" => true, "mensaje" => "Sesión iniciada correctamente", "usuario" => $usuarioRespuesta];
    echo json_encode($response);
  } else {
    http_response_code(401);
    echo json_encode(["logueado" => false, "mensaje" => "No se pudo obtener el usuario"]);
  }
}
?>