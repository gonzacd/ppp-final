<?php
session_start();

$keyword_secreto = "criolla"; // La palabra clave solicitada
$mensaje_error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $keyword_ingresado = isset($_POST['keyword']) ? trim($_POST['keyword']) : '';
    
    if (strtolower($keyword_ingresado) === $keyword_secreto) {
        // Palabra clave correcta, establecer sesión y redirigir
        $_SESSION['is_admin'] = true;
        header("location: gestion_trayectos.php");
        exit;
    } else {
        $mensaje_error = "Palabra clave incorrecta. Por favor, inténtelo de nuevo.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acceso de Administración</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="./crr.css">
</head>
<body style="background-color: #f8f9fa;">
    <div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh;">
        <div class="card p-4 shadow-lg" style="width: 100%; max-width: 400px;">
            <h2 class="card-title text-center mb-4">Acceso Admin</h2>
            <?php if (!empty($mensaje_error)): ?>
                <div class="alert alert-danger" role="alert"><?php echo $mensaje_error; ?></div>
            <?php endif; ?>
            <form action="admin.php" method="post">
                <div class="mb-3">
                    <label for="keyword" class="form-label">Palabra clave:</label>
                    <input type="password" class="form-control" id="keyword" name="keyword" required>
                </div>
                <button type="submit" class="btn btn-dark w-100">Ingresar</button>
            </form>
            <hr>
             <a href="index.php" class="btn btn-outline-secondary w-100 mt-2">Volver al Inicio</a>
        </div>
    </div>
</body>
</html>