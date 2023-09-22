<?php
// Configuración de la base de datos
require_once 'config.php';
// Manejar los métodos HTTP
$metodo = $_SERVER['REQUEST_METHOD'];

switch ($metodo) {
    case 'GET':
        // Listar unidaddenegocio
        $sql = "SELECT * FROM usuariosmodulos";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            $modulos = array();
            while ($row = $result->fetch_assoc()) {
                $modulos[] = $row;
            }
            echo json_encode($modulos);
        } else {
            echo "No se encontraron usuarios modulos.";
        }
        break;
    case 'INNER':
        // Listar unidaddenegocio
        $sql = "SELECT u.id_usuario_modulo, us.usuario, m.nombre_modulo, u.tiene_acceso, u.acceso_consulta, u.acceso_modificacion, u.acceso_creacion, u.cese_fecha_hora
        FROM usuariosmodulos u
        INNER JOIN usuarios us ON us.id_usuario = u.id_usuario
        INNER JOIN modulos m ON m.id_modulo = u.id_modulo";
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
         // Obtener los datos enviados por el cliente como JSON
        $data = json_decode(file_get_contents('php://input'), true);
        // Procesar cada objeto JSON en el arreglo recibido
        foreach ($data as $item) {
            // Obtener los datos del objeto JSON
            $id_usuario = $item["id_usuario"];
            $id_modulo = $item["id_modulo"];
            $tiene_acceso = $item["tiene_acceso"];
            $acceso_consulta = $item["acceso_consulta"];
            $acceso_modificacion = $item["acceso_modificacion"];
            $acceso_creacion = $item["acceso_creacion"];
            $apertura_fecha_hora = date('Y-m-d H:i:s');
            $cese_fecha_hora = date('Y-m-d H:i:s');

            // Realizar la inserción en la base de datos
            $sql = "INSERT INTO usuariosmodulos (id_usuario, id_modulo, tiene_acceso, acceso_consulta, acceso_modificacion, acceso_creacion, apertura_fecha_hora, cese_fecha_hora)
                    VALUES ('$id_usuario', '$id_modulo', '$tiene_acceso', '$acceso_consulta', '$acceso_modificacion', '$acceso_creacion', '$apertura_fecha_hora', '$cese_fecha_hora')";

            // Ejecutar la consulta
            if ($conn->query($sql) === TRUE) {
            echo "Nuevo registro insertado exitosamente.";
            } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
            }
        }
        break;
    case 'PUT':
        // Actualizar modulos
        $id_usuario_modulo = $_PUT["id_usuario_modulo"];
        $id_usuario = $_PUT["id_usuario"];
        $id_modulo = $_PUT["id_modulo"];
        $tiene_acceso = $_PUT["tiene_acceso"];
        $acceso_consulta = $_PUT["acceso_consulta"];
        $acceso_modificacion = $_PUT["acceso_modificacion"];
        $acceso_creacion = $_PUT["acceso_creacion"];
        $apertura_fecha_hora = $_PUT["apertura_fecha_hora"];
        $cese_fecha_hora = $_PUT["cese_fecha_hora"];

        $sql = "UPDATE usuariosmodulos
                SET id_usuario='$id_usuario', id_modulo='$id_modulo', tiene_acceso='$tiene_acceso', 
                    acceso_consulta='$acceso_consulta', acceso_modificacion='$acceso_modificacion', 
                    acceso_creacion='$acceso_creacion', apertura_fecha_hora='$apertura_fecha_hora', 
                    cese_fecha_hora='$cese_fecha_hora' 
                WHERE id_usuario_modulo=$id_usuario_modulo";

        if ($conn->query($sql) === TRUE) {
            echo "UsuariosModulo actualizado exitosamente.";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
        break;
    case 'DELETE':
        // Eliminar unidaddenegocio
        $data = json_decode(file_get_contents('php://input'), true);
        $id_usuario_modulo = $data['id_usuario_modulo'];

        $sql = "DELETE FROM usuariosmodulos WHERE id_usuario_modulo = $id_usuario_modulo";

        if ($conn->query($sql) === TRUE) {
            echo "usuariosmodulo eliminado exitosamente.";
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