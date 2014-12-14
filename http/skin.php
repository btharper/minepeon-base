<?php
/* SKIN SAVE OPTIMIZED */
function skinDisplay() {
	/* take the choice */
	if (!isset($_SESSION['skin']) || !$_SESSION['skin']) {
		$file = simplexml_load_file('xml/settingsSkin.xml');
		$_SESSION['skin'] = $file->SkinChooseByUser;
		if(empty($_SESSION['skin'])) {
			$_SESSION['skin'] = 'default';
		}
	}
	/* css */
	if (strval($_SESSION['skin'])=='default' || empy($_SESSION['skin'])) {
		return '<link href="/css/default/bootstrap.min.css" rel="stylesheet"><link href="/css/default/bootstrap-minepeon.css" rel="stylesheet">';
	} else {
		return '<link href="/css/bootstrap.min-new.css" rel="stylesheet"><link href="/css/'.$_SESSION['skin'].'/bootstrap-minepeon-'.$_SESSION["skin"].'.css" rel="stylesheet">';
	}
}

/* SETTING FOR CHOSE THE SKIN */
function skinChoose(){ //settings
	$formulaire = '<form name="skin" action="/settings.php" method="post" class="form-horizontal">'
		.'<fieldset><legend>Skin</legend><div class="form-group"><label for="skin"'
		.' class="control-label col-lg-3">Skin List</label><div class="col-lg-9">'
		.'<select name="skin" class="form-control">';
	//open file
	$databrute = simplexml_load_file("xml/settingsSkin.xml");
	foreach($databrute->SkinList->skin as $skin) {
		if ($skin == strval($databrute->SkinChooseByUser)) {
			$selected=' selected="selected"';
		} else {
			$selected='';
		}
		$formulaire.='<option value="'.$skin.'"'.$selected.'>'.$skin.'</option>';
	}
	$formulaire.='</select><br><button type="submit" id class="btn btn-default">Save</button>'
		.'</div></div></fieldset></form>';
	return $formulaire;
} //function skinChose

function skinReceptionNewChoice(){
	$write = 0;
	$settings = simplexml_load_file("xml/settingsSkin.xml");
	if(!empty($_POST['skin']) && $_POST['skin'] != $settings->SkinChooseByUser
		&& in_array($_POST['skin'], $settings->SkinList->skin)) {
			$settings->SkinChooseByUser=$val;
			$settings->asXml("xml/settingsSkin.xml");
	}
} //function skinReceptionNewChoice

//End functions declarations
skinReceptionNewChoice();

$tmp = simplexml_load_file('xml/settingsSkin.xml');
$_SESSION["btcUpdateTime"]=$tmp->trade->update;
if(intval($_SESSION["btcUpdateTime"])<intval(time())-1800){
/* actualized all 1/2 hour (1800s) */ 
	tradeBtcEuro(); tradeBtcDollars(); 
	$_SESSION["btcUpdateTime"]=time();
	$tmp = simplexml_load_file('xml/settingsSkin.xml');
	$tmp->trade->update=$_SESSION["btcUpdateTime"];
	$tmp->asXml('xml/settingsSkin.xml');
}else{
	$_SESSION["btceuro"]=floatval($tmp->trade->euro);
	$_SESSION["btceuroLast"]=floatval($tmp->trade->euro_last);
	$_SESSION["btcdollars"]=floatval($tmp->trade->dollars);
	$_SESSION["btcdollarsLast"]=floatval($tmp->trade->dollars_last);
}

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
	
$file=file_get_contents('http://fr.investing.com/currencies/btc-eur', 
false, $context);
	
$verif=preg_match('|<span\s+class="arial_26"\sid="last_last"\s*>(.*)</span>|',$file,$match);
	fclose($file);
	if ($verif){ 
		$_SESSION["btceuro"]=$match[1]; 
		$xml = simplexml_load_file('xml/settingsSkin.xml'); 
		$xml->trade->euro_last=$xml->trade->euro;
		$_SESSION["btceuroLast"]=floatval($xml->trade->euro);
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
	
$file=file_get_contents('http://fr.investing.com/currencies/btc-usd', 
false, $context);
	
$verif=preg_match('|<span\s+class="arial_26"\sid="last_last"\s*>(.*)</span>|',$file,$match);
	fclose($file);
	if ($verif){ 
		$_SESSION["btcdollars"]=$match[1]; 
		$xml = simplexml_load_file('xml/settingsSkin.xml'); 
		$xml->trade->dollars_last=$xml->trade->dollars;
		
$_SESSION["btcdollarsLast"]=floatval($xml->trade->dollars);
		$xml->trade->dollars=$_SESSION["btcdollars"];
		$xml->asXml('xml/settingsSkin.xml');
	}else{ 
		$_SESSION["btcdollars"]='error';
	}
}

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
			$xml = 
simplexml_load_file("xml/settingsSkin.xml");
			$xml->DisplayWebcam=$_SESSION["webcam"]; 
			$xml->asXml("xml/settingsSkin.xml");
		}
}


//reeception form Language
function changeLang(){
	//recept of the form for change the language
	if(isset($_POST["lang"]) && !empty($_POST["lang"])){
		$xml = simplexml_load_file('xml/settingsSkin.xml');
		foreach($xml->LangList as $v){
			foreach($v as $dim=>$full){
				
if(strval($_POST["lang"])==strval($dim)){ 
$xml->LangChoosed=strval($dim); $xml->asXml("xml/settingsSkin.xml"); }
			}
			
		}

	}
}
?>