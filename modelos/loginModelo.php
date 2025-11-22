<?php

    require_once "mainModel.php";

    class loginModelo extends mainModel {

        //------------- Iniciar sesión -------------//
        protected static function iniciar_sesion_modelo($datos) {
            $sql = mainModel::conectar()->prepare("SELECT * FROM usuario 
            WHERE usuario = :Usuario AND contrasena = :Contrasena;");
            
            $sql->bindParam(":Usuario", $datos['Usuario']);
            $sql->bindParam(":Contrasena", $datos['Contrasena']);
            
            $sql->execute();

            return $sql;
        }
    }