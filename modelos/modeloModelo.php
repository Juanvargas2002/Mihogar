<?php

    require_once "mainModel.php";

    class modeloModelo extends mainModel {

        //---------- Agregar modelo -------------//
        protected static function agregar_modelo_modelo($datos) {

            $sql = mainModel::conectar()->prepare("INSERT INTO modelo(nombre, corriente_max, temperatura_min, temperatura_max, vibracion_max) 
            VALUES(:Nombre, :CorrienteMax, :TemperaturaMin, :TemperaturaMax, :VibracionMax);");

            $sql->bindParam(":Nombre", $datos['Nombre']);
            $sql->bindParam(":CorrienteMax", $datos['CorrienteMax']);
            $sql->bindParam(":TemperaturaMin", $datos['TemperaturaMin']);
            $sql->bindParam(":TemperaturaMax", $datos['TemperaturaMax']);
            $sql->bindParam(":VibracionMax", $datos['VibracionMax']);

            $sql->execute();

            return $sql;
        }

        //---------- Eliminar modelo -------------//
        protected static function eliminar_modelo_modelo($id) {

            $sql = mainModel::conectar()->prepare("DELETE FROM modelo WHERE id = :Id;");

            $sql->bindParam(":Id", $id);

            $sql->execute();

            return $sql;
        }

        //---------- Obtener un modelo -------------//
        protected static function obtener_modelo_modelo($id) {

            $sql = mainModel::conectar()->prepare("SELECT * FROM modelo WHERE id = :Id;");

            $sql->bindParam(":Id", $id);

            $sql->execute();

            return $sql;
        }

        //---------- Obtener todos los modelos -------------//
        protected static function obtener_modelos_modelo() {

            $sql = mainModel::conectar()->prepare("SELECT * FROM modelo;");

            $sql->execute();

            return $sql->fetchAll(PDO::FETCH_OBJ);
        }

        //------------ Modelo para obtener cantidad de modelos ----------//
        protected static function obtener_cantidad_modelos_modelo() {

            $sql = mainModel::conectar()->prepare("SELECT COUNT(*) AS Cantidad FROM modelo;");

            $sql->execute();

            return $sql->fetch(PDO::FETCH_OBJ);
        }

        //------------ Modelo para actualizar modelo ----------//
        protected static function actualizar_modelo_modelo($datos) {

            $sql = mainModel::conectar()->prepare("UPDATE modelo SET
            corriente_max = :CorrienteMax,
            temperatura_min = :TemperaturaMin,
            temperatura_max = :TemperaturaMax,
            vibracion_max = :VibracionMax
            WHERE id = :Id");

            $sql->bindParam(":Id", $datos['Id']);
            $sql->bindParam(":CorrienteMax", $datos['CorrienteMax']);
            $sql->bindParam(":TemperaturaMin", $datos['TemperaturaMin']);
            $sql->bindParam(":TemperaturaMax", $datos['TemperaturaMax']);
            $sql->bindParam(":VibracionMax", $datos['VibracionMax']);

            $sql->execute();
            return $sql;
        }
    }
