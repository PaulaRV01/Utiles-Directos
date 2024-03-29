<?php include_once "../../UTILS/header.php"; ?>
<div class="content">
	<!-- Page Heading -->
		<div class="row">
			<div class="col-lg-12">
				<h5>Productos</h5>
				<a href="registro_producto.php" class="btn btn-primary">Nuevo</a>
				<div class="table-responsive">
					<table class="table table-striped table-bordered" id="table">
						<thead class="thead-dark">
							<tr>
								<th>ID</th>
								<th>PRODUCTO</th>
								<th>PRECIO</th>
								<th>STOCK</th>
								<?php if ($_SESSION['rol'] == 1) { ?>
									<th>ACCIONES</th>
								<?php } ?>
							</tr>
						</thead>
						<tbody>
							<?php
							include "../../DATOS/conexion/conexion.php";

							$query = mysqli_query($conexion, "SELECT * FROM producto");
							$result = mysqli_num_rows($query);
							if ($result > 0) {
								while ($data = mysqli_fetch_assoc($query)) { ?>
									<tr>
										<td><?php echo $data['codproducto']; ?></td>
										<td><?php echo $data['descripcion']; ?></td>
										<td><?php echo $data['precio']; ?></td>
										<td><?php echo $data['existencia']; ?></td>
										<?php if ($_SESSION['rol'] == 1) { ?>
											<td>
												<a href="agregar_producto.php?id=<?php echo $data['codproducto']; ?>" class="btn btn-primary"><i class='fas fa-audio-description'></i></a>

												<a href="editar_producto.php?id=<?php echo $data['codproducto']; ?>" class="btn btn-success"><i class='fas fa-edit'></i></a>

												<form action="eliminar_producto.php?id=<?php echo $data['codproducto']; ?>" method="post" class="confirmar d-inline">
													<button class="btn btn-danger" type="submit"><i class='fas fa-trash-alt'></i> </button>
												</form>
											</td>
										<?php } ?>
									</tr>
							<?php }
							} ?>
						</tbody>

					</table>
				</div>

			</div>
		</div>
</div>
<?php include_once "../../UTILS/footer.php"; ?>