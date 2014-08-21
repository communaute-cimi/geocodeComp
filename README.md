#GeocodeComp

## A gecoding comparison tool...

For main geocoding comparison solutions like google or nominatim.
Useful to compare with your own geocoding service

## Usage

First you have to *create the config file* /conf/conf.json (you can use sample --> conf.sample.json)
Buttons are created dynamicaly, a bind is done between JS function (used to call proxy and parse results) and PHP proxy (used ton invoke geocoding server).

For intranets calls you can specify a web proxy (in config file). The call is done with cUrl (PHP installation needed)


## Exemple

![Screenshot](/resources/screenshot/geocodeCompScr.jpg)
