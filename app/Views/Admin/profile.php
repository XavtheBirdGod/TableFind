<?php
/**
 * View voor de profielpagina.
 * Maakt gebruik van Bootstrap 5 voor formulieren.
 * Header, Sidebar en Footer worden automatisch geladen door de View helper.
 */
?>

<div class="pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2"><i class="fas fa-user-cog me-2"></i>Mijn Profiel</h1>
</div>

<div class="card shadow-sm border-0">
    <div class="card-header bg-white py-3">
        <h5 class="card-title mb-0">Persoonlijke Gegevens</h5>
    </div>
    <div class="card-body">
        <form action="/admin/profile" method="POST">
            <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
            <div class="mb-3">
                <label for="name" class="form-label">Volledige Naam</label>
                <input type="text" class="form-control" id="name" name="name" 
                       value="<?= \App\Core\Security::escape($user['name'] ?? '') ?>" required>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">E-mailadres</label>
                <input type="email" class="form-control" id="email" name="email" 
                       value="<?= \App\Core\Security::escape($user['email'] ?? '') ?>" required>
            </div>

            <hr class="my-4">

            <h5 class="mb-3">Beveiliging</h5>
            <div class="mb-3">
                <label for="password" class="form-label">Nieuw Wachtwoord</label>
                <input type="password" class="form-control" id="password" name="password" 
                       placeholder="Laat leeg om het huidige wachtwoord te behouden">
                <div class="form-text">Vul dit alleen in als u uw wachtwoord wilt wijzigen.</div>
            </div>

            <div class="d-flex justify-content-end">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-2"></i>Wijzigingen Opslaan
                </button>
            </div>
        </form>
    </div>
</div>