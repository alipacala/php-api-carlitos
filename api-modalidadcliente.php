<?php
require_once 'config.php';

// Manejar los métodos HTTP
$metodo = $_SERVER['REQUEST_METHOD'];

switch ($metodo) {
    case 'GET':
        $codigo = json_decode(file_get_contents('php://input'), true);
        $sql = "SELECT * FROM modalidadcliente";

        if (isset($_GET["codigo"])) {
            $codigo = $_GET["codigo"];
            $sql .= " WHERE id_modalidad = '$codigo'";
        }

        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            $modulos = array();
            while ($row = $result->fetch_assoc()) {
                $modulos[] = $row;
            }
            echo json_encode($modulos);
        } else {
            echo "No se encontraron modalidad clientes.";
        }
        break;
    case 'POST':
        // Insertar nueva unidaddenegocio
        $data = json_decode(file_get_contents('php://input'), true);
        $nombre_modalidad = $data['nombre_modalidad'];
        $descripcion_modalidad = $data['descripcion_modalidad'];
        
        $sql = "INSERT INTO modalidadcliente (
            nombre_modalidad,
            descripcion_modalidad
        ) VALUES (
            '$nombre_modalidad',
            '$descripcion_modalidad'
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
        $id_modalidad = $data['id_modalidad'];
        $nombre_modalidad = $data['nombre_modalidad'];
        $descripcion_modalidad = $data['descripcion_modalidad'];

        $sql = "UPDATE modalidadcliente 
        SET nombre_modalidad = '$nombre_modalidad',
        descripcion_modalidad = '$descripcion_modalidad'
        WHERE id_modalidad = '$id_modalidad'";

        if ($conn->query($sql) === TRUE) {
            echo "Modulo actualizado exitosamente.";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
        break;
    case 'DELETE':
        // Eliminar unidaddenegocio
        $data = json_decode(file_get_contents('php://input'), true);
        $id_modalidad = $data['id_modalidad'];

        $sql = "DELETE FROM modalidadcliente WHERE id_modalidad = $id_modalidad";

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