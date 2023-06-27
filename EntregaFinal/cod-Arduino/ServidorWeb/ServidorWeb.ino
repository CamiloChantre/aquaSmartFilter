#include <WiFi.h>
#include <HTTPClient.h>
#include <Arduino.h>
#include <WiFiClient.h>

const char* ssid = "Virus";
const char* password = "juan1234";

String protocolo = "https://";
const char* host = "smartfilter.000webhostapp.com";
String recurso = "/aqsmfi/post-esp-data.php";
String url = protocolo + host + recurso;
const int port = 80;
String line;

String apiKeyValue = "tPmAT5Ab3j7F9";
String sensorName1 = "turbidez";
String sensorName2 = "proximidad";
String sensorName3 = "turbidezDos";
String sensorLocation = "UNICAUCA";
float umbrale, quantity, delayServo;

const int valvulaPin1 = 2; // Pin 1 de control de la valvula en el L298N
const int valvulaPin2 = 4; // Pin 2 de control de la valvula en el L298N
const int valvulaEnablePin = 5; // Pin de habilitación de la valvula en el L298N

const int TRIGGER_PIN = 18;
const int ECHO_PIN = 19;
const int MAX_DISTANCE = 200;
const int MIN_DISTANCE = 0;
const float CONTAINER_HEIGHT = 24.5;
const float BASE_DIAMETER_BOTTOM = 19.5;
const float BASE_DIAMETER_TOP = 24.5;
const float MAX_VOLUME = 9.0;
const float LITER_PER_CUBIC_CM = 0.001;
const int turbidezPin = 32;
const int turbidezPinDos = 33;
const int ledPin = 13;
const  int ledPin2 = 1;

void setup() {

  Serial.begin(115200);
  pinMode(valvulaPin1, OUTPUT);
  pinMode(valvulaPin2, OUTPUT);
  pinMode(valvulaEnablePin, OUTPUT);
  
  pinMode(TRIGGER_PIN, OUTPUT);
  pinMode(ECHO_PIN, INPUT);
  pinMode(ledPin, OUTPUT);

  WiFi.begin(ssid, password);
  Serial.println("Conectando");
  while (WiFi.status() != WL_CONNECTED) {
    delay(1000);
    Serial.print(".");
  }
  Serial.println("");
  Serial.print("Conectado a la red wifi con IP Address: ");
  Serial.println(WiFi.localIP());
}

void loop() {
  
  String valorturbidez = leerAnalogico(turbidezPin);
  String valorturbidezDos = leerAnalogicoDos(turbidezPinDos);

  if (valorturbidez.toFloat() < umbrale) {
    digitalWrite(ledPin, HIGH);
    Serial.println("¡Cuidado! El agua no es potable");
  } else {
    digitalWrite(ledPin, LOW);
  }

  unsigned int distance = getWaterDistance();

  float waterLevel = map(distance, CONTAINER_HEIGHT, MIN_DISTANCE, 0, 100);
  waterLevel = constrain(waterLevel, 0, 100);

  float containerVolume = calculateCylinderVolume(BASE_DIAMETER_BOTTOM, BASE_DIAMETER_TOP, CONTAINER_HEIGHT);
  float waterVolume = (waterLevel / 100.0) * containerVolume;
  waterVolume = constrain(waterVolume, 0, MAX_VOLUME);

  if (WiFi.status() == WL_CONNECTED) {
    WiFiClient client;

    if (!client.connect(host, port)) {
      client.stop();
      return;
    }

    String postData1 = "api_key=" + apiKeyValue + "&sensorName=" + sensorName1 +
                       "&location=" + sensorLocation + "&readValue=" + String(valorturbidez);

    String postData2 = "api_key=" + apiKeyValue + "&sensorName=" + sensorName2 +
                       "&location=" + sensorLocation + "&readValue=" + String(waterVolume);

    String postData3 = "api_key=" + apiKeyValue + "&sensorName=" + sensorName3 +
                       "&location=" + sensorLocation + "&readValue=" + String(valorturbidezDos);                   

    client.print(String("POST ") + url + " HTTP/1.1\r\n" +
                 "Host: " + host + "\r\n" +
                 "Accept: */*\r\n" +
                 "Content-Length: " + postData1.length() + "\r\n" +
                 "Content-Type: application/x-www-form-urlencoded\r\n" +
                 "\r\n" + postData1);

    client.print(String("POST ") + url + " HTTP/1.1\r\n" +
                 "Host: " + host + "\r\n" +
                 "Accept: */*\r\n" +
                 "Content-Length: " + postData2.length() + "\r\n" +
                 "Content-Type: application/x-www-form-urlencoded\r\n" +
                 "\r\n" + postData2);

    client.print(String("POST ") + url + " HTTP/1.1\r\n" +
                 "Host: " + host + "\r\n" +
                 "Accept: */*\r\n" +
                 "Content-Length: " + postData3.length() + "\r\n" +
                 "Content-Type: application/x-www-form-urlencoded\r\n" +
                 "\r\n" + postData3);

    Serial.println("Valor de Turbidez primer sensor en NTU: " + valorturbidez);
    Serial.println("Valor de Turbidez segundo sensor en NTU : " + valorturbidezDos);

    Serial.println("Volumen de agua en litros: " + String(waterVolume));

    while (client.available()) {
      line = client.readStringUntil('\r');
      Serial.print(line);
    }

    if (actualizarUmbral() != umbrale) {
      umbrale = actualizarUmbral();
      Serial.print("Nuevo valor de umbral: ");
      Serial.println(umbrale);
    }

    if (millis()%120000>10) {
      quantity = actualizarQuantity();
      Serial.print("Nuevo valor de cantidad: ");
      Serial.println(quantity);
    }
    
  }

  else {
    Serial.println("WiFi Disconnected");
  }

  if (actualizarDelayServo() != delayServo) {
      delayServo = actualizarDelayServo();
      Serial.print("Nuevo valor de delay Servo: ");
      Serial.println(delayServo);
    }
}


unsigned int getWaterDistance() {
  digitalWrite(TRIGGER_PIN, LOW);
  delayMicroseconds(5);
  digitalWrite(TRIGGER_PIN, HIGH);
  delayMicroseconds(10);
  digitalWrite(TRIGGER_PIN, LOW);
  unsigned int duration = pulseIn(ECHO_PIN, HIGH, MAX_DISTANCE * 58);
  return duration / 58;
}

String leerAnalogico(int pinAnalogico) {
  int valorAnalogico = analogRead(pinAnalogico);
  float voltaje = valorAnalogico * (5.0 / 4095.0);
  String valorturbidez = String(voltaje, 2);
  return valorturbidez;
}

String leerAnalogicoDos(int pinAnalogicoDos) {
  int valorAnalogicoDos = analogRead(pinAnalogicoDos);
  float voltajeDos = valorAnalogicoDos * (5.0 / 4095.0);
  String valorturbidezDos = String(voltajeDos, 2);
  return valorturbidezDos;
}

float calculateCylinderVolume(float baseDiameterBottom, float baseDiameterTop, float height) {
  float radiusBottom = baseDiameterBottom / 2.0;
  float radiusTop = baseDiameterTop / 2.0;
  float volume = (1.0 / 3.0) * PI * height * (radiusBottom * radiusBottom + radiusTop * radiusTop + radiusBottom * radiusTop);
  return volume * LITER_PER_CUBIC_CM;
}

float actualizarUmbral() {
  WiFiClient client;
  HTTPClient http;
  http.begin(client, "http://smartfilter.000webhostapp.com/aqsmfi/get_umbral.php");

  int httpResponseCode = http.GET();
  if (httpResponseCode == 200) {
    String umbralStr = http.getString();
    umbrale = umbralStr.toFloat();
    Serial.println("Umbral actual: " + String(umbrale));
    return umbrale;
  } else {
    Serial.print("Error al obtener el valor de umbral. Código de respuesta HTTP: ");
    Serial.println(httpResponseCode);
    return 0;
  }
}

float actualizarQuantity() {

  WiFiClient client;
  HTTPClient http;
  http.begin(client, "http://smartfilter.000webhostapp.com/aqsmfi/getRequest.php");

  int httpResponseCode = http.GET();
  if (httpResponseCode == 200) {
    String quantityStr = http.getString();
    quantity = quantityStr.toFloat();
    Serial.println("Cantidad actual de agua en litros solicitada por usuario: " + String(quantity));
    
    float tiempoMaxLlenado = 20000; //Calibrar este valor
    float capacidadRecipiente = 9; //En litros
    Serial.println("Suministrando agua");

    digitalWrite(valvulaPin1, HIGH);
    digitalWrite(valvulaPin2, HIGH);

    float waterVolume = 0;

    do {
      unsigned int distance = getWaterDistance();

      float waterLevel = map(distance, CONTAINER_HEIGHT, MIN_DISTANCE, 0, 100);
      waterLevel = constrain(waterLevel, 0, 100);

      float containerVolume = calculateCylinderVolume(BASE_DIAMETER_BOTTOM, BASE_DIAMETER_TOP, CONTAINER_HEIGHT);
      waterVolume = (waterLevel / 100.0) * containerVolume;
      waterVolume = constrain(waterVolume, 0, MAX_VOLUME);

      Serial.println("Cantidad de agua : " + String(waterVolume));

      delay(300);

    } while ( waterVolume < quantity );

    Serial.println("Dejando de suministrar agua");

    digitalWrite(valvulaPin1, LOW);
    digitalWrite(valvulaPin2, LOW);

    return quantity;
  
  } else {
    Serial.print("Error al obtener el valor de cantidad. Código de respuesta HTTP: ");
    Serial.println(httpResponseCode);
    return 0;
  }
}

float actualizarDelayServo() {
  WiFiClient client;
  HTTPClient http;
  http.begin(client, "http://smartfilter.000webhostapp.com/aqsmfi/getDelayServo.php");

  int httpResponseCode = http.GET();
  if (httpResponseCode == 200) {
    String delayServoStr = http.getString();
    delayServo = delayServoStr.toFloat();
    Serial.println("Delay servo actual: " + String(delayServo));
    return delayServo;
  } else {
    Serial.print("Error al obtener delay servo. Código de respuesta HTTP: ");
    Serial.println(httpResponseCode);
    return 0;
  }
}
