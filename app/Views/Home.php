<?php include_once base_path('app/Views/partials/head.php'); ?>
<?php include_once base_path('app/Views/partials/navbar.php'); ?>
<main role="main" class="container">
    <?php if (has_flash()) { ?>
    <div class="alert alert-primary" role="alert">
        <?php echo print_flash(); ?>
    </div>
    <?php } ?>


    <div class="card">
        <div class="card-header">
            Send
        </div>
        <div class="card-body">
            <h5 class="card-title">Send a new SMS</h5>
            <p class="card-text">Send a new SMS usig MessageBird API.</p>
            <a href="<?php echo base_url('sms'); ?>" class="btn btn-primary">Send now</a>
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            List of SMS's
        </div>
        <div class="card-body">
            <h5 class="card-title">List of SMS's</h5>
            <p class="card-text">Get the list os all sent SMS's.</p>
            <a href="<?php echo base_url('sms/list'); ?>" class="btn btn-primary">Check SMS's</a>
        </div>
    </div>
</main>
<?php include_once base_path('app/Views/partials/footer.php'); ?>
