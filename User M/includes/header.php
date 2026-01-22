<?php
require_once __DIR__ . '/Session.php';
require_once __DIR__ . '/functions.php';
require_once __DIR__ . '/../config/config.php';

Session::start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?php echo isset($pageTitle) ? $pageTitle . ' - ' . SITE_NAME : SITE_NAME; ?>
    </title>
    <meta name="description" content="Book hotels in Bangladesh and worldwide with bKash, Nagad, and card payments">
    <script>
        window.SITE_URL = '<?php echo SITE_URL; ?>';
    </script>
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>/assets/css/style.css">
</head>

<body>
    <header class="header">
        <div class="container">
            <nav class="navbar">
                <a href="<?php echo SITE_URL; ?>/index.php" class="logo">
                    🏨
                    <?php echo SITE_NAME; ?>
                </a>

                <ul class="nav-menu" id="navMenu">
                    <?php
                    // Only show Home link if not on homepage
                    $currentPage = basename($_SERVER['PHP_SELF']);
                    if ($currentPage !== 'index.php'):
                        ?>
                        <li><a href="<?php echo SITE_URL; ?>/index.php" class="nav-link">Home</a></li>
                    <?php endif; ?>
                    <li><a href="<?php echo SITE_URL; ?>/hotels.php" class="nav-link">Hotels</a></li>

                    <?php if (Session::isLoggedIn()): ?>
                        <li><a href="<?php echo SITE_URL; ?>/profile.php" class="nav-link">My Profile</a></li>

                        <?php if (Session::isAdmin()): ?>
                            <li><a href="<?php echo SITE_URL; ?>/admin/dashboard.php" class="nav-link">Admin</a></li>
                        <?php endif; ?>

                        <li>
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <span style="color: var(--gray-600); font-size: 14px;">
                                    👤
                                    <?php echo Session::getUserName(); ?>
                                </span>
                                <a href="<?php echo SITE_URL; ?>/auth/logout.php" class="btn btn-outline btn-sm">Logout</a>
                            </div>
                        </li>
                    <?php else: ?>
                        <li><a href="#" onclick="showAuthModal('login'); return false;"
                                class="btn btn-outline btn-sm">Login</a></li>
                        <li><a href="#" onclick="showAuthModal('register'); return false;"
                                class="btn btn-primary btn-sm">Sign Up</a></li>
                    <?php endif; ?>
                </ul>

                <button class="mobile-menu-toggle" id="mobileToggle" onclick="toggleMobileMenu()">
                    <span></span>
                    <span></span>
                    <span></span>
                </button>
            </nav>
        </div>
    </header>

    <!-- Flash Messages -->
    <?php if (Session::hasFlash('success')): ?>
        <div class="container" style="margin-top: 20px;">
            <div class="alert alert-success">
                ✓
                <?php echo Session::getFlash('success'); ?>
            </div>
        </div>
    <?php endif; ?>

    <?php if (Session::hasFlash('error')): ?>
        <div class="container" style="margin-top: 20px;">
            <div class="alert alert-error">
                ✗
                <?php echo Session::getFlash('error'); ?>
            </div>
        </div>
    <?php endif; ?>

    <?php if (Session::hasFlash('warning')): ?>
        <div class="container" style="margin-top: 20px;">
            <div class="alert alert-warning">
                ⚠
                <?php echo Session::getFlash('warning'); ?>
            </div>
        </div>
    <?php endif; ?>

    <?php if (Session::hasFlash('info')): ?>
        <div class="container" style="margin-top: 20px;">
            <div class="alert alert-info">
                ℹ
                <?php echo Session::getFlash('info'); ?>
            </div>
        </div>
    <?php endif; ?>