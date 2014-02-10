<?php

require_once("cms2lite.php");

$cms = new cms2lite("/props/fasalud");

try{

 if($_POST){

 	   $errors = array();


 	   	   	   	$name = htmlentities(strip_tags($_POST["name"]));		
 	   	   	   	$date = htmlentities(strip_tags($_POST["date"]));		
 	   	   	   	$address = htmlentities(strip_tags($_POST["address"]));		
 	   	   	   	$email = htmlentities(strip_tags($_POST["email"]));		
 	   	   	   	$telephone = htmlentities(strip_tags($_POST["telephone"]));		
 	   	   	   	$message = htmlentities(strip_tags($_POST["note"]));		


 	   	   	   	if(empty($name) || strlen($name) < 3)
 	   	   	   		$errors[] = "Debes especificar un nombre valido.";

 	   	   	   	if(empty($date) || strlen($date) < 8)
 	   	   	   		$errors[] = "Debes especificar una fecha valida."; 	   	   	   	
 	   	   	   
 	   	   	   	if(!filter_var($email, FILTER_VALIDATE_EMAIL))
 	   	   	   		$errors[] = "Debes especificar un email valido.";

				if(strlen($message) < 20)
 	   	   	   		$errors[] = "Debes escribir una nota mayor a 20 carácteres. (Filtro Spam).";

 	   	   	    if(strlen($address) < 4)
 	   	   	   		$errors[] = "Debes escribir una dirección valida.";

 	   	   	   	if(empty($telephone) || strlen($telephone) < 7)
 	   	   	   		$errors[] = "Debes especificar un teléfono valido.";


 	   	   	   	if(count($errors) > 0)
 	   	   	   	{	
 	   	   	   		json("400", $errors);
 	   	   	   		die;
 	   	   	   	}
 	   	   		
 	   	   		$cita = "<table><tr><td>Fecha:&nbsp;</td><td>{$date}</td></tr>";
 	   	   		$cita .= "<tr><td>Nombre:&nbsp;</td><td>{$name}</td></tr>"; 	   	   		
 	   	   		$cita .= "<tr><td>email:&nbsp;</td><td>{$email}</td></tr>"; 	   	   		
 	   	   		$cita .= "<tr><td>Teléfono:&nbsp;</td><td>{$telephone}</td></tr>"; 	   	   		
 	   	   	
 	   	   		if(!empty($address))
 	   	   		$cita .= "<tr><td>Teléfono:&nbsp;</td><td>{$telephone}</td></tr>"; 	   	   		

 	   	   		if(!empty($message)) 	   	   
 	   	   		$cita .= "<tr><td>Nota:&nbsp;</td><td>{$message}</td></tr>"; 

 	   	   		$cita .= "</table> <br /><br />";	   	   		

 	   	   		$content["content"] = "Hola {$name}, <br /><br /> Hemos recibido tu solicitud de cita, a continuación los detalles: <br /> <br /> {$cita}.";
 	   	   		$content["head"] = "<img src='https://imageshack.com/scaled/large/820/ovto.png' alt='' width='100'/> <br />"; 	   	   		
 	   	   		$content["foot"] = "Saludos cordiales, <br /><br /> Fasalucisnes.com";
 	   	   		
 	   	   		if($cms->send_mail($email, "Gracias por contactarnos", $content, "Fasalud")){


 	   	   	    $content = "Hola Fasalud, <br /> <br /> {$message}  <br /><br /> Contactante: {$name} <br /> Telefono {$telephone}";
 	   	   		
 	   	   		if($cms->send_mail($email, "Solicitud de cita {$name}", $content, $name))
 	   	   			json("200", array("ok"));
 	   	   		else
 	   	   			json("400", array($mail->error()));

 	   	   		}else
 	   	   		  json("400", array($mail->error()));
 	   	 


 }

 }catch(Exception $e){

      json("400", array($e->getMessage()));

 }
