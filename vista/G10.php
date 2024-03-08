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

    <h4 class="text-center text-secondary">LISTA DE EMPLEADOS</h4>

    <?php
    include('../modelo/conexion.php');
    $sql = $conexion->query("SELECT * FROM u484202321_sylsa_personal.empleados WHERE GRUPO_TRABAJO = 'G10 PAV'");
    ?>

<form method="post">
        <label for="mes">Selecciona un mes:</label>
        <select name="mes" id="mes">
            <?php
              $nombresMeses = [
                1 => 'Enero',
                2 => 'Febrero',
                3 => 'Marzo',
                4 => 'Abril',
                5 => 'Mayo',
                6 => 'Junio',
                7 => 'Julio',
                8 => 'Agosto',
                9 => 'Septiembre',
                10 => 'Octubre',
                11 => 'Noviembre',
                12 => 'Diciembre',
            ];

            // Generar opciones para los meses
            for ($i = 1; $i <= 12; $i++) {
                echo "<option value=\"$i\">{$nombresMeses[$i]}</option>";
            }
            ?>
        </select>

        <input type="submit" value="Mostrar Asistencia">
    </form>
    <div style="margin-bottom: 20px;"></div>
    <?php
    // Verificar si se ha enviado el formulario
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Obtener el mes seleccionado
        $mesSeleccionado = $_POST["mes"];

        echo '<table class="table table-bordered table-hover col-12">';
        echo '<thead>';
        echo '<tr>';
        echo '<th scope="col">NOMBRE</th>';
        echo '<th scope="col">CARGO</th>';

        // Generar columnas para cada día del mes
        $numDias = cal_days_in_month(CAL_GREGORIAN, $mesSeleccionado, date('Y'));
        for ($i = 1; $i <= $numDias; $i++) {
            echo '<th scope="col">' . $i . '</th>';
        }

        echo '</tr>';
        echo '</thead>';
        echo '<tbody>';

        while ($datos = $sql->fetch_object()) {
            echo '<tr>';
            echo '<td>' . $datos->NOMBRE_COMPLETO . '</td>';
            echo '<td>' . $datos->CARGO . '</td>';

            // Generar celdas con listas desplegables para cada día del mes
            for ($i = 1; $i <= $numDias; $i++) {
                echo '<td><select class="asistencia" data-empleado="' . $datos->NOMBRE_COMPLETO . '" data-fecha="' . date('Y-m-d', strtotime("$i-$mesSeleccionado-" . date('Y'))) . '">';
                echo '<option value="0">?</option>'; // Selección
                echo '<option value="A">A</option>'; // Asistencia
                echo '<option value="I">I</option>'; // Incapacidad
                echo '<option value="F">F</option>'; // Falta
                echo '<option value="P">P</option>'; // Permiso
                echo '</select></td>';
            }

            echo '</tr>';
        }

        echo '</tbody>';
        echo '</table>';
    }
    ?>

</div>
</div>
<!-- fin del contenido principal -->


<!-- por último se carga el footer -->
<?php require('./layout/footer.php'); ?>
