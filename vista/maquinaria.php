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

    <h4 class="text-center text-secondary">REGISTRO DE EQUIPO</h4>

    <?php
    include('../modelo/conexion.php');
    $sql=$conexion->query("SELECT * FROM u484202321_sylsa_personal.Equipos");
    ?>
   
    <table class="table table-bordered table-hover col-12" id="example">
  <thead>
    <tr>
      <th scope="col">TIPO DE EQUIPO</th> 
      <th scope="col">PLACA</th>     
      <th scope="col">MARCA</th>
      <th scope="col">MODELO</th>
      <th scope="col">ASIGNADO</th>
    </tr>
  </thead>
  <tbody>

  <?php
    
    while ($datos=$sql->fetch_object()) { ?>
          <tr>
      <td><?=$datos->tipo_equipo ?></td>
      <td><?=$datos->placa ?></td>
      <td><?=$datos->marca ?></td>
      <td><?=$datos->modelo ?></td>
      <td><?=$datos->asignacion ?></td>
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