<?php
// Configuración de la base de datos
require_once 'config.php';
// Manejar los métodos HTTP
$metodo = $_SERVER['REQUEST_METHOD'];

switch ($metodo) {
    case 'GET':
        // Listar tipodeusuario
        $sql = "SELECT * FROM tipodeusuario";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            $tipos = array();
            while ($row = $result->fetch_assoc()) {
                $tipos[] = $row;
            }
            echo json_encode($tipos);
        } else {
            echo "No se encontraron tipos de usuarios.";
        }
        break;
    case 'POST':
        // Insertar nuevo tipodeusuario
        $data = json_decode(file_get_contents('php://input'), true);
        $tipo_de_usuario = $data['tipo_de_usuario'];
        $id_usuario_creacion = $data['id_usuario_creacion'];
        $fecha_creacion = $data['fecha_creacion'];
        $sql = "INSERT INTO tipodeusuario (
            tipo_de_usuario, id_usuario_creacion, fecha_creacion
        ) VALUES (
            '$tipo_de_usuario', '$id_usuario_creacion', '$fecha_creacion'
        )";
        if ($conn->query($sql) === TRUE) {
            echo "Nuevo registro insertado exitosamente.";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
        break;
    case 'PUT':
        // Actualizar tipodeusuario
        $data = json_decode(file_get_contents('php://input'), true);
        $id_tipo_de_usuario = $data['id_tipo_de_usuario'];
        $tipo_de_usuario = $data['tipo_de_usuario'];
        $id_usuario_creacion = $data['id_usuario_creacion'];
        $fecha_creacion = $data['fecha_creacion'];

        $sql = "UPDATE tipodeusuario 
        SET tipo_de_usuario = '$tipo_de_usuario',
            id_usuario_creacion = '$id_usuario_creacion',
            fecha_creacion = '$fecha_creacion'
        WHERE id_tipo_de_usuario = '$id_tipo_de_usuario'";

        if ($conn->query($sql) === TRUE) {
            echo "Tipo de usuario actualizado exitosamente.";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
        break;
    case 'DELETE':
        // Eliminar tipodeusuario
        $data = json_decode(file_get_contents('php://input'), true);
        $id_tipo_de_usuario = $data['id_tipo_de_usuario'];

        $sql = "DELETE FROM tipodeusuario WHERE id_tipo_de_usuario = $id_tipo_de_usuario";

        if ($conn->query($sql) === TRUE) {
            echo "Tipo de usuario eliminado exitosamente.";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
        break;
    default:
        http_response_code(405); // Método no permitido
        break;
}

// Cerrar la conexión
$conn->close();
?>