<?php

    require_once "mainModel.php";

    class usuarioModelo extends mainModel {

        //---------- Agregar un usuario -------------//
        protected static function agregar_usuario_modelo($datos) {

            $sql = mainModel::conectar()->prepare("INSERT INTO usuario (identificacion, nombre, usuario, contrasena) 
            VALUES (:Identificacion, :Nombre, :Usuario, :Contrasena);");

            $sql->bindParam(":Identificacion", $datos['Identificacion']);
            $sql->bindParam(":Nombre", $datos['Nombre']);
            $sql->bindParam(":Usuario", $datos['Usuario']);
            $sql->bindParam(":Contrasena", $datos['Contrasena']);
            
            $sql->execute();

            return $sql;
        }

        //---------- Eliminar un usuario específico -------------//
        protected static function eliminar_usuario_modelo($id) {

            $sql = mainModel::conectar()->prepare("DELETE FROM usuario WHERE id = :Id;");

            $sql->bindParam(":Id", $id);

            $sql->execute();

            return $sql;
        }

        //---------- Obtener un usuario específico -------------//
        protected static function obtener_usuario_modelo($id) {

            $sql = mainModel::conectar()->prepare("SELECT * FROM usuario WHERE id = :Id;");

            $sql->bindParam(":Id", $id);

            $sql->execute();

            return $sql;
        }

        //---------- Obtener todos los usuarios -------------//
        protected static function obtener_usuarios_modelo() {

            if ($_SESSION['rol_usuario'] == 'Administrador') {
                $sql = mainModel::conectar()->prepare("SELECT * FROM usuario WHERE rol = 'Técnico';");
            }
        
            $sql->execute();

            return $sql->fetchAll(PDO::FETCH_OBJ);
        }
        
        //------------ Obtener la cantidad de usuarios en BD -----------//
        protected static function obtener_cantidad_usuarios_modelo() {

            $sql = mainModel::conectar()->prepare("SELECT COUNT(*) AS Cantidad FROM usuario WHERE rol = 'Técnico';");
            
            $sql->execute();
            
            return $sql->fetch(PDO::FETCH_OBJ);
        }

        //------------ Actualizar datos del usuario -----------//
        protected static function actualizar_usuario_modelo($datos) {

            $sql = mainModel::conectar()->prepare("UPDATE usuario SET
            identificacion = :Identificacion,
            nombre = :Nombre,
            usuario.usuario = :Usuario,
            contrasena = :Contrasena
            WHERE id = :Id");

            $sql->bindParam(":Identificacion", $datos['Identificacion']);
            $sql->bindParam(":Nombre", $datos['Nombre']);
            $sql->bindParam(":Usuario", $datos['Usuario']);
            $sql->bindParam(":Contrasena", $datos['Contrasena']);
            $sql->bindParam(":Id", $datos['Id']);

            $sql->execute();
            
            return $sql;
        }
    }