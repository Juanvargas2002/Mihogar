<?php

    class vistasModelo {
        
        //---------- Obtener las vistas ----------//
        protected static function obtener_vistas_modelo($vistas) {
            $whiteList = ["panel", "monitoreos", "monitoreo-new", "consulta-monitoreos",
                            "clientes", "cliente-new", "cliente-update",
                            "equipos", "equipo-new", "equipo-update",
                            "tecnicos", "tecnico-new", "tecnico-update",
                            "usuarios", "usuario-new", "usuario-update",
                            "marcas", "marca-new", "marca-update",
                            "modelos", "modelo-new", "modelo-update",
                            "tipos", "tipo-new", "tipo-update"];
            if (in_array($vistas, $whiteList)) {
                if (is_file("./vistas/contenidos/".$vistas."-view.php")) {
                    $contenido = "./vistas/contenidos/".$vistas."-view.php";
                } else {
                    $contenido = "404";
                }
            } elseif ($vistas == "login" || $vistas == "index") {
                $contenido = "login";
            }else {
                $contenido = "404";
            }
            return $contenido;
        }
    }