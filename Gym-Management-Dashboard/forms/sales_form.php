<div class="card mt-4">
    <div class="card-header">
        <h5>Record New Sale</h5>
    </div>
    <div class="card-body">
        <form method="POST">
            <div class="form-group">
                <label for="member_id" class="form-label">Member (Optional)</label>
                <select class="form-control" id="member_id" name="member_id">
                    <option value="">No Member</option>
                    <?php
                    $members_result = $conn->query("SELECT member_id, CONCAT(first_name, ' ', last_name) AS full_name FROM members ORDER BY first_name");
                    while ($member = $members_result->fetch_assoc()) {
                        echo "<option value='" . $member['member_id'] . "'>" . htmlspecialchars($member['full_name']) . "</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label for="product_id" class="form-label">Product</label>
                <select class="form-control" id="product_id" name="product_id" required onchange="updatePrice()">
                    <option value="">Select Product</option>
                    <?php
                    $products_result = $conn->query("SELECT product_id, product_name, price, stock_quantity FROM products ORDER BY product_name");
                    while ($product = $products_result->fetch_assoc()) {
                        echo "<option value='" . $product['product_id'] . "' data-price='" . $product['price'] . "' data-stock='" . $product['stock_quantity'] . "'>" . 
                             htmlspecialchars($product['product_name']) . " ($" . number_format($product['price'], 2) . ") - " . $product['stock_quantity'] . " in stock</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label for="quantity" class="form-label">Quantity</label>
                <input type="number" class="form-control" id="quantity" name="quantity" min="1" value="1" onchange="updateTotalPrice()" onkeyup="updateTotalPrice()" required>
                <div class="text-danger" id="stock_warning" style="display:none;">Warning: Quantity exceeds available stock!</div>
            </div>
            <div class="form-group">
                <label for="price_per_unit" class="form-label">Price Per Unit</label>
                <input type="number" step="0.01" min="0.01" class="form-control" id="price_per_unit" name="price_per_unit" onchange="updateTotalPrice()" onkeyup="updateTotalPrice()" required>
            </div>
            <div class="form-group">
                <label class="form-label">Total Price</label>
                <div class="form-control-plaintext" id="total_price">$0.00</div>
            </div>
            <button type="submit" name="add_sale" class="btn btn-primary">Record Sale</button>
        </form>
    </div>
</div>

<script>
function updatePrice() {
    const productSelect = document.getElementById('product_id');
    const priceField = document.getElementById('price_per_unit');
    const quantityField = document.getElementById('quantity');
    const stockWarning = document.getElementById('stock_warning');
    
    if (productSelect.selectedIndex > 0) {
        const selectedOption = productSelect.options[productSelect.selectedIndex];
        const price = selectedOption.getAttribute('data-price');
        const stock = parseInt(selectedOption.getAttribute('data-stock'));
        
        priceField.value = price;
        
        // Check if quantity exceeds stock
        if (parseInt(quantityField.value) > stock) {
            stockWarning.style.display = 'block';
        } else {
            stockWarning.style.display = 'none';
        }
    } else {
        priceField.value = '';
        stockWarning.style.display = 'none';
    }
    
    updateTotalPrice();
}

function updateTotalPrice() {
    const quantity = document.getElementById('quantity').value || 0;
    const price = document.getElementById('price_per_unit').value || 0;
    const totalPrice = (quantity * price).toFixed(2);
    
    document.getElementById('total_price').textContent = '$' + totalPrice;
    
    // Check stock limitations when quantity changes
    const productSelect = document.getElementById('product_id');
    if (productSelect.selectedIndex > 0) {
        const selectedOption = productSelect.options[productSelect.selectedIndex];
        const stock = parseInt(selectedOption.getAttribute('data-stock'));
        
        if (parseInt(quantity) > stock) {
            document.getElementById('stock_warning').style.display = 'block';
        } else {
            document.getElementById('stock_warning').style.display = 'none';
        }
    }
}

// Initialize price when page loads
document.addEventListener('DOMContentLoaded', function() {
    updatePrice();
});
</script>