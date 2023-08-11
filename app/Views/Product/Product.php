<h2><?php echo $viewVars['product']->name ?></h2>
<h5 class="mt-2">Price: <?php echo $viewVars['product']->price ?></h5>
<form action="/cart/add" method="post">
    <input type="hidden" name="product" value="<?php echo $viewVars['product']->id ?>" required>
    <div class="row mt-3">
        <div class="col-auto"><input type="number" name="quantity" value="1" min="1" class="form-control" required></div>
        <div class="col-auto"><input class="btn btn-primary" type="submit" value="Addd to cart"></div>
    </div>
</form>