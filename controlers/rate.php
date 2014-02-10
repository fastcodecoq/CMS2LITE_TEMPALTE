<?

 function rate($rate){
  
    $con = new MongoClient();
    $db = $con->selectDB("serviaseo");
    $col = $db->rates;
    $raters = $db->raters;
    $ip = $_SERVER["REMOTE_ADDR"];

    
    if(!is_numeric(str_replace(".","",$ip)) || !is_numeric($rate))
    {	
    	echo json_encode(array("success"=>0));
    	die;
    }

    if($rate < 1)
    	{	
    	echo json_encode(array("success"=>0));
    	die;
       }

    if(is_array($raters->findOne(array("ip" => $ip))))
	{	
    	echo json_encode(array("success"=>-1));
    	die;
    }    

    $rs = $col->update(array("rate" => $rate) , array('$inc' => array("points" => 1)));

    if($rs)
    	{
    		$raters->save(array("ip" => $ip));
    		$rs = iterator_to_array($col->find());
    		$prom = 0;

    		foreach ($rs as $rate)
    			$prom += $rate["points"];

    		$rates = array();
    		

    		foreach ($rs as $rate)
    			   $rates[] = ( $rate["points"] * 100 ) / $prom . "% (" . $rate["points"] . " votos)";

    		echo json_encode(array("success" => 1 , "data" => $rates));

    	}



 }



  if(isset($_GET["rate"]))
 	rate((int) $_POST["rate"]);



/* $con = new MongoClient();
    $db = $con->selectDB("serviaseo");
    $col = $db->rates;

    for($i = 1; $i<6; $i++){
    	 $rate = array("rate" => $i, "points"=>0);
    	 $col->save($rate);
    }  */