<?php

    $peticionAjax = true;

    require_once "../config/APP.php";

    if (isset($_POST['nombre']) || isset($_POST['id_eliminar']) || isset($_POST['id_actualizar']) || isset($_POST['q'])) {
        
        //---------- Instancia al controlador ------------//
        require_once "../controladores/marcaControlador.php";
        $ins_marca = new marcaControlador();

        //---------- Búsqueda de marcas (autocompletado) ------------//
        if (isset($_POST['q'])) {
            $q = $_POST['q'];
            $results = $ins_marca->buscar_marcas_controlador($q);
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode($results);
            exit();
        }

        //---------- Agregar una marca ------------//
        if (isset($_POST['nombre'])) {
            echo $ins_marca->agregar_marca_controlador();
        }

        //---------- Eliminar una marca ------------//
        if (isset($_POST['id_eliminar'])) {
            echo $ins_marca->eliminar_marca_controlador();
        }

        //---------- Actualizar una marca ------------//
        if (isset($_POST['id_actualizar']) && isset($_POST['nombre_actualizar'])) {
            echo $ins_marca->actualizar_marca_controlador();
        }
    } else {
        session_start(['name' => 'STMH']);
        session_unset();
        session_destroy();
        header("Location: " . SERVER_URL . "login/");
        exit();
    }