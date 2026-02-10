<?php
/**
 * Pagina voor het aanmaken van een nieuwe tafel.
 * Bevat een formulier om tafelnummer, capaciteit en status in te voeren.
 */
?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Nieuwe Tafel Toevoegen</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="/admin/tables" class="btn btn-sm btn-outline-secondary">
            <i class="fas fa-arrow-left me-1"></i> Terug naar overzicht
        </a>
    </div>
</div>

<div class="card shadow">
    <div class="card-body">
        <form action="/admin/tables/store" method="POST">
            <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="table_number" class="form-label">Tafelnummer</label>
                    <input type="text" class="form-control" id="table_number" name="table_number" placeholder="bijv. T-01" required>
                    <div class="form-text">Voer een uniek nummer of naam in voor de tafel.</div>
                </div>
                
                <div class="col-md-6 mb-3">
                    <label for="capacity" class="form-label">Capaciteit (Aantal personen)</label>
                    <input type="number" class="form-control" id="capacity" name="capacity" min="1" value="2" required>
                </div>
            </div>

            <div class="mb-3">
                <label for="status" class="form-label">Status</label>
                <select class="form-select" id="status" name="status" required>
                    <option value="available" selected>Beschikbaar (Available)</option>
                    <option value="occupied">Bezet (Occupied)</option>
                    <option value="out_of_order">Buiten gebruik (Out of order)</option>
                </select>
            </div>

            <hr>
            
            <div class="d-flex justify-content-end">
                <button type="reset" class="btn btn-light me-2">Wissen</button>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-1"></i> Tafel Opslaan
                </button>
            </div>
        </form>
    </div>
</div>