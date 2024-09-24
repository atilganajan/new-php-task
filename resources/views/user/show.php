<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail</title>
</head>
<body>
<header>
    <h1>Detail</h1>
</header>
<main>
    <p>User ID: <?= htmlspecialchars($user->id) ?></p>
    <p>User Full Name: <?= htmlspecialchars($user->full_name) ?></p>
    <?php if (isset($language)): ?>
        <p>Language: <?= htmlspecialchars($language->name) ?></p>
    <?php endif; ?>

</main>
</body>
</html>