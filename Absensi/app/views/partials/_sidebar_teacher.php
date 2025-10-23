<aside class="sidebar">
    <div>
        <h2>Absensi Pro</h2>
        <ul>
            <?php $currentAction = $_GET['action'] ?? 'home'; ?>
            
            <li><a href="?action=home" title="Dashboard" class="<?= $currentAction == 'home' ? 'active' : '' ?>">
                <i class="bi bi-house-door-fill"></i><span class="menu-text"> Dashboard</span>
            </a></li>
        </ul>
    </div>
    <ul class="logout">
        <li><a href="?action=logout" title="Logout">
            <i class="bi bi-box-arrow-right"></i><span class="menu-text"> Logout</span>
        </a></li>
    </ul>
</aside>