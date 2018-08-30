<?php include_once base_path('app/Views/partials/head.php'); ?>
<?php include_once base_path('app/Views/partials/navbar.php'); ?>
<main role="main" class="container">
    <form class="text-center border border-light p-5" action="/messages" method="POST">

        <p class="h4 mb-4">Send a message!</p>

        <!-- Recipient -->
        <input type="text" name="recipient" class="form-control mb-4" placeholder="Recipient">

        <!-- Originator -->
        <input type="originator" name="originator" class="form-control mb-4" placeholder="Originator">

        <!-- Message -->
        <div class="form-group">
            <textarea class="form-control rounded-0" name="message" rows="3" placeholder="Message"></textarea>
        </div>

        <!-- Send button -->
        <button class="btn btn-info btn-block" type="submit">Send</button>
    </form>
</main>
<?php include_once base_path('Views/partials/footer.php'); ?>
