import Adafruit_DHT
import time 
import bmp388
from datetime import datetime
import requests
import random
import serial

mport = "/dev/serial0" 
bmp388 = bmp388.DFRobot_BMP388_I2C()
DHT_SENSOR = Adafruit_DHT.DHT22
DHT_PIN = 21
url = "https://stacjapogodowaair.pl/dodawanie.php/get"
def parseGPS(data):
    if data[0:6] == "$GPGGA":
        s = data.split(",")
        if s[7] == '0' or s[7]=='00':
            print ("brak danych")
            return
        time = s[1][0:2] + ":" + s[1][2:4] + ":" + s[1][4:6]
        #print("-----------")
        lat = decode(s[2])
        lon = decode(s[4])
        return  lat,lon
    
def decode(coord):
    l = list(coord)
    for i in range(0,len(l)-1):
            if l[i] == "." :
                    break
    base = l[0:i-2]
    degi = l[i-2:i]
    degd = l[i+1:]
    baseint = int("".join(base))
    degiint = int("".join(degi))
    degdint = float("".join(degd))
    degdint = degdint / (10**len(degd))
    degs = degiint + degdint
    full = float(baseint) + (degs/60)  
    return full
ser = serial.Serial(mport,9600,timeout = 2)
ser1 = serial.Serial('/dev/ttyUSB0')
time.sleep(60)
while True:
	#zczytywanie danych z sensowów
    while True:
        try:
            dat = ser.readline().decode()
            lat,lon = parseGPS(dat)
            print(lat , "   " , lon)
            break
        except:
            print("error")
        is_none = 1
    while is_none:
        dane = []
        for index in range(0,10):
            dane1 = ser1.read()
            dane.append(dane1)
        pmt25 = int.from_bytes(b''.join(dane[2:4]), byteorder='little') / 10
        pmt10 = int.from_bytes(b''.join(dane[4:6]), byteorder='little') / 10
        print(pmt25)
        print(pmt10)
        humidity, temperature = Adafruit_DHT.read(DHT_SENSOR, DHT_PIN)
        pres = bmp388.readPressure()
        zanieczyszczenie = random.randint(0,500)
        if humidity is not None and temperature is not None and pres is not None and lat is not None and lon is not None:
            poziom = round((1.0 - pow(pres/ 101325, 0.190284)) * 287.15 / 0.65,2)
            print(f'Temp= {temperature:.1f}C Humidity= {humidity:.1f}% Pressure = {pres:.1f}hPa PNPM = {poziom:.1f}m.n.p.m')
            dane = {
            "temp" : round(temperature, 2),
            "cisn" : round(pres,2),
            "wilg" : round(humidity,2),
            "pm25"  : pmt25,
            "pm10"  : pmt10,
            "lat"  : lat,
            "lng"  : lon
            }	
            r = requests.get(url, params=dane)  
            print(r.url)
            is_none = 0
        else:
            print('ERROR')		
        #ustawienie czasu po którym wykona się następna pętla
    time.sleep(60)
    
