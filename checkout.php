<?php
include 'includes\header.php';
echo ' | Checkout';
include 'includes\header2.php';
?>

<div class="container-fluid checkout-container ">
        <!-- Checkout Header -->
        <div class="title text-center">
            <h1 style="margin-top: 3%">Checkout</h1>
        </div>
        &nbsp;
        <div class="row">
            <!-- Left side: Delivery Address -->
            <div class="col-md-6">
                <div class="card">
                    <h4 class="mb-3">Delivery Address</h4>
                    <form>
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" class="form-control" id="name" placeholder="Enter your name" required>
                            &nbsp;
                        </div>
                        <div class="form-group">
                            <label for="address">Address</label>
                            <input type="text" class="form-control" id="address" placeholder="Enter your address" required>
                            &nbsp;
                        </div>
                        <div class="form-group">
                            <label for="postcode">Postcode</label>
                            <input type="text" class="form-control" id="postcode" placeholder="Enter your postcode" required>
                            &nbsp;
                        </div>
                        <div class="form-group">
                            <label for="city">State</label>
                            <input type="text" class="form-control" id="state" placeholder="Enter your state" required>
                            &nbsp;
                        </div>
                    </form>
                </div>
            </div>
            
        <!-- Right side: Order Details -->
            <div class="col-md-6">
                <div class="card">
                    <h4 class="mb-3">Order Details</h4>
<!--                    <div class="order-summary">
                        <p>Item: Awesome Product</p>
                        <p>Price: RM 700</p>
                        <p>Quantity: 2</p>
                        <p>Item: Hp 24 inch monitor</p>
                        <p>Price: RM 600</p>
                        <p>Quantity: 1</p>
                    </div>
                    <div class="order-summary">
                        <p>Total items: 3</p>
                        <p>Total price: <strong>RM 2000</strong></p>
                    </div>-->
                    <table class="cart-table">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Price</th>
                                <th>Quantity</th>
                                <th>Total</th>
                                <th>Remove</th>
                            </tr>
                        </thead>
                    </table>
                    <div class="cart-summary">
                        <h4>Summary</h4>
                        <p>Total Items: </p>
                        <p>Total Cost: </p>
                    </div>
                        
                </div>
            </div>
        </div>

                <!-- Buttons -->
                <div class="d-flex justify-content-center mt-3">
                    <button type="button" class="btn btn-dark mx-2" onclick="window.location.href='cart.php'">Back to Cart</button>
                    <button type="button" class="btn btn-dark mx-2" onclick="window.location.href='payment.php'">Proceed to Payment</button>
                </div>
&nbsp;
</div>
&nbsp;

<?php
include 'includes\footer.php';
?>