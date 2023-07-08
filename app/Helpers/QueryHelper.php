<?php


namespace App\Helpers;


class QueryHelper
{

    /**
     * Combinar valores iniciales y generar valores NULL según la cantidad de parámetros
     * del procedimiento almacenado.
     *
     * @param array $values, valores de entrada
     * @param string $params,
     * @return array
     */
    public static function mergeValuesFromProcedureParams(array $values, string $params){
        $count = count(explode(',', $params));

        for ($i = 0; $i < $count; $i++){
            if(isset($values[$i])){
                continue;
            }

            $values[$i] = NULL;
        }

        return $values;
    }

    public static function generateSyntaxPHPToProcedureParams(int $count) {
        $output = [];

        for ($i = 0; $i < $count; $i++){
            $output[$i] = '?';
        }

        return implode(',', $output);
    }
}