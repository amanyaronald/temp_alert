#include <SoftwareSerial.h>
#include <DHT.h>

#define DHTPIN 2             // Pin where the DHT sensor is connected
#define DHTTYPE DHT22        // DHT11 or DHT22
#define SENSOR_ID "1"  // Custom sensor identifier

#define RX 10  // GSM Module TX pin connected to Arduino pin 10
#define TX 11  // GSM Module RX pin connected to Arduino pin 11

SoftwareSerial gsm(RX, TX);
DHT dht(DHTPIN, DHTTYPE);


float minTemp = 22.0;
float maxTemp = 30.0;
unsigned long lastReportTime = 0;
const unsigned long reportInterval = 5 * 60 * 1000UL; // 5 minutes
String phoneNumber = "+256782150448"; // Replace with your actual number

void setup() {
  Serial.begin(9600);
  gsm.begin(9600);
  dht.begin();
  delay(1000);

    Serial.println("[LOG] ----");

  sendSMS(phoneNumber, "Sensor booted. Send 'SET MIN <value>' or 'SET_MAX <value>' to configure.");
}

void loop() {
  float temp = dht.readTemperature();
  if (isnan(temp)) {
    Serial.println("Failed to read from DHT sensor!");
    return;
  }

  // Check thresholds and send alerts
  if (temp < minTemp) {
    sendTempReport(temp, "low");
  } else if (temp > maxTemp) {
    sendTempReport(temp, "high");
  } else {
    // Send report every 5 minutes
    if (millis() - lastReportTime > reportInterval) {
      lastReportTime = millis();
      sendTempReport(temp, "normal");
    }
  }

  // Check for incoming SMS
  if (gsm.available()) {
    String message = gsm.readString();
    message.trim();
    Serial.println("SMS Received: " + message);

    // Strip out the default message for test messages
    if (message.startsWith("TEST")) {
      sendSMS(phoneNumber, "Test message received successfully.");
      return;
    }

    // remove Sent from your Twilio Trial account - and decode [SET_MAX=30;SET MIN=25.6] string into an array
    // Remove "Sent from your Twilio Trial account" and decode "SET_MAX=30;SET MIN=25.6" string into an array
    message.replace("Sent from your Twilio Trial account", "");
    if (message.indexOf("SET_MAX") != -1 || message.indexOf("SET MIN") != -1) {
      int maxIndex = message.indexOf("SET_MAX");
      int minIndex = message.indexOf("SET MIN");
      if (maxIndex != -1) {
        String maxValue = message.substring(maxIndex + 8, message.indexOf(";", maxIndex));
        maxTemp = maxValue.toFloat();
        sendThresholdResponse("max_set", maxTemp);
      }
      if (minIndex != -1) {
        String minValue = message.substring(minIndex + 8, message.indexOf(";", minIndex));
        minTemp = minValue.toFloat();
        sendThresholdResponse("min_set", minTemp);
      }
    }
  }
}

void sendSMS(String number, String message) {
  gsm.println("AT+CMGF=1"); delay(100);
  gsm.print("AT+CMGS=\""); gsm.print(number); gsm.println("\""); delay(300);
  gsm.print(message); delay(100);
  gsm.write(26); // CTRL+Z to send
  delay(2000);
  Serial.println("SMS Sent: " + message);
}

//sensor=SENSOR1;temp=2.6;status=normal
void sendTempReport(float temp, String status) {
  String message = "sensor=" + String(SENSOR_ID) +
                   ";temp=" + String(temp, 1) +
                   ";status=" + status;
  sendSMS(phoneNumber, message);
}

void sendThresholdResponse(String key, float value) {
  String message = "sensor=" + String(SENSOR_ID) +
                   ";" + key + "=" + String(value, 1);
  sendSMS(phoneNumber, message);
}

float extractValue(String message) {
  int spaceIndex = message.lastIndexOf(' ');
  if (spaceIndex > 0) {
    String valStr = message.substring(spaceIndex + 1);
    return valStr.toFloat();
  }
  return NAN;
}
