<?php

// Manejar los métodos HTTP
$metodo = $_SERVER['REQUEST_METHOD'];

switch ($metodo) {
    case 'GET':
        $hash = 'gPs39Bds648Kgh345fsdGjshjdsjeh73HF7T';
        $tipo = $_GET["tipo"];
        $doc = $_GET["doc"];

        // Comprobar que se proporcionaron los parámetros 'tipo' y 'doc'
        if (isset($tipo) && isset($doc)) {
            // Construir la URL con los parámetros
            $url = "http://perufacturo.com/hash/ver.php?hash=" . $hash . "&tipo=" . $tipo . "&doc=" . $doc;
            
            // Realizar la solicitud a la URL
            $json = file_get_contents($url);

            if ($json !== false) {
                // Devolver la respuesta como JSON
                header('Content-Type: application/json');
                echo $json;
            } else {
                // Manejar errores de solicitud
                http_response_code(500); // Error interno del servidor
                echo json_encode(array('error' => 'Error al obtener la respuesta del enlace.'));
            }
        } else {
            // Manejar error si falta alguno de los parámetros requeridos
            http_response_code(400); // Solicitud incorrecta
            echo json_encode(array('error' => 'Faltan parámetros en la solicitud.'));
        }
        break;
    default:
        http_response_code(405); // Método no permitido
        break;
}

?>