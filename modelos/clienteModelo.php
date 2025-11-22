<?php

    require_once "mainModel.php";

    class clienteModelo extends mainModel {

        //---------- Agregar un cliente -------------//
        protected static function agregar_cliente_modelo($datos) {

            $sql = mainModel::conectar()->prepare("INSERT INTO cliente(cedula, nombre, contacto, correo, direccion) 
            VALUES(:Cedula, :Nombre, :Contacto, :Correo, :Direccion);");

            $sql->bindParam(":Cedula", $datos['Cedula']);
            $sql->bindParam(":Nombre", $datos['Nombre']);
            $sql->bindParam(":Contacto", $datos['Contacto']);
            $sql->bindParam(":Correo", $datos['Correo']);
            $sql->bindParam(":Direccion", $datos['Direccion']);

            $sql->execute();

            return $sql;
        }

        //---------- Eliminar un cliente específico -------------//
        protected static function eliminar_cliente_modelo($id) {

            $sql = mainModel::conectar()->prepare("DELETE FROM cliente WHERE id = :Id;");

            $sql->bindParam(":Id", $id);

            $sql->execute();

            return $sql;
        }

        //---------- Obtener los datos de un cliente específico -------------//
        protected static function obtener_cliente_modelo($id) {

            $sql = mainModel::conectar()->prepare("SELECT * FROM cliente WHERE id = :Id;");

            $sql->bindParam(":Id", $id);

            $sql->execute();

            return $sql;
        }

        //---------- Obtener los datos de todos los clientes -------------//
        protected static function obtener_clientes_modelo() {

            $sql = mainModel::conectar()->prepare("SELECT * FROM cliente;");

            $sql->execute();

            return $sql->fetchAll(PDO::FETCH_OBJ);
        }

        //------------ Obtener cantidad de clientes ----------//
        protected static function obtener_cantidad_clientes_modelo() {

            $sql = mainModel::conectar()->prepare("SELECT COUNT(*) AS Cantidad FROM cliente;");

            $sql->execute();

            return $sql->fetch(PDO::FETCH_OBJ);
        }

        //------------ Actualizar datos de un cliente específico ----------//
        protected static function actualizar_cliente_modelo($datos) {

            $sql = mainModel::conectar()->prepare("UPDATE cliente SET
            cedula = :Cedula,
            nombre = :Nombre,
            contacto = :Contacto,
            correo = :Correo,
            direccion = :Direccion
            WHERE id = :Id");

            $sql->bindParam(":Id", $datos['Id']);
            $sql->bindParam(":Cedula", $datos['Cedula']);
            $sql->bindParam(":Nombre", $datos['Nombre']);
            $sql->bindParam(":Contacto", $datos['Contacto']);
            $sql->bindParam(":Correo", $datos['Correo']);
            $sql->bindParam(":Direccion", $datos['Direccion']);

            $sql->execute();
            return $sql;
        }
    }
