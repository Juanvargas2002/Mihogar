<?php

    require_once "mainModel.php";

    class marcaModelo extends mainModel {

        //---------- Agregar marca -------------//
        protected static function agregar_marca_modelo($datos) {

            $sql = mainModel::conectar()->prepare("INSERT INTO marca(nombre) 
            VALUES(:Nombre);");

            $sql->bindParam(":Nombre", $datos['Nombre']);

            $sql->execute();

            return $sql;
        }

        //---------- Eliminar marca -------------//
        protected static function eliminar_marca_modelo($id) {

            $sql = mainModel::conectar()->prepare("DELETE FROM marca WHERE id = :Id;");

            $sql->bindParam(":Id", $id);

            $sql->execute();

            return $sql;
        }

        //---------- Obtener una marca -------------//
        protected static function obtener_marca_modelo($id) {

            $sql = mainModel::conectar()->prepare("SELECT * FROM marca WHERE id = :Id;");

            $sql->bindParam(":Id", $id);

            $sql->execute();

            return $sql;
        }

        //---------- Obtener todas las marcas -------------//
        protected static function obtener_marcas_modelo() {

            $sql = mainModel::conectar()->prepare("SELECT * FROM marca;");

            $sql->execute();

            return $sql->fetchAll(PDO::FETCH_OBJ);
        }

        //------------ Modelo para obtener cantidad de marcas ----------//
        protected static function obtener_cantidad_marcas_modelo() {

            $sql = mainModel::conectar()->prepare("SELECT COUNT(*) AS Cantidad FROM marca;");

            $sql->execute();

            return $sql->fetch(PDO::FETCH_OBJ);
        }

        //------------ Modelo para actualizar marca ----------//
        protected static function actualizar_marca_modelo($datos) {

            $sql = mainModel::conectar()->prepare("UPDATE marca SET
            nombre = :Nombre WHERE id = :Id");

            $sql->bindParam(":Id", $datos['Id']);
            $sql->bindParam(":Nombre", $datos['Nombre']);

            $sql->execute();
            return $sql;
        }
    }