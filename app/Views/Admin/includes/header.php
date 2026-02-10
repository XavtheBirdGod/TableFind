<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'Admin Dashboard') ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { background-color: #f8f9fa; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        .sidebar { min-height: 100vh; background: #212529; color: white; box-shadow: inset -1px 0 0 rgba(0, 0, 0, .1); }
        .sidebar a { color: #adb5bd; text-decoration: none; padding: 12px 20px; display: block; transition: all 0.3s; }
        .sidebar a:hover { background: #343a40; color: #fff; }
        .sidebar a.active { color: #fff; background: #0d6efd; }
        .main-content { padding: 25px; }
    </style>
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <?php 
        $sidebarPath = __DIR__ . DIRECTORY_SEPARATOR . 'sidebar.php';
        if (file_exists($sidebarPath)) {
            // We gebruiken include in plaats van require om data scope te behouden
            include $sidebarPath;
        }
        ?>
        
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 main-content">
            <?php 
            $flashPath = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'partials' . DIRECTORY_SEPARATOR . 'flash.php';
            if (file_exists($flashPath)) {
                include $flashPath;
            }
            ?>