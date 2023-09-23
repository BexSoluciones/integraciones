<?php
namespace App\Traits;

use Exception;

use Illuminate\Support\Facades\DB;

trait ConversionSentencesSqlTrait {
    
    public function convertionSentenceSql($sentence, $cia, $desde, $cuantos, $idConsulta){

        try {
            $sentence = "SET QUOTED_IDENTIFIER OFF; ".$sentence." SET QUOTED_IDENTIFIER ON;";
            //Replace double quotes with single quotes
            $singleQuotes = str_replace("'", '"', $sentence);
            //Replace greater than
            $greaterThan = str_replace(">", "&gt;", $singleQuotes);
            //Replace smalller than 
            $smallerThan = str_replace("<", "&lt;", $greaterThan);
            //Remplace @Cia
            $cia         = str_replace("@Cia", $cia, $smallerThan);
            //Replace @desde
            $desde = str_replace("@Desde", $desde, $cia);
            //Replace @Cuantos
            $cuantos = str_replace("@Cuantos", $cuantos, $desde);
            //All sentence
            $sentence    = $cuantos;
            
            $this->info('◘ Consulta '.$idConsulta.' convertida con exito');
            return $sentence;
        } catch (\Exception $e) {
            $this->error('Error al convertir sentencia: ' . $e->getMessage());
        }
    }
}