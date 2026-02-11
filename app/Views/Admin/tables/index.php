<div class="container-fluid pt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Tafelbeheer</h2>
        <a href="/admin/tables/create" class="btn btn-primary">
            <i class="fas fa-plus"></i> Nieuwe Tafel
        </a>
    </div>

    <?php if (isset($_SESSION['flash'])): ?>
        <div class="alert alert-<?= $_SESSION['flash']['type']; ?> alert-dismissible fade show">
            <?= $_SESSION['flash']['message']; ?>
            <?php unset($_SESSION['flash']); ?>
        </div>
    <?php endif; ?>

    <div class="card shadow">
        <div class="card-body">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Tafel Nummer</th>
                        <th>Capaciteit</th>
                        <th>Status</th>
                        <th>Acties</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($tables as $table): ?>
                        <tr>
                            <td><strong><?= $table['table_number']; ?></strong></td>
                            <td><?= $table['capacity']; ?> personen</td>
                            <td>
                                <span class="badge bg-<?= $table['status'] === 'available' ? 'success' : 'danger'; ?>">
                                    <?= $table['status']; ?>
                                </span>
                            </td>
                            <td>
                                <a href="/admin/tables/edit/<?= $table['id']; ?>" class="btn btn-sm btn-info text-white">
                                    <i class="fas fa-edit"></i>
                                </a>
                                
                                <form action="/admin/tables/delete/<?= $table['id']; ?>" method="POST" class="d-inline" onsubmit="return confirm('Weet u het zeker?');">
                                    <input type="hidden" name="csrf_token" value="<?= $csrf_token; ?>">
                                    <button type="submit" class="btn btn-sm btn-danger">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>