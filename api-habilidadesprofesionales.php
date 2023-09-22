<?php
require 'config.php';
// Manejar los métodos HTTP
$metodo = $_SERVER['REQUEST_METHOD'];

switch ($metodo) {
    case 'GET':
        // Listar unidaddenegocio
        $sql = "SELECT * FROM habilidadesprofesionales";
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
    case 'INNER':
        // Listar unidaddenegocio
        $sql = "SELECT t.apellidos, t.nombres, t.telefono, t.Email, t.fecha_ingreso, t.nro_documento, GROUP_CONCAT(h.descripcion SEPARATOR ', ') AS habilidades
        FROM terapistas t
        INNER JOIN terapistashabilidades ph ON t.id_persona = ph.id_persona
        INNER JOIN habilidadesprofesionales h ON ph.id_habilidad = h.id_habilidad
        GROUP BY t.id_persona, t.apellidos, t.nombres, t.telefono, t.Email, t.fecha_ingreso, t.nro_documento";
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
        $codigo_habilidad = $data['codigo_habilidad'];
        $descripcion = $data['descripcion'];
        
        $sql = "INSERT INTO habilidadesprofesionales (
            codigo_habilidad, descripcion
        ) VALUES (
            '$codigo_habilidad', '$descripcion'
        )";
         if ($conn->query($sql) === TRUE) {
            echo "Nuevo registro insertado exitosamente habilidadesprofesionales.";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
        break;
    case 'PUT':
        // Actualizar modulos
        $data = json_decode(file_get_contents('php://input'), true);
        $id_habilidad = $data["id_habilidad"];
        $codigo_habilidad = $data['codigo_habilidad'];
        $descripcion = $data['descripcion'];

        $sql = "UPDATE habilidadesprofesionales
                SET codigo_habilidad = '$codigo_habilidad',
                descripcion = '$descripcion' 
                WHERE id_habilidad = $id_habilidad";

        if ($conn->query($sql) === TRUE) {
            echo "habilidadesprofesionales actualizado exitosamente.";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
        break;
    case 'DELETE':
        // Eliminar unidaddenegocio
        $data = json_decode(file_get_contents('php://input'), true);
        $id_habilidad = $data['id_habilidad'];

        $sql = "DELETE FROM habilidadesprofesionales WHERE id_habilidad = $id_habilidad";

        if ($conn->query($sql) === TRUE) {
            echo "habilidadesprofesionales eliminado exitosamente.";
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