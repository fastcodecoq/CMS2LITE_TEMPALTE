<?php

require_once("cms2lite.php");

$cms = new cms2lite("/props/fasalud");

try{

 if($_POST){

 	   $errors = array();


 	   	   	   	$name = htmlentities(strip_tags($_POST["name"]));		
 	   	   	   	$email = htmlentities(strip_tags($_POST["email"]));		
 	   	   	   	$telephone = htmlentities(strip_tags($_POST["telephone"]));		
 	   	   	   	$message = htmlentities(strip_tags($_POST["message"]));		


 	   	   	   	if(strlen($name) < 3)
 	   	   	   		$errors[] = "Debes especificar un nombre valido";

 	   	   	   if(!empty($email))
 	   	   	   	if(!filter_var($email, FILTER_VALIDATE_EMAIL))
 	   	   	   		$errors[] = "Debes especificar un email valido";

				if(strlen($message) < 20)
 	   	   	   		$errors[] = "Debes escribir un mensaje mayor a 20 carácteres. (Filtro Spam)";

 	   	   	   	if(count($errors) > 0)
 	   	   	   	{	
 	   	   	   		json("400", $errors);
 	   	   	   		die;
 	   	   	   	}
 	   	   		
 	   	   		$content["content"] = "Hola {$name}, <br /><br /> Muchas gracias por tomarte la molestia de escribirnos, en breve te responderemos.";
 	   	   		$content["head"] = "<img src='https://imageshack.com/scaled/large/820/ovto.png' alt='' width='100'/> <br />"; 	   	   		
 	   	   		$content["foot"] = "Saludos cordiales, <br /><br /> Fasalucisnes.com";
 	   	   		
 	   	   		if($cms->send_mail($email, "Gracias por contactarnos", $content, "Fasalud")){


 	   	   	    $content = "Hola Fasalud, <br /> <br /> {$message}  <br /><br /> Contactante: {$name} <br /> Telefono {$telephone}";
 	   	   		
 	   	   		if($cms->send_mail($email, "Mensaje de contacto desde la web", $content, $name))
 	   	   			json("200", array("ok"));
 	   	   		else
 	   	   			json("400", array($mail->error()));

 	   	   		}else
 	   	   		  json("400", array($mail->error()));
 	   	 


 }

 }catch(Exception $e){

      json("400", array($e->getMessage()));

 }
