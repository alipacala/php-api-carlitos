<?php
require_once 'config.php';
require_once('incrementador.php');
// Manejar los métodos HTTP
$metodo = $_SERVER['REQUEST_METHOD'];

$responseData = array(
    'mensaje' => "Nuevo reservahabitaciones insertado exitosamente."
);

switch ($metodo) {
    case 'GET':
        $codigo = $_GET["nro_reserva"];
        $sql = "SELECT * FROM reservahabitaciones  WHERE nro_reserva = '$codigo'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $reservas = array();
            while ($row = $result->fetch_assoc()) {
                $reservas[] = $row;
            }
            echo json_encode($reservas);
        } else {
            if (isset($_GET["codigo"])) {
                echo "No se encontraron reservas!";
            } else {
                echo "No se encontraron reservas!";
            }
        }

        break;
    case 'GET2':
        if (isset($_GET['codigo'])) {
            $codigo = $_GET['codigo'];
            $sql = "SELECT p.id_producto, p.nombre_producto, p.codigo, p.precio_venta_01, p.precio_venta_02, p.precio_venta_03 FROM habitaciones h
            INNER JOIN productos p ON h.id_producto = p.id_producto WHERE h.nro_habitacion = '$codigo'";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                $modulos = array();
                while ($row = $result->fetch_assoc()) {
                    $modulos[] = $row;
                }
                echo json_encode($modulos);
            } else {
                echo "No se encontraron productos.";
            }
        } else {
            echo "El parámetro 'codigo' no se encuentra en la URL.";
        }
        break;
    case 'GET3':
        $sql = "SELECT id_producto, nombre_producto FROM productos WHERE tipo = 'SVH'";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            $modulos = array();
            while ($row = $result->fetch_assoc()) {
                $modulos[] = $row;
            }
            echo json_encode($modulos);
        } else {
            echo "No se encontraron productos.";
        }
        break;
    case 'GET5':
        if (isset($_GET['codigo'])) {
            $codigo = $_GET['codigo'];
            $sql = "SELECT 
            ch.nro_registro_maestro,
            ch.nro_reserva,
            ch.tipo_documento,
            ch.nro_documento,
            p.id_persona,
            p.apellidos,
            ch.nombre,
            p.lugar_de_nacimiento,
            p.fecha,
            p.edad,
            p.sexo,
            p.ocupacion,
            p.direccion,
            ch.lugar_procedencia,
            ch.telefono,
            p.email,
            ch.estacionamiento,
            ch.nro_placa,
            ro.nro_habitacion,
            pr.nombre_producto,
            ro.tarifa,
            ch.fecha_in,
            ch.fecha_out,
            ch.hora_in,
            ch.hora_out,
            ch.forma_pago,
            ch.tipo_comprobante,
            ch.tipo_documento_comprobante,
            ch.nro_documento_comprobante,
            ch.razon_social,
            ch.nro_ninos,
            ch.nro_adultos,
            ch.nro_infantes,
            ch.nro_personas,
            ch.direccion_comprobante,
            CASE
                WHEN pr.precio_venta_01 = ro.tarifa THEN 'Precio Normal'
                WHEN pr.precio_venta_02 = ro.tarifa THEN 'Precio Corporativo'
                WHEN pr.precio_venta_03 = ro.tarifa THEN 'Precio Cliente Premium'
                ELSE 'Precio Booking'
            END AS tipo_precio
            FROM cheking ch
            LEFT JOIN personanaturaljuridica p ON ch.id_persona = p.id_persona
            LEFT JOIN rooming ro ON ro.nro_registro_maestro = ch.nro_registro_maestro
            LEFT JOIN productos pr ON ro.id_producto = pr.id_producto
            WHERE ch.id_checkin = '$codigo'
            GROUP BY ch.nro_registro_maestro";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                $modulos = array();
                while ($row = $result->fetch_assoc()) {
                    $modulos[] = $row;
                }
                echo json_encode($modulos);
            } else {
                echo "No se encontraron productos.";
            }
        } else {
            echo "El parámetro 'codigo' no se encuentra en la URL.";
        }
        break;
    case 'GET4':
        if (isset($_GET['codigo'])) {
            $codigo = $_GET['codigo'];
            $sql = "SELECT p.id_producto, p.nombre_producto, p.codigo, p.precio_venta_01, p.precio_venta_02, p.precio_venta_03 
            FROM productos p WHERE p.nombre_producto = '$codigo'";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                $modulos = array();
                while ($row = $result->fetch_assoc()) {
                    $modulos[] = $row;
                }
                echo json_encode($modulos);
            } else {
                echo "No se encontraron productos.";
            }
        } else {
            echo "El parámetro 'codigo' no se encuentra en la URL.";
        }
        break;
    case 'INNER':
        // Listar unidaddenegocio
        $sql = "SELECT h.id_habitacion, h.nro_habitacion
        FROM habitaciones h WHERE h.id_unidad_de_negocio = 3";
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
    case 'INNER2':
        // Listar unidaddenegocio
        $sql = "SELECT
        r.id_reserva,
        r.nro_reserva,
        r.fecha_llegada,
        r.fecha_salida,
        rh.nro_noches,
        r.nombre,
        r.nro_personas,
        r.lugar_procedencia,
        r.estado_pago,
        r.nro_registro_maestro,
        r.observaciones_hospedaje,
        r.observaciones_pago,
         COUNT(rh.nro_habitacion) AS cantidad_habitaciones,
        GROUP_CONCAT(rh.nro_habitacion ORDER BY rh.nro_habitacion) AS nro_habitacion
      FROM reservas r
      INNER JOIN reservahabitaciones rh ON r.nro_reserva = rh.nro_reserva
      WHERE r.nro_reserva = rh.nro_reserva
      GROUP BY r.id_reserva, r.nro_reserva, r.fecha_llegada, r.fecha_salida, rh.nro_noches, r.nombre, r.nro_personas, r.lugar_procedencia, r.estado_pago, r.nro_registro_maestro, r.observaciones_hospedaje, r.observaciones_pago";
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
        date_default_timezone_set('America/Lima');
        $id_unidad_de_negocio = 3;
        $nro_registro_maestro = NULL;
        $nro_reserva = $data['nro_reserva'];
        $nombre = $data['nombre'];
        $lugar_procedencia = $data['lugar_procedencia'];
        $id_modalidad = $data['id_modalidad'];
        $fecha_llegada = $data['fecha_llegada'];
        $hora_llegada = $data['hora_llegada'];
        $fecha_salida = $data['fecha_salida'];
        $tipo_transporte = $data['tipo_transporte'];
        $telefono = $data['telefono'];
        $observaciones_hospedaje = $data['observaciones_hospedaje'];
        $observaciones_pago = $data['observaciones_pago'];
        $nro_personas = $data['nro_personas'];
        $nro_adultos = $data['nro_adultos'];
        $nro_niños = $data['nro_niños'];
        $nro_infantes = $data['nro_infantes'];
        $monto_total = $data['monto_total'];
        $adelanto = $data['adelanto'];
        $porcentaje_pago = $data['porcentaje_pago'];
        $habitaciones = $data['habitaciones'];
        $fecha_pago = date('Y-m-d');
        $forma_pago = $data['forma_pago'];
        $estado_pago = 0;

        $sql = "INSERT INTO reservas (
            id_unidad_de_negocio,
            nro_reserva,
            nro_registro_maestro,
            nombre,
            lugar_procedencia,
            id_modalidad,
            fecha_llegada,
            hora_llegada,
            fecha_salida,
            tipo_transporte,
            telefono,
            observaciones_hospedaje,
            observaciones_pago,
            nro_personas,
            nro_adultos,
            nro_niños,
            nro_infantes,
            monto_total,
            adelanto,
            porcentaje_pago,
            fecha_pago,
            forma_pago,
            estado_pago
        ) VALUES (
            '$id_unidad_de_negocio',
            '$nro_reserva',
            '$nro_registro_maestro',
            '$nombre',
            '$lugar_procedencia',
            '$id_modalidad',
            '$fecha_llegada',
            '$hora_llegada',
            '$fecha_salida',
            '$tipo_transporte',
            '$telefono',
            '$observaciones_hospedaje',
            '$observaciones_pago',
            '$nro_personas',
            '$nro_adultos',
            '$nro_niños',
            '$nro_infantes',
            '$monto_total',
            '$adelanto',
            '$porcentaje_pago',
            '$fecha_pago',
            '$forma_pago',
            '$estado_pago'
        )";
        if ($conn->query($sql) === TRUE) {
            echo json_encode($responseData);
            // Obtener el último valor
            $ano_peru = date('y');
            $codigo = "RE" . $ano_peru;
            actualizarNumeroCorrelativoReserva($codigo);

        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
        break;
    case 'PUT':
        // Actualizar modulos
        $data = json_decode(file_get_contents('php://input'), true);
        $id_reserva = $data['id_reserva'];
        $id_unidad_de_negocio = $data['id_unidad_de_negocio'];
        $nro_reserva = $data['nro_reserva'];
        $nro_registro_maestro = $data['nro_registro_maestro'];
        $nombre = $data['nombre'];
        $lugar_procedencia = $data['lugar_procedencia'];
        $id_modalidad = $data['id_modalidad'];
        $fecha_llegada = $data['fecha_llegada'];
        $hora_llegada = $data['hora_llegada'];
        $fecha_salida = $data['fecha_salida'];
        $tipo_transporte = $data['tipo_transporte'];
        $telefono = $data['telefono'];
        $observaciones_hospedaje = $data['observaciones_hospedaje'];
        $observaciones_pago = $data['observaciones_pago'];
        $nro_personas = $data['nro_personas'];
        $nro_adultos = $data['nro_adultos'];
        $nro_niños = $data['nro_niños'];
        $nro_infantes = $data['nro_infantes'];
        $monto_total = $data['monto_total'];
        $adelanto = $data['adelanto'];
        $porcentaje_pago = $data['porcentaje_pago'];
        $fecha_pago = $data['fecha_pago'];
        $forma_pago = $data['forma_pago'];

        $sql = "UPDATE reservas 
        SET id_unidad_de_negocio = '$id_unidad_de_negocio',
        nro_reserva = '$nro_reserva',
        nro_habitacion = '$nro_habitacion',
        nro_registro_maestro = '$nro_registro_maestro',
        nombre = '$nombre',
        lugar_procedencia = '$lugar_procedencia',
        id_modalidad = '$id_modalidad',
        fecha_llegada = '$fecha_llegada',
        hora_llegada = '$hora_llegada',
        fecha_salida = '$fecha_salida',
        tipo_transporte = '$tipo_transporte',
        telefono = '$telefono',
        observaciones_hospedaje = '$observaciones_hospedaje',
        observaciones_pago = '$observaciones_pago',
        nro_personas = '$nro_personas',
        nro_adultos = '$nro_adultos',
        nro_niños = '$nro_niños',
        monto_total = '$monto_total',
        adelanto = '$adelanto',
        porcentaje_pago = '$porcentaje_pago',
        fecha_pago = '$fecha_pago',
        forma_pago = '$forma_pago'
        WHERE id_reserva = '$id_reserva'";

        if ($conn->query($sql) === TRUE) {
            echo "Modulo actualizado exitosamente.";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
        break;
    case 'UPDATE':
        // Actualizar modulos
        $data = json_decode(file_get_contents('php://input'), true);
        $nro_reserva = $data['nro_reserva'];
        $fecha_llegada = $data['fecha_llegada'];
        $fecha_salida = $data['fecha_salida'];
        $observaciones_hospedaje = $data['observaciones_hospedaje'];
        $observaciones_pago = $data['observaciones_pago'];

        $sql = "UPDATE reservas 
            SET fecha_llegada = '$fecha_llegada',
            fecha_salida = '$fecha_salida',
            observaciones_hospedaje = '$observaciones_hospedaje',
            observaciones_pago = '$observaciones_pago'
            WHERE nro_reserva = '$nro_reserva'";

        if ($conn->query($sql) === TRUE) {
            echo "Modulo actualizado exitosamente.";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
        break;
    case 'CHECKIN':
        // Actualizar modulos
        $data = json_decode(file_get_contents('php://input'), true);
        date_default_timezone_set('America/Lima');
        $ano_peru = date('y');
        $nro_reserva = $data['nro_reserva'];
        $codigo = "HT" . $ano_peru;

        $ultimo_valor = obtenerCorrelativoHotel($codigo);
        $nro_maestro = $codigo . str_pad($ultimo_valor + 1, 6, '0', STR_PAD_LEFT);
        actualizarNumeroCorrelativoHotel($codigo);

        $sql = "UPDATE reservas 
    SET nro_registro_maestro = '$nro_maestro',
    estado_pago = 1
    WHERE nro_reserva = '$nro_reserva'";
        if ($conn->query($sql) === TRUE) {
            $sql2 = "SELECT  r.nombre, r.lugar_procedencia, r.id_modalidad, r.id_unidad_de_negocio, r.nro_registro_maestro, r.nro_reserva, rh.precio_unitario, r.nro_personas, r.fecha_llegada, r.hora_llegada, r.fecha_salida FROM reservas r
            INNER JOIN reservahabitaciones rh ON rh.nro_reserva = r.nro_reserva
            WHERE r.nro_reserva =  '$nro_reserva'";
            $result2 = $conn->query($sql2);
            if ($result2->num_rows > 0) {
                $row2 = $result2->fetch_assoc();
                $id_unidad_de_negocio = $row2["id_unidad_de_negocio"];
                $nro_registro_maestro = $row2["nro_registro_maestro"];
                $tipo_de_servicio = "HOTEL";
                $nombre = $row2["nombre"];
                $lugar_procedencia = $row2["lugar_procedencia"];
                $id_modalidad = $row2["id_modalidad"];
                $nro_personas = $row2["nro_personas"];
                $fecha_in2 = $row2["fecha_llegada"];
                $hora_in2 = $row2["hora_llegada"];
                $fecha_out2 = $row2["fecha_salida"];
                $valor = $row2["precio_unitario"];

                $insert_checking = "INSERT INTO cheking (
                    id_unidad_de_negocio,
                    nro_registro_maestro,
                    tipo_de_servicio,
                    nro_reserva,
                    nombre,
                    lugar_procedencia,
                    id_modalidad,
                    nro_personas,
                    fecha_in,
                    hora_in,
                    fecha_out
                ) VALUES (
                '$id_unidad_de_negocio',
                '$nro_registro_maestro',
                '$tipo_de_servicio',
                '$nro_reserva',
                '$nombre',
                '$lugar_procedencia',
                '$nro_personas',
                '$id_modalidad',
                '$fecha_in2',
                '$hora_in2',
                '$fecha_out2'
                )";
                if ($conn->query($insert_checking) === TRUE) {
                    $id_checkin = $conn->insert_id;
                    $sql2 = "SELECT rh.nro_personas, rh.nro_habitacion, h.id_producto, rh.precio_unitario, r.fecha_llegada, r.fecha_salida, r.hora_llegada FROM reservahabitaciones rh
                    INNER JOIN reservas r ON r.nro_reserva = rh.nro_reserva
                    INNER JOIN habitaciones h ON rh.nro_habitacion = h.nro_habitacion
                    WHERE r.nro_reserva = '$nro_reserva'";

                    $resultado = $conn->query($sql2);
                    if ($resultado->num_rows > 0) {
                        $datos = array(
                            'Habitaciones' => array(),
                        );

                        while ($fila = $resultado->fetch_assoc()) {
                            $habitacion = array(
                                'Nro Personas' => $fila['nro_personas'],
                                'precio unitario' => $fila['precio_unitario'],
                                'id producto' => $fila['id_producto'],
                                'Nro habitacion' => $fila['nro_habitacion'],
                                'Fecha llegada' => $fila['fecha_llegada'],
                                'Fecha salida' => $fila['fecha_salida'],
                                'Hora llegada' => $fila['hora_llegada'],
                            );

                            $datos['Habitaciones'][] = $habitacion;
                        }

                        foreach ($datos['Habitaciones'] as $habitacion) {
                            $nro_personas = $habitacion['Nro Personas'];
                            $precio_unitario = $habitacion['precio unitario'];
                            $id_producto = $habitacion['id producto'];
                            $nroHabitacion = $habitacion['Nro habitacion']; // Mover esta línea al bucle externo
                            $fechaLlegada = new DateTime($habitacion['Fecha llegada']);
                            $fechaSalida = new DateTime($habitacion['Fecha salida']);
                            $horaLlegada = $habitacion['Hora llegada'];

                            $resultados = array();

                            for ($fecha = clone $fechaLlegada; $fecha < $fechaSalida; $fecha->modify('+1 day')) {
                                $resultado = array(
                                    'nro_personas' => $nro_personas,
                                    'precio_unitario' => $precio_unitario,
                                    'id_producto' => $id_producto,
                                    'nro_habitacion' => $nroHabitacion, // Mantener el mismo número de habitación
                                    'fecha' => $fecha->format('Y-m-d'),
                                    'hora' => $horaLlegada,
                                );

                                $resultados[] = $resultado;

                                // Cambiamos la hora de llegada a '12:00' para las siguientes filas de la misma habitación
                                $horaLlegada = '12:00';
                            }

                            // Reemplazamos el elemento actual en el array con los resultados
                            $habitacion = $resultados;

                            // Luego, ejecutamos la consulta de inserción para cada conjunto de datos
                            foreach ($habitacion as $resultado) {
                                $nro_personas = $resultado['nro_personas'];
                                $precio_unitario = $resultado['precio_unitario'];
                                $id_producto = $resultado['id_producto'];
                                $nro_habitacion = $resultado['nro_habitacion'];
                                $fecha = $resultado['fecha'];
                                $hora = $resultado['hora'];
                                $estado = 'NA';
                                // Luego, ejecutamos la consulta de inserción para cada conjunto de datos
                                $insert_rooming = "INSERT INTO rooming (
                                    id_checkin,
                                    nro_registro_maestro,
                                    nro_habitacion,
                                    id_producto,
                                    fecha,
                                    hora,
                                    nro_personas,
                                    tarifa,
                                    estado
                                ) VALUES (
                                    '$id_checkin',
                                    '$nro_registro_maestro',
                                    '$nro_habitacion',
                                    '$id_producto',
                                    '$fecha',
                                    '$hora',
                                    '$nro_personas',
                                    '$precio_unitario',
                                    '$estado'
                                )";
                                //aqui insertaremos los datos de documento detalle
                                $tipo_movimiento = 'SA';
                                $nivel_descargo = 1;
                                $cantidad = 1;
                                $tipo_de_unidad = 'UNID';
                                $precio_unitario = $valor;
                                $precio_total = $valor;
                                $fecha_hora_registro = date('Y-m-d H:i:s');
                                $insert_documento_detalle = "INSERT INTO documento_detalle (
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
                                    '$tipo_movimiento',
                                    '$nro_registro_maestro',
                                    '$fecha',
                                    '$id_producto',
                                    '$nivel_descargo',
                                    '$nro_habitacion',
                                    '$cantidad',
                                    '$tipo_de_unidad',
                                    '$precio_unitario',
                                    '$precio_total',
                                    '$fecha',
                                    '1',
                                    '$fecha_hora_registro'
                                )";
                                $resulto2 = $conn->query($insert_rooming);
                                $resulto3 = $conn->query($insert_documento_detalle);
                            }
                            if ($resulto2 === TRUE) {
                                echo "Rooming's insertados exitosamente.";
                                //imprimimos en json el habitacion
                                echo json_encode($habitacion);

                            } else {
                                echo "Error: " . $sql . "<br>" . $conn->error;
                            }
                        }
                        // agregamos la persona como acompañante
                        $insert_acompanante = "INSERT INTO acompanantes (
                            nro_registro_maestro,
                            tipo_de_servicio,
                            nro_de_orden_unico,
                            nro_habitacion,
                            apellidos_y_nombres
                        ) VALUES (
                            '$nro_registro_maestro',
                            '$tipo_de_servicio',
                            0,
                            '$nro_habitacion',
                            '$nombre'
                        )";

                        $resulto4 = $conn->query($insert_acompanante);

                    } else {
                        echo "No se encontraron reservas.";
                    }
                } else {
                    echo "Error: " . $insert_checking . "<br>" . $conn->error;
                }
            } else {
                echo "no hay datos";
            }
        } else {
            echo "Error: " . $sql2 . "<br>" . $conn->error;
        }

        break;
    case 'DELETE':
        $data = json_decode(file_get_contents('php://input'), true);
        echo json_encode($data);

        foreach ($data as $item) {
            $id_reserva = $item["id"];
            // Ejecutar la consulta de eliminación para cada ID de reserva
            $sql = "DELETE FROM reservahabitaciones WHERE id_reserva_habitaciones = $id_reserva";

            if ($conn->query($sql) === TRUE) {
                echo "Reserva eliminada exitosamente.";
            } else {
                echo "Error al eliminar la reserva: " . $conn->error;
            }
        }

        break;
    case 'ESTADO':
        // Eliminar unidaddenegocio
        $data = json_decode(file_get_contents('php://input'), true);
        $id_reserva = $data['id_reserva'];
        $fecha_pago = date('Y-m-d');
        $sql = "UPDATE reservas 
        SET
        estado_pago = 1,
        fecha_pago = '$fecha_pago'
        WHERE id_reserva = '$id_reserva'";

        if ($conn->query($sql) === TRUE) {
            echo "modulo pago completado exitosamente.";
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