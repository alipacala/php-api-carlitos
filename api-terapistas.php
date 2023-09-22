<?php
// Configuración de la base de datos
require_once 'config.php';

// Manejar los métodos HTTP
$metodo = $_SERVER['REQUEST_METHOD'];

switch ($metodo) {
    case 'GET':
        // Listar unidaddenegocio
        $sql = "SELECT * FROM terapistas";

        if (isset($_GET["codigo"])) {
            $codigo = $_GET["codigo"];
            $sql .= " WHERE nro_documento = '$codigo'";
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
        $tipo_documento = 0;
        $nro_documento = $data['nro_documento'];
        $apellidos = $data['apellidos'];
        $nombres = $data['nombres'];
        $sexo = $data['sexo'];
        $fecha_de_nacimiento = $data['fecha_de_nacimiento'];
        $lugar_de_nacimiento = $data['lugar_de_nacimiento'];
        $estado_civil = $data['estado_civil'];
        $nombre_del_conyugue = $data['nombre_del_conyugue'];
        $tipo_de_cliente = $data['tipo_de_cliente'];
        $direccion = $data['direccion'];
        $distrito = $data['distrito'];
        $provincia = $data['provincia'];
        $telefono = $data['telefono'];
        $celular = $data['celular'];
        $Email = $data['Email'];
        $contacto_de_Emergencia = $data['contacto_de_Emergencia'];
        $direccion_familia = $data['direccion_familia'];
        $telefono_familia = $data['telefono_familia'];
        $compania_que_pertenece = $data['compania_que_pertenece'];
        $fecha_ingreso = date('Y-m-d');
        $baja = 0;
        $fecha_de_baja = NULL;
        $hora_de_ingreso = $data['hora_ingreso'];
        $hora_de_salida = $data['hora_salida'];
        $hora_de_ingreso2 = $data['hora_de_ingreso2'];
        $hora_de_salida2 = $data['hora_de_salida2'];
        $dia_descanso = $data['dia_descanso'];
        $area_de_trabajo = $data['area_de_trabajo'];
        $cargo = $data['cargo'];
        $usuario = $data['usuario'];
        $clave_acceso = $data['clave_acceso'];
        $nro_autogenerado = $data['nro_autogenerado'];
        $nro_cussp = $data['nro_cussp'];
        $tipo_de_trabajo = $data['tipo_de_trabajo'];
        $haber_basico = $data['haber_basico'];
        $asignacion_familiar = $data['asignacion_familiar'];
        $nro_hijos = $data['nro_hijos'];
        $dependiente = $data['dependiente'];
        $habilidades = $data['habilidades'];

        //Varibales para persona natural
        $tipo_persona = "NATU";
        $fecha = date('Y-m-d');
        $id_usuario_creacion = 0;
        $nacionalidad = "PER";
        $pais = "PER";
        $email = $Email;
        $ciudad = $provincia;
        $ocupacion = $cargo;
        $fecha_creacion = date('Y-m-d H:i:s');
        function obtener_edad_segun_fecha($fechaNacimiento)
        {
            $nacimiento = new DateTime($fechaNacimiento);
            $ahora = new DateTime(date("Y-m-d"));
            $diferencia = $ahora->diff($nacimiento);
            return $diferencia->format("%y");
        }
        $edad = obtener_edad_segun_fecha($fecha_de_nacimiento);

        // Insertar en la tabla personanaturaljuridica
        $insert_person_query = "INSERT INTO personanaturaljuridica (
            tipo_persona, tipo_documento, nro_documento, apellidos, nombres,
            sexo, lugar_de_nacimiento, fecha, edad, nacionalidad, ocupacion, direccion,
            ciudad, pais, celular, email, id_usuario_creacion, fecha_creacion
        ) VALUES (
            '$tipo_persona', '$tipo_documento', '$nro_documento', '$apellidos', '$nombres',
            '$sexo', '$lugar_de_nacimiento', '$fecha', '$edad', '$nacionalidad', '$ocupacion', '$direccion',
            '$ciudad', '$pais', '$celular', '$email', '$id_usuario_creacion', '$fecha_creacion'
        )";

        if ($conn->query($insert_person_query) === TRUE) {
            // Obtenemos el id_persona
            $id_persona = $conn->insert_id;

            if ($id_persona) {
                // Insertar en la tabla terapistas
                $insert_terapistas_query = "INSERT INTO terapistas (
                    id_persona, tipo_documento, nro_documento, apellidos, nombres, sexo, fecha_de_nacimiento,
                    lugar_de_nacimiento, estado_civil, nombre_del_conyugue, tipo_de_cliente, direccion, distrito,
                    provincia, telefono, celular, Email, contacto_de_Emergencia, direccion_familia, telefono_familia,
                    compania_que_pertenece, fecha_ingreso, baja, fecha_de_baja, hora_de_ingreso, hora_de_salida,
                    hora_de_ingreso2, hora_de_salida2, dia_descanso, area_de_trabajo, cargo, usuario, clave_acceso,
                    nro_autogenerado, nro_cussp, tipo_de_trabajo, haber_basico, asignacion_familiar, nro_hijos, dependiente
                ) VALUES (
                    '$id_persona','$tipo_documento','$nro_documento', '$apellidos', '$nombres', '$sexo', '$fecha_de_nacimiento',
                    '$lugar_de_nacimiento', '$estado_civil', '$nombre_del_conyugue', '$tipo_de_cliente', '$direccion', '$distrito',
                    '$provincia', '$telefono', '$celular', '$Email', '$contacto_de_Emergencia', '$direccion_familia', '$telefono_familia',
                    '$compania_que_pertenece', '$fecha_ingreso', '$baja', '$fecha_de_baja', '$hora_de_ingreso', '$hora_de_salida',
                    '$hora_de_ingreso2', '$hora_de_salida2', '$dia_descanso', '$area_de_trabajo', '$cargo', '$usuario', '$clave_acceso',
                    '$nro_autogenerado', '$nro_cussp', '$tipo_de_trabajo', '$haber_basico', '$asignacion_familiar', '$nro_hijos', '$dependiente'
                )";

                if ($conn->query($insert_terapistas_query) === TRUE){
                    foreach ($habilidades as $item) {
                        // Insertar en la tabla terapistashabilidades
                        $id_habilidad = $item["id_habilidad"];
                        $insert_habilidades_query = "INSERT INTO terapistashabilidades (
                            id_persona, id_habilidad
                        ) VALUES (
                            '$id_persona', '$id_habilidad'
                        )";

                        if ($conn->query($insert_habilidades_query) === TRUE) {
                            echo "Nuevo registro insertado exitosamente en terapistashabilidades.";
                        } else {
                            echo "Error: " . $insert_habilidades_query . "<br>" . $conn->error;
                        }
                    }
                    echo "Nuevo Terapista Agregado!";
                } else {
                    echo "Error insertando en la tabla terapistas: " . $conn->error;
                }
            } else {
                echo "Error al obtener id_persona después de la inserción.";
            }
        } else {
            echo "Error insertando en la tabla personanaturaljuridica: " . $conn->error;
        }

        break;
    case 'PUT':
        // Actualizar unidaddenegocio
        $data = json_decode(file_get_contents('php://input'), true);
        $id_profesional = $data['id_profesional'];
        $tipo_documento = $data['tipo_documento'];
        $nro_documento = $data['nro_documento'];
        $apellidos = $data['apellidos'];
        $nombres = $data['nombres'];
        $sexo = $data['sexo'];
        $fecha_de_nacimiento = $data['fecha_de_nacimiento'];
        $lugar_de_nacimiento = $data['lugar_de_nacimiento'];
        $estado_civil = $data['estado_civil'];
        $nombre_del_conyugue = $data['nombre_del_conyugue'];
        $tipo_de_cliente = $data['tipo_de_cliente'];
        $direccion = $data['direccion'];
        $distrito = $data['distrito'];
        $provincia = $data['provincia'];
        $telefono = $data['telefono'];
        $celular = $data['celular'];
        $Email = $data['Email'];
        $contacto_de_Emergencia = $data['contacto_de_Emergencia'];
        $direccion_familia = $data['direccion_familia'];
        $telefono_familia = $data['telefono_familia'];
        $compania_que_pertenece = $data['compania_que_pertenece'];
        $fecha_ingreso = $data['fecha_ingreso'];
        $baja = $data['baja'];
        $fecha_de_baja = $data['fecha_de_baja'];
        $hora_de_ingreso = $data['hora_de_ingreso'];
        $hora_de_salida = $data['hora_de_salida'];
        $hora_de_ingreso2 = $data['hora_de_ingreso2'];
        $hora_de_salida2 = $data['hora_de_salida2'];
        $dia_descanso = $data['dia_descanso'];
        $area_de_trabajo = $data['area_de_trabajo'];
        $cargo = $data['cargo'];
        $usuario = $data['usuario'];
        $clave_acceso = $data['clave_acceso'];
        $nro_autogenerado = $data['nro_autogenerado'];
        $nro_cussp = $data['nro_cussp'];
        $tipo_de_trabajo = $data['tipo_de_trabajo'];
        $haber_basico = $data['haber_basico'];
        $asignacion_familiar = $data['asignacion_familiar'];
        $nro_hijos = $data['nro_hijos'];
        $dependiente = $data['dependiente'];

        $sql = "UPDATE terapistas
        SET
            tipo_documento = '$tipo_documento',
            apellidos = '$apellidos',
            nombres = '$nombres',
            sexo = '$sexo',
            fecha_de_nacimiento = '$fecha_de_nacimiento',
            lugar_de_nacimiento = '$lugar_de_nacimiento',
            estado_civil = '$estado_civil',
            nombre_del_conyugue = '$nombre_del_conyugue',
            tipo_de_cliente = '$tipo_de_cliente',
            direccion = '$direccion',
            distrito = '$distrito',
            provincia = '$provincia',
            telefono = '$telefono',
            celular = '$celular',
            Email = '$Email',
            contacto_de_Emergencia = '$contacto_de_Emergencia',
            direccion_familia = '$direccion_familia',
            telefono_familia = '$telefono_familia',
            compania_que_pertenece = '$compania_que_pertenece',
            fecha_ingreso = '$fecha_ingreso',
            baja = '$baja',
            fecha_de_baja = '$fecha_de_baja',
            hora_de_ingreso = '$hora_de_ingreso',
            hora_de_salida = '$hora_de_salida',
            hora_de_ingreso2 = '$hora_de_ingreso2',
            hora_de_salida2 = '$hora_de_salida2',
            dia_descanso = '$dia_descanso',
            area_de_trabajo = '$area_de_trabajo',
            cargo = '$cargo',
            usuario = '$usuario',
            clave_acceso = '$clave_acceso',
            nro_autogenerado = '$nro_autogenerado',
            nro_cussp = '$nro_cussp',
            tipo_de_trabajo = '$tipo_de_trabajo',
            haber_basico = '$haber_basico',
            asignacion_familiar = '$asignacion_familiar',
            nro_hijos = '$nro_hijos',
            dependiente = '$dependiente'
        WHERE
            id_profesional = '$id_profesional'";

        if ($conn->query($sql) === TRUE) {
            echo "Terapista actualizado exitosamente.";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
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