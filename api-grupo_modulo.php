<?php
// Configuración de la base de datos
require_once 'config.php';

// Manejar los métodos HTTP
$metodo = $_SERVER['REQUEST_METHOD'];

switch ($metodo) {
    case 'GET':
        // Listar unidaddenegocio
        $sql = "SELECT * FROM grupo_modulo";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            $modulos = array();
            while ($row = $result->fetch_assoc()) {
                $modulos[] = $row;
            }
            echo json_encode($modulos);
        } else {
            echo "No se encontraron grupo modulos.";
        }
        break;
    case 'POST':
        // Insertar nueva unidaddenegocio
        $data = json_decode(file_get_contents('php://input'), true);
        $nombre_grupo_modulo = $data['nombre_grupo_modulo'];
        
        $sql = "INSERT INTO grupo_modulo (
            nombre_grupo_modulo
        ) VALUES (
            '$nombre_grupo_modulo'
        )";
         if ($conn->query($sql) === TRUE) {
            echo "Nuevo registro insertado exitosamente.";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
        break;
    case 'PUT':
        // Actualizar modulos
        $data = json_decode(file_get_contents('php://input'), true);
        $id_grupo_modulo = $data['id_grupo_modulo'];
        $nombre_grupo_modulo = $data['nombre_grupo_modulo'];

        $sql = "UPDATE grupo_modulo
        SET nombre_grupo_modulo = '$nombre_grupo_modulo'
        WHERE id_grupo_modulo = '$id_grupo_modulo'";

        if ($conn->query($sql) === TRUE) {
            echo "Modulo actualizado exitosamente.";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
        break;
    case 'DELETE':
        // Eliminar unidaddenegocio
        $data = json_decode(file_get_contents('php://input'), true);
        $id_grupo_modulo = $data['id_grupo_modulo'];

        $sql = "DELETE FROM grupo_modulo WHERE id_grupo_modulo = $id_grupo_modulo";

        if ($conn->query($sql) === TRUE) {
            echo "modulo eliminado exitosamente.";
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