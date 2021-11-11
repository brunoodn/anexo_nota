<?php 

 ini_set('display_errors',1);
 ini_set('display_startup_erros',1);
 error_reporting(E_ALL);
$num = null;
if (isset($_POST['numeronota'])){
    $num = $_POST['numeronota']; 
}

$device = (strpos($_SERVER['HTTP_USER_AGENT'], "Windows"));
    
?>
<h3>Informe o número da nota fiscal: </h3>
        <form class="form" action="" method="post">
            <label>Numero da nota:</label>
            <input name='numeronota' type="number" name="buscanota">
            <button name="buscanf" class="btn btn-primary">Buscar</button>
        </form>
        <br>
        <br>
        <h4>Notas: </h4>
        <br>
        <?php echo isset($_SESSION['inserido']) ? "<p class='alert alert-success'>Inserido com sucesso</p>": "";?>
        <?php echo isset($_SESSION['deletado']) ? "<p class='alert alert-danger'>deletado com sucesso</p>": ""; ?>
        <?php echo isset($_SESSION['editado']) ? "<p class='alert alert-success'>Editado com sucesso</p>": ""; ?>
        <?php echo isset($_SESSION['erroinserir']) ? "<p class='alert alert-danger'>Erro ao inserir registro</p>": ""; ?>
        <?php session_destroy(); ?>
        <table class="table table-hover">
            <thdead>
                <tr>
                    <th scope="col">CLIENTE</th>
                    <th scope="col">PEDIDO</th>
                    <th scope="col">NOTA</th>
                    <th scope="col">FILIAL</th>
                    <th scope="col">ARQUIVO</th>
                    <th scope="col">AÇÃO</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                <?php
                    $stid = oci_parse($conn, Sql($num));
                    $row = oci_execute($stid);
                    if ($row > 0) {
                        while(($row = oci_fetch_array($stid, OCI_ASSOC + OCI_RETURN_NULLS)) != false) {
                        $codcli = OCIResult($stid, "CODCLI");
                        $cliente = OCIResult($stid, "CLIENTE");
                        $cgcent = OCIResult($stid, 'CGCENT');
                        $pedido = OCIResult($stid, "NUMPED");
                        $nota = OCIResult($stid, "NUMNOTA");
                        $filial = OCIResult($stid, "CODFILIAL");

                        ?>
                        <td scope="row"><?=$cliente ?></td>
                        <td scope="row"><?=$pedido ?></td>
                        <td scope="row"><?=$nota ?></td>
                        <td scope="row"><?=$filial ?></td>
                        <td scope="row"><?php verifica_arquivo($codcli, $nota); ?></td>
                        <td><a href="<?=verifica_registro($codcli, $nota)?>_nota.php?codcli=<?=$codcli?>&cliente=<?=str_replace("&", "%26", $cliente)?>&numped=<?=$pedido?>&numnota=<?=$nota?>&codfilial=<?=$filial?>&cgcent=<?=$cgcent?>"><?php verifica_registro($codcli, $nota);?></a></td>  
                </tr>
                <?php } 
                    }?>
            </tbody>
        </table>
