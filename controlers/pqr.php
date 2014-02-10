<?
require_once(dirname(__FILE__) . "/app.mail.php");

define("dbname", "serviaseo");

class pqr {

   private $con;
   private $db;
   private $col;

   function __construct(){

      $this->con = new MongoClient();
      $this->db = $this->con->selectDB(dbname);
      $this->col = $this->db->pqrs;

   }


   public function neww (){

      $sede = array("Corozal" , "Chinu",  "El Carmen", "Magangue" , "Sampues" ,"San Onofre");  
      $mails = array(NULL , "pqr.chinu@serviaseo.com.co",  "pqr.elcarmen@serviaseo.com.co", "pqr.magangue@serviaseo.com.co" , "pqr.sampues@serviaseo.com.co" ,"pqr.sanonofre@serviaseo.com.co");  

       foreach ($_POST as $key => $value)
          $_POST[$key] = trim(htmlentities(strip_tags($value),ENT_QUOTES, 'UTF-8'));        

       if(!is_numeric($_POST["nic"]) || !is_numeric($_POST["cc"])){
             echo json_encode(array("success" => 0)); 
             die;
       }



       $pin = substr(md5(time()), 0, 4);
       $num_pqr = substr(md5(time()), 6, 10);
       $sedin = (int) $_POST["sede"];
       $_POST["sede"] = $sede[$_POST["sede"]];


       $data = "<div style='padding:5px; display:block; border:1px solid #f2f2f2; border-radius:3px'>";
       $data .= "Detalles de la solicitud<br><br>";
       $data .= "Nombre: " . $_POST["name"] . "<br>";
       $data .= "Nic: " . $_POST["nic"] . "<br>";
       $data .= "Cédula: " . $_POST["cc"] . "<br>";
       $data .= "Sede: " . $_POST["sede"] . "<br>";
       if(filter_var($_POST["email"], FILTER_VALIDATE_EMAIL))       
       $data .= "Correo: " . $_POST["email"]. "<br><br>";
       $data .= "solicitud: <br>" . $_POST["msg"] ;
       $data .= "</div>";

     

      
     $mail = new Mail("Serviaseo - Servicio al Cliente", "pqr@serviaseo.com.co");

    $content = "Hola {$_POST['name']}, <br><br> Hemos recibido su solicitud, por favor guarde el número de solicitud <b>{$num_pqr}</b> y el PIN <b>{$pin}</b>. <br><br>" . $data . "<br><br>Haremos nuestro mejor esfuerzo para responder en el menor tiempo posible." ;
      $mail->set("head", "<div><img src=\"http://serviaseo.com.co/themes/serviaseo/img/logo.png\" width=\"130\" alt=\"\" ></div><br><br>");
      $mail->set("content", "<div>{$content}</div>");
      $mail->set("foot", "<div>Saludos Cordiales, <br /> <br /> Servicio al Cliente <br />Serviaseo S.A. E.S.P.</div>"); 

    $content = "Hola Equipo, <br><br> Hemos recibido una solicitud de PQR, bajo número de solicitud <b>{$num_pqr}</b>. <br><br>" . $data . "<br><br>Tratemos de responder en el menor tiempo posible." ;
      

     if(filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)){
      if( $mail->send($_POST["name"], $_POST["email"], "Hemos recibido su solicitud") )
             {

           $mail = new Mail("Serviaseo - Servicio al Cliente", "pqr@serviaseo.com.co");
           $mail->set("head", "<div><img src=\"http://serviaseo.com.co/themes/serviaseo/img/logo.png\" width=\"130\" alt=\"\" ></div><br><br>");
           $mail->set("content", "<div>{$content}</div>");
           $mail->set("foot", "<div>Saludos Cordiales, <br /> <br /> Servicio al Cliente <br />Serviaseo S.A. E.S.P.</div>"); 

              if( $mail->send($_POST["name"], "pqr@serviaseo.com.co", "Solicitud PQR") )               
              {
                
                $_POST["pin"] = $pin;
                $_POST["num_pqr"] = $num_pqr;
                
                if($mails[$sedin] != NULL){ 

                 $mail = new Mail("Serviaseo - Servicio al Cliente", "pqr@serviaseo.com.co");
                 $mail->set("head", "<div><img src=\"http://serviaseo.com.co/themes/serviaseo/img/logo.png\" width=\"130\" alt=\"\" ></div><br><br>");
                 $mail->set("content", "<div>{$content}</div>");
                 $mail->set("foot", "<div>Saludos Cordiales, <br /> <br /> Servicio al Cliente <br />Serviaseo S.A. E.S.P.</div>"); 

                 $mail->send($_POST["name"], $mails[$sedin], "Solicitud PQR");
                 $this->register($_POST);
                 echo json_encode(array("success" => 1 , "pin" => $pin , "num_pqr" => $num_pqr));  


                 }else
                 echo json_encode(array("success" => 1 , "pin" => $pin , "num_pqr" => $num_pqr)); 




               }
               else
              echo json_encode(array("success" => 0));                  

             }

           }

     else{

              
           $mail = new Mail("Serviaseo - Servicio al Cliente", "pqr@serviaseo.com.co");
           $mail->set("head", "<div><img src=\"http://serviaseo.com.co/themes/serviaseo/img/logo.png\" width=\"130\" alt=\"\" ></div><br><br>");
           $mail->set("content", "<div>{$content}</div>");
           $mail->set("foot", "<div>Saludos Cordiales, <br /> <br /> Servicio al Cliente <br />Serviaseo S.A. E.S.P.</div>"); 


              if( $mail->send($_POST["name"], "pqr@serviaseo.com.co", "Solicitud PQR") )               
              { 
                $_POST["pin"] = $pin;
                $_POST["num_pqr"] = $num_pqr;

                if($mails[$sedin] != NULL){ 

                 $mail = new Mail("Serviaseo - Servicio al Cliente", "pqr@serviaseo.com.co");
                 $mail->set("head", "<div><img src=\"http://serviaseo.com.co/themes/serviaseo/img/logo.png\" width=\"130\" alt=\"\" ></div><br><br>");
                 $mail->set("content", "<div>{$content}</div>");
                 $mail->set("foot", "<div>Saludos Cordiales, <br /> <br /> Servicio al Cliente <br />Serviaseo S.A. E.S.P.</div>"); 

                 $mail->send($_POST["name"], $mails[$sedin], "Solicitud PQR");
                 $this->register($_POST);
                 echo json_encode(array("success" => 1 , "pin" => $pin , "num_pqr" => $num_pqr));  


                 }else
                 echo json_encode(array("success" => 1 , "pin" => $pin , "num_pqr" => $num_pqr));  



               }
               else
              echo json_encode(array("success" => 0));                  
       
        }

   }

   public function find(){

     $col = $this->col;
     $rs = $col->find();
     $rs->sort(array("date" => -1));

     $rs = iterator_to_array($rs);

     if(is_array($rs))
      echo json_encode(array("success" => 1 , "data" => $rs));
    else
     echo json_encode(array("success" => 0));

   }


   public function del($id){


     $col = $this->col;
     
     $params = array(
        "_id" => new MongoId($id)
      );

     $rs = $col->remove($params);

     if($rs)
     echo json_encode(array("success" => 1 ));
    else
     echo json_encode(array("success" => 0));

   }


   protected function register($data){
 
      $col = $this->col;     

      $data["date"] = new MongoDate();
      $data["status"] = "0";

      $col->save($data);

   }

}



$pqr = new pqr;


if($_GET)
{

    if(isset($_GET["new"]))
      $pqr->neww();


    if(isset($_GET["find"]))
      $pqr->find();

}