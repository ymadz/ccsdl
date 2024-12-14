<nav class="navbar">
    <div class="container-fluid navigation ms-3 p-2 d-flex justify-content-between align-items-center">
        <a class="d-flex align-items-center" href="../user/home.php">
            <img src="../img/ccs_logo.png" alt="Logo" width="50" height="50" class="d-inline-block align-text-top">
        </a>
        <div class="nav-links d-flex justify-content-end align-items-center">
            <ul class="text-white">
                <li class="font-bold"><a href="../user/user.home.php" class="text-white text-decoration-none">Home</a></li>
                <li class="font-bold"><a href="../user/apply.php" class="text-white text-decoration-none">Apply for Dean's List</a></li>
                <li class="font-bold"><a href="../user/leaderboard.php" class="text-white text-decoration-none">Leaderboard</a></li>
                <li class="font-bold"><a href="../user/about.php" class="text-white text-decoration-none">About Us</a></li>
            </ul>
        </div>
        <div class="nav-actions d-flex align-items-center">
            <i class="lni lni-alarm text-white"></i>
            <!-- Added Profile Icon -->
            <div class="profile-icon mx-3">
                <i class="lni lni-user text-white"></i>
            </div>
            <a href="../account/logout.php" class="logout-button font-semibold d-flex align-items-center gap-1 p-2 text-white">
                <i class="lni lni-exit"></i>
                Logout
            </a>
        </div>
    </div>
</nav>

<style>
/* Add just this new style for the profile icon */
.profile-icon {
    width: 35px;
    height: 35px;
    background-color: rgba(255, 255, 255, 0.1);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
}

.profile-icon:hover {
    background-color: rgba(255, 255, 255, 0.2);
    transform: scale(1.05);
}

.profile-icon i {
    font-size: 1.2rem;
}
</style>