<?php
// Configuración de la base de datos
require_once 'config.php';

// Manejar los métodos HTTP
$metodo = $_SERVER['REQUEST_METHOD'];

switch ($metodo) {
    case 'GET':
        // Listar correlativos del config
        $sql = "SELECT * FROM config";
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
    case 'RESERVAS':
        // Listar reservas + correlativo
        $sql = "SELECT * FROM config WHERE id_config = 2";
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
    case 'HOTEL':
        // Listar hotel + correlativo
        $sql = "SELECT * FROM config WHERE id_config = 11";
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
        $codigo = $data['codigo'];
        $numero_correlativo = $data['numero_correlativo'];
        
        $sql = "INSERT INTO config (
            codigo,
            numero_correlativo
        ) VALUES (
            '$codigo',

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
        $id_config = $data['id_config'];
        $nombre_config = $data['nombre_config'];

        $sql = "UPDATE config
        SET nombre_config = '$nombre_config'
        WHERE id_config = '$id_config'";

        if ($conn->query($sql) === TRUE) {
            echo "Modulo actualizado exitosamente.";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
        break;
    case 'DELETE':
        // Eliminar unidaddenegocio
        $data = json_decode(file_get_contents('php://input'), true);
        $id_config = $data['id_config'];

        $sql = "DELETE FROM config WHERE id_config = $id_config";

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