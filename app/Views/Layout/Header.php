<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DDD Shop</title>
    <link rel="stylesheet" href="/assets/css/bootstrap.min.css">
    <script src="/assets/js/bootstrap.bundle.min.js"></script>
</head>
<body>
    <nav class="navbar bg-dark border-bottom border-body navbar-expand-lg bg-body-tertiary" data-bs-theme="dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="/">DDD SHop</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link" href="/products">Products</a>
                </li>
            </ul>
            <form class="d-flex">
                <button class="btn btn-outline-light" type="button" data-bs-toggle="modal" data-bs-target="#cartModal">Cart</button>
            </form>
            </div>
        </div>
    </nav>
    <div class="modal fade" id="cartModal" tabindex="-1" aria-labelledby="cartModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="cartModalLabel">Cart</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <ul id="cartNavModal"></ul>
                    <div id="totalPriceNavModal"></div>
                    <script>
                        fetch('/cart')
                        .then(r => r.json())
                        .then(data => {
                            if(data['products'].length > 0){
                                let cartNavModal = document.getElementById('cartNavModal');
                                cartNavModal.innerHTML = '';
                                data['products'].forEach(element => {
                                    let li = document.createElement('li');
                                    li.innerHTML = element.product.name+' - x'+element.quantity;
                                    cartNavModal.appendChild(li);
                                });
                                document.getElementById('totalPriceNavModal').innerHTML = 'Total price: '+data['total_price']+'$';
                            } else {
                                document.getElementById('cartNavModal').innerHTML = 'No product';
                                document.getElementById('totalPriceNavModal').innerHTML = '';
                            }
                        })
                        .catch(e => {
                            console.log(e);
                        })
                    </script>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <a href="/checkout/review" class="btn btn-primary">Checkout</a>
                </div>
            </div>
        </div>
    </div>
    <?php
    if(isset($_SESSION['success'])){
        ?>
        <div class="alert alert-success m-2" role="alert">
            <?php echo $_SESSION['success']; ?>
        </div>
        <?php
        unset($_SESSION['success']);
    }
    ?>
    <div class="container mt-3">
