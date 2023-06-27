<?php include 'log_in.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Interfaz</title>
    <!--boostrap-->
    <link href="css/bootstrap.min.css" rel="stylesheet">
     <!--custom CSS-->
    <link href="css/interfaz.css" rel="stylesheet">

</head>
<body>
   
    <nav class="navbar navbar-expand-lg  navbar-dark bg-dark">
        <div class="container-fluid">

          <a class="navbar-brand" href="#">Bienvenido <?php echo $username; ?></a>
          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>
          <div class="collapse navbar-collapse" id="navbarNav">
              <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                  <a class="nav-link" href="index.html">main</a>
                </li>
                
                 <li class="nav-item">
                  <a class="nav-link" href="Turbidez.html">turbidez data</a>
                </li>

                <li class="nav-item">
                  <a href="log_out.php" class="nav-link">log out</a>
                </li>
              
              </ul>
            
            </div>
        </div>
      </nav>

     <div class="container my-3">
        <div class="row">
            <div class="col-sm-12 col-md-4 col-lg-4 col-xl-4 py-3 bg_white text-aling">
                <h2 > Horario de consumo </h2>
              <div>
                  
              <p class="lh-1">
               Ingresa tu peticion: 
              </p>
            </div>
                
                <form action="Request.php" onsubmit="return validation()" method="POST">

                      <div class="mb-3">
                        <label for="quantity" class="form-label">Cantidad</label>
                        <input type="number" class="form-control" id="quantity" name="quantity"  step="any" min="0.5" max="9.0" autofocus>
                      </div>
                      <div class="mb-3">
                        <label for="desiredTime" class="form-label">Hora: </label>
                        <input type="time" class="form-control" id="desiredTime"  name="desiredTime" min="00:00"  >
                      </div>
                      <div class="mb-3">
                          <select class="form-select"  name="diaSemana" id="diaSemana" label="Default select example">
                            <option selected>Seleccione el dia</option>
                            <option value="Monday">Lunes</option>
                            <option value="Tuesday">Martes</option>
                            <option value="Wednesday">Miercoles</option>
                            <option value="Thursday">Jueves</option>
                            <option value="Friday">viernes</option>
                            <option value="Saturday">Sabado</option>
                            <option value="Sunday">Domingo</option>
                          </select>

                      </div>
                  

                      <div class="d-grid gap-2">
                        <button class="btn btn-success" type="submit"  name="submit">Guardar</button>
                      </div>
                </form>
            </div>
            <div class="col-sm-12 col-md-8 col-lg-8 col-xl-8 py-3 bg_white text-center">
                <h2> Nivel de Agua </h2>
                <div class=" container-fluid px-0 water-container  bg-dark" id = "water-container" >
                  <label class="form-label">9ls(max)</label>
                  <div class="water-level  bg-primary" id="water-level" ></div>
                  <script src="js/contenedoragua.js"></script>

                </div>
                
            </div>


        </div>
       
     </div>
 <!--bootstrap para las naimacipnes-->
     <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"> </script>
   
   <!--validacion de cmapos -->
     <script>
      function validation() {
        // Obtener los valores de los campos del formulario
        var quantity = document.getElementById("quantity").value;
        var desiredTime = document.getElementById("desiredTime").value;
        var diaSemana = document.getElementById("diaSemana").value;
      
        // Realizar las validaciones necesarias
        if (quantity === "" || desiredTime === "" || diaSemana === "Seleccione el dia") {
          alert("Por favor, complete todos los campos del formulario.");
          return false; // Evita que el formulario se envíe
        }
      
        // Si todas las validaciones pasan, puedes enviar el formulario
        return true;
      }
      </script>
   
</body>
</html>
