<?php include 'includes/header.php'; ?>
<div class="container-fluid">
    <h1 class="mt-4">Reservering #<?= $res['id'] ?> Aanpassen</h1>
    
    <div class="card mb-4 shadow-sm">
        <div class="card-body">
            <form action="/admin/reservations/update/<?= $res['id'] ?>" method="POST">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Klantnaam</label>
                        <input type="text" name="customer_name" class="form-control" value="<?= htmlspecialchars($res['customer_name']) ?>" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">E-mailadres</label>
                        <input type="email" name="customer_email" class="form-control" value="<?= htmlspecialchars($res['customer_email'] ?? '') ?>">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Telefoon</label>
                        <input type="text" name="customer_phone" class="form-control" value="<?= htmlspecialchars($res['customer_phone']) ?>" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Aantal Gasten</label>
                        <input type="number" name="guest_count" class="form-control" value="<?= $res['guest_count'] ?>" min="1" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Tafel Toewijzen</label>
                        <select name="table_id" class="form-select">
                            <option value="">-- Geen Tafel --</option>
                            <?php foreach ($tables as $table): ?>
                                <option value="<?= $table['id'] ?>" <?= $res['table_id'] == $table['id'] ? 'selected' : '' ?>>
                                    <?= $table['table_number'] ?> (Capaciteit: <?= $table['capacity'] ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Datum</label>
                        <input type="date" name="reservation_date" class="form-control" value="<?= $res['reservation_date'] ?>" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Tijd</label>
                        <input type="time" name="reservation_time" class="form-control" value="<?= $res['reservation_time'] ?>" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="pending" <?= $res['status'] === 'pending' ? 'selected' : '' ?>>In afwachting</option>
                            <option value="confirmed" <?= $res['status'] === 'confirmed' ? 'selected' : '' ?>>Bevestigd</option>
                            <option value="cancelled" <?= $res['status'] === 'cancelled' ? 'selected' : '' ?>>Geannuleerd</option>
                            <option value="completed" <?= $res['status'] === 'completed' ? 'selected' : '' ?>>Voltooid</option>
                        </select>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Opmerkingen</label>
                    <textarea name="notes" class="form-control" rows="3"><?= htmlspecialchars($res['notes'] ?? '') ?></textarea>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-success px-4">Wijzigingen Opslaan</button>
                    <a href="/admin/reservations" class="btn btn-secondary px-4">Annuleren</a>
                </div>
            </form>
        </div>
    </div>
</div>
<?php include 'includes/footer.php'; ?>