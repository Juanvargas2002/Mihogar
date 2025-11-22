<?php

    require_once "mainModel.php";

    class monitoreoModelo extends mainModel {

        //---------- Agregar monitoreo -------------//
        protected static function agregar_monitoreo_modelo($datos) {

            $sql = mainModel::conectar()->prepare("INSERT INTO monitoreo(
                fecha_hora, duracion, corriente_max, temperatura_min, 
                temperatura_max, vibracion_max, grafico_corr, grafico_temp, 
                comentario, id_usuario, id_equipo
            ) VALUES(
                :FechaHora, :Duracion, :CorrienteMax, :TemperaturaMin, 
                :TemperaturaMax, :VibracionMax, :GraficoCorr, :GraficoTemp, 
                :Comentario, :IdUsuario, :IdEquipo
            )");

            $sql->bindParam(":FechaHora", $datos['FechaHora']);
            $sql->bindParam(":Duracion", $datos['Duracion']);
            $sql->bindParam(":CorrienteMax", $datos['CorrienteMax']);
            $sql->bindParam(":TemperaturaMin", $datos['TemperaturaMin']);
            $sql->bindParam(":TemperaturaMax", $datos['TemperaturaMax']);
            $sql->bindParam(":VibracionMax", $datos['VibracionMax']);
            $sql->bindParam(":GraficoCorr", $datos['GraficoCorr']);
            $sql->bindParam(":GraficoTemp", $datos['GraficoTemp']);
            $sql->bindParam(":Comentario", $datos['Comentario']);
            $sql->bindParam(":IdUsuario", $datos['IdUsuario']);
            $sql->bindParam(":IdEquipo", $datos['IdEquipo']);

            $sql->execute();

            return $sql;
        }

        //---------- Eliminar monitoreo -------------//
        protected static function eliminar_monitoreo_modelo($id) {

            $sql = mainModel::conectar()->prepare("DELETE FROM monitoreo WHERE id = :Id");

            $sql->bindParam(":Id", $id);

            $sql->execute();

            return $sql;
        }

        //---------- Obtener un monitoreo -------------//
        protected static function obtener_monitoreo_modelo($id) {

            $sql = mainModel::conectar()->prepare("SELECT 
                m.*,
                e.num_serie,
                e.lote,
                ma.nombre AS marca_nombre,
                t.nombre AS tipo_nombre,
                mo.nombre AS modelo_nombre,
                c.nombre AS cliente_nombre,
                c.cedula AS cliente_cedula,
                c.contacto AS cliente_contacto,
                u.nombre AS tecnico_nombre
                FROM monitoreo m
                INNER JOIN equipo e ON m.id_equipo = e.id
                INNER JOIN marca ma ON e.id_marca = ma.id
                INNER JOIN tipo t ON e.id_tipo = t.id
                INNER JOIN modelo mo ON e.id_modelo = mo.id
                INNER JOIN cliente c ON e.id_cliente = c.id
                INNER JOIN usuario u ON m.id_usuario = u.id
                WHERE m.id = :Id");

            $sql->bindParam(":Id", $id);

            $sql->execute();

            return $sql;
        }

        //---------- Obtener cantidad de monitoreos -------------//
        protected static function obtener_cantidad_monitoreos_modelo() {

            $sql = mainModel::conectar()->prepare("SELECT COUNT(*) AS Cantidad FROM monitoreo");

            $sql->execute();

            return $sql->fetch(PDO::FETCH_OBJ);
        }
    }
