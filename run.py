#!/usr/bin/python

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
		print myTool.blue + "[-h|--help]" + myTool.stop + "\t\tDisplays this help menu."
		print myTool.blue + "[-ct|--create-tendril]" + myTool.stop + "\tCreate a new tendril."
		print ""
		sys.exit()

	if "-ct" in sys.argv or "--create-tendril" in sys.argv:
		#candc = raw_input("# C&C-Server: ")
		virtualEnvWine = raw_input("# Virtual wine environment root-path: ")
		pyinstaller = raw_input("# pyinstaller.py root-path: ")
		os.system(". " + virtualEnvWine + "/bin/activate; wine c:/Python27/python.exe " + pyinstaller + " -w -a -F tendril.py")
		print myTool.green + "[+]" + myTool.stop + " tendril.exe saved into dist/ folder."
		sys.exit()
