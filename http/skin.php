<?php
/* SKIN SAVE OPTIMIZED */
	function skinDisplay(){
		/* take the choice*/
		if (!isset($_SESSION["skin"]) || !$_SESSION["skin"]){ 
			$file = simplexml_load_file('xml/settingsSkin.xml'); $_SESSION["skin"] = $file->SkinChooseByUser; 
		}
		/* css */
		if ($_SESSION["skin"]=="default" || !$_SESSION["skin"]){
			return '<link href="/css/default/bootstrap.min.css" rel="stylesheet"><link href="/css/default/bootstrap-minepeon.css" rel="stylesheet">';
		}else{
			return '<link href="/css/bootstrap.min-new.css" rel="stylesheet"><link href="/css/'.$_SESSION["skin"].'/bootstrap-minepeon-'.$_SESSION["skin"].'.css" rel="stylesheet">';}

	}
	
/* SETTING FOR CHOSE THE SKIN */
 	function skinChoose(){ //settings

	$formulaire = '<form name="skin" action="/settings.php" method="post" class="form-horizontal">'.
	    '<fieldset>'.
	      '<legend>Skin</legend>'.
	      '<div class="form-group">'.
	       '<label for="skin" class="control-label col-lg-3">Skin List</label>'.
	        '<div class="col-lg-9">'.
	          '<select name="skin" class="form-control">';
			#open file
		$databrute = simplexml_load_file("xml/settingsSkin.xml");
				foreach($databrute->SkinList->skin as $skin){ 
					if ($skin == $databrute->SkinChooseByUser){$selected="selected";}else{$selected="";} 
					$formulaire.='<option value="'.$skin.'" '.$selected.'>'.$skin.'</option>';
				}
	          $formulaire.='</select>'.
	          '<br>'.
	          '<button type="submit" id class="btn btn-default">Save</button>'.
	        '</div>'.
	      '</div>'.
	    '</fieldset>'.
	  '</form>';
		return $formulaire;	
	}

        skinReceptionNewChoice();
        function skinReceptionNewChoice(){
                        $write = 0;
                        $settings = simplexml_load_file("xml/settingsSkin.xml");
                if ($_POST['skin'] && $_POST['skin']!= $settings->SkinChooseByUser){
                        foreach($settings->SkinList->skin as $val){
                                if ($val == $_POST["skin"]){$settings->SkinChooseByUser=$val; $settings->asXml("xml/settingsSkin.xml");}

                        }
                }
        }
?>
<?php
if(isset($_SESSION["btceuroTime"]) && $_SESSION["btceuroTime"]){
	if($_SESSION["btceuroTime"]<(time()-1800)){ tradeBtcEuro(); tradeBtcDollars(); } /* actualized all 1/2 hour (1800s) */
}else{ tradeBtcEuro(); tradeBtcDollars();}
function tradeBtcEuro(){
	$opts = array(
	  'http'=>array(
	    'method'=>"GET",
	    'header'=>	"Accept-language: en\r\n" .
	 	        "Cookie: Infernalis=Creatorem\r\n",
			'user_agent' => 'Firefox/31'
	  )
	);
	$context = stream_context_create($opts);
	$file=file_get_contents('http://fr.investing.com/currencies/btc-eur', false, $context);
	$tab=preg_match('{<span\s+class="arial_26"\sid="last_last"\s*>([\d,]+).*?</span>}',$file,$match);
	fclose($file);
	if ($tab){ 
		$_SESSION["btceuroTime"]=time();
		$_SESSION["btceuro"]=$match[0]; 
		$xml = simplexml_load_file('xml/settingsSkin.xml'); 
		$_SESSION["btcdeuroLast"]=$xml->trade->dollars;
		$xml->trade->euro=$_SESSION["btceuro"];
		$xml->asXml('xml/settingsSkin.xml');
	}else{ 
		$_SESSION["btceuro"]='error';
	}
}
function tradeBtcDollars(){
	$opts = array(
	  'http'=>array(
	    'method'=>"GET",
	    'header'=>	"Accept-language: en\r\n" .
	 	        "Cookie: Creatorem=Infernalis\r\n",
			'user_agent' => 'Firefox/31'
	  )
	);
	$context = stream_context_create($opts);
	$file=file_get_contents('http://fr.investing.com/currencies/btc-usd', false, $context);
	$tab=preg_match('{<span\s+class="arial_26"\sid="last_last"\s*>([\d,]+).*?</span>}',$file,$match);
	fclose($file);
	if ($tab){ 
		$_SESSION["btcdollars"]=$match[0]; 
		$xml = simplexml_load_file('xml/settingsSkin.xml'); 
		$_SESSION["btcdollarsLast"]=$xml->trade->dollars;
		$xml->trade->dollars=$_SESSION["btcdollars"];
		$xml->asXml('xml/settingsSkin.xml');
	}else{ 
		$_SESSION["btcdollars"]='error';
	}
}
?>


<?php
//reception of form (settings.php)
function webcamChooseDisplay(){
		$_SESSION["webcam"] = -1;
	if (isset($_POST["webcamON"]) && $_POST["webcamON"]=="1"){
		$_SESSION["webcam"]=1;
	}
	if (isset($_POST["webcamOFF"]) && $_POST["webcamOFF"]=="1"){
		$_SESSION["webcam"]=0;
	}
		#save the choice in /xml/settings.xml
		if ($_SESSION["webcam"]>=0){
			$xml = simplexml_load_file("xml/settingsSkin.xml");
			$xml->DisplayWebcam=$_SESSION["webcam"]; 
			$xml->asXml("xml/settingsSkin.xml");
		}
}
?>
