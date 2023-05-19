#include <WiFi.h>
#include <HTTPClient.h>
#include <Arduino.h>

const char* ssid = "FAMILIA-CORONEL";
const char* password ="11221987";
const char* serverName = "http://192.168.0.16/aquasmartfilter/post-esp-data.php";

String apiKeyValue = "tPmAT5Ab3j7F9";
String sensorName = "S-Turbidez";
String sensorLocation = "UNICAUCA";

float umbrale;  
const int turbidezPin = 36;
const int ledPin = 13;


void setup() {
  Serial.begin(115200);

  pinMode(ledPin, OUTPUT);
  
  WiFi.begin(ssid, password);
  Serial.println("Conectando");
  while(WiFi.status() != WL_CONNECTED) { 
        delay(500);
        Serial.print(".");
  }
  Serial.println("");
  Serial.print("Conectado a red WIFI con  IP Address: ");
  Serial.println(WiFi.localIP());

}

void loop() {

  //String valorturbidez = leerAnalogico(turbidezPin);
  float valorturbidez  =random(2,4.5);                  //Para prueba se toman datos aleatorios, de lo contrario con el sensor descomentar linea anterior

    // Funcionalidad del LED: 

    if (valorturbidez.toFloat() < umbrale) {
      digitalWrite(ledPin, HIGH);
      Serial.println("El agua no es potable");
    } else {
      digitalWrite(ledPin, LOW);
    }

    // Funcionalidad Envio de datos al servidorlocal: 

    if(WiFi.status()== WL_CONNECTED) {
        WiFiClient client;
        HTTPClient http;
        http.begin(client, serverName);
        http.addHeader("Content-Type", "application/x-www-form-urlencoded");
        delay(500);
        
        // Preparar la cadena de datos a enviar al servidor:

        String httpRequestData = "api_key=" + apiKeyValue + "&sensor=" + sensorName + "&location=" + sensorLocation + "&turbidez=" + 
        String(valorturbidez);

        //Envia los datos y recibe el codigo de respuesta del servidor:

        int httpCode =http.POST(httpRequestData);
        Serial.println(httpRequestData);                   //Muestra la cadena de datos enviados
        Serial.println(httpCode);                          //Muestra el codigo respuesta
  
        
        // Funcionalidad Actualizar el valor de umbral desde la base de datos: 

        if(actualizarUmbral()!=umbrale) {
          umbrale=actualizarUmbral();
          Serial.print("Nuevo valor de umbral: ");
          Serial.println(umbrale);
          }
        
        // Liberar recursos
        http.end();
    }
    else {
      Serial.println("WiFi Desconectado");
    }
}

String leerAnalogico(int pinAnalogico) {
  int valorAnalogico = analogRead(pinAnalogico);
  float voltaje = valorAnalogico * (5.0 / 4095.0);
  String valorturbidez = String(voltaje, 2);
  return valorturbidez;
}

float actualizarUmbral() {
  WiFiClient client;
  HTTPClient http;
  http.begin(client, "https://192.168.0.16/aquasmartfilter/get_umbral.php");

  int httpResponseCode = http.GET();
  if (httpResponseCode == 200) {
    String umbralStr = http.getString();
    umbrale = umbralStr.toFloat();
    Serial.println("Umbral actual: " + String(umbrale));
    return umbrale;

  } else {
      Serial.print("Error al obtener el valor de umbral Código de respuesta HTTP: ");
      Serial.println(httpResponseCode);
      return false;
  }
}