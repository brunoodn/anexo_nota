<?php
session_start();
//ini_set('display_errors',1);
//ini_set('display_startup_erros',1);
//error_reporting(E_ALL);


define( 'MYSQL_HOST', 'localhost' );
define( 'MYSQL_USER', 'root' );
define( 'MYSQL_PASSWORD', 'cbr#maria#r00t' );
define( 'MYSQL_DB_NAME', 'nf_digital' );

function conecta(){
	$PDO = new PDO( 'mysql:host=' . MYSQL_HOST . ';dbname=' . MYSQL_DB_NAME, MYSQL_USER, MYSQL_PASSWORD );

try{
    $PDO = new PDO( 'mysql:host=' . MYSQL_HOST . ';dbname=' . MYSQL_DB_NAME, MYSQL_USER, MYSQL_PASSWORD );
}catch ( PDOException $e ){
    echo 'Erro ao conectar com o MySQL: ' . $e->getMessage();
}
return $PDO;
}

// verifica se foi passado o comando de inserir e chama a função de inserir
if(isset($_GET['acao']) && $_GET['acao'] == 'inserir'){
	inserir();
}
// verifica se foi passado o comando de deletar e chama a função de deletar
if(isset($_GET['acao']) && $_GET['acao'] == 'deletar'){
	deletar();
}
// verifica se foi passado o GET de editar e chama a função de editar
if(isset($_GET['acao']) && $_GET['acao'] == 'editar'){
	editar();
}

// Função que verifica se o arquivo existe no diretorio de arquivos
function verifica_arquivo($codcli, $numnota){
	$arquivo = $codcli ."_". $numnota . ".jpg";
	$arquivopdf = $codcli ."_". $numnota . ".pdf";
	$arquivopng = $codcli ."_". $numnota . ".png";
	$arquivotif = $codcli ."_". $numnota . ".tif";
	if (file_exists("arquivos/".$arquivo)){
		//echo "<a target='_blank' href='uploads/".$arquivo."'>".$arquivo ."</a>";
		echo "<a target='_blank' href='arquivos/".$arquivo."'>Arquivo</a>";
	}elseif (file_exists("arquivos/".$arquivopdf)){
		echo "<a target='_blank' href='arquivos/".$arquivopdf."'>Arquivo</a>";
	}elseif(file_exists("arquivos/".$arquivopng)){
		echo "<a target='_blank' href='arquivos/".$arquivopng."'>Arquivo</a>";
	}elseif(file_exists("arquivos/".$arquivotif)){
		echo "<a target='_blank' href='arquivos/".$arquivotif."'>Arquivo</a>";
	}else{
		echo "Sem Arquivo";
	}
}
// função para alterar o nome do arquivo inserido e mover para o diretorio de arquivos 
function altera_nome_arquivo(){
         
	$ext = pathinfo($_FILES['arquivo']['name'], PATHINFO_EXTENSION);  //Pegando extensão do arquivo.
	$novonome = $_SESSION['codcliente'].'_'.$_SESSION['numeronota'].'.'.$ext; //Criando um padrão de nome para o arquivo
	$dir = '/var/www/html/CD/anexo_nota_2/arquivos/'; // Diretorio onde o arquivo sera salvo
	// Move arquivo para o diretorio uploads
	if (move_uploaded_file($_FILES['arquivo']['tmp_name'], $dir.$novonome)){
		echo 'movido com sucesso';
	}else {
		echo 'erro ao mover';
	}
}
// Função que apaga o arquivo do diretorio
function deleta_arquivo(){
	$dir = '/var/www/html/CD/anexo_nota_2/arquivos/'; // Diretorio onde o arquivo sera salvo

	$arquivo = $dir.$_GET['codcli'].'_'.$_GET['numnota'].'.jpg';
	$arquivopdf = $dir.$_GET['codcli'].'_'.$_GET['numnota'].'.pdf';
	$arquivopng = $dir.$_GET['codcli'].'_'.$_GET['numnota'].'.png';
	$arquivotif = $dir.$_GET['codcli'].'_'.$_GET['numnota'].'.tif';
	if (file_exists($arquivo)){
		unlink($arquivo);
	}elseif(file_exists($arquivopdf)){
		unlink($arquivopdf);
	}elseif(file_exists($arquivopng)){
		unlink($arquivopng);
	}elseif(file_exists($arquivotif)){
		unlink($arquivotif);
	}else{
		echo 'nao existe arquivo';
	}
}

// função de inserir a nota no banco de dados mysql e incluir foto no diretorio dos arquivos
function inserir(){
	if ($_GET['acao'] == 'inserir'){

		$ext = strtolower(substr($_FILES['arquivo']['name'],-4));
		$codcliente    = $_SESSION['codcliente'];
		$nomecliente   = $_SESSION['nomecliente'];
		$cgcent    = $_SESSION['cgcent'];
		$numeropedido    = $_SESSION['numeropedido'];
		$numeronota   = $_SESSION['numeronota'];
		$pdf       = $codcliente.'_'.$numeronota.$ext;
		$codfilial = $_SESSION['codfilial'];
	
		$sql = conecta()->prepare("insert into nf values(null, :codcli, :cliente, :cgcent, :numped, :numnota,:pdf ,:codfilial, now() )");
		$sql->bindParam(':codcli', $codcliente, PDO::PARAM_INT);
		$sql->bindParam(':cliente', $nomecliente,  PDO::PARAM_STR);
		$sql->bindParam(':cgcent', $cgcent, PDO::PARAM_STR);
		$sql->bindParam(':numped', $numeropedido, PDO::PARAM_INT);
		$sql->bindParam(':numnota', $numeronota, PDO::PARAM_INT);
		$sql->bindParam(':pdf', $pdf, PDO::PARAM_STR);
		$sql->bindParam(':codfilial', $codfilial, PDO::PARAM_INT);
		//Verifica se o registro foi inserido.. caso sim ele chama a funçao de alterar o nome do arquivo e move para a pasta uploads
		if ($sql->execute()){
			echo "Inserido com sucesso.";
			altera_nome_arquivo();
			$_SESSION['inserido'] = 'inserido';
			header('Location: ../index.php');
		} else{
			echo "Erro ao inserir";
			$_SESSION['erroinserir'] = 'erroinserir';
			header('Location: ../index.php');
		}
	}
}

// Função que verifica se existe o cadastro da nota no banco de dados Mysql
function verifica_registro($cli, $nota){

	$sql = conecta()->prepare("select * from nf where codcli = :codcli and numnota = :numnota");
	$sql->bindParam(':codcli', $cli, PDO::PARAM_INT);
	$sql->bindParam(':numnota', $nota, PDO::PARAM_INT);
	$sql->execute();
	$count = $sql->rowCount();
	
	if ($count > 0){
		echo "editar";
	}else{
		echo "inserir";
	}
}
// Função de deletar o registro no banco mysql e chama a função de deletar o arquivo
function deletar(){
	$cli = $_GET['codcli'];
	$nota = $_GET['numnota'];
	$sql = conecta()->prepare("delete from nf where codcli = :codcli and numnota = :numnota");
	$sql->bindParam(':codcli', $cli, PDO::PARAM_INT);
	$sql->bindParam(':numnota', $nota, PDO::PARAM_INT);

	if($sql->execute()){
		echo "Deletado com sucesso.";
		
		deleta_arquivo();
		$_SESSION['deletado'] = 'deletado';
		header('Location: ../index.php');
	}
}

//função que edita a data de inserçao no banco e substitui o arquivo no diretorio
function editar(){
	if ($_GET['acao'] == 'editar'){
		$codcliente    = $_SESSION['codcliente'];
		$numeronota   = $_SESSION['numeronota'];

		$sql = conecta()->prepare("update nf set data_insercao = now() where numnota = :numnota and codcli = :codcli ");
		$sql->bindParam(':codcli', $codcliente, PDO::PARAM_INT);
		$sql->bindParam(':numnota', $numeronota, PDO::PARAM_INT);

		if ($sql->execute()){
			echo "Inserido com sucesso.";
			altera_nome_arquivo();
			$_SESSION['editado'] = 'editado';
			header('Location: ../index.php');
		} else{
			echo "Erro ao inserir";
		}
	}
}
