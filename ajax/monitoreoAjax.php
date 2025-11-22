<?php

    $peticionAjax = true;

    require_once "../config/APP.php";
    
    if (isset($_POST['id_equipo']) || isset($_POST['id_eliminar'])) {
        
        //---------- Instancia al controlador ------------//
        require_once "../controladores/monitoreoControlador.php";
        $ins_monitoreo = new monitoreoControlador();

        //---------- Agregar un monitoreo ------------//
        if (isset($_POST['id_equipo']) && isset($_POST['duracion'])) {
            echo $ins_monitoreo->agregar_monitoreo_controlador();
        }

        //---------- Eliminar un monitoreo ------------//
        if (isset($_POST['id_eliminar'])) {
            echo $ins_monitoreo->eliminar_monitoreo_controlador();
        }

    } else {
        session_start(['name' => 'STMH']);
        session_unset();
        session_destroy();
        header("Location: " . SERVER_URL . "login/");
        exit();
    }