<?php
if (!empty($_GET['id'])) {
    require("../../DATOS/conexion/conexion.php");
    $id = $_GET['id'];
    $query_delete = mysqli_query($conexion, "DELETE FROM usuario WHERE idusuario = $id");
    mysqli_close($conexion);
    header("location: lista_usuarios.php");
}
?>