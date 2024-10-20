<!DOCTYPE html>
<html lang="zxx" class="js">
<?php include 'head.php' ?>

<body class="nk-body ui-rounder has-sidebar ">
    <div class="nk-app-root">
        <!-- main @s -->
        <div class="nk-main ">
            <!-- sidebar @s -->
            <?php include 'sidebar.php' ?>
            <!-- sidebar @e -->
            <!-- wrap @s -->
            <div class="nk-wrap ">
                <!-- main header @s -->
                <?php include 'header.php' ?>
                <!-- main header @e -->
                <!-- content @s -->
                <?php if (isset($_GET['users'])): ?>
                    <?php include('./resource/users.php') ?>
                <?php elseif (isset($_GET['materi'])): ?>
                    <?php include('./resource/materi.php') ?>
                <?php elseif (isset($_GET['payment'])): ?>
                    <?php include('./resource/payment.php') ?>
                <?php elseif (isset($_GET['soal'])): ?>
                    <?php include('./resource/soal.php') ?>
                <?php elseif (isset($_GET['laporan'])): ?>
                    <?php include('./resource/laporan.php') ?>
                <?php else: ?>
                    <?php include('main.php') ?>
                <?php endif; ?>
                <!-- content @e -->
                <!-- footer @s -->
                <?php include 'footer.php' ?>
                <!-- footer @e -->
            </div>
            <!-- wrap @e -->
        </div>
        <!-- main @e -->
    </div>
    <!-- app-root @e -->
    <!-- select region modal -->

    <!-- JavaScript -->
    <script src="./assets/js/bundle.js?ver=3.2.2"></script>
    <script src="./assets/js/scripts.js?ver=3.2.2"></script>
    <script src="./assets/js/charts/gd-campaign.js?ver=3.2.2"></script>
</body>

</html>