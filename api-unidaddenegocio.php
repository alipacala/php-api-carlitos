<?php
// Configuración de la base de datos
require_once 'config.php';
// Manejar los métodos HTTP
$metodo = $_SERVER['REQUEST_METHOD'];

switch ($metodo) {
    case 'GET':
        // Listar unidaddenegocio
        $sql = "SELECT * FROM unidaddenegocio";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            $unidades = array();
            while ($row = $result->fetch_assoc()) {
                $unidades[] = $row;
            }
            echo json_encode($unidades);
        } else {
            echo "No se encontraron unidades de negocio.";
        }
        break;
    case 'POST':
        // Insertar nueva unidaddenegocio
        $data = json_decode(file_get_contents('php://input'), true);
        $codigo_unidad_de_negocio = $data['codigo_unidad_de_negocio'];
        $nombre_unidad_de_negocio = $data['nombre_unidad_de_negocio'];
        $id_usuario_creacion = $data['id_usuario_creacion'];
        $fecha_creacion = $data['fecha_creacion'];
        
        $sql = "INSERT INTO unidaddenegocio (
            codigo_unidad_de_negocio, nombre_unidad_de_negocio,
            id_usuario_creacion, fecha_creacion
        ) VALUES (
            '$codigo_unidad_de_negocio', '$nombre_unidad_de_negocio',
            '$id_usuario_creacion', '$fecha_creacion'
        )";
         if ($conn->query($sql) === TRUE) {
            echo "Nuevo registro insertado exitosamente.";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
        break;
    case 'PUT':
        // Actualizar unidaddenegocio
        $data = json_decode(file_get_contents('php://input'), true);
        $id_unidad_de_negocio = $data['id_unidad_de_negocio'];
        $codigo_unidad_de_negocio = $data['codigo_unidad_de_negocio'];
        $nombre_unidad_de_negocio = $data['nombre_unidad_de_negocio'];
        $id_usuario_creacion = $data['id_usuario_creacion'];
        $fecha_creacion = $data['fecha_creacion'];

        $sql = "UPDATE unidaddenegocio 
        SET codigo_unidad_de_negocio = '$codigo_unidad_de_negocio',
            nombre_unidad_de_negocio = '$nombre_unidad_de_negocio',
            id_usuario_creacion = '$id_usuario_creacion',
            fecha_creacion = '$fecha_creacion'
        WHERE id_unidad_de_negocio = '$id_unidad_de_negocio'";

        if ($conn->query($sql) === TRUE) {
            echo "Unidad de negocio actualizada exitosamente.";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
        break;
    case 'DELETE':
        // Eliminar unidaddenegocio
        $data = json_decode(file_get_contents('php://input'), true);
        $id_unidad_de_negocio = $data['id_unidad_de_negocio'];

        $sql = "DELETE FROM unidaddenegocio WHERE id_unidad_de_negocio = $id_unidad_de_negocio";

        if ($conn->query($sql) === TRUE) {
            echo "Unidad de negocio eliminada exitosamente.";
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