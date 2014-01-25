<?
require("mailer/class.phpmailer.php"); // Requer PHPMailer (http://sourceforge.net/projects/phpmailer/)



$tamanho_maximo = 1024*7000; //Tamanho máximo de upload (em KB) 1GB (1GB para KB = 1048576)
$url = "http://dominio.com.br/"; //Barra "/" no final é necessário
$caminho = "anexos/"; // Pasta para uploads (Necessário permissão 777 na pasta)
$contador = 0;
$contador2 = 0;
$dataposta=getdate(date("U"));
$datacompleta = "$dataposta[mday]-$dataposta[month]-$dataposta[year]-$dataposta[hours]-$dataposta[minutes]-$dataposta[seconds]";
if(isset($_POST) and $_SERVER['REQUEST_METHOD'] == "POST"){
  foreach ($_FILES['files']['name'] as $f => $name) {     
      if ($_FILES['files']['error'][$f] == 4) {
	        continue; 
	    }	       
	    if ($_FILES['files']['error'][$f] == 0) {	           
	        if ($_FILES['files']['size'][$f] > $tamanho_maximo) {
	            $message[] = "$name muito pesado!.";
	            continue; 
	        }
		
	        else{ 
	            if(move_uploaded_file($_FILES["files"]["tmp_name"][$f], $caminho.$datacompleta.$contador2.preg_replace('/[^a-zA-Z0-9_%\[().\]\\/-]/s', '', $name))) {
	            	$contador++;
	            	$nomee = $contador2++;
	            	
	            }
	        }
	    }
	    $arquivosenviados .= "<a href=\"$url$caminho$datacompleta$nomee" . preg_replace('/[^a-zA-Z0-9_%\[().\]\\/-]/s', '', $name) . "\">" . preg_replace('/[^a-zA-Z0-9_%\[().\]\\/-]/s', '', $name) . "</a><br>";
	}
}




$Nome = $_POST['name']; 
$Email = $_POST['email'];
$Mensagem = $_POST['text'];

$message .= "Nome: $Nome <br> Email: $Email <br> Mensagem: $Mensagem <br> Anexos:<br>$arquivosenviados";



$mail = new PHPMailer(); // Cria a instância
$mail->SetLanguage("br"); // Define o Idioma
$mail->CharSet = "utf-8"; // Define a Codificação
$mail->IsSMTP(); // Define que será enviado por SMTP
$mail->Host = "smtp.seudominio.com.br"; // Servidor SMTP
$mail->SMTPAuth = true; // Caso o servidor SMTP precise de autenticação
$mail->Username = "email@dominio.com.br"; // Usuário ou E-mail para autenticação no SMTP
$mail->Password = "senha"; // Senha do E-mail
$mail->IsHTML(true); // Enviar como HTML
$mail->From = "email@dominio.com.br"; // Define o Remetente
$mail->FromName = $Nome; // Nome do Remetente
$mail->AddAddress("destinatario@dominio.com.br","Seu Nome"); // Email e Nome do destinatário 

// Estes campos a seguir são opcionais, caso não queira usar, remova-os
$mail->AddReplyTo($Email,$Nome); // E-mail de Resposta

// Configuração de Assuntos e Corpo do E-mail
$mail->Subject = "Mensagem de $Nome"; // Define o Assunto
$mail->Body = $message; // Corpo da mensagem em formato HTML

// Fazemos o envio do email
if(!$mail->Send()){
	print "<SCRIPT LANGUAGE='JavaScript'>
        window.alert('Erro: <php print .$mail->ErrorInfo; ?>, Tente novamente mais tarde.')
        </SCRIPT>";
}else{
	print "<SCRIPT LANGUAGE='JavaScript'>
        window.alert('Obrigado, Mensagem Enviada.')

        </SCRIPT>";
}
?>