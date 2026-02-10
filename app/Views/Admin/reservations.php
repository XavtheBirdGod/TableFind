<div class="container-fluid">
    <h1 class="mt-4"><?= $title ?></h1>
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-table me-1"></i> Alle Reserveringen
        </div>
        <div class="card-body">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Klant</th>
                        <th>Datum & Tijd</th>
                        <th>Gasten</th>
                        <th>Tafel</th>
                        <th>Status</th>
                        <th>Acties</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($reservations as $res): ?>
                    <tr>
                        <td><?= $res['id'] ?></td>
                        <td>
                            <strong><?= htmlspecialchars($res['customer_name']) ?></strong><br>
                            <small><?= htmlspecialchars($res['customer_phone']) ?></small>
                        </td>
                        <td><?= $res['reservation_date'] ?> <?= $res['reservation_time'] ?></td>
                        <td><?= $res['guest_count'] ?></td>
                        <td><?= $res['table_number'] ?? '<span class="text-muted">Niet toegewezen</span>' ?></td>
                        <td>
                            <?php 
                                $badgeClass = match($res['status']) {
                                    'confirmed' => 'bg-success',
                                    'cancelled' => 'bg-danger',
                                    'completed' => 'bg-info',
                                    default => 'bg-warning'
                                };
                            ?>
                            <span class="badge <?= $badgeClass ?>"><?= ucfirst($res['status']) ?></span>
                        </td>
                        <td>
                            <a href="/admin/reservations/edit/<?= $res['id'] ?>" class="btn btn-sm btn-primary">Bewerken</a>
                            <form action="/admin/reservations/delete/<?= $res['id'] ?>" method="POST" style="display:inline;" onsubmit="return confirm('Weet u het zeker?');">
                                <button type="submit" class="btn btn-sm btn-danger">Wis</button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php include 'includes/footer.php'; ?>