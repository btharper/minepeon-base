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

/**
 * Get Investing.com exchange rates
 *
 * @author btharper1221@gmail.com
 *
 * @param integer $curr Currency to translate <code>$fromCurr</code> into, using investing.com's indexes.
 * @param integer $fromCurr Optional. Currency to translate from, defaults to BTC.
 * @param float $amount Optional. Number of units of <code>$fromCurr</code> to convert into <code>$curr</code>.
 *
 * @return array An array consisting of the exchange rate, reverse rate, and converted 
 * 	<code>$amount</code> of <code>$fromCurr</code> to <code>$curr</code>.
 */
function tradeBtcCurr($curr, $fromCurr = 189, $amount = 1) {
	/*Lookup currency exchange rates based on investing.com's rates
	* from tool http://www.investing.com/webmaster-tools/currency-converter
	* GET http://tools.investing.com/currency-converter/index.php?from=XXX&to=ZZZ
	*	Also returns: Currency list, reverse rate
	* POST http://tools.investing.com/currency-converter/js/ajax_func.php {action:convert_currencies,cur1:XXX,cur2:ZZZ,amount:Y}
	*	Returns exactly: rate (rate_basic * amount), rate_basic, and rate_basic_reverse
	* 189 BTC; 191 LTC; 17 EUR; 12 USD; No leading zeros */
	//TODO: Permit passing of string currencies (eg EUR,USD)
	
	//Setup request data and parameters
	$formData = 'action=convert_currencies&cur1='.$fromCurr.'&cur2='.$curr.'&amount='.$amount;
	$fetchUrl = 'http://tools.investing.com/currency-converter/js/ajax_func.php';
	$httpOpts = array('method' => 'POST',
			'max_redirects' => 3, //Shouldn't be any redirects
			'ignore_errors' => true,
			'content' => $formData,
			//TODO: proxy support
			//'timeout' => 2.5, //in seconds
			'header' => 'Accept: application/xml, text/xml, */*'."\r\n"
				.'User-Agent: '.getUserAgent()."\r\n"
				.'Content-Type: application/x-www-form-urlencoded'."\r\n"
				.'Content-Length: '.strlen($formData)."\r\n"
			);
	$context = stream_context_create(array('http'=>$httpOpts));
	
	//Grab it, limit fetched result to 2kb as a sanity check
	$fetch = file_get_contents($fetchUrl, false, $context, -1, 2048);
	if($fetch === false) {
		error_log("Failed to fetch currency conversions from $fetchUrl");
		return null;
	}
	
	//Setup xml parsing
	if(libxml_use_internal_errors(true) && !empty(libxml_get_errors())) {
		/*libxml_use_internal_errors(true) suppresses E_WARNINGs on bad xml being passed to
		* simplexml_load_* and allows PHP scripts to handle errors themselves. When we turn
		* this on we expect there not to be any errors in the libxml_get_errors() buffer,
		* but if there are, log an error and clear it. */
		
		error_log("Call to libxml_get_errors() was not empty when it was expected to be.");
		libxml_clear_errors();
	}
	
	//Begin xml parsing and error handling
	$xml = simplexml_load_string($fetch);
	if($xml !== false && empty(libxml_get_errors())) {
		//XML parsing worked and there were no errors
		return array($xml->rate_basic, $xml->rate_basic_reverse);
	} else {
		//Format and print errors
		$errs = libxml_get_errors();
		$text = "Failed to parse xml";
		foreach($errs as $err) {
			$text .= $err->message." \t";
		}
		error_log($text);
		return null;
	}
}

/**
 * Returns the user's User-Agent, or <code>$default</code>
 *
 * @author btharper1221@gmail.com
 *
 * @param string $default Optional. User-Agent to use if <code>$_SERVER['HTTP_USER_AGENT']</code> is not present
 * @return string User-Agent string value
 */
function getUserAgent($default = 'Firefox/31') {
	/*Fetches the user's user agent to be more realistic
	* fallback to default if user supplied isn't available
	* 'HTTP_*' will not be populated if run from cli */

	if(empty($_SERVER['HTTP_USER_AGENT'])) {
		return $default;
	} else {
		return trim($_SERVER['HTTP_USER_AGENT']);
	}
}

function tradeBtcEuro(){
	$opts = array(
	    'http'=>array(
		'method' => "GET",
		'header' => "Accept-language: en\r\n" .
	 	        "Cookie: Infernalis=Creatorem\r\n",
		'user_agent' => 'Firefox/31'
	  )
	);
	$context = stream_context_create($opts);
	
$file=file_get_contents('http://fr.investing.com/currencies/btc-eur', false, $context);
	
$verif=preg_match('|<span\s+class="arial_26 pid-22-last" id="last_last"\s*>(.*)</span>|',$file,$match);
	//fclose($file);
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
	
$verif=preg_match('|<span\s+class="arial_26 pid-21-last" id="last_last"\s*>(.*)</span>|',$file,$match);
	//fclose($file);
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
