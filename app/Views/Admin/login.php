<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Admin Login | TableFind</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #343a40; display: flex; align-items: center; justify-content: center; height: 100vh; }
        .login-card { width: 400px; padding: 2rem; border-radius: 10px; background: white; }
    </style>
</head>
<body>
    <div class="login-card shadow-lg">
        <h3 class="text-center mb-4">Admin Login</h3>
        <?php if (isset($_SESSION['flash'])): ?>
            <div class="alert alert-danger"><?= $_SESSION['flash']['message']; unset($_SESSION['flash']); ?></div>
        <?php endif; ?>
        <form action="/login/auth" method="POST">
            <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
            <div class="mb-3">
                <label class="form-label">E-mailadres</label>
                <input type="email" name="email" class="form-control" required placeholder="admin@example.com">
            </div>
            <div class="mb-3">
                <label class="form-label">Wachtwoord</label>
                <input type="password" name="password" class="form-control" required placeholder="******">
            </div>
            <button type="submit" class="btn btn-primary w-100">Inloggen</button>
        </form>
        <div class="mt-3 text-center">
            <small class="text-muted">Gebruik uw beheerdersgegevens.</small>
        </div>
    </div>
</body>
</html>