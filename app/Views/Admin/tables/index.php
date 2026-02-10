<?php
/**
 * Overzichtspagina voor tafelbeheer.
 * Toont een lijst met alle tafels en hun status.
 */
?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Tafelbeheer</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="/admin/tables/create" class="btn btn-sm btn-outline-primary">
            <i class="fas fa-plus me-1"></i> Nieuwe Tafel Toevoegen
        </a>
    </div>
</div>

<div class="card shadow">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover align-middle">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Tafelnummer</th>
                        <th>Capaciteit</th>
                        <th>Status</th>
                        <th>Acties</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($tables)): ?>
                        <?php foreach ($tables as $table): ?>
                            <tr>
                                <td><?= htmlspecialchars((string)$table['id']) ?></td>
                                <td><strong><?= htmlspecialchars($table['table_number']) ?></strong></td>
                                <td><?= htmlspecialchars((string)$table['capacity']) ?> personen</td>
                                <td>
                                    <?php 
                                    $statusClass = match($table['status']) {
                                        'available'   => 'bg-success',
                                        'occupied'    => 'bg-danger',
                                        'out_of_order' => 'bg-secondary',
                                        default       => 'bg-info'
                                    };
                                    ?>
                                    <span class="badge <?= $statusClass ?>">
                                        <?= htmlspecialchars($table['status']) ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <a href="/admin/tables/edit/<?= $table['id'] ?>" class="btn btn-sm btn-outline-secondary">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="/admin/tables/delete/<?= $table['id'] ?>" method="POST" class="d-inline" onsubmit="return confirm('Weet u zeker dat u deze tafel wilt verwijderen?');">
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center">Geen tafels gevonden in de database.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>