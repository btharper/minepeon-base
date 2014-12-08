<?php
if(!isset($settings['update'])) {
    $settings['update'] = false;
}

//include the good language's file
if (!empty($_SESSION["lang"])) {
	$langue = strval($_SESSION["lang"]);
	require_once('lang/'.$langue.'/lang.'.$langue.'.php');
} else {
	$xml = simplexml_load_file('xml/settingsSkin.xml');
	$_SESSION["lang"] = strval($xml->LangChoosed);
	$langue = strval($_SESSION["lang"]);
	require_once('lang/'.$langue.'/lang.'.$langue.'.php');
}
?>
<div class="navbar navbar-default">
<div class="container">
	<a class="navbar-brand" href="https://nebuleuse0rion.ddns.net:444/minepeon/">MinePeon<span id="voxedition"> VoxEdition</span></a>
	<ul class="nav navbar-nav">
		<li><a href="/"><?=$lang["status"]?></a></li>
		<li><a href="/pools.php"><?=$lang["pools"]?></a></li>
		<li><a href="/settings.php"><?=$lang["settings"]?></a></li>
		<li><a href="/plugins.php"><?=$lang["plugins"]?></a></li>
		<li><a href="/about.php"><?=$lang["about"]?></a></li>
		<li><a href="http://minepeon.com/forums/" target=_blank>Forum</a></li>
		<li><a class='btceuro' href="http://fr.investing.com/currencies/btc-eur" target=_blank>BTC/<?=$_SESSION["btceuro"]?> &euro; <span class="bitecoinCompareMenu<?php
			if($_SESSION["btceuro"] > $_SESSION["btceuroLast"]) {
				echo 'Plus">+';
			} elseif($_SESSION["btceuro"] < $_SESSION["btceuroLast"]) {
				echo 'Minus">-';
			} elseif($_SESSION["btceuro"]==$_SESSION["btceuroLast"]) {
				echo 'Egal">=';
			} ?></span></a></li>
		<li><a class='btcdollars' href="http://fr.investing.com/currencies/btc-usd" target=_blank>
		<?php echo 'BTC/'.$_SESSION["btcdollars"].' $'; if($_SESSION["btcdollars"]>$_SESSION["btcdollarsLast"]){ echo ' <span class="bitecoinCompareMenuPlus">+</span>';}elseif($_SESSION["btcdollars"]<$_SESSION["btcdollarsLast"]){ echo ' <span class="bitecoinCompareMenuMinus">-</span>';}elseif($_SESSION["btcdollars"]==$_SESSION["btcdollarsLast"]){ echo ' <span class="bitecoinCompareMenuEgal">=</span>';}  ?></a></li>
<?php 
   if ($handle = opendir('plugins/api_menu/')) {
        while (false !== ($entry = readdir($handle))) {
            if ($entry != "." && $entry != "..") {
               $menuadd=simplexml_load_file("plugins/api_menu/" . $entry);
echo "<li><a href='" . $menuadd->pl_folder . "'>" . $menuadd->Menu_text . "</a></li>";

            }
        }
        closedir($handle);
   }
?>
</ul>
  </div>
</div>
<?php
if ($settings['update'] == "true"){
?>
<div align="center" class="container">
<div class="alert alert-info alert-dismissable">
  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
  <strong>Minepeon:</strong> Update available! <a href="/update.php" class="alert-link">Do you want to update?</a>
</div>
</div>
<?php
}
?>
