<?php

date_default_timezone_set ('America/Sao_Paulo'); //AQUI DEFINES EL O LA REGION DEL TIEMPO PUEDES BUSCAR EN GOOGLE YO NO LO CAMBIO PORQUE QUE WEBA FLOJERA NISIQUIERA SE VE O AFECTA

// TAL CUAL DICE AQUI ESTOS ARCHIVOS DEBEN ESTAR TAL CUAL SIN MOFICIAR O TOCAR PRO NO LOS TOQUES TELEGRAM PJP Y DADOSBOT
include __DIR__.'/Telegram.php';

if (!file_exists('dadosBot.ini')){

	echo "Instale el bot primero!";
	exit;

}

$textoMsg=json_decode (file_get_contents('textos.json'));
$iniParse=parse_ini_file('dadosBot.ini');

$ip=$iniParse ['ip'];
$token=$iniParse ['token'];
$limite=$iniParse ['limite'];

define ('TOKEN', $token); //  DEFINIR EL TOKEN ECHO EN @botfather

// Instancia das classes
$tlg=new Telegram (TOKEN);
$redis=new Redis ();
$redis->connect ('localhost', 6379); //PUERTO PADRE PARA EL BOT NO CAMBIAR O TOCAR EN CASO DE QUERER HACERLO USA EL PUERTO 5999 6888

// BLOQUE UTILIZADO EN SONDEO LARGO

while (true){

$updates=$tlg->getUpdates();

for ($i=0; $i < $tlg->UpdateCount(); $i++){

$tlg->serveUpdate($i);

switch ($tlg->Text ()){

	case '/start':

	$tlg->sendMessage ([
		'chat_id' => $tlg->ChatID (),
		'text' => $textoMsg->start,
		'parse_mode' => 'html',
		'reply_markup' => $tlg->buildInlineKeyBoard ([
			[$tlg->buildInlineKeyboardButton ('GENERAR SSH GRATIS', null, '/sshgratis')]
		])
	]);

	break;
	case '/sobre':

	$tlg->sendMessage ([
		'chat_id' => $tlg->ChatID (),
		'text' => 'ESTE BOT FUE ECHO POR EL MANDARIN SNIFF @GATESCCN SI QUIERES MAS PROYECTOS COMO ESTE PUEDES UNIRTE A MI CANAL @LATAMSRC COMANDOS PARA USAR EL BOT /sobre , (muestra info y comandos del bot) , /start (Muestra Menu) , /sshgratis (crea una ssh gratuita) , /total (MUESTRA EL TOTAL DE LAS SSH GENERADAS EN 12 HORAS)'
	]);

	break;
	case '/total':

	$tlg->sendMessage ([
		'chat_id' => $tlg->ChatID (),
		'text' => 'EN TOTAL <b>'.$redis->dbSize ().'</b> CUENTAS CREADAS EN LAS ULTIMAS 12horas',
		'parse_mode' => 'html'
	]);

	break;
	case '/sshgratis':

	$tlg->answerCallbackQuery ([
	'callback_query_id' => $tlg->Callback_ID()
	]);

	if ($redis->dbSize () == $limite){

		$textoSSH=$textoMsg->sshgratis->limite;

	} elseif ($redis->exists ($tlg->UserID ())){

		$textoSSH=$textoMsg->sshgratis->nao_criado;

	} else {

		$usuario=substr (str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 6);
		$senha=mt_rand(11111, 999999);

		exec ('./gerarusuario.sh '.$usuario.' '.$senha.' 1 1');

		$textoSSH="Cuenta SSH creada  ;)\r\n\r\n<b>Servidor:</b> <code>".$ip."</code>\r\n<b>Usuario:</b> <code>".$usuario."</code>\r\n<b>Pass:</b> <code>".$senha."</code>\r\n<b>Logins:</b> 1\r\n<b>Validade:</b> ".date ('d/m', strtotime('+7 day'))."</b> Dominio 80:<code>"f.hnetcol.online"</code></b> Dominio 443:<code>"f.netcol.nl"</code> 1\r\n<b>Validade:</b> \r\n\r\n🤙 SHH GRATIS PARA APLICACIONES DE @NETCOLVIP";

		$redis->setex ($tlg->UserID (), 43200, 'true'); 
		//BIEN AHORA ESTE PUNTO ES IMPORTANTE ESTE VAINA O NUMEROS 43200 SON SEGUNDOS SI SACAS EN LA CALCULADORA 
		// PORFAVOR NO MODIFICAR ESTA INFO PUTO COME WEBO ATT EL MANDARIN SNIFF
		// BIEN DICHO LO DE ARRIBA SIGAMOS XD
		// ESTO ES BASICO PERO BUENO 3600 SEGUNDOS SON 1 HORA ENTONCES SACA TUS CUENTAS "3600 X (NUMERO DE HORAS QUE DESEES)"
		// BIEN EN ESTE CASO COMO ME LO PEDISTE QUERIAS 12 HORAS ENTONCES 3600X12 SON  43200 SEGUNDOS O 12 HORAS LISTO

	}

	$tlg->sendMessage ([
		'chat_id' => $tlg->ChatID (),
		'text' => $textoSSH,
		'parse_mode' => 'html'
	]);

	break;

}

}}
