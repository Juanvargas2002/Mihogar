<?php

    $peticionAjax = true;

    require_once "../config/APP.php";

    if (isset($_POST['num_serie']) || isset($_POST['id_eliminar']) || isset($_POST['id_actualizar']) || isset($_POST['q'])) {
        
        //---------- Instancia al controlador ------------//
        require_once "../controladores/equipoControlador.php";
        $ins_equipo = new equipoControlador();

        //---------- Búsqueda de equipos por serie (autocompletado) ------------//
        if (isset($_POST['q'])) {
            $q = $_POST['q'];
            $results = $ins_equipo->buscar_equipos_controlador($q);
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode($results);
            exit();
        }

        //---------- Agregar un equipo ------------//
        if (isset($_POST['num_serie']) && isset($_POST['id_marca']) && isset($_POST['id_tipo']) && isset($_POST['id_modelo']) && isset($_POST['id_cliente'])) {
            echo $ins_equipo->agregar_equipo_controlador();
        }

        //---------- Eliminar un equipo ------------//
        if (isset($_POST['id_eliminar'])) {
            echo $ins_equipo->eliminar_equipo_controlador();
        }

        //---------- Actualizar un equipo ------------//
        if (isset($_POST['id_actualizar'])) {
            echo $ins_equipo->actualizar_equipo_controlador();
        }
    } else {
        session_start(['name' => 'STMH']);
        session_unset();
        session_destroy();
        header("Location: " . SERVER_URL . "login/");
        exit();
    }
