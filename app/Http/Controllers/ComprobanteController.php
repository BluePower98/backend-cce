<?php

namespace App\Http\Controllers;

use App\Helpers\FileHelper;
use App\Helpers\QueryHelper;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;


class ComprobanteController extends Controller
{
    private $procedureParams;
    private $procedureCalling;

    public function __construct()
    {
        $this->procedureParams = QueryHelper::generateSyntaxPHPToProcedureParams(6);
        $this->procedureCalling = "Exec Lo_Man_lo_ventas_comprobantes {$this->procedureParams}";
    }

    
    public function getComprobante($idempresa, $idtipodocumento, $serie, $numero, $idsucursal)
    {

        $result = DB::select(
            'Exec Lo_Man_lo_ventas_comprobantes ?,?,?,?,?,?',
            array(
                'S01',
                $idempresa,
                $idtipodocumento,
                $serie,
                $numero,
                $idsucursal
        ));

        $Ventasenc = current($result);
        $images = DB::table('zg_sucursales')
            ->where([
                'idempresa' => $idempresa
            ])->where([
                'idsucursal' => $idsucursal
            ])
            ->first();

            $server = env('APP_URL');
            if($images->logo==null){
                $localFile = str_replace($server, '', 'logo.png');
                $pathLocalFile = public_path($localFile);
                
                $Ventasenc->image = FileHelper::getDataURI($pathLocalFile);
                return response()->json($Ventasenc, Response::HTTP_OK);

            }else{
                $localFile = str_replace($server, '', $images->logo);
                $pathLocalFile = public_path($localFile);
                $Ventasenc->image = FileHelper::getDataURI($pathLocalFile);
                return $Ventasenc;
            }

        return response()->json($Ventasenc, Response::HTTP_OK);

        
    }

    
    public function GetVentasDetalleId_Comprobante($idempresa, $idtipodocumento, $serie, $numero)
    {
        $params = QueryHelper::mergeValuesFromProcedureParams( ['S02', $idempresa, $idtipodocumento, $serie, $numero ], $this->procedureParams);
        $Ventasenc = DB::select($this->procedureCalling, $params);
        
        return $Ventasenc;
    }

    public function getVentaPagos($idempresa, $idtipodocumento, $serie, $numero) {
      
        $Mediospago = DB::select('Exec Lo_Man_lo_ventaspagos ?,?,?,?,?,?,?,?,?,?',
           array(
               'S01',
               $idempresa, 
               $idtipodocumento,
               $serie,
               $numero,
               null,
               null,
               null,
               null,
               null
           ));
       return $Mediospago ;
    }

    public function getPDFs(Request $request)
    {
        $contador = 0;
        foreach ($request->all() as $item) { 

            $Ventas[$contador] = $this->getComprobante($item['idempresa'], $item['idtipodocumento'], $item['Serie'], $item['numero'], 1);
            $Ventas[$contador]->det = $this->GetVentasDetalleId_Comprobante($item['idempresa'], $item['idtipodocumento'], $item['Serie'], $item['numero']);
            $Ventas[$contador]->pagos = $this->getVentaPagos($item['idempresa'], $item['idtipodocumento'], $item['Serie'], $item['numero']);
            $contador++;
        }

        return $Ventas;
    }
   
}
