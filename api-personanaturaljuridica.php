<?php
// Configuración de la base de datos
require_once 'config.php';
// Manejar los métodos HTTP
$metodo = $_SERVER['REQUEST_METHOD'];

switch ($metodo) {
    case 'GET':
        $codigo = json_decode(file_get_contents('php://input'), true);
        $sql2 = "SELECT * FROM usuarios";

        if (isset($_GET["codigo"])) {
            $codigo = $_GET["codigo"];
            $sql2 .= " WHERE nro_doc = '$codigo'";
        }

        $result2 = $conn->query($sql2);

        if ($result2->num_rows == 1) {
            echo "Ya hay un usuario registrado con el número de documento proporcionado.";
        } else {
            $sql = "SELECT * FROM personanaturaljuridica";

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
        }
        break;
    case 'POST':
        // Insertar nueva personanaturaljuridica
        $data = json_decode(file_get_contents('php://input'), true);
        $tipo_persona = $data['tipo_persona'];
        $tipo_documento = $data['tipo_documento'];
        $nro_documento = $data['nro_documento'];
        $apellidos = $data['apellidos'];
        $nombres = $data['nombres'];
        $sexo = $data['sexo'];
        $lugar_de_nacimiento = $data['lugar_de_nacimiento'];
        $fecha = $data['fecha'];
        $edad = $data['edad'];
        $nacionalidad = $data['nacionalidad'];
        $ocupacion = $data['ocupacion'];
        $direccion = $data['direccion'];
        $ciudad = $data['ciudad'];
        $pais = $data['pais'];
        $celular = $data['celular'];
        $email = $data['email'];
        $id_usuario_creacion = $data['id_usuario_creacion'];
        $fecha_creacion = $data['fecha_creacion'];
        $sql = "INSERT INTO personanaturaljuridica (
            tipo_persona, tipo_documento, nro_documento, apellidos, nombres,
            sexo, lugar_de_nacimiento, fecha, edad, nacionalidad, ocupacion, direccion,
            ciudad, pais, celular, email, id_usuario_creacion, fecha_creacion
        ) VALUES (
            '$tipo_persona', '$tipo_documento', '$nro_documento', '$apellidos', '$nombres',
            '$sexo', '$lugar_de_nacimiento', '$fecha', '$edad', '$nacionalidad', '$ocupacion', '$direccion',
            '$ciudad', '$pais', '$celular', '$email', '$id_usuario_creacion', '$fecha_creacion'
        )";
        if ($conn->query($sql) === TRUE) {
            echo "Nuevo regitro insertado exitosamente.";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
        break;
    case 'PUT':
        // Actualizar personanaturaljuridica
        $data = json_decode(file_get_contents('php://input'), true);
        // Aquí obtienes los demás datos actualizados de la persona desde el cuerpo de la solicitud
        $id_persona = $data['id_persona'];
        $tipo_persona = $data['tipo_persona'];
        $tipo_documento = $data['tipo_documento'];
        $nro_documento = $data['nro_documento'];
        $apellidos = $data['apellidos'];
        $nombres = $data['nombres'];
        $sexo = $data['sexo'];
        $lugar_de_nacimiento = $data['lugar_de_nacimiento'];
        $fecha = $data['fecha'];
        $edad = $data['edad'];
        $nacionalidad = $data['nacionalidad'];
        $ocupacion = $data['ocupacion'];
        $direccion = $data['direccion'];
        $ciudad = $data['ciudad'];
        $pais = $data['pais'];
        $celular = $data['celular'];
        $email = $data['email'];
        $id_usuario_creacion = $data['id_usuario_creacion'];
        $fecha_creacion = $data['fecha_creacion'];

        $sql = "UPDATE personanaturaljuridica 
        SET tipo_persona = '$tipo_persona',
            tipo_documento = '$tipo_documento',
            nro_documento = '$nro_documento',
            apellidos = '$apellidos',
            nombres = '$nombres',
            sexo = '$sexo',
            lugar_de_nacimiento = '$lugar_de_nacimiento',
            fecha = '$fecha',
            edad = '$edad',
            nacionalidad = '$nacionalidad',
            ocupacion = '$ocupacion',
            direccion = '$direccion',
            ciudad = '$ciudad',
            pais = '$pais',
            celular = '$celular',
            email = '$email',
            id_usuario_creacion = '$id_usuario_creacion',
            fecha_creacion = '$fecha_creacion'
        WHERE id_persona = '$id_persona'";

        if ($conn->query($sql) === TRUE) {
            echo "Persona actualizada exitosamente.";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
        break;
    case 'DELETE':
        // Eliminar personanaturaljuridica
        $data = json_decode(file_get_contents('php://input'), true);
        $id_persona = $data['id_persona'];

        $sql = "DELETE FROM personanaturaljuridica WHERE id_persona = $id_persona";

        if ($conn->query($sql) === TRUE) {
            echo "Persona eliminada exitosamente.";
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