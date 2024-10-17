<!DOCTYPE html>
<html lang="zxx" class="js">
<?php include('head.php');?>
<body class="nk-body bg-lighter npc-general has-sidebar ">
    <div class="nk-app-root">
        <!-- main @s -->
        <div class="nk-main ">
        <?php include('sidebar.php');?>   
            <!-- wrap @s -->
            <div class="nk-wrap ">
            <?php include('header.php');?> 
            <?php include('content.php');?>   
            </div>
            <!-- wrap @e -->
        </div>
        <!-- main @e -->
    </div>
    <?php include('footer.php');?>   
    <!-- JavaScript -->
    <script src="./assets/js/bundle.js?ver=3.2.2"></script>
    <script src="./assets/js/scripts.js?ver=3.2.2"></script>
    <script src="./assets/js/charts/chart-lms.js?ver=3.2.2"></script>
</body>

</html>