<?php
// Configuración de la base de datos
require_once 'config.php';
// Manejar los métodos HTTP
$metodo = $_SERVER['REQUEST_METHOD'];

switch ($metodo) {
    case 'GET':
        // Listar usuarios
        $sql = "SELECT * FROM usuarios";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            $usuarios = array();
            while ($row = $result->fetch_assoc()) {
                $usuarios[] = $row;
            }
            echo json_encode($usuarios);
        } else {
            echo "No se encontraron usuarios.";
        }
    break;
    case 'INNER':
    // Listar unidaddenegocio
    $sql = "SELECT u.id_usuario, u.nro_doc, p.apellidos, p.nombres, u.cargo, u.usuario, u.activo, u.fecha_cese
    FROM usuarios u
    INNER JOIN personanaturaljuridica p ON p.id_persona = u.id_persona";
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
        // Insertar nuevo usuario
        $data = json_decode(file_get_contents('php://input'), true);
        $nro_doc = $data['nro_doc'];
        $usuario = $data['usuario'];
        $id_persona = $data['id_persona'];
        $clave = $data['clave'];
        $clave_apertura = $data['clave'];
        $creacion_fecha_hora = date('Y-m-d H:i:s');
        $id_unidad_de_negocio = $data['id_unidad_de_negocio'];
        $cargo = $data['cargo'];
        $id_tipo_de_usuario = $data['id_tipo_de_usuario'];
        $hora_ingreso = $data['hora_ingreso'];
        $hora_salida = $data['hora_salida'];
        $activo = $data['activo'];
        $fecha_cese = $data['fecha_cese'];
        $id_usuario_creacion = 0;
        $fecha_creacion = date('Y-m-d');
        $insert_user_query = "INSERT INTO usuarios (
            nro_doc,
            usuario, 
            id_persona,
            clave,
            clave_apertura,
            creacion_fecha_hora,
            id_unidad_de_negocio,
            cargo,
            id_tipo_de_usuario,
            hora_ingreso,
            hora_salida,
            activo,
            fecha_cese,
            id_usuario_creacion, 
            fecha_creacion
        ) VALUES (
            '$nro_doc',
            '$usuario',
            '$id_persona',
            '$clave',
            '$clave_apertura',
            '$creacion_fecha_hora',
            '$id_unidad_de_negocio',
            '$cargo',
            '$id_tipo_de_usuario',
            '$hora_ingreso',
            '$hora_salida',
            '$activo',
            '$fecha_cese',
            '$id_usuario_creacion',
            '$fecha_creacion'
        )";

        if ($conn->query($insert_user_query) === TRUE) {
            // Obtenemos el id_usuario recién insertado
            $id_usuario = $conn->insert_id;
            $permisos = $data['permisos'];
            if ($id_usuario) {
                foreach ($permisos as $item) {
                   
                    $id_modulo = $item["moduleId"];
                    $tiene_acceso = $item["tieneAcceso"];
                    $acceso_consulta = $item["accesoConsulta"];
                    $acceso_modificacion = $item["accesoModificacion"];
                    $acceso_creacion = $item["accesoCreacion"];
                    $apertura_fecha_hora = date('Y-m-d H:i:s');
                    $cese_fecha_hora = NULL;
        
                    // Realizar la inserción en la base de datos
                    $insert_permisos = "INSERT INTO usuariosmodulos (
                        id_usuario,
                        id_modulo,
                        tiene_acceso,
                        acceso_consulta,
                        acceso_modificacion,
                        acceso_creacion,
                        apertura_fecha_hora,
                        cese_fecha_hora)
                        VALUES (
                        '$id_usuario',
                        '$id_modulo',
                        '$tiene_acceso',
                        '$acceso_consulta',
                        '$acceso_modificacion',
                        '$acceso_creacion',
                        '$apertura_fecha_hora',
                        '$cese_fecha_hora')";
        
                    // Ejecutar la consulta
                    if ($conn->query($insert_permisos) === TRUE) {
                    echo "Nuevo registro insertado exitosamente.";
                    } else {
                    echo "Error: " . $insert_permisos . "<br>" . $conn->error;
                    }
                }

                if ($conn->query($insert_permisos) === TRUE) {
                    echo "Nuevo Permisos Asignados exitosamente.";
                } else {
                    echo "Error inserting into permisos table: " . $conn->error;
                }
            } else {
                echo "Error retrieving id_persona after insertion.";
            }
        } else {
            echo "Error: " . $insert_permisos . "<br>" . $conn->error;
        }
        break;
    case 'PUT':
        // Actualizar usuario
        $data = json_decode(file_get_contents('php://input'), true);
        $id_usuario = $data['id_usuario'];
        $nro_doc = $data['nro_doc'];
        $usuario = $data['usuario'];
        $id_persona = $data['id_persona'];
        $clave = $data['clave'];
        $clave_apertura = $data['clave_apertura'];
        $creacion_fecha_hora = date('Y-m-d H:i:s');
        $id_unidad_de_negocio = $data['id_unidad_de_negocio'];
        $cargo = $data['cargo'];
        $id_tipo_de_usuario = $data['id_tipo_de_usuario'];
        $hora_ingreso = $data['hora_ingreso'];
        $hora_salida = $data['hora_salida'];
        $activo = $data['activo'];
        $fecha_cese = $data['fecha_cese'];
        $id_usuario_creacion = $data['id_usuario_creacion'];
        $fecha_creacion = $data['fecha_creacion'];

        $sql = "UPDATE usuarios
        SET
            nro_doc = '$nro_doc',
            usuario = '$usuario',
            id_persona = '$id_persona',
            clave = '$clave',
            clave_apertura = '$clave_apertura',
            creacion_fecha_hora = '$creacion_fecha_hora',
            id_unidad_de_negocio = '$id_unidad_de_negocio',
            cargo = '$cargo',
            id_tipo_de_usuario = '$id_tipo_de_usuario',
            hora_ingreso = '$hora_ingreso',
            hora_salida = '$hora_salida',
            activo = '$activo',
            fecha_cese = '$fecha_cese',
            id_usuario_creacion = '$id_usuario_creacion',
            fecha_creacion = '$fecha_creacion'
        WHERE
        id_usuario = '$id_usuario'";

        if ($conn->query($sql) === TRUE) {
            echo "Usuario actualizado exitosamente.";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
        break;
    case 'DELETE':
        // Eliminar usuario
        $data = json_decode(file_get_contents('php://input'), true);
        $id_usuario = $data['id_usuario'];

        $sql = "DELETE FROM usuarios WHERE id_usuario = $id_usuario";

        if ($conn->query($sql) === TRUE) {
            echo "Usuario eliminado exitosamente.";
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