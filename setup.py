#!/usr/bin/python

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


import os
import sys
from tools.tools import tools


myTool = tools()
usageString = "Usage: " + sys.argv[0] + myTool.blue  + " <options>" + myTool.stop

def completeMessage():
	print myTool.green + "[+] " + myTool.stop + "Setup complete."
	print "*** Remember: \"A hacker should only be limited by his imagination and not by his tools.\"\n"	

# set all important chmods
def chmods():
	try:
		os.system("chmod a+x run.py")
	except:
		print myTool.fail + "[-] " + myTool.stop + " Something went wrong with run.py."
	try:
		os.system("chmod 777 panel/data/config")
	except:
		print myTool.fail + "[-] " + myTool.stop + " Something went wrong with config."
	try:
		os.system("chmod 777 panel/data/")
	except:
		print myTool.fail + "[-] " + myTool.stop + " Something went wrong with data folder."
	try:
		os.system("chmod 777 panel/data/bots/")
	except:
		print myTool.fail + "[-] " + myTool.stop + " Something went wrong with bots folder."		
	try:
		os.system("chmod 777 panel/data/password")
	except:
		print myTool.fail + "[-] " + myTool.stop + " Something went wrong with password."
	try:
		os.system("chmod 777 cgi-bin/")
	except:
		print myTool.fail + "[-] " + myTool.stop + " Something went wrong with cgi-bin folder."
	try:
		os.system("chmod 777 cgi-bin/configuration.html")
	except:
		print myTool.fail + "[-] " + myTool.stop + " Something went wrong with configuration.html."			
	try:
		os.system("chmod 777 cgi-bin/creation.html")
	except:
		print myTool.fail + "[-] " + myTool.stop + " Something went wrong with creation.html."			
	try:
		os.system("chmod 777 cgi-bin/index.html")
	except:
		print myTool.fail + "[-] " + myTool.stop + " Something went wrong with index.html."			
	
	print myTool.green + "[+] " + myTool.stop + "All needed chmods set."

def environment():
	try:
		os.system("wget https://www.python.org/ftp/python/2.7.8/python-2.7.8.msi")
	except:
		print myTool.fail + "[-] " + myTool.stop + " Something went wrong with downloading the python msi installer."
	try:
		os.system("wget http://downloads.sourceforge.net/project/pywin32/pywin32/Build%20218/pywin32-218.win32-py2.7.exe")
	except:
		print myTool.fail + "[-] " + myTool.stop + " Something went wrong with downloading the pywin32 installer."
	try:
		os.system("git clone https://github.com/pyinstaller/pyinstaller")
	except:
		print myTool.fail + "[-] " + myTool.stop + " Something went wrong with cloning pyinstaller."
	try:
		os.system("git clone https://github.com/htgoebel/virtual-wine.git")
	except:
		print myTool.fail + "[-] " + myTool.stop + " Something went wrong with cloning virtual-wine."
	try:
		os.system("apt-get install scons")
	except:
		print myTool.fail + "[-] " + myTool.stop + " Something went wrong with installing scons."	
	print myTool.green + "[+] " + myTool.stop + "All needed third party downloads complete."
	print myTool.green + "[+] " + myTool.stop + "Installing virtual-wine (You may choose windows 7 at the end if asked.)..."
	try:
		os.system("./virtual-wine/vwine-setup venv_wine")
	except:
		print myTool.fail + "[-] " + myTool.stop + " Something went wrong with installing virtual-wine."
	try:
		os.system(". venv_wine/bin/activate")
	except:
		print myTool.fail + "[-] " + myTool.stop + " Something went wrong with activating virtual-wine."	
	print myTool.green + "[+] " + myTool.stop + "Installing virtual-wine complete."
	try:
		os.system("wine msiexec -i python-2.7.8.msi")
	except:
		print myTool.fail + "[-] " + myTool.stop + " Something went wrong with installing python 2.7.8 for Windows."
	try:
		os.system("wine pywin32-218.win32-py2.7.exe")
	except:
		print myTool.fail + "[-] " + myTool.stop + " Something went wrong with installing pywin32 for Windows."
	print myTool.green + "[+] " + myTool.stop + "Installation of third party downloads complete."
	try:
		os.system("rm python-2.7.8.msi")
		os.system("rm pywin32-218.win32-py2.7.exe")
	except:
		print myTool.fail + "[-] " + myTool.stop + " Error while cleanup."


if len(sys.argv) == 2:
	if "-h" in sys.argv or "--help" in sys.argv:
		print usageString
		print myTool.blue + "[-h|--help]" + myTool.stop + "\t\tDisplays this help menu."
		print myTool.blue + "[-c|--complete]" + myTool.stop + "\t\tPerform complete setup."
		print myTool.blue + "[-p|--permissions]" + myTool.stop + "\tSet all necessary permissions (chmod)."
		print myTool.blue + "[-e|--environment]" + myTool.stop + "\tInstall needed software to compile windows executables."
		print ""

	if "-p" in sys.argv or "--permissions" in sys.argv:
		chmods()
		completeMessage()
		sys.exit(0)
		
	if "-e" in sys.argv or "--environment" in sys.argv:
		environment()
		completeMessage()
		sys.exit(0)
		
	if "-c" in sys.argv or "--complete" in sys.argv:
		chmods()
		environment()
		completeMessage()
		sys.exit(0)
else:
	print myTool.fail + "[-]" + myTool.stop + "\tWorng number of input parameters."
