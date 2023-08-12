<?php
if($viewVars['status'] == 'ok'){
    ?>
        <div class="alert alert-success" role="alert">
            <h3>Payment success.</h3>
            <h4>Thank you for your order.</h4>
        </div>
    <?php
} else {
    ?>
        <div class="alert alert-danger" role="alert">
            <h3>Payment failed.</h3>
            <h4>You can Try again later.</h4>
        </div>
    <?php
}