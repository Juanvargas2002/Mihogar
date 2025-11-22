<?php

    require_once "mainModel.php";

    class equipoModelo extends mainModel {

        //---------- Agregar equipo -------------//
        protected static function agregar_equipo_modelo($datos) {

            $sql = mainModel::conectar()->prepare("INSERT INTO equipo(num_serie, lote, id_marca, id_tipo, id_modelo, id_cliente) 
            VALUES(:NumSerie, :Lote, :IdMarca, :IdTipo, :IdModelo, :IdCliente);");

            $sql->bindParam(":NumSerie", $datos['NumSerie']);
            $sql->bindParam(":Lote", $datos['Lote']);
            $sql->bindParam(":IdMarca", $datos['IdMarca']);
            $sql->bindParam(":IdTipo", $datos['IdTipo']);
            $sql->bindParam(":IdModelo", $datos['IdModelo']);
            $sql->bindParam(":IdCliente", $datos['IdCliente']);

            $sql->execute();

            return $sql;
        }

        //---------- Eliminar equipo -------------//
        protected static function eliminar_equipo_modelo($id) {

            $sql = mainModel::conectar()->prepare("DELETE FROM equipo WHERE id = :Id;");

            $sql->bindParam(":Id", $id);

            $sql->execute();

            return $sql;
        }

        //---------- Obtener un equipo -------------//
        protected static function obtener_equipo_modelo($id) {

            $sql = mainModel::conectar()->prepare("SELECT * FROM equipo WHERE id = :Id;");

            $sql->bindParam(":Id", $id);

            $sql->execute();

            return $sql;
        }

        //---------- Obtener todos los equipos -------------//
        protected static function obtener_equipos_modelo() {

            $sql = mainModel::conectar()->prepare("SELECT * FROM equipo;");

            $sql->execute();

            return $sql->fetchAll(PDO::FETCH_OBJ);
        }

        //------------ Modelo para obtener cantidad de equipos ----------//
        protected static function obtener_cantidad_equipos_modelo() {

            $sql = mainModel::conectar()->prepare("SELECT COUNT(*) AS Cantidad FROM equipo;");

            $sql->execute();

            return $sql->fetch(PDO::FETCH_OBJ);
        }

        //------------ Modelo para actualizar equipo ----------//
        protected static function actualizar_equipo_modelo($datos) {

            $sql = mainModel::conectar()->prepare("UPDATE equipo SET
            num_serie = :NumSerie,
            lote = :Lote,
            id_marca = :IdMarca,
            id_tipo = :IdTipo,
            id_modelo = :IdModelo,
            id_cliente = :IdCliente
            WHERE id = :Id");

            $sql->bindParam(":Id", $datos['Id']);
            $sql->bindParam(":NumSerie", $datos['NumSerie']);
            $sql->bindParam(":Lote", $datos['Lote']);
            $sql->bindParam(":IdMarca", $datos['IdMarca']);
            $sql->bindParam(":IdTipo", $datos['IdTipo']);
            $sql->bindParam(":IdModelo", $datos['IdModelo']);
            $sql->bindParam(":IdCliente", $datos['IdCliente']);

            $sql->execute();
            return $sql;
        }
    }
