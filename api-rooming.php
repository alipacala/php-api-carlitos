<?php
// Configuración de la base de datos
require_once 'config.php';

// Manejar los métodos HTTP
$metodo = $_SERVER['REQUEST_METHOD'];

switch ($metodo) {
    case 'GET':
        // Listar unidaddenegocio
        $sql = "SELECT * FROM rooming";
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
    case 'INNER':
        // Listar unidaddenegocio
        $sql = "SELECT 
        p.nombre_producto, 
        h.nro_habitacion, 
        ch.nro_registro_maestro, 
        ch.nro_reserva, 
        ch.nombre, 
        ch.nro_personas, 
        ch.fecha_in, 
        ch.fecha_out
        FROM habitaciones h
        LEFT JOIN rooming r ON r.nro_habitacion = h.nro_habitacion
        LEFT JOIN cheking ch ON ch.id_checkin = r.id_checkin
        LEFT JOIN reservas re ON ch.nro_registro_maestro = re.nro_registro_maestro  AND CURDATE() BETWEEN ch.fecha_in AND ch.fecha_out
        LEFT JOIN productos p ON p.id_producto = h.id_producto
        WHERE h.id_unidad_de_negocio = 3
        GROUP BY  h.nro_habitacion
        ORDER BY h.nro_habitacion ASC";
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
        case 'POST2':
            $data = json_decode(file_get_contents('php://input'), true);
            $fecha = $data['fecha'];
            // Listar unidaddenegocio
            $sql = "SELECT
            COALESCE(p.nombre_producto, '') AS nombre_producto,
            COALESCE(h.nro_habitacion, '') AS nro_habitacion,
            COALESCE(ch.nro_registro_maestro, '') AS nro_registro_maestro,
            COALESCE(ch.nro_reserva, '') AS nro_reserva,
            COALESCE(ch.nombre, '') AS nombre,
            COALESCE(ch.nro_personas, '') AS nro_personas,
            COALESCE(ch.fecha_in, '') AS fecha_llegada,
            COALESCE(ch.fecha_out, '') AS fecha_salida
            FROM habitaciones h
            LEFT JOIN rooming r ON r.nro_habitacion = h.nro_habitacion
            LEFT JOIN cheking ch ON ch.id_checkin = r.id_checkin
            LEFT JOIN reservas re ON ch.nro_registro_maestro = re.nro_registro_maestro  AND '$fecha' BETWEEN ch.fecha_in AND ch.fecha_out
            LEFT JOIN productos p ON p.id_producto = h.id_producto
            WHERE h.id_unidad_de_negocio = 3
            GROUP BY h.nro_habitacion
            ORDER BY h.nro_habitacion ASC";
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