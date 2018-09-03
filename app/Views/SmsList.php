<?php include_once base_path('app/Views/partials/head.php'); ?>
<?php include_once base_path('app/Views/partials/navbar.php'); ?>
<main role="main" class="container">
    <table class="table">
        <thead class="thead-dark">
            <tr>
                <th scope="col">#</th>
                <th scope="col">Originator</th>
                <th scope="col">Body</th>
                <th scope="col">Recipients</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($list->items as $i => $sms) { ?>
            <tr>
                <th scope="row">
                    <a href="<?php echo base_url('sms/' . ($sms->getId())) ;?>">#</a>
                </th>
                <td><?php echo $sms->originator ;?></td>
                <td><?php echo ($sms->type == 'binary') ? hex_to_str($sms->body) : $sms->body ;?></td>
                <td>
                    <?php foreach ($sms->recipients->items as $r) { ?>
                        <small><?php echo $r->recipient . ' ' . $r->status ;?></small></br>
                    <?php } ?>
                </td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</main>
<?php include_once base_path('app/Views/partials/footer.php'); ?>