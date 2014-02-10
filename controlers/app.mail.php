<?php

/*

    class Mail is a simple code for sending customized emails  easy


    @ params
  		
  		$sender_name = String name of sender mail 
  		$sender_mail = String email of sender mail 
  		$save = [boolean, tue || false] false default, if u want activate storage engine set this to true
  		$lang = String Language of email es-CO default, Colombian Spanish



  	@dependences

  	   class Mongo (To use storage engine)


    Taking email structure like website arquitecture:


    -------------------------------------------
      head
    -------------------------------------------
     


      content o mail info




	-------------------------------------------
	  foot  
	-------------------------------------------
      

    Wherever you want customize a template remember:


    %head% => this will replace for $head value
    %content% => this will replace for $content value
    %foot% => this will replace for $foot value



    HOW TO USE



      $mail = new Mail("gomosoft", "info@gomosoft.com");

      $mail->set("head", "<div>your header content here</div>");
      $mail->set("content", "<div>your info content here</div>");
      $mail->set("foot", "<div>Best Regards, <br /> <br /> Chunchun <br />Naolite Inc.</div>"); 

      //you can use html or simple string content, anyway it's ok


      //if u want set a customized mail template add a html template like this or wherever u want
       // $mail->set("template", "<table width='600' border='1' border-color='#ccc'> <thead><tr><td>%head%</td></tr></thead> <tbody><tr><td style='background:#fafafa'>%content%</td></tr></tbody> </table> %foot%")

      if( $mail->send())     
        echo "Thanks, we reply you as soon posible."
      else
        //error controller



    Happy Coding
  
    
    Copyrights @gomosoft 2013


    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.


*/


			define("logo","http://localhost/props/encuestas/assets/img/golf.jpg");


class Mail{

	private $con;
	private $cursor;
	private $head;
	private $content;
	private $foot;
	private $template;	
	private $lang;
	private $headers;
	private $save;
	private $sender_name;
	private $sender_mail;
	private $errors;
	private $To;
	private $Cc;

	public function __construct($sender_name = "Alguien", $sender_mail = "uncorreo@dealguien.com", $save = false , $lang = "es-CO"){



		if($save){
		$this->con = new Mongo();
		$this->db = $this->con->selectDB("gomosoft"); //cambiar por su base de datos en MongoDB

		$this->col = $this->con->mails;

		 }

		$this->head = "<img src='" . logo . "' alt='' width=\"300\" />";
		$this->lang = $lang;
		$this->save = $save;
		$this->sender_name = $sender_name;
		$this->sender_mail = $sender_mail;
		$this->errors = array();
		$this->To = array();
		$this->Cc = array();

		if(! class_exists("mongoClient"))
			$this->save = false;
   

     //formando el cuerpo del email para que este no se quede en la carpeta spam y además se muestre de manera correcta al usuario


		$this->template = '<!DOCTYPE html>
		 <html lang="es-CO">
		 <head>
		  <meta charset="UTF-8" />
		  <title>%subject%</title>
		  <style type="text/css">
		    body{

		      font-family: "Lucida grande", Verdana, sans-serif;
		      font-size: 13px/28px;

		    }
		  </style>
		 </head>
		 <body>
		 <table cellpadding="0" cellspacing="0" border="0" id="backgroundTable" style="padding: 40px 0; background: #f1f1f1;  width: 100%; line-height: 150%;">
      	<tr>
		<td valign="top">' . "
		  <table width='520' border='0' align='center' style='background: #ffffff; border: 1px solid #D3D3D3; border-bottom: 1px solid #B6B6B6; padding: 30px 40px 40px 40px; line-height: 1.5; font-family: 16px/30px \"Lucida grande\",Verdana,sans-serif!important;  box-shadow: 0 0 5px rgba(0, 0, 0, 0.2); -moz-box-shadow: 0 1px 1px rgba(0, 0, 0, .9);
-webkit-box-shadow: 0 1px 1px rgba(0, 0, 0, .5);
box-shadow: 0 1px 1px rgba(0, 0, 0, .5);'>
          <thead>
            <tr><td style='letter-spacing: 1px;background: #4d90fe;color: white;padding: 7px 0;text-align: center;font-size: 9px;'>Permite mostrar las imagenes del correo, para una mejor experiencia visual</td></tr>
            <tr>
               <td style='padding-top:10px'><br><br>%head%</td>
            </tr>
          </thead>
          <tbody>
          <tr>
              <td style='padding-bottom:55px'>

                  %content%
              </td>
          </tr>
          <tr>
           <td style='border-top: 1px solid #ccc; padding-top: 25px; padding-bottom:15px; padding-left: 5px;'> %foot% </td>
          </tr>
          </tbody>          
		</table> " .  '</td>
		  </tr> 
	   </table>
	  </body>
	<html>';


		$this->ini_headers();


	}
 

   // inicializa los headers del correo, colocando el From mail 

	private function ini_headers(){

		$this->headers = 'MIME-Version: 1.0' . "\r\n";		
		$this->headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
    $this->add_to_headers($this->sender_name, $this->sender_mail, "From");    
      

	}


    //add_to_header function => añade un campo from | to a la cabecera del correo
    //@param $name String persona 
    //@param $mail String correo de la persona 
    //@param $verb [String, To || From]  tipo de acción 


    private function add_to_headers($name = "", $mail = "info@gomosoft.com", $target = "To"){


		$this->headers .= $target . ': '. $name .' <' . $mail . '>' . "\r\n";


    }

   //add_dest añade un destinatatio
    //@param $name String nombre del destinatario
     //@param $mail String mail del destinatatio

    private function add_dest($name, $mail){

    	 $this->To[] = "{$mail}";

    }

    //add_cc añade un destinatario (CC)
    //@param $name String nombre del destinatario
     //@param $mail String mail del destinatatio

     private function add_cc($name, $mail){

    	 $this->Cc[] = "{$name} <{$mail}>";    	 

    }


    // Function get_headers String
    //retorna las cabeceras del email

    public function get_headers(){

    	return $this->headers;

    }


    public function merge_headers(){

           if(count($this->To) > 0)
    	   { 

    	   	$to = implode(",", $this->To);
    	   	$this->headers .= 'To: ' . $to . "\r\n";

    	    }

    	   if(count($this->Cc) > 0)
    	   {
    	   	
    	   	$cc = implode(",", $this->Cc);
    	   	$this->headers .= 'Cc: ' . $cc . "\r\n";

    	   }




    }


     //resetea las cabeza del email

    public function clean_headers(){

    	 
		$this->headers = "";		
		$this->ini_headers();
		

    }



    // personaliza tu plantilla de email 
    // @param $where = [String, 'head' - 'content' - 'foot', 'template'] que hace referencia a la parte que deseas modificar 
    // @param $what = String , valor que quieres insertar dentro de cada parte que conforma el cuerpo del email


    function set($where = "" , $what){

    	 switch ($where) {
    	 	
    	 	case 'head':
    	 		
    	 		  $this->head = $what;
    	 		  return true;

    	 		break;

    	   	case 'content':
    	 		
    	 		  $this->content = $what;

    	     return true;

    	 		break;


    	     	case 'foot':
    	 		
    	 		  $this->foot = $what;
    	 		  return true;

    	 		break;

    	 	   
    	 	   case 'template':
    	 		
    	 		  $this->template = $what;
    	 		  return true;

    	 		break;


    	 	   case 'headers':

    	 	    if(is_array($what))
    	 	   	 $this->headers = implode("\r\n", $what);
    	 	   	else
    	 	   	 {

    	 	   	 	$this->errors[] = "No se pudo añadir las cabeceras, el parametro pasado no es un Array";
    	 	   	 	return false;

    	 	   	 }

    	 	   break;

    	 	
    	 	    default:
    	 			
    	 			$this->errors[] = "No se pudo modificar el contenido de {$where}";
    	 	    	return false;

    	 		break;
    	 
    	 }

    }

 //salva un mail como plantilla, 
    //@param $name String por defecto coloca la palabra template_ más un aleatorio en base a la fecha y hora de generación

    function save_as_template($name = "template_"){

    	  if($this->save){

    	  	  $col = $this->db->mails_templates;

    	  	  if($name === "template_")
    	  	  	  $name = $name . (substr( md5(date("Y-m-d g:s:i a")), 3,5));

    	  	  try{

    	  	  	$template = htmlentities($this->template, ENT_QUOTES, 'UTF-8');

    	  	    $query = array("name" => $name,  "template" => $template, "lang" => $this->lang);

    	  	    return true;

    	  	   } catch(Exception $e){

    	  	   	 $this->errors[] = "No se pudo salvar la plantilla";
    	  	   	 return false;

    	  	   }

    	  }

    }


 // function error String || Array
    // @ param $what [String, last (String) || all (Array)] last default


    function error($what = "last"){


    		switch ($what) {

    			case 'all':
    				
    			   return  $this->errors;


    				break;
    			
    			case 'last':

    			   return end($this->errors);    			    				

    			break;

    		}

    }


    
    


    //Send function boolean => envía el email
    //@param $name String persona que lo envía
    //@param $mail String cooreo de la persona que se le envía el correo     
    //@param $subject String Asunto del correo 


	public function send($name = "Gomosoft" , $mail = "info@gomosoft.com", $subject = "Un amigo te ha invitado a realizar una encuesta" ){


		$template = $this->template;
		$template  = str_replace("%head%", $this->head, $template);			
		$template  = str_replace("%content%", $this->content, $template);
		$template  = str_replace("%foot%", $this->foot, $template);	
		$template  = str_replace("%subject%", $subject, $template);	

		if(!filter_var($mail, FILTER_VALIDATE_EMAIL))
			return false;

		 $this->add_dest("", $mail);
		 $this->merge_headers();
  	
   try{

	if( $send = mail($mail, $subject, $template, $this->get_headers()) ){


			$this->clean_headers();

		
       	
       	  if($this->save){
			if($this->save($mail, $subject, $template))
				return true;
			else{
										
		        $this->errors[] = "Error enviando correo (No se pudo guardar en la base de datos)";
				return false;

			   }
		  }
		  else
			return true;



		}else{
		  

		   $this->errors[] = "Error enviando correo, no se pudo enviar, revisa que tu proveedor de hosting admite el envío de correos";
		   return false;

		} 

	 } catch(Exception $e){

	 	 $this->errors[] = $e;
	     return false;

	 }


	}


	//function save boolean => guarda un email en la base de datos
	 //@param $mail String email del destinatario
	 //@param $subject String asunto del correo


	private function save($mail,$subject){



		try{

			$query = array();

			$query["to"] = $mail;			
			$query["subject"] = $subject;
			$query["content"] = addslashes( htmlentities($this->template, ENT_QUOTES, 'UTF-8'));
			$query["date"] = date("Y/m/d g:s:i a");


		    $this->col->insert($query);

		    return true;

		}catch(Exception $e){


			return false;


		}


  

	}


	//function del boolean => elimina un email
	  //@param $id => id del email a elimminar

	private function del($id){


		 if($this->save){

			$id = new MongoId($id);

			try{

			   if($this->col->remove(array("_id" => $d)))
			   	  return true;

		   }catch(Exception $e){

		   	      return false;

		   }

		    }else{

		       $this->errors[] = "No se ha activado el sistema de gestión en base de datos, para activarlo debes configurar MongoDB";

		    }


	}


}



