<aside class="sidebar">
    <div>
        <h2>Absensi Pro</h2>
        <ul>
            <?php
            $currentAction = $_GET['action'] ?? 'home';
            $studentActions = ['manage_students', 'add_student', 'edit_student', 'list_all_students'];
            $classActions = ['manage_classes', 'delete_class'];
            $teacherActions = ['manage_teachers', 'edit_teacher', 'delete_teacher'];
            ?>
            
            <li><a href="?action=home" title="Dashboard" class="<?= $currentAction == 'home' ? 'active' : '' ?>">
                <i class="bi bi-house-door-fill"></i><span class="menu-text"> Dashboard</span>
            </a></li>
            <li><a href="?action=manage_classes" title="Kelola Kelas" class="<?= in_array($currentAction, $classActions) ? 'active' : '' ?>">
                <i class="bi bi-collection-fill"></i><span class="menu-text"> Kelola Kelas</span>
            </a></li>
            <li><a href="?action=manage_students" title="Kelola Siswa" class="<?= in_array($currentAction, $studentActions) ? 'active' : '' ?>">
                <i class="bi bi-people-fill"></i><span class="menu-text"> Kelola Siswa</span>
            </a></li>
            <li><a href="?action=manage_teachers" title="Kelola Guru" class="<?= in_array($currentAction, $teacherActions) ? 'active' : '' ?>">
                <i class="bi bi-person-video3"></i><span class="menu-text"> Kelola Guru</span>
            </a></li>
        </ul>
    </div>
    <ul class="logout">
        <li><a href="?action=logout" title="Logout">
            <i class="bi bi-box-arrow-right"></i><span class="menu-text"> Logout</span>
        </a></li>
    </ul>
</aside>