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

    PAGINA DE MAQUINARIA

</div>
</div>
<!-- fin del contenido principal -->


<!-- por último se carga el footer -->
<?php require('./layout/footer.php'); ?>