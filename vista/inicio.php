<?php
   session_start();
   if (empty($_SESSION['nombre']) and empty($_SESSION['apellido'])) {
       header('location:login/login.php');
  }

?>

<!-- primero se carga el topbar -->
<?php require('./layout/topbar.php'); ?>
<!-- luego se carga el sidebar -->
<?php require('./layout/sidebar.php'); ?>

<!-- inicio del contenido principal -->
<div class="page-content">

    <h4 class="text-center text-secondary">REGISTRO DE EMPLEADOS</h4>

    <?php
    include "../modelo/conexion.php";
    $sql=$conexion->query("SELECT * FROM sylsa_personal.empleados")

    ?>

    <table class="table table-bordered table-hover col-12" id="example">
  <thead>
    <tr>
      <th scope="col">DUI</th> 
      <th scope="col">NOMBRE</th>     
      <th scope="col">CARGO</th>
      <th scope="col">ISSS</th>
      <th scope="col">AFP</th>
      <th scope="col">TELEFONO</th>
      <th scope="col">GRUPO</th>
      <th scope="col">FECHA DE INGRESO</th>
      <th scope="col">SEXO</th>
      <th scope="col">FECHA DE NACIMIENTO</th>
      <th scope="col">DIRECCION</th> 
      <th scope="col">CUENTA BANCARIA</th>
      <th scope="col">CONTACTO DE EMERGENCIA</th>
      <th scope="col">TELEFONO DE EMERGENCIA</th>
      <th scope="col">PARENTESCO</th>
    </tr>
  </thead>
  <tbody>
    <?php
    
    while ($datos=$sql->fetch_object()) { ?>
          <tr>
      <td><?=$datos->DUI ?></td>
      <td><?=$datos->NOMBRE_COMPLETO ?></td>
      <td><?=$datos->CARGO ?></td>
      <td><?=$datos->ISSS ?></td>
      <td><?=$datos->AFP ?></td>
      <td><?=$datos->TELEFONO ?></td>
      <td><?=$datos->GRUPO_TRABAJO ?></td>
      <td><?=$datos->FECHA_INGRESO ?></td>
      <td><?=$datos->SEXO ?></td>
      <td><?=$datos->FECHA_NACIMIENTO ?></td>
      <td><?=$datos->DIRECCION ?></td>
      <td><?=$datos->CUENTA_BANCARIA ?></td>
      <td><?=$datos->CONTACTO_EMERGENCIA ?></td>
      <td><?=$datos->TELEFONO_EMERGENCIA ?></td>
      <td><?=$datos->PARENTESCO ?></td>
    </tr>
    <?php
    }
    ?>
  </tbody>
</table>

</div>
</div>
<!-- fin del contenido principal -->


<!-- por ultimo se carga el footer -->
<?php require('./layout/footer.php'); ?>