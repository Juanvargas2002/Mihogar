<?php

    $peticionAjax = true;

    require_once "../config/APP.php";

    if (isset($_POST['nombre']) || isset($_POST['id_eliminar']) || isset($_POST['id_actualizar']) || isset($_POST['q'])) {
        
        //---------- Instancia al controlador ------------//
        require_once "../controladores/tipoControlador.php";
        $ins_tipo = new tipoControlador();

        //---------- Búsqueda de tipos (autocompletado) ------------//
        if (isset($_POST['q'])) {
            $q = $_POST['q'];
            $results = $ins_tipo->buscar_tipos_controlador($q);
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode($results);
            exit();
        }

        //---------- Agregar un tipo ------------//
        if (isset($_POST['nombre'])) {
            echo $ins_tipo->agregar_tipo_controlador();
        }

        //---------- Eliminar un tipo ------------//
        if (isset($_POST['id_eliminar'])) {
            echo $ins_tipo->eliminar_tipo_controlador();
        }

        //---------- Actualizar un tipo ------------//
        if (isset($_POST['id_actualizar']) && isset($_POST['nombre_actualizar'])) {
            echo $ins_tipo->actualizar_tipo_controlador();
        }
    } else {
        session_start(['name' => 'STMH']);
        session_unset();
        session_destroy();
        header("Location: " . SERVER_URL . "login/");
        exit();
    }
