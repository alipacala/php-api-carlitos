<?php
require_once 'config.php';

// Manejar los métodos HTTP
$metodo = $_SERVER['REQUEST_METHOD'];

switch ($metodo) {
    case 'GET':
        $codigo = json_decode(file_get_contents('php://input'), true);
        $sql = "SELECT * FROM modulos";

        if (isset($_GET["codigo"])) {
            $codigo = $_GET["codigo"];
            $sql .= " WHERE id_grupo_modulo = '$codigo'";
        }

        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            $modulos = array();
            while ($row = $result->fetch_assoc()) {
                $modulos[] = $row;
            }
            echo json_encode($modulos);
        } else {
            echo "No se encontraron modulos.";
        }
        break;
    case 'INNER':
        // Listar unidaddenegocio
        $sql = "SELECT m.id_modulo, g.nombre_grupo_modulo, m.nombre_modulo, m.descripcion, m.archivo_acceso
        FROM modulos m
        INNER JOIN grupo_modulo g ON g.id_grupo_modulo = m.id_grupo_modulo
        GROUP BY m.id_modulo, m.nombre_modulo, m.descripcion, m.archivo_acceso";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            $modulos = array();
            while ($row = $result->fetch_assoc()) {
                $modulos[] = $row;
            }
            echo json_encode($modulos);
        } else {
            echo "No se encontraron habilidades profesionales";
        }
        break;
    case 'POST':
        // Insertar nueva unidaddenegocio
        $data = json_decode(file_get_contents('php://input'), true);
        $id_grupo_modulo = $data['id_grupo_modulo'];
        $nombre_modulo = $data['nombre_modulo'];
        $descripcion = $data['descripcion'];
        $archivo_acceso = $data['archivo_acceso'];
        
        $sql = "INSERT INTO modulos (
           id_grupo_modulo, nombre_modulo,
            descripcion, archivo_acceso
        ) VALUES (
            '$id_grupo_modulo', '$nombre_modulo',
            '$descripcion', '$archivo_acceso'
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
        $id_modulo = $data['id_modulo'];
        $id_grupo_modulo = $data['id_grupo_modulo'];
        $nombre_modulo = $data['nombre_modulo'];
        $descripcion = $data['descripcion'];
        $archivo_acceso = $data['archivo_acceso'];

        $sql = "UPDATE modulos 
        SET id_grupo_modulo = '$id_grupo_modulo',
            nombre_unidad_de_negocio = '$nombre_unidad_de_negocio',
            nombre_modulo = '$nombre_modulo',
            descripcion = '$descripcion',
            archivo_acceso = '$archivo_acceso'
        WHERE id_modulo = '$id_modulo'";

        if ($conn->query($sql) === TRUE) {
            echo "Modulo actualizado exitosamente.";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
        break;
    case 'DELETE':
        // Eliminar unidaddenegocio
        $data = json_decode(file_get_contents('php://input'), true);
        $id_modulo = $data['id_modulo'];

        $sql = "DELETE FROM modulos WHERE id_modulo = $id_modulo";

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