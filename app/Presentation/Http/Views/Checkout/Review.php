<div class="card" style="width: 18rem;">
  <ul class="list-group list-group-flush" id="products">
  </ul>
</div>
<h5 class="mt-2" id="totalPrice"></h5>
<form action="/checkout" method="post">
    <textarea name="address" cols="30" rows="10" class="form-control" placeholder="address" required></textarea>
    <button type="submit" class="btn btn-primary mt-3">Pay</button>
</form>
<script>
    fetch('/cart')
    .then(r => r.json())
    .then(data => {
        let products = document.getElementById('products');
        products.innerHTML = '';
        data['products'].forEach(element => {
            let li = document.createElement('li');
            li.innerHTML = `<li class="list-group-item">${element.product.name} - Fee: ${element.product.price}$ - x${element.quantity}</li>`;
            products.appendChild(li);
        });
        document.getElementById('totalPrice').innerHTML = 'Total price: '+data['total_price']+'$';
    })
    .catch(e => {
        console.log(e);
    })
</script>