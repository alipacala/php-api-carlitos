<?php
// Configuración de la base de datos
require_once 'config.php';
// Manejar los métodos HTTP
$metodo = $_SERVER['REQUEST_METHOD'];

switch ($metodo) {
    case 'GET':
        // Listar unidaddenegocio
        $sql = "SELECT * FROM terapistashabilidades";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            $item = array();
            while ($row = $result->fetch_assoc()) {
                $item[] = $row;
            }
            echo json_encode($item);
        } else {
            echo "No se encontraron terapistas habilidades ";
        }
        break;
    case 'POST':
         // Insertar nueva unidaddenegocio
        $data = json_decode(file_get_contents('php://input'), true);
        $id_persona = $data['id_persona'];
        $id_habilidad = $data['id_habilidad'];
        
        $sql = "INSERT INTO terapistashabilidades (
            id_persona, id_habilidad
        ) VALUES (
            '$id_persona', '$id_habilidad'
        )";
         if ($conn->query($sql) === TRUE) {
            echo "Nuevo registro insertado exitosamente terapistashabilidades.";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
        break;
    case 'PUT':
        // Actualizar modulos
        $data = json_decode(file_get_contents('php://input'), true);
        $id_terapistas_habilidad = $data['id_terapistas_habilidad'];
        $id_habilidad = $data['id_habilidad'];
        $id_persona = $data['id_persona'];

        $sql = "UPDATE terapistashabilidades
                SET id_persona = '$id_persona',
                id_habilidad = '$id_habilidad' 
                WHERE id_terapistas_habilidad = '$id_terapistas_habilidad'";

        if ($conn->query($sql) === TRUE) {
            echo "terapistashabilidades actualizado exitosamente.";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
        break;
    case 'DELETE':
        // Eliminar unidaddenegocio
        $data = json_decode(file_get_contents('php://input'), true);
        $id_terapistas_habilidad = $data['id_terapistas_habilidad'];

        $sql = "DELETE FROM terapistashabilidades WHERE id_terapistas_habilidad = $id_terapistas_habilidad";

        if ($conn->query($sql) === TRUE) {
            echo "terapistashabilidades eliminado exitosamente.";
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