<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,700;0,900;1,400&family=EB+Garamond:ital,wght@0,400;0,700;1,400&display=swap" rel="stylesheet">
    <title><?= $title ?? 'TableFind | Authentiek Genieten' ?></title>
    <style>
        body {
            background-color: #f4eee0; 
            font-family: 'EB Garamond', serif;
            background-image: url('https://www.transparenttextures.com/patterns/paper-fibers.png');
            color: #2c2420;
        }
        h1, h2, h3, .font-serif {
            font-family: 'Playfair Display', serif;
        }
        /* تأثير الورق القديم للحواف */
        .vintage-container {
            border: 1px solid rgba(44, 36, 32, 0.2);
            box-shadow: inset 0 0 100px rgba(44, 36, 32, 0.05);
        }
    </style>
</head>
<body class="min-h-screen flex flex-col">

    <?php 
    $nav = __DIR__ . '/../partials/nav.php';
    if(file_exists($nav)) include $nav; 
    ?>

    <main class="flex-grow vintage-container">
        <?= $content ?? 'Geen content geladen.' ?>
    </main>

    <?php 
    $footer = __DIR__ . '/../partials/footer.php';
    if(file_exists($footer)) include $footer; 
    ?>

</body>
</html>