// Obtén el elemento del contenedor y el nivel de agua
var waterContainer = document.getElementById('water-container');
var waterLevel = document.getElementById('water-level');

// Realiza una solicitud AJAX al servidor para obtener el valor de la base de datos
fetch('getLevelAgua.php')
  .then(response => response.json())
  .then(data => {
    // Obtén el valor de la base de datos del objeto de respuesta
    var databaseValue = data.valor; 

    // Calcula la altura del nivel de agua en píxeles
    var waterHeight = (waterContainer.clientHeight * databaseValue) / 9;

    // Establece la altura del nivel de agua en el contenedor
    waterLevel.style.height = waterHeight + 'px';

    // Actualiza el contenido del elemento waterLevel con el valor actual
    waterLevel.innerText = databaseValue +' lts';
  })
  .catch(error => {
    console.error('Error al obtener el valor de la base de datos:', error);
  });