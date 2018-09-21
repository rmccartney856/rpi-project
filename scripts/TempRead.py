#!/usr/bin/python
import Adafruit_DHT
import os
import time
import datetime
import glob
import MySQLdb
from time import strftime

# Set sensor type : Options are DHT11,DHT22 or AM2302
sensor = Adafruit_DHT.DHT11
# Set GPIO sensor is connected to
gpio = 23
count = 1

def dateTime():  # get UNIX time
    secs = float(time.time())
    secs = secs * 1000
    return secs

while True:

    os.system('clear')
    print("-------------------------------------------")
    print('Sensor Logging Script. Log entry {0}.'.format(count))
    print("-------------------------------------------")
    print("")

    # Use read_retry method. This will retry up to 15 times to
    # get a sensor reading (waiting 2 seconds between each retry).
    print "1 = Reading sensor data."
    humidity, temperature = Adafruit_DHT.read_retry(sensor, gpio)

    # Variables for MySQL
    db = MySQLdb.connect(host="localhost", user="root", passwd="GaragePi", db="weatherdata")  # replace password with your password
    # Open database connection
    cur = db.cursor()

    # Reading the DHT11 is very sensitive to timings and occasionally
    # the Pi might fail to get a valid reading. So check if readings are valid.
    if humidity is not None and temperature is not None:

        secs = dateTime()

        sql = ("""INSERT INTO garagesensor (datetime,temperature,humidity) VALUES (%s,%s,%s)""", (secs, temperature, humidity))

        try:
            print "2 = Writing to the database."
            print('3 = Temperature is {0:0.1f}*C and Humidity is {1:0.1f}%'.format(temperature, humidity))
            cur.execute(*sql)
            db.commit()
            print "4 = Write complete. Database updated."
            count = count + 1

        except:
            db.rollback()
            print "4 = Problem writing data to SQL database."

        cur.close()
        db.close()

        with open('/var/www/html/data/temperature.txt', 'w') as f:
            print >> f, temperature

        with open('/var/www/html/data/humidity.txt', 'w') as f:
            print >> f, humidity, "%"

        print('5 = Writing most recent data to text file.')

        print('6 = Waiting for 10 minutes before attempting to submit more data.')
        time.sleep(600)

    else:
        print('2 = Failed to get reading from temperature sensor.')
        time.sleep(1)
        print('3 = Retrying...')
        time.sleep(1)


