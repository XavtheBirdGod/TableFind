<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title><?= $title ?? 'TableFind' ?></title>
</head>
<body class="bg-[#f4eee0] flex flex-col min-h-screen">

    <?php include __DIR__ . '/../partials/nav.php'; ?>

    <main class="flex-grow">
        <?= $content ?>
    </main>

    <?php include __DIR__ . '/../partials/footer.php'; ?>

</body>
</html>