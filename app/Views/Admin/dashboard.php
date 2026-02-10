<?php
/**
 * Dashboard overzichtspagina voor beheerders.
 * Dit bestand bevat enkel de specifieke content voor het dashboard.
 * De lay-out (header/sidebar/footer) wordt automatisch afgehandeld door de View class.
 */
?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Admin Dashboard</h1>
</div>

<div class="row">
    <div class="col-md-4 mb-4">
        <div class="card bg-primary text-white shadow">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-white-50 small">Totaal Reserveringen</div>
                        <div class="text-lg fw-bold"><?= htmlspecialchars((string)($stats['total_reservations'] ?? 0)) ?></div>
                    </div>
                    <i class="fas fa-calendar fa-2x text-gray-300"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4 mb-4">
        <div class="card bg-success text-white shadow">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-white-50 small">Beschikbare Tafels</div>
                        <div class="text-lg fw-bold"><?= htmlspecialchars((string)($stats['available_tables'] ?? 0)) ?></div>
                    </div>
                    <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4 mb-4">
        <div class="card bg-warning text-white shadow">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-white-50 small">Vandaag Reserveringen</div>
                        <div class="text-lg fw-bold"><?= htmlspecialchars((string)($stats['today_reservations'] ?? 0)) ?></div>
                    </div>
                    <i class="fas fa-clock fa-2x text-gray-300"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Systeemoverzicht</h6>
    </div>
    <div class="card-body">
        <p>Welkom bij het beheersysteem. Gebruik de sidebar om reserveringen te beheren en de restaurantstatus bij te werken.</p>
    </div>
</div>