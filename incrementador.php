<?php 
require_once 'config.php';

function actualizarNumeroCorrelativoHotel($codigo) {
    global $conn; // Hace referencia a la conexión global definida en config.php

    // Consulta para seleccionar el último valor
    $sql_select = "SELECT numero_correlativo
                    FROM config 
                    WHERE codigo = '$codigo'";

    $result_select = $conn->query($sql_select);

    if ($result_select) {
        $row = $result_select->fetch_assoc();
        $ultimo_valor = $row["numero_correlativo"];

        // Sumar 1 al último valor
        $ultimo_valor += 1;

        // Actualizar la fila con el nuevo valor
        $sql_update = "UPDATE config SET numero_correlativo = $ultimo_valor WHERE codigo = '$codigo'";
        $result_update = $conn->query($sql_update);

        if ($result_update) {
            return $ultimo_valor;
        } else {
            return false; // Error en la actualización
        }
    } else {
        return false; // Error en la consulta
    }
}

function actualizarNumeroCorrelativoReserva($codigo) {
    global $conn; // Hace referencia a la conexión global definida en config.php

    // Consulta para seleccionar el último valor
    $sql_select = "SELECT numero_correlativo
                    FROM config 
                    WHERE codigo = '$codigo'";

    $result_select = $conn->query($sql_select);

    if ($result_select) {
        $row = $result_select->fetch_assoc();
        $ultimo_valor = $row["numero_correlativo"];

        // Sumar 1 al último valor
        $ultimo_valor += 1;

        // Actualizar la fila con el nuevo valor
        $sql_update = "UPDATE config SET numero_correlativo = $ultimo_valor WHERE codigo = '$codigo'";
        $result_update = $conn->query($sql_update);

        if ($result_update) {
            return $ultimo_valor;
        } else {
            return false; // Error en la actualización
        }
    } else {
        return false; // Error en la consulta
    }
}

function obtenerProductoconhabitacion($codigo){
    global $conn;

    $sql_select = "SELECT p.id_producto FROM productos p
    INNER JOIN habitaciones h ON h.id_producto = p.id_producto
    WHERE h.nro_habitacion = '$codigo'";

    $result_select = $conn->query($sql_select);

    if ($result_select) {
    $row = $result_select->fetch_assoc();
    $id_producto = $row["id_producto"];

    return $id_producto;

    } else {
    return false; // Error en la consulta
    }
}
function obtenerIdcheckingconnromaestro($codigo){
      global $conn;
      
    $sql_select = "SELECT c.id_checkin FROM cheking c WHERE c.nro_registro_maestro = '$codigo'";

    $result_select = $conn->query($sql_select);

    if ($result_select) {
    $row = $result_select->fetch_assoc();
    $id_producto = $row["id_producto"];

    return $id_producto;

    } else {
    return false; // Error en la consulta
    }
}
?>