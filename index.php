<?php

	require("config.php");
	
	// fucntion to get files 

	function getFiles($filter = "") { 

		global $SYS_DIR;
		
		$files = scandir($SYS_DIR); 
		

		$filterFiles = array();

		foreach ($files as $k => $v) {
			if(strpos($v, $filter) > 0) { 
				$filterFiles[] = $v;
			}
		}

		return $filterFiles;

	}


	print "Try read files on = " . $SYS_DIR . $v . "\n"; 

	/*
	 *	CITIES 
	 */
	$cidades = getFiles('cidade'); 
	$cidadesSYS_OUT = array();


	foreach ($cidades as $k => $v) {

		$handle = @fopen($SYS_DIR . $v, "r");

		while (($buffer = fgetcsv($handle, 4096, "|")) !== false) {

			$cidadesSYS_OUT[$buffer[0]] = array(
					'nome' 	=> $buffer[1], 
					'sigla' => $buffer[2], 
			);

		}
	}

	$clientes = getFiles('cliente'); 

	//  SYS_OUT 
	$fp = @fopen($SYS_OUT, 'w');

	if(@!$fp)
		die("Erro ao abrir " . $SYS_OUT);

	// HEADER 
	$map = array(
				0 => 'Código', 
				1 => 'Apelido', 
				2 => 'Nome/Razão', 
				3 => 'Endereço', 
				4 => 'Bairro', 
				5 => 'Cidade', 
				6 => 'Cep', 
				7 => '', 
				8 => 'E-mail 1', 
				9 => 'Tipo de pessoa(F/J)', 
				10 => 'CPF', 
				11 => 'RG', 
				12 => 'Inscrição Municipal', 
				13 => '',
				14 => '',
				15 => '',
				16 => '',
				17 => '',	
				18 => 'Telefone residencial', 
				19 => '', 
				20 => 'Data de nascimento',
				21 => '',
				22 => '', 
				23 => 'Limite de compra', 
				24 => '',
				25 => '',
				26 => '',
				27 => 'Endereço de entrega',
				28 => 'Bairro de entrega',
				29 => 'Cidade do cadastro',
				30 => 'Cep de entrega',
				31 => '', 
				32 => '', 
				33 => '', 
				34 => '', 
				35 => '', 
				36 => '', 
				37 => '', 
				38 => 'Site',
				39 => '',
				40 => '',
	);

	// FIX MAP CHARSET
	foreach ($map as $key => $value) {
		$map[$key] = htmlspecialchars($value);
	}

	$map = array_map("utf8_decode", $map);

	fputcsv($fp, $map, ';');

	// PRA CADA ARQUIVO 
	foreach ($clientes as $k => $v) {

		// ABRE 		
		$handle = @fopen($SYS_DIR . $v, "r");

		if(@!$handle)
			die("Erro ao abrir " . $SYS_DIR.$v );

		// PRA CADA LINHA 
		while (($buffer = fgetcsv($handle, 4096, "|")) !== false) {
        	// FIX CIDADE 
        	$buffer[5] = $cidadesSYS_OUT[$buffer[5]]['nome'];
        	$buffer[29] = $cidadesSYS_OUT[$buffer[29]]['nome'];
        	fputcsv($fp, $buffer, ';');
    	}

	}



	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// files to email 

	print "Try sending e-mail -----> \n";
 
 	require 'PHPMailer/PHPMailerAutoload.php';

	$mail = new PHPMailer;

	// $mail->SMTPDebug = 3;                              			// Enable verbose debug SYS_OUT

	$mail->isSMTP();                                      			// Set mailer to use SMTP
	$mail->Host 		= $SMTP_HOST;  								// Specify main and backup SMTP servers
	$mail->SMTPAuth 	= true;                               		// Enable SMTP authentication
	$mail->Username 	= $SMTP_USER;                 				// SMTP username
	$mail->Password 	= $SMTP_PASS;                           	// SMTP password
	//$mail->SMTPSecure 	= 'tls';                            	// Enable TLS encryption, `ssl` also accepted
	$mail->Port 		= $SMTP_PORT;                              	// TCP port to connect to

	$mail->From 		= $SMTP_FROM;
	$mail->FromName 	= $SMTP_NAME;

	$mail->addAddress($SMTP_ADDR);     								// Add a recipient

	$mail->addAttachment($SYS_OUT);        							// Add attachments
	// $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    		// Optional name
	$mail->isHTML(true);                                  			// Set email format to HTML

	$mail->Subject = $SYS_OUT;
	$mail->Body    = 'Exportação de ' . $SYS_OUT;

	// $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

	if(!$mail->send()) {

	    echo 'Message could not be sent.';
	    echo 'Mailer Error: ' . $mail->ErrorInfo;

	} else {

	    echo 'Message has been sent';

	}

	unlink($SYS_OUT);
?>