<?php
    // Conexao com o banco do winthor
    $conn = oci_connect('', '', '');

    function Sql($numnota){
        $sql = 
        "SELECT 
            NFSAID.NUMNOTA
            ,CLIENT.CGCENT 
            ,NFSAID.NUMPED 
            ,NFSAID.CODFILIAL
            ,CLIENT.CLIENTE
            ,NFSAID.CODCLI 
            FROM CMLBRASIL.PCNFSAID NFSAID
            JOIN CMLBRASIL.PCCLIENT CLIENT ON CLIENT.CODCLI = NFSAID.CODCLI 
            WHERE 1=1
            AND NFSAID.DTCANCEL IS NULL -- NÃO RETORNAR NOTAS CANCELADAS
            AND NFSAID.CONDVENDA IN (1,4, 5,10) -- 
            AND NFSAID.SITUACAONFE = 100 -- NOTAS APROVADAS NA 1452
            AND NFSAID.NUMNOTA = '$numnota' ";
    
            return $sql;
    }

?>