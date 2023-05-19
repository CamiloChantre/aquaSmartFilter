#include <WiFi.h>
#include <HTTPClient.h>
#include <Arduino.h>
#include <WiFiClient.h>     //lib adicional

const char* ssid = "Virus";
const char* password ="juan1234";

//String protocolo="http://";  
String protocolo="https://";                                //http o https
const char* host = "aqsmfi.000webhostapp.com";                   // Dirección IP local o remota, del Servidor Web
String recurso = "/aquasmartfilter/post-esp-data.php";      
String url = protocolo + host + recurso;                    //serverName = "https://aqsmfi.000webhostapp.com/aquasmartfilter/esp-data.php";
const int   port = 80;                                      // Puerto, HTTP es 80 por defecto, cambiar si es necesario.
String line;                                                // recibe la respuesta del servidor web


String apiKeyValue = "tPmAT5Ab3j7F9";                       // clave entre de la esp32 con el php esp-data-post

//variables propias sensor:

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
  Serial.print("Conectado a la red wifi con  IP Address: ");
  Serial.println(WiFi.localIP());


}

void loop() {

    String valorturbidez = leerAnalogico(turbidezPin);
   // float valorturbidez  =random(0,4.5);    

   if (valorturbidez.toFloat() < umbrale) {
    digitalWrite(ledPin, HIGH);
    Serial.println("El agua no es potable");
  } else {
    digitalWrite(ledPin, LOW);
  } 

  if (millis()%3000>10){
  
        if(WiFi.status()== WL_CONNECTED) {

        WiFiClient client;                   // Objeto que  permite el envio de datos en el protocolo https

        if (!client.connect(host, port)) {   // se intenta conectar con el servidor web y en caso de no conexion se imprime lo propio
        //Serial.println("Conexión falló...");
        client.stop();
        return;
        }

      // Envio de datos al servidor: metodo print
      // Preparar el valor de umbral actualizado desde la base de datos

        String  postData = "api_key=" + apiKeyValue + "&sensor=" + sensorName
                              + "&location=" + sensorLocation + "&turbidez=" + String(valorturbidez);

      

        client.print(String("POST ") + url + " HTTP/1.1\r\n" +
                "Host: " + host + "\r\n" + 
                "Accept: *" + "/" + "*\r\n" + 
                "Content-Length: " + postData.length() + "\r\n" +
                "Content-Type: application/x-www-form-urlencoded\r\n" +
                "\r\n" + postData);

                Serial.println("Valor de Turbidez: " +valorturbidez);
                


      // lectura de la respuesta del servidor web:

        while(client.available()){
        line = client.readStringUntil('\r');
        Serial.print(line);
        }

          // Actualizar el valor de umbral desde la base de datos
          if(actualizarUmbral()!=umbrale) {
          umbrale=actualizarUmbral();
          Serial.print("Nuevo valor de umbral: ");
          Serial.println(umbrale);
          }

        }
        else {
          Serial.println("WiFi Disconnected");
        }
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
  http.begin(client, "http://aqsmfi.000webhostapp.com/aquasmartfilter/get_umbral.php"); //modificar

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
