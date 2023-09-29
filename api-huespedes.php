<?php
// Configuración de la base de datos
require_once 'config.php';
require_once('incrementador.php');
// Manejar los métodos HTTP
$metodo = $_SERVER['REQUEST_METHOD'];
date_default_timezone_set('America/Lima');
switch ($metodo) {
    case 'GET':
        // Listar unidaddenegocio
        $sql = "SELECT * FROM checking";

        if (isset($_GET["codigo"])) {
            $codigo = $_GET["codigo"];
            $sql .= " WHERE nro_reserva = '$codigo'";
        }

        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $personas = array();
            while ($row = $result->fetch_assoc()) {
                $personas[] = $row;
            }
            echo json_encode($personas);
        } else {
            if (isset($_GET["codigo"])) {
                echo "No se encontraron personas naturales o jurídicas con el código proporcionado.";
            } else {
                echo "No se encontraron personas naturales o jurídicas.";
            }
        }
        break;
    case 'POST':
        // Insertar nueva unidaddenegocio
        $data = json_decode(file_get_contents('php://input'), true);
        // Extract data from the $data array and assign them to variables
        $tipo_de_servicio = "HOTEL";
        $id_unidad_de_negocio = 3;
        $apellidos = $data['apellidos'];
        $nombres = $data['nombres'];
        $tipo_documento = $data['tipo_documento'];
        $ocupacion = $data['ocupacion'];
        $ciudad = $data['ciudad'];
        $celular = $data['celular'];
        $email = $data['email'];
        $fecha_in = $data['fecha_in'];
        $fecha_out = $data['fecha_out'];
        $nro_habitacion = $data['nro_habitacion'];
        $nro_reserva = $data['nro_reserva'];
        $nro_registro = $data['nro_registro'];
        $direccion = $data['direccion'];
        $direccion_comprobante = $data['direccion_comprobante'];
        $edad = $data['edad'];
        $estacionamiento = $data['estacionamiento'];
        $fecha_nacimiento = $data['fecha_nacimiento'];
        $hora_in = $data['hora_in'];
        $hora_out = $data['hora_out'];
        $lugar_de_nacimiento = $data['lugar_de_nacimiento'];
        $ocupacion = $data['ocupacion'];
        $valor = $data['valor'];
        $nro_placa = $data['nro_placa'];
        $nombre = $apellidos . " " . $nombres;
        $forma_pago  = $data['forma_pago'];
        $nro_adultos = $data['nro_adultos'];
        $nro_ninos = $data['nro_nino'];
        $nro_infantes = $data['nro_infantes'];
        $nro_personas = $nro_adultos + $nro_ninos + $nro_infantes;
        $nro_documento = $data['nro_documento'];
        $nro_documento_comprobante = $data['nro_documento_comprobante'];
        $razon_social = $data['razon_social'];
        $tipo_comprobante = $data['tipo_comprobante'];
        $tipo_documento_comprobante = $data['tipo_documento_comprobante'];
        $sexo = $data['sexo'];
        //comprobar si el nro_documento existe en la tabla persona natural juridica y terapistas
        $sql = "SELECT * FROM personanaturaljuridica WHERE nro_documento = '$nro_documento'";
        $result = $conn->query($sql);
        //condicionamos un if con la variable result para ver si encontro nro_documento
        if($result->num_rows > 0){
            $row = $result->fetch_assoc();
            $id_persona = $row["id_persona"];
            // Insertar en la tabla checking 
            $insert_huesped_query = "INSERT INTO cheking (
                tipo_de_servicio,
                id_unidad_de_negocio,
                nro_adultos,
                nro_ninos,
                nombre,
                id_persona,
                lugar_procedencia,
                telefono,
                fecha_in,
                fecha_out,
                nro_reserva,
                nro_registro_maestro,
                estacionamiento,
                hora_in,
                hora_out, 
                nro_placa,
                nro_documento,
                tipo_documento,
                forma_pago,
                nro_personas,
                nro_habitacion,
                direccion_comprobante,
                nro_documento_comprobante,
                razon_social,
                tipo_comprobante,
                tipo_documento_comprobante,
                sexo
            ) VALUES (
                '$tipo_de_servicio',
                '$id_unidad_de_negocio',
                '$nro_adultos',
                '$nro_ninos',
                '$nombre',
                '$id_persona',
                '$lugar_de_nacimiento',
                '$celular',
                '$fecha_in',
                '$fecha_out',
                '$nro_reserva',
                '$nro_registro',
                '$estacionamiento',
                '$hora_in',
                '$hora_out',
                '$nro_placa',
                '$nro_documento',
                '$tipo_documento',
                '$forma_pago',
                '$nro_personas',
                '$nro_habitacion',
                '$direccion_comprobante',
                '$nro_documento_comprobante',
                '$razon_social',
                '$tipo_comprobante',
                '$tipo_documento_comprobante',
                '$sexo'
            )";

            if ($conn->query($insert_huesped_query) === TRUE) {
                $id_checkin = $conn->insert_id;
                $ano_peru = date('y');
                $codigo = "HT". $ano_peru;
                actualizarNumeroCorrelativoHotel($codigo);
                $acompanantes = $data['acompanantes'];
                $nro_documento2 = $nro_documento;
                $nro_de_orden_unico = 0;
                echo json_encode($acompanantes);
                    foreach($acompanantes as $nro_de_orden_unico => $item3){
                        $apellidos_y_nombres = $item3['nombre'];
                        $edad = $item3['edad'];
                        $sexo = $item3['sexo'];
                        $parentesco = $item3['parentesco'];
                        $insert_acopanantes_query = "INSERT INTO acompanantes (
                            nro_registro_maestro, nro_documento, tipo_de_servicio, nro_de_orden_unico, nro_habitacion, apellidos_y_nombres, sexo, edad, parentesco
                        ) VALUES (
                            '$nro_registro', '$nro_documento2','$tipo_de_servicio', '$nro_de_orden_unico', '$nro_habitacion', '$apellidos_y_nombres', '$sexo', '$edad', '$parentesco'
                        )";
                        $nro_documento2 = "";
                        //comprobar si se inserto los datos en la tabla acompanantes
                        $resulto = $conn->query($insert_acopanantes_query);
                        // Incrementar $nro_de_orden_unico aquí, después de insertar el registro
                        $nro_de_orden_unico++;
                    }
                    if ($resulto === TRUE) {
                    //comprobar si se inserto los datos en la tabla rooming
                    $id_producto = obtenerProductoconhabitacion($nro_habitacion);
                    $fechaLlegada = new DateTime($fecha_in);
                    $fechaSalida = new DateTime($fecha_out);
                    $hora = $hora_in;
                    $estado = 'NA';
                    $fechasArray = array(); // Creamos un array para almacenar las fechas y horas de llegada

                    for ($fecha = clone $fechaLlegada; $fecha < $fechaSalida; $fecha->modify('+1 day')) {
                        $fechaFormateada = $fecha->format('Y-m-d');
                        $fechasArray[] = array(
                            'fecha' => $fechaFormateada,
                            'horaLlegada' => $hora
                        );
                        // Cambiamos la hora de llegada a '12:00' para las siguientes filas de la misma habitación
                        $hora = '12:00';
                    }
                    foreach ($fechasArray as $item){
                        $fecha = $item['fecha'];
                        $horaLlegada = $item['horaLlegada'];
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
                            '$nro_registro',
                            '$nro_habitacion',
                            '$id_producto',
                            '$fecha',
                            '$horaLlegada',
                            '$nro_personas',
                            '$valor',
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
                            '$nro_registro',
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
                        //comprobar si se inserto los datos en la tabla rooming
                        
                    }
                    if ($resulto2 === TRUE) {
                        echo "se inserto dentro del rooming <br>";
                        echo "<br>";
                    } else {
                        echo "Error: " . $insert_rooming . "<br>" . $conn->error;
                    }
                            
                } else {
                    echo "Error insertando en la tabla acompanantes: " . $conn->error;
                }
            } else {
                echo "Error insertando en la tabla personanaturaljuridica: " . $conn->error;
            }
        } else {
            $tipo_persona = 0;
            $sexo = 'N';
            $nacionalidad = 'NA';
            $pais = 'NA';
            $id_usuario_creacion = 0;
            $fecha_creacion = date('Y-m-d');
            $sql2 = "INSERT INTO personanaturaljuridica (
                tipo_persona, tipo_documento, nro_documento, apellidos, nombres,
                sexo, lugar_de_nacimiento, fecha, edad, nacionalidad, ocupacion, direccion,
                ciudad, pais, celular, email, id_usuario_creacion, fecha_creacion
            ) VALUES (
                '$tipo_persona', '$tipo_documento', '$nro_documento', '$apellidos', '$nombres',
                '$sexo', '$lugar_de_nacimiento', '$fecha_nacimiento', '$edad', '$nacionalidad', '$ocupacion', '$direccion',
                '$ciudad', '$pais', '$celular', '$email', '$id_usuario_creacion', '$fecha_creacion'
            )";
                if ($conn->query($sql2) === TRUE) {
                    $id_persona = $conn->insert_id;
                    // Insertar en la tabla checking
                    $insert_huesped_query = "INSERT INTO cheking (
                        tipo_de_servicio,
                        id_unidad_de_negocio,
                        nro_adultos,
                        nro_ninos,
                        nombre,
                        id_persona,
                        lugar_procedencia,
                        telefono,
                        fecha_in,
                        fecha_out,
                        nro_reserva,
                        nro_registro_maestro,
                        estacionamiento,
                        hora_in,
                        hora_out, 
                        nro_placa,
                        nro_documento,
                        tipo_documento,
                        forma_pago,
                        nro_personas,
                        nro_habitacion,
                        direccion_comprobante,
                        nro_documento_comprobante,
                        razon_social,
                        tipo_comprobante,
                        tipo_documento_comprobante,
                        sexo
                    ) VALUES (
                        '$tipo_de_servicio',
                        '$id_unidad_de_negocio',
                        '$nro_adultos',
                        '$nro_ninos',
                        '$nombre',
                        '$id_persona',
                        '$lugar_de_nacimiento',
                        '$celular',
                        '$fecha_in',
                        '$fecha_out',
                        '$nro_reserva',
                        '$nro_registro',
                        '$estacionamiento',
                        '$hora_in',
                        '$hora_out',
                        '$nro_placa',
                        '$nro_documento',
                        '$tipo_documento',
                        '$forma_pago',
                        '$nro_personas',
                        '$nro_habitacion',
                        '$direccion_comprobante',
                        '$nro_documento_comprobante',
                        '$razon_social',
                        '$tipo_comprobante',
                        '$tipo_documento_comprobante',
                        '$sexo'
                    )";        
        
                    if ($conn->query($insert_huesped_query) === TRUE) {
                        $id_checkin = $conn->insert_id;
                        $ano_peru = date('y');
                        $codigo = "HT". $ano_peru;
                        $nro_documento2 = $nro_documento;
                        actualizarNumeroCorrelativoHotel($codigo);
                        $acompanantes = $data['acompanantes'];
                        foreach($acompanantes as $nro_de_orden_unico => $item3){
                            $apellidos_y_nombres = $item3['nombre'];
                            $edad = $item3['edad'];
                            $sexo = $item3['sexo'];
                            $parentesco = $item3['parentesco'];
                            $insert_acopanantes_query = "INSERT INTO acompanantes (
                                nro_registro_maestro, nro_documento, tipo_de_servicio, nro_de_orden_unico, nro_habitacion, apellidos_y_nombres, sexo, edad, parentesco
                            ) VALUES (
                                '$nro_registro', '$nro_documento2','$tipo_de_servicio', '$nro_de_orden_unico', '$nro_habitacion', '$apellidos_y_nombres', '$sexo', '$edad', '$parentesco'
                            )";
                            $nro_documento2 = "";
                             //comprobar si se inserto los datos en la tabla acompanantes
                             $resulto = $conn->query($insert_acopanantes_query);
                            // Incrementar $nro_de_orden_unico aquí, después de insertar el registro
                            $nro_de_orden_unico++;
                        }
                        if ($resulto === TRUE) {
                            //comprobar si se inserto los datos en la tabla rooming
                            $id_producto = obtenerProductoconhabitacion($nro_habitacion);
                            $fechaLlegada = new DateTime($fecha_in);
                            $fechaSalida = new DateTime($fecha_out);
                            $hora = $hora_in;
                            $estado = 'NA';
                            $fechasArray = array(); // Creamos un array para almacenar las fechas y horas de llegada
        
                            for ($fecha = clone $fechaLlegada; $fecha < $fechaSalida; $fecha->modify('+1 day')) {
                                $fechaFormateada = $fecha->format('Y-m-d');
                                $fechasArray[] = array(
                                    'fecha' => $fechaFormateada,
                                    'horaLlegada' => $hora
                                );
                                // Cambiamos la hora de llegada a '12:00' para las siguientes filas de la misma habitación
                                $hora = '12:00';
                            }
                            foreach ($fechasArray as $item){
                                $fecha = $item['fecha'];
                                $horaLlegada = $item['horaLlegada'];
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
                                    '$nro_registro',
                                    '$nro_habitacion',
                                    '$id_producto',
                                    '$fecha',
                                    '$horaLlegada',
                                    '$nro_personas',
                                    '$valor',
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
                                    '$nro_registro',
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
                            //comprobar si se inserto los datos en la tabla rooming
                            if ($resulto2 === TRUE) {
                                echo "se inserto dentro del if";
                                echo "<br>";
                            } else {
                                echo "Error: " . $insert_rooming . "<br>" . $conn->error;
                            }       
                        } else {
                            echo "Error insertando en la tabla acompanantes: " . $conn->error;
                        }
                       
                    } else {
                        echo "Error insertando en la tabla personanaturaljuridica: " . $conn->error;
                    }
                    
                } else {
                    echo "Error: " . $sql2 . "<br>" . $conn->error;
                }
        }
        break;
    case 'PUT':
        // Insertar nueva unidaddenegocio
        $data = json_decode(file_get_contents('php://input'), true);
        // Extract data from the $data array and assign them to variables
        date_default_timezone_set('America/Lima');
        $tipo_de_servicio = "HOTEL";
        $id_unidad_de_negocio = 3;
        $apellidos = $data['apellidos'];
        $nombres = $data['nombres'];
        $tipo_documento = $data['tipo_documento'];
        $ocupacion = $data['ocupacion'];
        $ciudad = $data['ciudad'];
        $celular = $data['celular'];
        $email = $data['email'];
        $fecha_in = $data['fecha_in'];
        $fecha_out = $data['fecha_out'];
        $nro_habitacion = $data['nro_habitacion'];
        $nro_reserva = $data['nro_reserva'];
        $nro_registro = $data['nro_registro'];
        $direccion = $data['direccion'];
        $direccion_comprobante = $data['direccion_comprobante'];
        $edad = $data['edad'];
        $estacionamiento = $data['estacionamiento'];
        $fecha_nacimiento = $data['fecha_nacimiento'];
        $hora_in = $data['hora_in'];
        $hora_out = $data['hora_out'];
        $lugar_de_nacimiento = $data['lugar_de_nacimiento'];
        $ocupacion = $data['ocupacion'];
        $valor = $data['valor'];
        $nro_placa = $data['nro_placa'];
        $nombre = $apellidos . " " . $nombres;
        $forma_pago  = $data['forma_pago'];
        $nro_adultos = $data['nro_adultos'];
        $nro_ninos = $data['nro_nino'];
        $nro_infantes = $data['nro_infantes'];
        $nro_personas = $nro_adultos + $nro_ninos + $nro_infantes;
        $nro_documento = $data['nro_documento'];
        $nro_documento_comprobante = $data['nro_documento_comprobante'];
        $razon_social = $data['razon_social'];
        $tipo_comprobante = $data['tipo_comprobante'];
        $tipo_documento_comprobante = $data['tipo_documento_comprobante'];
        $sexo = $data['sexo'];
        // Insertar en la tabla checking 
        $tipo_persona = 0;
        $nacionalidad = 'NA';
        $pais = 'NA';
        $id_usuario_creacion = 0;
        $fecha_creacion = date('Y-m-d');
        $sql2 = "INSERT INTO personanaturaljuridica (
            tipo_persona,
            tipo_documento,
            nro_documento,
            apellidos,
            nombres,
            sexo,
            lugar_de_nacimiento,
            fecha,
            edad,
            nacionalidad,
            ocupacion,
            direccion,
            ciudad,
            pais,
            celular,
            email,
            id_usuario_creacion,
            fecha_creacion
        ) VALUES (
            '$tipo_persona',
            '$tipo_documento',
            '$nro_documento',
            '$apellidos',
            '$nombres',
            '$sexo',
            '$lugar_de_nacimiento',
            '$fecha_nacimiento',
            '$edad',
            '$nacionalidad',
            '$ocupacion',
            '$direccion',
            '$ciudad',
            '$pais',
            '$celular',
            '$email',
            '$id_usuario_creacion',
            '$fecha_creacion'
        )";
        //ejecutamos la consulta de sql2 con un if
        if($conn->query($sql2) == TRUE){
            $id_persona = $conn->insert_id;
                $insert_huesped_query = "UPDATE cheking
                SET
                    id_persona = '$id_persona',
                    tipo_de_servicio = '$tipo_de_servicio',
                    nro_adultos = '$nro_adultos',
                    nro_ninos = '$nro_ninos',
                    nombre = '$nombre',
                    lugar_procedencia = '$lugar_de_nacimiento',
                    telefono = '$celular',
                    fecha_in = '$fecha_in',
                    fecha_out = '$fecha_out',
                    nro_reserva = '$nro_reserva',
                    nro_registro_maestro = '$nro_registro',
                    estacionamiento = '$estacionamiento',
                    hora_in = '$hora_in',
                    hora_out = '$hora_out',
                    nro_placa = '$nro_placa',
                    nro_documento = '$nro_documento',
                    tipo_documento = '$tipo_documento',
                    forma_pago = '$forma_pago',
                    nro_personas = '$nro_personas',
                    nro_habitacion = '$nro_habitacion',
                    direccion_comprobante = '$direccion_comprobante',
                    nro_documento_comprobante = '$nro_documento_comprobante',
                    razon_social = '$razon_social',
                    tipo_comprobante = '$tipo_comprobante',
                    tipo_documento_comprobante = '$tipo_documento_comprobante',
                    sexo = '$sexo'
                WHERE nro_reserva = '$nro_reserva'";

                if ($conn->query($insert_huesped_query) === TRUE) {
                    $nro_de_orden_unico = 0;
                    $nro_documento2 = $nro_documento;
                    $acompanantes = $data['acompanantes'];
                    foreach($acompanantes as $item){
                        $apellidos_y_nombres = $item['nombre'];
                        $edad = $item['edad'];
                        $sexo = $item['sexo'];
                        $parentesco = $item['parentesco'];
                        $insert_acopanantes_query = "INSERT INTO acompanantes (
                            nro_registro_maestro, tipo_de_servicio, nro_de_orden_unico, nro_documento, nro_habitacion, apellidos_y_nombres, sexo, edad, parentesco
                        ) VALUES (
                            '$nro_registro', '$tipo_de_servicio', '$nro_de_orden_unico', '$nro_documento2', '$nro_habitacion', '$apellidos_y_nombres', '$sexo', '$edad', '$parentesco'
                        )";
                        $nro_documento2 = "";
                        $nro_de_orden_unico++;
                        if ($conn->query($insert_acopanantes_query) === TRUE) {
                            echo "se inserto acompañante" . $nro_de_orden_unico;
                        } else {
                            echo "Error insertando en la tabla acompanantes: " . $conn->error;
                        }
                    }

                } else {
                    echo "Error insertando en la tabla personanaturaljuridica: " . $conn->error;
                }
        }else{
            echo "Error: " . $sql2 . "<br>" . $conn->error;
        }
        
        break;
    case 'DELETE':
        $data = json_decode(file_get_contents('php://input'), true);
        $id_profesional = $data['id_profesional'];

        $sql = "DELETE FROM terapistas WHERE id_profesional = $id_profesional";

        if ($conn->query($sql) === TRUE) {
            echo "Terapista eliminado exitosamente.";
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