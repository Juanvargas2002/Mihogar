<?php

    require_once "mainModel.php";

    class tipoModelo extends mainModel {

        //---------- Agregar tipo -------------//
        protected static function agregar_tipo_modelo($datos) {

            $sql = mainModel::conectar()->prepare("INSERT INTO tipo(nombre) 
            VALUES(:Nombre);");

            $sql->bindParam(":Nombre", $datos['Nombre']);

            $sql->execute();

            return $sql;
        }

        //---------- Eliminar tipo -------------//
        protected static function eliminar_tipo_modelo($id) {

            $sql = mainModel::conectar()->prepare("DELETE FROM tipo WHERE id = :Id;");

            $sql->bindParam(":Id", $id);

            $sql->execute();

            return $sql;
        }

        //---------- Obtener un tipo -------------//
        protected static function obtener_tipo_modelo($id) {

            $sql = mainModel::conectar()->prepare("SELECT * FROM tipo WHERE id = :Id;");

            $sql->bindParam(":Id", $id);

            $sql->execute();

            return $sql;
        }

        //---------- Obtener todos los tipos -------------//
        protected static function obtener_tipos_modelo() {

            $sql = mainModel::conectar()->prepare("SELECT * FROM tipo;");

            $sql->execute();

            return $sql->fetchAll(PDO::FETCH_OBJ);
        }

        //------------ Modelo para obtener cantidad de tipos ----------//
        protected static function obtener_cantidad_tipos_modelo() {

            $sql = mainModel::conectar()->prepare("SELECT COUNT(*) AS Cantidad FROM tipo;");

            $sql->execute();

            return $sql->fetch(PDO::FETCH_OBJ);
        }

        //------------ Modelo para actualizar tipo ----------//
        protected static function actualizar_tipo_modelo($datos) {

            $sql = mainModel::conectar()->prepare("UPDATE tipo SET
            nombre = :Nombre WHERE id = :Id");

            $sql->bindParam(":Id", $datos['Id']);
            $sql->bindParam(":Nombre", $datos['Nombre']);

            $sql->execute();
            return $sql;
        }
    }
