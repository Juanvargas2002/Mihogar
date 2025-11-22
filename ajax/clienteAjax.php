<?php

    $peticionAjax = true;

    require_once "../config/APP.php";

    if (isset($_POST['cedula']) || isset($_POST['id_eliminar']) || isset($_POST['id_actualizar']) || isset($_POST['q'])) {
        
        //---------- Instancia al controlador ------------//
        require_once "../controladores/clienteControlador.php";
        $ins_cliente = new clienteControlador();

        //---------- Búsqueda de clientes (autocompletado por cédula) ------------//
        if (isset($_POST['q'])) {
            $q = $_POST['q'];
            $results = $ins_cliente->buscar_clientes_controlador($q);
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode($results);
            exit();
        }

        //---------- Agregar un cliente ------------//
        // Para agregar, solo cedula, nombre y contacto son obligatorios; correo/direccion son opcionales
        if (isset($_POST['cedula']) && isset($_POST['nombre']) && isset($_POST['contacto'])) {
            echo $ins_cliente->agregar_cliente_controlador();
        }

        //---------- Eliminar un cliente ------------//
        if (isset($_POST['id_eliminar'])) {
            echo $ins_cliente->eliminar_cliente_controlador();
        }

        //---------- Actualizar un cliente ------------//
        if (isset($_POST['id_actualizar'])) {
            echo $ins_cliente->actualizar_cliente_controlador();
        }
    } else {
        session_start(['name' => 'STMH']);
        session_unset();
        session_destroy();
        header("Location: " . SERVER_URL . "login/");
        exit();
    }
