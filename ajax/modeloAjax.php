<?php

    $peticionAjax = true;

    require_once "../config/APP.php";

    if (isset($_POST['nombre']) || isset($_POST['id_eliminar']) || isset($_POST['id_actualizar']) || isset($_POST['q'])) {
        
        //---------- Instancia al controlador ------------//
        require_once "../controladores/modeloControlador.php";
        $ins_modelo = new modeloControlador();

        //---------- Búsqueda de modelos (autocompletado) ------------//
        if (isset($_POST['q'])) {
            $q = $_POST['q'];
            $results = $ins_modelo->buscar_modelos_controlador($q);
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode($results);
            exit();
        }

        //---------- Agregar un modelo (corriente opcional) ------------//
        if (isset($_POST['nombre'])) {
            echo $ins_modelo->agregar_modelo_controlador();
        }

        //---------- Eliminar un modelo ------------//
        if (isset($_POST['id_eliminar'])) {
            echo $ins_modelo->eliminar_modelo_controlador();
        }

        //---------- Actualizar un modelo ------------//
        if (isset($_POST['id_actualizar']) && isset($_POST['nombre_actualizar'])) {
            echo $ins_modelo->actualizar_modelo_controlador();
        }
    } else {
        session_start(['name' => 'STMH']);
        session_unset();
        session_destroy();
        header("Location: " . SERVER_URL . "login/");
        exit();
    }
