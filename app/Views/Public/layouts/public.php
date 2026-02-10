<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title><?= $title ?? 'TableFind' ?></title>
</head>
<body class="bg-[#F4EEE0] flex flex-col min-h-screen">

<?php include __DIR__ . '/../partials/nav.php'; ?>

<main class="flex-grow">
    <?= $content ?>
</main>

<?php include __DIR__ . '/../partials/footer.php'; ?>

</body>
</html>







<!--<!DOCTYPE html>-->
<!--<html lang="nl">-->
<!--<head>-->
<!--    <meta charset="UTF-8">-->
<!--    <meta name="viewport" content="width=device-width, initial-scale=1.0">-->
<!--    <script src="https://cdn.tailwindcss.com"></script>-->
<!--    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,700;1,400&family=Spectral:wght@400;700&display=swap" rel="stylesheet">-->
<!--    <title>TableFind | Authentiek Genieten</title>-->
<!--    <style>-->
<!--        body {-->
<!--            font-family: 'Spectral', serif;-->
<!--            background-color: #f4eee0; /* Aged paper color */-->
<!--            color: #2c2420; /* Deep sepia ink */-->
<!--        }-->
<!--        h1, h2, h3 {-->
<!--            font-family: 'Playfair Display', serif;-->
<!--        }-->
<!--        .vintage-border {-->
<!--            border: 3px double #2c2420;-->
<!--        }-->
<!--    </style>-->
<!--</head>-->
<!--<body class="min-h-screen flex flex-col">-->
<!---->
<?php //include __DIR__ . '/../partials/nav.php'; ?>
<!---->
<!--<main class="flex-grow container mx-auto px-6 py-12">-->
<!--    <header class="text-center py-16 border-b-2 border-sepia-900/20">-->
<!--        <h1 class="text-5xl md:text-7xl font-bold mb-6 italic">Heerlijk Eten, Perfecte Sfeer</h1>-->
<!--        <p class="text-xl md:text-2xl mb-10 max-w-2xl mx-auto opacity-90">-->
<!--            Ervaar de beste culinaire hoogstandjes. Reserveer vandaag nog uw tafel online.-->
<!--        </p>-->
<!--        <a href="views/reservations/book" class="inline-block bg-[#2c2420] text-[#f4eee0] px-10 py-4 text-lg font-bold tracking-widest uppercase hover:bg-opacity-90 transition shadow-xl vintage-border">-->
<!--            Direct Reserveren-->
<!--        </a>-->
<!--    </header>-->
<!---->
<!--    <section class="py-16">-->
<!--        <div class="grid grid-cols-1 md:grid-cols-3 gap-12 text-center">-->
<!--            <div class="p-6 border border-[#2c2420]/10">-->
<!--                <h3 class="text-2xl font-bold mb-4 underline decoration-1 underline-offset-8">Vers Eten</h3>-->
<!--                <p class="leading-relaxed">Wij werken alleen met de meest verse lokale ingrediënten, bereid volgens grootmoeders recept.</p>-->
<!--            </div>-->
<!--            <div class="p-6 border border-[#2c2420]/10">-->
<!--                <h3 class="text-2xl font-bold mb-4 underline decoration-1 underline-offset-8">Gezellige Sfeer</h3>-->
<!--                <p class="leading-relaxed">Een herberg waar de tijd even stil lijkt te staan. De perfecte plek voor uw gezelschap.</p>-->
<!--            </div>-->
<!--            <div class="p-6 border border-[#2c2420]/10">-->
<!--                <h3 class="text-2xl font-bold mb-4 underline decoration-1 underline-offset-8">Snel Reserveren</h3>-->
<!--                <p class="leading-relaxed">Geen gedoe met de post of telefoon; leg uw plekje vast in ons digitale register.</p>-->
<!--            </div>-->
<!--        </div>-->
<!--    </section>-->
<!--</main>-->
<!---->
<?php //include __DIR__ . '/../partials/footer.php'; ?>
<!---->
<!--</body>-->
<!--</html>-->
