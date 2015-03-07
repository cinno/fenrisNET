#!/usr/bin/python


import os
from tools.tools import tools

myTool = tools()
# set all important chmods
try:
	os.system("chmod a+x run.py")
except:
	print myTool.fail + "[-] " + myTool.stop + " Something went wrong with run.py."

print myTool.green + "[+] " + myTool.stop + "Setup complete."

