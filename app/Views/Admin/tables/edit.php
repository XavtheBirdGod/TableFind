<?php
/**
 * Pagina voor het bewerken van een bestaande tafel.
 * Bevat een formulier om tafelnummer, capaciteit en status aan te passen.
 */
?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Tafel Bewerken: <?= \App\Core\Security::escape($table['table_number'] ?? '') ?></h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="/admin/tables" class="btn btn-sm btn-outline-secondary">
            <i class="fas fa-arrow-left me-1"></i> Terug naar overzicht
        </a>
    </div>
</div>

<div class="card shadow">
    <div class="card-body">
        <form action="/admin/tables/update/<?= \App\Core\Security::escape((string)$table['id']) ?>" method="POST">
            <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="table_number" class="form-label">Tafelnummer</label>
                    <input type="text" class="form-control" id="table_number" name="table_number" 
                           value="<?= \App\Core\Security::escape($table['table_number'] ?? '') ?>" placeholder="bijv. T-01" required>
                    <div class="form-text">Voer een uniek nummer of naam in voor de tafel.</div>
                </div>
                
                <div class="col-md-6 mb-3">
                    <label for="capacity" class="form-label">Capaciteit (Aantal personen)</label>
                    <input type="number" class="form-control" id="capacity" name="capacity" min="1" 
                           value="<?= \App\Core\Security::escape((string)($table['capacity'] ?? 2)) ?>" required>
                </div>
            </div>

            <div class="mb-3">
                <label for="status" class="form-label">Status</label>
                <select class="form-select" id="status" name="status" required>
                    <option value="available" <?= ($table['status'] ?? '') === 'available' ? 'selected' : '' ?>>Beschikbaar (Available)</option>
                    <option value="occupied" <?= ($table['status'] ?? '') === 'occupied' ? 'selected' : '' ?>>Bezet (Occupied)</option>
                    <option value="out_of_order" <?= ($table['status'] ?? '') === 'out_of_order' ? 'selected' : '' ?>>Buiten gebruik (Out of order)</option>
                </select>
            </div>

            <hr>
            
            <div class="d-flex justify-content-end">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-1"></i> Wijzigingen Opslaan
                </button>
            </div>
        </form>
    </div>
</div>
