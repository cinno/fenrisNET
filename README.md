<h1>tendrilNET</h1>
<i>Version 0.1-alpha</i>
<hr>
<h2>About:</h2>
This program is a simple <b>proof of concept (POC)</b> of how someone can easily implement a simple botnet with a <b>DDoS</b> feature. The botnet is implemented in the context of a research question and is for <b>EDUCATIONAL PURPOSE ONLY</b>. So the author does not take responsibility for any misuse and abuse. Thatswhy: Just test and use this software on systems you own or where you have the permission to execute these kind of software. 
<hr>
<h2>How It Works:</h2>
At first the botmaster creates a bot-executable for windows using the run.py script. After he spread the malware he can control the zombies by accessing the web panel (orders.php).
<hr>
<h2>Requirements:</h2>
<ul>
<li>Pyinstaller</li>
<li>Python 2.7 for Linux systems</li>
<li>python-2.7.8.msi (for Wine)</li>
<li>pywin32-218.win32-py2.7.exe (for Wine)</li>
<li>Virtual-Wine</li>
<li>Webserver with PHP module and htaccess functionality activated</li>
<li>Wine</li>
</ul>
<hr>
<h2>Installation:</h2>
<ul>
<li>Before you first run the program you should execute the setup script (python setup.py -h)</li>
<li>If anything fails have a look at INSTALL.</li>
</ul>
<hr>
<h2>Usage Examples:</h2>
<ul>
<li>Create a new Windows executable (Bot): ./run.py -ct</li>
<li>Access the webinterface: Copy all files and folders from panel/ to your webservers www folder and access the panel by calling http://YOURWEBSERVER/orders.php</li>
</ul>
<hr>
<h2>License:</h2>
<p>Copyright 2015 Daniel Haake</p>
<p>This program is free software: you can redistribute it and/or modify<br />
it under the terms of the GNU General Public License as published by<br />
the Free Software Foundation, either version 3 of the License, or<br />
(at your option) any later version.</p>
<p>This program is distributed in the hope that it will be useful,<br />
but WITHOUT ANY WARRANTY; without even the implied warranty of<br />
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the<br />
GNU General Public License for more details.<br /></p>
<p>You should have received a copy of the GNU General Public License<br />
along with this program.  If not, see <http://www.gnu.org/licenses/>.</p>
