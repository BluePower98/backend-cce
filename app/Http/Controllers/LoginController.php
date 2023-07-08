<?php

namespace App\Http\Controllers;

use App\Helpers\QueryHelper;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;


class LoginController extends Controller
{
    private $procedureParams;
    private $procedureCalling;

    public function __construct()
    {
        $this->procedureParams = QueryHelper::generateSyntaxPHPToProcedureParams(4);
        $this->procedureCalling = "exec fe_logueo {$this->procedureParams}";
    }

    //Controlador de Login
    public function login($ruc, $rucdni, $ruc_clave)
    {
        $params = ['1', $ruc];
        $params = QueryHelper::mergeValuesFromProcedureParams($params, $this->procedureParams);
        $countParams = count($params);
        $params[$countParams - 2] = $rucdni;
        $params[$countParams - 1] = $ruc_clave;
        $result = DB::select($this->procedureCalling, $params);
        // return $params;
        return response()->json($result, Response::HTTP_OK);
    }
}
