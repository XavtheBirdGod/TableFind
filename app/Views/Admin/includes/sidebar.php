<nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block sidebar collapse bg-dark">
    <div class="position-sticky pt-3">
        <h5 class="px-3 mb-3 text-uppercase text-secondary fs-6">Beheerpaneel</h5>
        <ul class="nav flex-column">
            <?php 
            // Huidige URI ophalen voor active state
            $currentUri = $_SERVER['REQUEST_URI'] ?? '/';
            ?>

            <li class="nav-item">
                <a class="nav-link <?= strpos($currentUri, '/admin/dashboard') !== false ? 'active' : 'text-white-50' ?>" href="/admin/dashboard">
                    <i class="fas fa-tachometer-alt me-2"></i> Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= strpos($currentUri, '/admin/reservations') !== false ? 'active' : 'text-white-50' ?>" href="/admin/reservations">
                    <i class="fas fa-calendar-check me-2"></i> Reserveringen
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= strpos($currentUri, '/admin/tables') !== false ? 'active' : 'text-white-50' ?>" href="/admin/tables">
                    <i class="fas fa-chair me-2"></i> Tafels
                </a>
            </li>

            <hr class="text-secondary mx-3">

            <h6 class="px-3 mb-2 text-uppercase text-muted fs-6">Account</h6>
            <li class="nav-item">
                <a class="nav-link <?= strpos($currentUri, '/admin/profile') !== false ? 'active' : 'text-white-50' ?>" href="/admin/profile">
                    <i class="fas fa-user-cog me-2"></i> Profiel Instellingen
                </a>
            </li>
            
            <li class="nav-item mt-3">
                <a class="nav-link text-danger" href="/logout">
                    <i class="fas fa-sign-out-alt me-2"></i> Uitloggen
                </a>
            </li>
        </ul>
    </div>
</nav>

<style>
/* Extra styling voor active state indien niet in main CSS */
.sidebar .nav-link.active {
    color: #fff !important;
    background-color: #0d6efd; /* Bootstrap Primary */
    border-radius: 4px;
}
.sidebar .nav-link:hover:not(.active) {
    color: #fff !important;
    background-color: rgba(255,255,255,0.1);
}
</style>