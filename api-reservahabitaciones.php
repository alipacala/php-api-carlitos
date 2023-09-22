<?php
require_once 'config.php';

// Manejar los métodos HTTP
$metodo = $_SERVER['REQUEST_METHOD'];

switch ($metodo) {
    case 'GET':
        $codigo = json_decode(file_get_contents('php://input'), true);
        $sql = "SELECT * FROM reservahabitaciones";

        if (isset($_GET["codigo"])) {
            $codigo = $_GET["codigo"];
            $sql .= " WHERE nro_reserva = '$codigo'";
        }

        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            $modulos = array();
            while ($row = $result->fetch_assoc()) {
                $modulos[] = $row;
            }
            echo json_encode($modulos);
        } else {
            echo "No se encontraron reservahabitaciones.";
        }
        break;
    case 'POST':
        // Insertar nueva unidaddenegocio
        $data = json_decode(file_get_contents('php://input'), true);
        $id_unidad_de_negocio = 3;
        $nro_reserva = $data['nro_reserva'];
        $fecha_ingreso = $data['fecha_llegada'];
        $nro_noches = $data["noches"];
        $fecha_salida = $data['fecha_salida'];
        $precio_total = $data['monto_total'];
        $array = $data['carritoItems'];

        foreach ($array as $item) {
            $nro_habitacion = $item["habitacion"];
            $nro_personas = $item["personas"];
            $precio_unitario = $item["monto"];
            $sql = "INSERT INTO reservahabitaciones (
                id_unidad_de_negocio,
                nro_reserva,
                nro_habitacion,
                fecha_ingreso,
                fecha_salida,
                nro_noches,
                precio_unitario,
                precio_total,
                nro_personas
            ) VALUES (
                '$id_unidad_de_negocio',
                '$nro_reserva',
                '$nro_habitacion',
                '$fecha_ingreso',
                '$fecha_salida',
                '$nro_noches',
                '$precio_unitario',
                '$precio_total',
                '$nro_personas'
            )";
             if ($conn->query($sql) === TRUE) {
                echo "Nuevo reservahabitaciones insertado exitosamente.";
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        }
        
        
        
        break;
    case 'PUT':
        // Actualizar modulos
        $data = json_decode(file_get_contents('php://input'), true);
        $id_reserva_habitaciones = $data['id_reserva_habitaciones'];
        $id_unidad_de_negocio = $data['id_unidad_de_negocio'];
        $nro_reserva = $data['nro_reserva'];
        $nro_habitacion = $data['nro_habitacion'];
        $fecha_ingreso = $data['fecha_ingreso'];
        $fecha_salida = $data['fecha_salida'];
        $nro_noches = $data['nro_noches'];
        $precio_unitario = $data['precio_unitario'];
        $precio_total = $data['precio_total'];
        $nro_personas = $data['nro_personas'];

        $sql = "UPDATE reservahabitaciones 
        SET id_unidad_de_negocio = '$id_unidad_de_negocio',
        nro_reserva = '$nro_reserva',
        nro_habitacion = '$nro_habitacion',
        fecha_ingreso = '$fecha_ingreso',
        fecha_salida = '$fecha_salida',
        nro_noches = '$nro_noches',
        precio_unitario = '$precio_unitario',
        precio_total = '$precio_total',
        nro_personas = '$nro_personas'
        WHERE id_reserva_habitaciones = '$id_reserva_habitaciones'";

        if ($conn->query($sql) === TRUE) {
            echo "Modulo actualizado exitosamente.";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
        break;
    case 'DELETE':
        // Eliminar unidaddenegocio
        $data = json_decode(file_get_contents('php://input'), true);
        $id_reserva_habitaciones = $data['id_reserva_habitaciones'];

        $sql = "DELETE FROM reservahabitaciones WHERE id_reserva_habitaciones = $id_reserva_habitaciones";

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