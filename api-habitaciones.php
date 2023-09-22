<?php
require_once 'config.php';

// Manejar los métodos HTTP
$metodo = $_SERVER['REQUEST_METHOD'];

switch ($metodo) {
    case 'GET':
        $codigo = json_decode(file_get_contents('php://input'), true);
        $sql = "SELECT * FROM habitaciones";

        if (isset($_GET["codigo"])) {
            $codigo = $_GET["codigo"];
            $sql .= " WHERE nro_habitacion = '$codigo'";
        }

        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            $modulos = array();
            while ($row = $result->fetch_assoc()) {
                $modulos[] = $row;
            }
            echo json_encode($modulos);
        } else {
            echo "No se encontraron habitaciones.";
        }
        break;
    case 'POST':
        // Insertar nueva unidaddenegocio
        $data = json_decode(file_get_contents('php://input'), true);
        $id_unidad_de_negocio = $data['id_unidad_de_negocio'];
        $nro_habitacion = $data['nro_habitacion'];
        $id_producto = $data['id_producto'];
        $estado = $data['estado'];
        
        $sql = "INSERT INTO habitaciones (
           id_unidad_de_negocio, nro_habitacion,
            id_producto, estado
        ) VALUES (
            '$id_unidad_de_negocio', '$nro_habitacion',
            '$id_producto', '$estado'
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
        $id_habitacion = $data['id_habitacion'];
        $id_unidad_de_negocio = $data['id_unidad_de_negocio'];
        $nro_habitacion = $data['nro_habitacion'];
        $id_producto = $data['id_producto'];
        $estado = $data['estado'];

        $sql = "UPDATE habitaciones 
        SET id_unidad_de_negocio = '$id_unidad_de_negocio',
            nro_habitacion = '$nro_habitacion,
            id_producto = '$id_producto',
            estado = '$estado'
        WHERE id_habitacion = '$id_habitacion'";

        if ($conn->query($sql) === TRUE) {
            echo "Modulo actualizado exitosamente.";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
        break;
    case 'DELETE':
        // Eliminar unidaddenegocio
        $data = json_decode(file_get_contents('php://input'), true);
        $id_habitacion = $data['id_habitacion'];

        $sql = "DELETE FROM habitaciones WHERE id_habitacion = $id_habitacion";

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