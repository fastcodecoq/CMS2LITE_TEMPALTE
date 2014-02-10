<?php

/*

Desarrollando un theme para cms2lite es tan simple como se describe a continuación:

   estructura del directorio del theme:

				mitema
				    ...
					- pages (requerido)
						en este directorio colocaras todas las vistas de  tu theme ej. home, contacto, foot, head...

				   index.html (requerido): en este archivo harás el modelo base de tu page sin incluir head ni body. Mirar el index.html de ejemplo adjunto
				   config.json (requerido): en este archivo definiras cosas como: autor, nombre, directorio del tema, archivo cabecera, foot y vista slider. ver config.json adjunto
				   
				   




*/


class cms2liteException extends Exception{};


define("site_name", "Fasalud Cisnes I.S.P." );
define("HIHGER_PRICE", "p>");
define("LOWEST_PRICE", "p<");

class cms2lite{
		
		private $erros;
		private $apps;
		private $site_info;
		private $base;

		function __construct( $path = NULL){

				$this->errors = array();
				$this->apps = array(
					"mailer" => "app.mailer.php",
					"products" => "app.getProductos.php"
					);			

				if($path === NULL)
				$this->base = $_SERVER["DOCUMENT_ROOT"] . "/panel/core/php";
			    else
			    $this->base = $_SERVER["DOCUMENT_ROOT"] . $path . "/panel/core/php";

		}



		function send_mail($mail, $subject, $content, $name = NULL){			     

				 include_once( $this->base . "/" . $this->apps["mailer"]);

				 if($name === NULL)				 	 
				 		$name = site_name;	

				 $mail = new Mail;

				 if(is_array($content)){				 	

				 		if(isset($content["header"]))
				 		$mail->set("header", $content["header"]);

				 		if(isset($content["content"]))				 						
				 		$mail->set("content", $content["content"]);

				 		if(isset($content["foot"]))				 		
				 		$mail->set("foot", $content["foot"]);

				 	    if(! $mail->send($mail, $name, $subject) ) 
				 	    	 throw new cms2liteException( $mail->error() );				 	    
				   
				    }else{

						 						
				 		$mail->set("content", $content);

				 	    if(! $mail->send($mail, $name, $subject) ) 
				 	    	 throw new cms2liteException( $mail->error() );
				 	    
				    }

				 	    return true;


		}



		function getProducts( $filter = NULL ){
				 
	
				 include_once( $this->base . "/" . $this->apps["products"] );

				 if($filter === NULL)
				 	 $filter = "";


				 $products = new products;

				 return $products->get($filter);


		}

}






function json($code, $vars){

    if(empty($vars) || $vars == NULL)
       $vars = array();

    header('Cache-Control: no-cache, must-revalidate');
    header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
    header('Content-type: application/json');

    echo json_encode(array("success" => $code , "rs" => $vars));

}
