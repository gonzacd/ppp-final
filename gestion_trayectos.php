<?php
session_start();

// Verificar si el usuario es administrador. Si no, redirigir a la página de acceso.
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    header("location: admin.php");
    exit;
}

// Incluir la configuración de la base de datos
include 'config.php';

$mensaje = "";
$modo_edicion = false;
$trayecto_a_editar = ['id' => '', 'titulo' => '', 'descripcion' => '', 'imagen' => ''];

// --- Lógica CRUD ---

// 1. ELIMINAR TRAYECTO
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $id = mysqli_real_escape_string($link, $_GET['id']);
    $sql = "DELETE FROM trayectos WHERE id = $id";
    if (mysqli_query($link, $sql)) {
        $mensaje = "<div class='alert alert-success'>Trayecto eliminado correctamente.</div>";
    } else {
        $mensaje = "<div class='alert alert-danger'>ERROR al eliminar el trayecto: " . mysqli_error($link) . "</div>";
    }
}

// 2. CARGAR DATOS PARA EDICIÓN
if (isset($_GET['action']) && $_GET['action'] == 'edit' && isset($_GET['id'])) {
    $id = mysqli_real_escape_string($link, $_GET['id']);
    $sql = "SELECT id, titulo, descripcion, imagen FROM trayectos WHERE id = $id";
    $result = mysqli_query($link, $sql);
    
    if (mysqli_num_rows($result) == 1) {
        $trayecto_a_editar = mysqli_fetch_assoc($result);
        $modo_edicion = true;
    } else {
        $mensaje = "<div class='alert alert-warning'>Trayecto no encontrado.</div>";
    }
}


// 3. PROCESAR FORMULARIO (AGREGAR O ACTUALIZAR)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Escapar los datos del formulario para prevenir inyección SQL
    $id = mysqli_real_escape_string($link, $_POST['id']);
    $titulo = mysqli_real_escape_string($link, $_POST['titulo']);
    $descripcion = mysqli_real_escape_string($link, $_POST['descripcion']);
    $imagen = mysqli_real_escape_string($link, $_POST['imagen']); // Asume URL o ruta local
    
    if (!empty($id)) {
        // Actualizar (UPDATE)
        $sql = "UPDATE trayectos SET titulo='$titulo', descripcion='$descripcion', imagen='$imagen' WHERE id=$id";
        if (mysqli_query($link, $sql)) {
            $mensaje = "<div class='alert alert-success'>Trayecto actualizado correctamente.</div>";
        } else {
            $mensaje = "<div class='alert alert-danger'>ERROR al actualizar el trayecto: " . mysqli_error($link) . "</div>";
        }
    } else {
        // Insertar (CREATE)
        $sql = "INSERT INTO trayectos (titulo, descripcion, imagen) VALUES ('$titulo', '$descripcion', '$imagen')";
        if (mysqli_query($link, $sql)) {
            $mensaje = "<div class='alert alert-success'>Nuevo trayecto agregado correctamente.</div>";
        } else {
            $mensaje = "<div class='alert alert-danger'>ERROR al agregar el trayecto: " . mysqli_error($link) . "</div>";
        }
    }
    
    // Resetear el modo edición después de la operación POST
    $modo_edicion = false;
    $trayecto_a_editar = ['id' => '', 'titulo' => '', 'descripcion' => '', 'imagen' => ''];
}

// 4. LEER TODOS LOS TRAYECTOS (READ)
$trayectos = obtenerTrayectos($link); // Usa la función de config.php

// Cerrar sesión
if (isset($_GET['action']) && $_GET['action'] == 'logout') {
    $_SESSION = array();
    session_destroy();
    header("location: index.php");
    exit;
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Trayectos - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="./crr.css">
    <style>
        .admin-container { padding-top: 50px; padding-bottom: 50px; }
        .table img { max-width: 100px; height: auto; }
    </style>
</head>
<body>
    <div class="admin-container container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="text-primary">Gestión de Trayectos Formativos</h1>
            <a href="gestion_trayectos.php?action=logout" class="btn btn-danger">Cerrar Sesión</a>
        </div>

        <?php echo $mensaje; // Mostrar mensajes de éxito o error ?>

        <!-- Formulario de Creación/Edición -->
        <div class="card mb-5 shadow-sm">
            <div class="card-header bg-dark text-white">
                <?php echo $modo_edicion ? 'Editar Trayecto' : 'Crear Nuevo Trayecto'; ?>
            </div>
            <div class="card-body">
                <form action="gestion_trayectos.php" method="post">
                    <!-- Campo oculto para el ID (usado en la edición) -->
                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($trayecto_a_editar['id']); ?>">
                    
                    <div class="mb-3">
                        <label for="titulo" class="form-label">Título</label>
                        <input type="text" class="form-control" id="titulo" name="titulo" value="<?php echo htmlspecialchars($trayecto_a_editar['titulo']); ?>" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="descripcion" class="form-label">Descripción</label>
                        <textarea class="form-control" id="descripcion" name="descripcion" rows="3" required><?php echo htmlspecialchars($trayecto_a_editar['descripcion']); ?></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label for="imagen" class="form-label">Ruta de Imagen (Ej: img/foto.jpg)</label>
                        <input type="text" class="form-control" id="imagen" name="imagen" value="<?php echo htmlspecialchars($trayecto_a_editar['imagen']); ?>">
                    </div>
                    
                    <button type="submit" class="btn btn-primary">
                        <?php echo $modo_edicion ? 'Guardar Cambios' : 'Agregar Trayecto'; ?>
                    </button>
                    <?php if ($modo_edicion): ?>
                        <a href="gestion_trayectos.php" class="btn btn-secondary">Cancelar Edición</a>
                    <?php endif; ?>
                </form>
            </div>
        </div>

        <!-- Listado de Trayectos -->
        <h2 class="mb-3">Trayectos Existentes</h2>
        <?php if (count($trayectos) > 0): ?>
            <div class="table-responsive">
                <table class="table table-striped table-bordered align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Imagen</th>
                            <th>Título</th>
                            <th>Descripción (Snippet)</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($trayectos as $trayecto): ?>
                        <tr>
                            <td><?php echo $trayecto['id']; ?></td>
                            <td><img src="<?php echo htmlspecialchars($trayecto['imagen']); ?>" alt="Imagen de <?php echo $trayecto['titulo']; ?>" class="img-thumbnail"></td>
                            <td><?php echo htmlspecialchars($trayecto['titulo']); ?></td>
                            <td><?php echo htmlspecialchars(substr($trayecto['descripcion'], 0, 100)) . '...'; ?></td>
                            <td class="text-nowrap">
                                <a href="gestion_trayectos.php?action=edit&id=<?php echo $trayecto['id']; ?>" class="btn btn-warning btn-sm mb-1">Editar</a>
                                <a href="gestion_trayectos.php?action=delete&id=<?php echo $trayecto['id']; ?>" onclick="return confirm('¿Está seguro de que desea eliminar este trayecto?');" class="btn btn-danger btn-sm">Eliminar</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <p class="alert alert-info">No hay trayectos agregados en la base de datos.</p>
        <?php endif; ?>
    </div>
</body>
</html>

<?php
// Se cierra la conexión aquí también, después de todo el uso
mysqli_close($link);
?>