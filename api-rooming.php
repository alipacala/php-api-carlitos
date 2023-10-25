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
        ch.id_checkin,
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
    LEFT JOIN cheking ch ON ch.id_checkin = r.id_checkin AND CURDATE() BETWEEN ch.fecha_in AND ch.fecha_out
    LEFT JOIN reservas re ON ch.nro_registro_maestro = re.nro_registro_maestro 
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
    case 'POST2':
        $data = json_decode(file_get_contents('php://input'), true);
        $fecha = $data['fecha'];
        // Listar unidaddenegocio
        $sql = "SELECT
            COALESCE(ch.id_checkin, '') AS id_checkin,
            COALESCE(p.nombre_producto, '') AS nombre_producto,
            COALESCE(h.nro_habitacion, '') AS nro_habitacion,
            COALESCE(ch.nro_registro_maestro, '') AS nro_registro_maestro,
            COALESCE(ch.nro_reserva, '') AS nro_reserva,
            COALESCE(ch.nombre, '') AS nombre,
            COALESCE(ch.nro_personas, '') AS nro_personas,
            COALESCE(ch.fecha_in, '') AS fecha_in,
            COALESCE(ch.fecha_out, '') AS fecha_out
            FROM habitaciones h
            LEFT JOIN rooming r ON r.nro_habitacion = h.nro_habitacion
            LEFT JOIN cheking ch ON ch.id_checkin = r.id_checkin  AND '$fecha' BETWEEN ch.fecha_in AND ch.fecha_out
            LEFT JOIN reservas re ON ch.nro_registro_maestro = re.nro_registro_maestro 
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
    case 'FECHA':
        // Actualizar modulos
        $data = json_decode(file_get_contents('php://input'), true);
        $codigo = $data['codigo'];
        $fechasJson = $data['fechasJson'];

        // mapear las fechas a un array
        $fechasJson = array_column($fechasJson, 'fecha');

        // Convierte los objetos JSON en un array de fechas
        // Realiza una consulta SQL para verificar las fechas en la tabla rooming
        $sql = "SELECT DISTINCT * FROM rooming WHERE id_checkin = '$codigo'";
        $resultado = $conn->query($sql);


        if (!$resultado) {
            die("Error en la consulta: " . $conn->error);
        }

        $fechasExistentes = array();
        //almcenare las en variables los datos de la consulta
        $id_checkin = "";
        $id_producto = "";
        $nro_habitacion = "";
        $nro_registro_maestro = "";
        $hora = "12:00:00";
        $nro_personas = "";
        $tarifa = "";
        $estado = "";
        while ($fila = $resultado->fetch_assoc()) {
            $fechasExistentes[] = $fila['fecha'];
            $id_checkin = $fila['id_checkin'];
            $id_producto = $fila['id_producto'];
            $nro_habitacion = $fila['nro_habitacion'];
            $nro_registro_maestro = $fila['nro_registro_maestro'] ?? $nro_registro_maestro;
            $nro_personas = $fila['nro_personas'];
            $tarifa = $fila['tarifa'];
            $estado = $fila['estado'];
        }

        // Compara las fechas recibidas con las fechas existentes y ajusta la tabla
        // Comparar las fechas existentes con las fechas enviadas y eliminar las no presentes
        foreach ($fechasExistentes as $fechaExistente) {
            if (!in_array($fechaExistente, $fechasJson)) {
                // La fecha existe en la base de datos pero no en el rango enviado, así que la eliminamos
                $sqlDelete = "DELETE FROM rooming WHERE id_checkin = '$codigo' AND fecha = '$fechaExistente'";

                // borrar los documentos detalles
                $sqlDeleteDetalle = "DELETE FROM documento_detalle WHERE nro_registro_maestro = '$nro_registro_maestro' AND fecha_servicio = '$fechaExistente'";

                $result = $conn->query($sqlDeleteDetalle);

                // Ejecutar la consulta
                if ($conn->query($sqlDelete) !== TRUE && $result !== TRUE) {
                    // Error al eliminar la fecha
                    http_response_code(500);
                    echo json_encode(array('error' => 'Error al eliminar fecha en la base de datos.'));
                    exit();
                }
            }
        }
        // Procesa las fechas y realiza las operaciones en la base de datos
        foreach ($fechasJson as $fecha) {
            // Inserta o actualiza los datos en la tabla 'rooming' según tus necesidades
            if (!in_array($fecha, $fechasExistentes)) {
                // La fecha no existe en la base de datos, así que la insertamos
                $sql = "INSERT INTO rooming (
                    id_checkin,
                    nro_registro_maestro,
                    nro_habitacion,
                    id_producto,
                    fecha,
                    hora,
                    nro_personas,
                    tarifa,
                    estado)
                    VALUES (
                    '$id_checkin', 
                    '$nro_registro_maestro', 
                    '$nro_habitacion', 
                    '$id_producto', 
                    '$fecha', 
                    '$hora', 
                    '$nro_personas', 
                    '$tarifa', 
                    '$estado')";

                $sqlDetalle = "INSERT INTO documento_detalle (
                                    tipo_movimiento,
                                    nro_registro_maestro,
                                    fecha,
                                    id_producto,
                                    nivel_descargo,
                                    nro_habitacion,
                                    cantidad,
                                    tipo_de_unidad,
                                    precio_unitario,
                                    precio_total,
                                    fecha_servicio,
                                    id_usuario,
                                    fecha_hora_registro
                                ) VALUES (
                                    'SA',
                                    '$nro_registro_maestro',
                                    '$fecha',
                                    '$id_producto',
                                    '1',
                                    '$nro_habitacion',
                                    '1',
                                    'UND',
                                    '$tarifa',
                                    '$tarifa',
                                    '$fecha',
                                    '1',
                                    '$fecha'
                                )";

                $result = $conn->query($sqlDetalle);

                if ($conn->query($sql) !== TRUE && $result !== TRUE) {
                    // Error al insertar el registro
                    http_response_code(500);
                    echo json_encode(array('error' => 'Error al insertar fecha en la base de datos.'));
                    exit();
                }
            }

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