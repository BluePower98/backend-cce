<?php

namespace App\Http\Controllers;

use App\Helpers\QueryHelper;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;
use Exception;


class ClienteController extends Controller
{
    private $procedureParams;
    private $procedureCalling;

    public function __construct()
    {
        $this->procedureParams = QueryHelper::generateSyntaxPHPToProcedureParams(11);
        $this->procedureCalling = "exec fe_Listar_comprobantes {$this->procedureParams}";
    }

    //Controlador de Lista de Comprobantes por Cliente
    public function getClientes($idempresa, $fechainicial, $fechafinal, $tipoComprobante, $Serie, $numero, $razonsocial, $estado)
    {
        $params = ['1', $idempresa];
        $params = QueryHelper::mergeValuesFromProcedureParams($params, $this->procedureParams);
        $countParams = count($params);
        $params[$countParams - 1] = $estado;
        $params[$countParams - 8] = $razonsocial;
        $params[$countParams - 5] = $numero;
        $params[$countParams - 6] = $Serie;
        $params[$countParams - 7] = $tipoComprobante;
        $params[$countParams - 3] = $fechafinal;
        $params[$countParams - 4] = $fechainicial;
        $result = DB::select($this->procedureCalling, $params);
       
        return response()->json($result, Response::HTTP_OK);        
    }

    //Controlador de Lista de Comprobantes Individuales
    public function getClienteIndividual($idempresa, $total, $numero, $Serie, $Tipo, $FecEmiCom, $rucdni)
    {
        $params = ['2', $idempresa];
        $params = QueryHelper::mergeValuesFromProcedureParams($params, $this->procedureParams);
        $countParams = count($params);
        $params[$countParams - 2] = $total;
        $params[$countParams - 5] = $numero;
        $params[$countParams - 6] = $Serie;
        $params[$countParams - 7] = $Tipo;
        $params[$countParams - 4] = $FecEmiCom;
        $params[$countParams - 9] = $rucdni;
        $result = DB::select($this->procedureCalling, $params);
        return response()->json($result, Response::HTTP_OK);

    }

    //Controlador de Descarga de XML Y CDR
    public function downloadXML(Request $request){
        $ruc = $request->get('ruc');
        $date = $request->get('date');
        $filename = $request->get('filename');

        $this->validate($request, [
            'ruc' => 'required',
            'date' => 'required|date_format:Y-m-d',
            'filename' => 'required',
        ]);

        $company = DB::connection('sqlsrv_facturacion')
                    ->table('Fe_Empresas')
                    ->where(['EmisorRuc' => $ruc])
                    ->first();

        if(!$company) {
            throw new Exception(
                "No se encontró registro de empresa con el ruc \"{$ruc}\".",
                Response::HTTP_NOT_FOUND
            );
        }

        $companyNameAndRuc = substr(strrchr($company->RutFilXmlFacEleSerLoc, "\\"), 1);
        $stringDate = str_replace('-', '', $date);

        $server = config('services.facturacion_electronica.server');

        $remoteFile = $server['documents']['path_xml'] . '/' . "{$companyNameAndRuc}/{$stringDate}/{$filename}";

        $host = $server['host'];
        $username = $server['username'];
        $password = $server['password'];

        error_reporting(E_ERROR | E_PARSE | E_NOTICE);

        $connection = ftp_connect($host);

        if (!$connection) {
            throw new Exception('Problemas de conexión con el FTP.', Response::HTTP_BAD_REQUEST);
        }

        ftp_set_option($connection, FTP_TIMEOUT_SEC, 180);

        if (!ftp_login($connection, $username, $password)) {
            throw new Exception('Problemas de conexión con el FTP.', Response::HTTP_BAD_REQUEST);
        }

        ftp_pasv($connection, true);

        $list = ftp_nlist($connection, $remoteFile);
       

        if(!$foundedFile = current($list)) {
            throw new Exception('El fichero no existe en el FTP.', Response::HTTP_NOT_FOUND);
        }

        $foundedFileName = pathinfo($foundedFile, PATHINFO_BASENAME);
        $foundedFileExtension = strtolower(pathinfo($foundedFile, PATHINFO_EXTENSION));

        if($foundedFileExtension !== 'xml'){
            throw new Exception(
                'Sólo se pueden descargar ficheros con extensión "xml".',
                Response::HTTP_BAD_REQUEST
            );
        }

        $file = tempnam(sys_get_temp_dir(), 'tmp');

        if(file_exists($file)){
            unlink($file);
        }

        $download = ftp_nb_get($connection, $file, $foundedFile, FTP_BINARY);

        while ($download === FTP_MOREDATA){
            $download = ftp_nb_continue($connection);
        }

        if($download !== FTP_FINISHED){
            ftp_close($connection);

            throw new Exception(
                'Ocurrió un problema durante la descarga del fichero desde el FTP.',
                Response::HTTP_BAD_REQUEST
            );
        }

        ftp_close($connection);

        header('Content-type: "text/xml"; charset="utf8"');
        header("Content-Disposition: attachment;filename={$foundedFileName}");

        $content = file_get_contents($file);

        unlink($file);
        return $content;

        echo $content;
        exit();
    }

}
