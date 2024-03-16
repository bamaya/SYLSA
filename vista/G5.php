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
    $sql = $conexion->query("SELECT * FROM u484202321_sylsa_personal.empleados WHERE GRUPO_TRABAJO = 'G5 PAV'");
    ?>

    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
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
            // Obtener el mes seleccionado (si está disponible)
            $mesSeleccionado = isset($_POST["mes"]) ? $_POST["mes"] : date('n'); // Mes actual si no se ha seleccionado uno

            // Generar opciones para los meses
            foreach ($nombresMeses as $numeroMes => $nombreMes) {
                $selected = ($numeroMes == $mesSeleccionado) ? 'selected' : '';
                echo "<option value=\"$numeroMes\" $selected>$nombreMes</option>";
            }
            ?>
        </select>

        <input type="submit" value="Mostrar Asistencia">
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
                    $fecha = date('Y-m-d', strtotime("$i-$mesSeleccionado-" . date('Y')));

                    // Obtener registros de la base de datos para la fecha actual
                    $sqlAsistencia = $conexion->prepare("SELECT asistio FROM Asistencias WHERE nombre_completo = ? AND fecha = ?");
                    $sqlAsistencia->bind_param("ss", $datos->NOMBRE_COMPLETO, $fecha);
                    $sqlAsistencia->execute();
                    $resultAsistencia = $sqlAsistencia->get_result();

                    echo '<td><select class="asistencia" name="asistencia[' . $datos->NOMBRE_COMPLETO . '][' . $fecha . ']">';
                    echo '<option value="0">?</option>'; // Selección

                    // Opciones para cada tipo de asistencia
                    $opcionesAsistencia = ['A', 'I', 'F', 'P'];

                    // Si hay un registro en la base de datos para esta fecha, selecciona la opción correspondiente
                    if ($resultAsistencia->num_rows > 0) {
                        $registroAsistencia = $resultAsistencia->fetch_assoc();
                        $asistenciaSeleccionada = $registroAsistencia['asistio'];
                        foreach ($opcionesAsistencia as $opcion) {
                            $selected = ($asistenciaSeleccionada == $opcion) ? 'selected' : '';
                            echo '<option value="' . $opcion . '" ' . $selected . '>' . $opcion . '</option>';
                        }
                    } else {
                        // Si no hay registro, muestra las opciones predeterminadas
                        foreach ($opcionesAsistencia as $opcion) {
                            echo '<option value="' . $opcion . '">' . $opcion . '</option>';
                        }
                    }

                    echo '</select></td>';
                }

                echo '</tr>';
            }

            echo '</tbody>';
            echo '</table>';

            echo '<button type="submit" name="guardar_asistencia">Guardar Asistencia</button>';
        }
        ?>
    </form>

    <?php
    // Verificar si se ha enviado el formulario para guardar la asistencia
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["guardar_asistencia"])) {
        // Obtener el mes seleccionado
        $mesSeleccionado = $_POST["mes"];

        // Recorrer los datos de asistencia enviados
        foreach ($_POST['asistencia'] as $nombre_completo => $asistencias) {
            foreach ($asistencias as $fecha => $asistencia) {
                // Verificar si el valor de asistencia es diferente de cero antes de insertarlo en la base de datos
                if ($asistencia != "0") {
                    // Verificar si ya existe un registro para el empleado y la fecha
                    $sqlExist = $conexion->prepare("SELECT * FROM Asistencias WHERE nombre_completo = ? AND fecha = ?");
                    $sqlExist->bind_param("ss", $nombre_completo, $fecha);
                    $sqlExist->execute();
                    $resultado = $sqlExist->get_result();
                    if ($resultado->num_rows > 0) {
                        // Si ya existe un registro, actualizarlo
                        $sqlUpdate = $conexion->prepare("UPDATE Asistencias SET asistio = ? WHERE nombre_completo = ? AND fecha = ?");
                        $sqlUpdate->bind_param("sss", $asistencia, $nombre_completo, $fecha);
                        $sqlUpdate->execute();
                    } else {
                        // Si no existe un registro, insertarlo
                        $sqlInsert = $conexion->prepare("INSERT INTO Asistencias (nombre_completo, fecha, asistio) VALUES (?, ?, ?)");
                        $sqlInsert->bind_param("sss", $nombre_completo, $fecha, $asistencia);
                        $sqlInsert->execute();
                    }
                }
            }
        }
        echo "<script>window.location.href = window.location.href;</script>";
        exit;
    }
    ?>

</div>
</div>
<!-- fin del contenido principal -->


<!-- por último se carga el footer -->
<?php require('./layout/footer.php'); ?>
