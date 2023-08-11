<div class="row g-2 justify-content-center">
<?php
foreach($viewVars['products'] as $product){
    ?>
    <div class="card col-3 mx-2">
        <div class="card-header">
            <?php echo $product->price ?>$
        </div>
        <div class="card-body">
            <h5 class="card-title"><?php echo $product->name ?></h5>
            <a href="/product/<?php echo $product->id ?>" class="btn btn-primary">See product</a>
        </div>
    </div>
    <?php
}
?>
</div>