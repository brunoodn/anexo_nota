<?php 
session_start();
$codcliente = $_GET['codcli'];
$nomecliente = $_GET['cliente'];
$numeropedido = $_GET['numped'];
$numeronota = $_GET['numnota'];
$codfilial = $_GET['codfilial'];
$cgcent = $_GET['cgcent'];

$_SESSION['codcliente'] = $codcliente;
$_SESSION['nomecliente'] = $nomecliente;
$_SESSION['numeropedido'] = $numeropedido;
$_SESSION['numeronota'] = $numeronota;
$_SESSION['codfilial'] = $codfilial;
$_SESSION['cgcent'] = $cgcent;
$device = (strpos($_SERVER['HTTP_USER_AGENT'], "Windows"));
?>

<a href="index.php"><button class="btn btn-primary mb-3">Voltar</button></a>

<form class="form" enctype="multipart/form-data" method="POST" onsubmit="myButton.disabled = true; return true;" action="functions/conexao_mysql.php?acao=inserir">
    <label>Cod Cliente</label>
    <input class="form-control" name="codcliente" type="number" disabled value="<?=$codcliente?>">
    <label>Cliente</label>
    <input class="form-control" name="nomecliente" type="text" disabled value="<?=$nomecliente?>">
    <label>CGCENT</label>
    <input class="form-control" name="cgcent" type="text" disabled value="<?=$cgcent?>">
    <label>Numero Pedido</label>
    <input class="form-control" name="numeropedido" type="number" disabled value="<?=$numeropedido?>">
    <label>Numero nota</label>
    <input class="form-control" name="numeronota" type="number" disabled value="<?=$numeronota?>">
    <label>Codigo da filial</label>
    <input class="form-control" name="codfilial" type="text" disabled value="<?=$codfilial?>">
    
    <?php if ($device > 0){ ?>
        <label>Arquivo</label>
        <input class="form-control" accept="image/jpeg" required name="arquivo" type="file" >
    <?php }else{ ?>
        
        <input class="form-control mt-3" id="buttonInsert" type="file" value="Foto" required name="arquivo" accept="image/*" capture="camera">
    <?php }?>

    <input id="buttonInsert" type="submit" value="Cadastrar" name="myButton" class="btn btn-primary mt-3">
</form>

