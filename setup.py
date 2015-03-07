#!/usr/bin/python

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


import os
from tools.tools import tools

myTool = tools()
# set all important chmods
try:
	os.system("chmod a+x run.py")
except:
	print myTool.fail + "[-] " + myTool.stop + " Something went wrong with run.py."

print myTool.green + "[+] " + myTool.stop + "Setup complete."

