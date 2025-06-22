
#include <AM2302-Sensor.h>
// #define outPin 8        // Defines pin number to which the sensor is connected

AM2302::AM2302_Sensor am2302{8};


void setup() {
	Serial.begin(9600);
  am2302.begin();
}

void loop() {

  auto status = am2302.read();
  Serial.print("\n\nstatus of sensor read(): ");
  Serial.println(AM2302::AM2302_Sensor::get_sensorState(status));

  Serial.print("Temperature: ");
  Serial.println(am2302.get_Temperature());

	float t = am2302.get_Temperature();        // Read temperature


	Serial.print("Temperature = ");
	Serial.print(t);
	Serial.print("°C | ");
	Serial.print((t*9.0)/5.0+32.0);        // Convert celsius to fahrenheit
	Serial.println("°F ");
	// Serial.print("Humidity = ");
	// Serial.print(h);
	// Serial.println("% ");
	// Serial.println("");

	delay(5000); // wait two seconds
}