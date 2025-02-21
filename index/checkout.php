    <?php
    session_start();

    // Database connection
    $servidor = "localhost";
    $usuario = "root";
    $senha = "";
    $banco = "gestao_utilizadores";

    $con = new mysqli($servidor, $usuario, $senha, $banco);

    if ($con->connect_error) {
        die(json_encode(['success' => false, 'error' => 'Connection failed']));
    }   

    // Verify user is logged in
    if (!isset($_SESSION['id_utilizadores'])) {
        header('Location: login.php');
        exit();
    }

    $usuario_id = $_SESSION['id_utilizadores'];

    // Get cart items
    $cart_items = [];
    $sql = "SELECT c.quantidade, p.id, p.nome, p.preco, p.imagem 
            FROM carrinho c 
            JOIN produtos p ON c.produto_id = p.id 
            WHERE c.usuario_id = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("i", $usuario_id);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $row['total'] = $row['preco'] * $row['quantidade'];
        $cart_items[] = $row;
    }

    $stmt->close();

    // Calculate totals
$subtotal = array_sum(array_column($cart_items, 'total'));

// Calculate shipping based on subtotal
if ($subtotal >= 100) {
    $shipping = 0; // Frete grátis
} else {
    // O frete aumenta proporcionalmente ao subtotal, até um máximo de 9.99
    $shipping = min(9.99, $subtotal * 0.1); // Exemplo: 10% do subtotal
}

$total = $subtotal + $shipping;

    // Handle order submission
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['place_order'])) {
        // Create order
        $stmt = $con->prepare("INSERT INTO pedidos (usuario_id, total, status) VALUES (?, ?, 'pending')");
        $stmt->bind_param("id", $usuario_id, $total);
        
        if ($stmt->execute()) {
            $order_id = $stmt->insert_id;
            
            // Add order items
            $stmt = $con->prepare("INSERT INTO pedido_items (pedido_id, produto_id, quantidade, preco) VALUES (?, ?, ?, ?)");
            
            foreach ($cart_items as $item) {
                $stmt->bind_param("iiid", $order_id, $item['id'], $item['quantidade'], $item['preco']);
                $stmt->execute();
            }
            
            // Clear cart
            $stmt = $con->prepare("DELETE FROM carrinho WHERE usuario_id = ?");
            $stmt->bind_param("i", $usuario_id);
            $stmt->execute();
            
            // Redirect to confirmation
            header('Location: order_confirmation.php?order_id=' . $order_id);
            exit();
        }
    }
    ?>
    <!DOCTYPE html>
    <html lang="pt-BR">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Checkout</title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css" />
        <style>
            :root {
                --primary-color: #3b82f6;
                --primary-hover: #2563eb;
                --gray-50: #f9fafb;
                --gray-100: #f3f4f6;
                --gray-200: #e5e7eb;
                --gray-300: #d1d5db;
                --gray-600: #4b5563;
                --gray-700: #374151;
                --gray-800: #1f2937;
                --success-color: #22c55e;
            }

            * {
                margin: 0;
                padding: 0;
                box-sizing: border-box;
            }

            body {
                font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
                line-height: 1.5;
                background-color: var(--gray-50);
            }

            .header {
                background-color: white;
                box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
                padding: 1rem 0;
            }

            .container {
                max-width: 1280px;
                margin: 0 auto;
                padding: 0 1rem;
            }

            .header-content {
                display: flex;
                justify-content: space-between;
                align-items: center;
            }

            .page-title {
                font-size: 1.5rem;
                font-weight: bold;
                color: var(--gray-800);
            }

            .secure-badge {
                display: flex;
                align-items: center;
                gap: 0.5rem;
                color: var(--gray-600);
            }

            .secure-badge i {
                color: var(--success-color);
            }

            .main-content {
                padding: 2rem 0;
            }

            .checkout-grid {
                display: grid;
                grid-template-columns: 1fr;
                gap: 2rem;
            }

            @media (min-width: 1024px) {
                .checkout-grid {
                    grid-template-columns: 2fr 1fr;
                }
            }

            .progress-steps {
                display: flex;
                justify-content: space-between;
                margin-bottom: 2rem;
                position: relative;
            }

            .progress-step {
                display: flex;
                flex-direction: column;
                align-items: center;
                z-index: 1;
            }

            .step-number {
                width: 2rem;
                height: 2rem;
                border-radius: 50%;
                background-color: var(--gray-200);
                color: var(--gray-600);
                display: flex;
                align-items: center;
                justify-content: center;
                margin-bottom: 0.5rem;
                font-weight: 500;
            }

            .step-number.active {
                background-color: var(--primary-color);
                color: white;
            }

            .step-label {
                font-size: 0.875rem;
                font-weight: 500;
                color: var(--gray-600);
            }

            .progress-line {
                position: absolute;
                top: 1rem;
                left: 10%;
                right: 10%;
                height: 2px;
                background-color: var(--gray-200);
                z-index: 0;
            }

            .checkout-form {
                background-color: white;
                border-radius: 0.5rem;
                box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
                padding: 1.5rem;
            }

            .form-section {
                display: none;
            }

            .form-section.active {
                display: block;
            }

            .section-title {
                font-size: 1.25rem;
                font-weight: 600;
                color: var(--gray-800);
                margin-bottom: 1.5rem;
            }

            .form-grid {
                display: grid;
                grid-template-columns: 1fr;
                gap: 1rem;
            }

            @media (min-width: 640px) {
                .form-grid {
                    grid-template-columns: repeat(2, 1fr);
                }
            }

            .form-group {
                margin-bottom: 1rem;
            }

            .form-group.full-width {
                grid-column: 1 / -1;
            }

            .form-label {
                display: block;
                font-size: 0.875rem;
                font-weight: 500;
                color: var(--gray-700);
                margin-bottom: 0.5rem;
            }

            .form-input {
                width: 100%;
                padding: 0.5rem;
                border: 1px solid var(--gray-300);
                border-radius: 0.375rem;
                font-size: 1rem;
                transition: border-color 0.15s ease-in-out;
            }

            .form-input:focus {
                outline: none;
                border-color: var(--primary-color);
                box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
            }

            .button {
                display: inline-block;
                padding: 0.5rem 1rem;
                font-size: 1rem;
                font-weight: 500;
                text-align: center;
                text-decoration: none;
                border-radius: 0.375rem;
                transition: background-color 0.15s ease-in-out;
                cursor: pointer;
                border: none;
            }

            .button-primary {
                background-color: var(--primary-color);
                color: white;
            }

            .button-primary:hover {
                background-color: var(--primary-hover);
            }

            .form-buttons {
                display: flex;
                justify-content: flex-end;
                gap: 1rem;
                margin-top: 1.5rem;
            }

            .order-summary {
                background-color: white;
                border-radius: 0.5rem;
                box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
                padding: 1.5rem;
                position: sticky;
                top: 1rem;
            }

            .summary-title {
                font-size: 1.25rem;
                font-weight: 600;
                color: var(--gray-800);
                margin-bottom: 1.5rem;
            }

            .cart-items {
                margin-bottom: 1.5rem;
            }

            .cart-item {
                display: flex;
                gap: 1rem;
                padding: 1rem 0;
                border-bottom: 1px solid var(--gray-200);
            }

            .cart-item:last-child {
                border-bottom: none;
            }

            .item-image {
                width: 4rem;
                height: 4rem;
                object-fit: cover;
                border-radius: 0.375rem;
            }

            .item-details {
                flex: 1;
            }

            .item-name {
                font-weight: 500;
                color: var(--gray-800);
                margin-bottom: 0.25rem;
            }

            .item-price {
                color: var(--gray-600);
                font-size: 0.875rem;
            }

            .summary-totals {
                border-top: 1px solid var(--gray-200);
                padding-top: 1rem;
                margin-top: 1rem;
            }

            .summary-row {
                display: flex;
                justify-content: space-between;
                margin-bottom: 0.5rem;
                font-size: 0.875rem;
                color: var(--gray-600);
            }

            .summary-total {
                display: flex;
                justify-content: space-between;
                font-weight: 600;
                color: var(--gray-800);
                font-size: 1.125rem;
                margin-top: 0.5rem;
            }

            .benefits {
                margin-top: 1.5rem;
                padding-top: 1.5rem;
                border-top: 1px solid var(--gray-200);
            }

            .benefit-item {
                display: flex;
                align-items: center;
                gap: 0.5rem;
                color: var(--gray-600);
                font-size: 0.875rem;
                margin-bottom: 0.5rem;
            }

            .benefit-item i {
                color: var(--primary-color);
            }
        </style>
    </head>
    <body>
        <header class="header">
            <div class="container">
                <div class="header-content">
                    <h1 class="page-title">Checkout</h1>
                    <div class="secure-badge">
                        <i class="fas fa-lock"></i>
                        <span>Secure Checkout</span>
                    </div>
                </div>
            </div>
        </header>

        <main class="main-content">
            <div class="container">
                <div class="checkout-grid">
                    <div class="checkout-main">
                        <div class="progress-steps">
                            <div class="progress-line"></div>
                            <div class="progress-step">
                                <div class="step-number active">1</div>
                                <span class="step-label">Shipping</span>
                            </div>
                            <div class="progress-step">
                                <div class="step-number">2</div>
                                <span class="step-label">Payment</span>
                            </div>
                            <div class="progress-step">
                                <div class="step-number">3</div>
                                <span class="step-label">Review</span>
                            </div>
                        </div>

                        <form id="checkoutForm" method="POST" class="checkout-form">
                            <div class="form-section active" id="shipping">
                                <h2 class="section-title">Shipping Information</h2>
                                <div class="form-grid">
                                    <div class="form-group">
                                        <label class="form-label">First Name</label>
                                        <input type="text" name="firstName" class="form-input" required>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Last Name</label>
                                        <input type="text" name="lastName" class="form-input" required>
                                    </div>

                                    <div class="form-group full-width">
                                        <label class="form-label">Address</label>
                                        <input type="text" name="address" class="form-input" required>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">City</label>
                                        <input type="text" name="city" class="form-input" required>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Postal Code</label>
                                        <input type="text" name="postalCode" class="form-input" required>
                                    </div>
                                </div>
                            </div>

                            <div class="form-section" id="payment">
                                <h2 class="section-title">Payment Information</h2>
                                <div class="form-grid">
                                    <div class="form-group full-width">
                                        <label class="form-label">Card Number</label>
                                        <input type="text" name="cardNumber" class="form-input" placeholder="1234 5678 9012 3456" required>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Expiry Date</label>
                                        <input type="text" name="expiryDate" class="form-input" placeholder="MM/YY" required>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">CVV</label>
                                        <input type="text" name="cvv" class="form-input" placeholder="123" required>
                                    </div>
                                </div>
                            </div>

                            <div class="form-section" id="review">
                                <h2 class="section-title">Review Order</h2>
                                <div id="orderReview"></div>
                            </div>

                            <div class="form-buttons">
                                <button type="button" id="prevBtn" class="button button-secondary" style="display: none;">Previous</button>
                                <button type="button" id="nextBtn" class="button button-primary">Continue</button>
                                <button type="submit" id="placeOrderBtn" name="place_order" class="button button-primary" style="display: none;">Place Order</button>
                            </div>
                        </form>
                    </div>

                    <div class="order-summary">
                        <h2 class="summary-title">Order Summary</h2>
                        <div class="cart-items">
                            <?php foreach ($cart_items as $item): ?>
                            <div class="cart-item">
                                <img src="utilizador/uploads/<?php echo htmlspecialchars($item['imagem']); ?>" alt="<?php echo htmlspecialchars($item['nome']); ?>" class="item-image">
                                <div class="item-details">
                                    <h3 class="item-name"><?php echo htmlspecialchars($item['nome']); ?></h3>
                                    <p class="item-price">
                                        Quantity: <?php echo $item['quantidade']; ?><br>
                                        €<?php echo number_format($item['total'], 2); ?>
                                    </p>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>

                        <div class="summary-totals">
                            <div class="summary-row">
                                <span>Subtotal</span>
                                <span>€<?php echo number_format($subtotal, 2); ?></span>
                            </div>
                            <div class="summary-row">
                                <span>Shipping</span>
                                <span>€<?php echo number_format($shipping, 2); ?></span>
                            </div>
                            <div class="summary-total">
                                <span>Total</span>
                                <span>€<?php echo number_format($total, 2); ?></span>
                            </div>
                        </div>

                        <div class="benefits">
                            <div class="benefit-item">
                                <i class="fas fa-truck"></i>
                                <span>Free shipping on orders over €100</span>
                            </div>
                            <div class="benefit-item">
                                <i class="fas fa-credit-card"></i>
                                <span>Secure payment processing</span>
                            </div>
                            <div class="benefit-item">
                                <i class="fas fa-shopping-bag"></i>
                                <span>30-day return policy</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>

        <script>
            let currentStep = 1;
            const form = document.getElementById('checkoutForm');
            const sections = document.querySelectorAll('.form-section');
            const stepNumbers = document.querySelectorAll('.step-number');
            const nextBtn = document.getElementById('nextBtn');
            const prevBtn = document.getElementById('prevBtn');
            const placeOrderBtn = document.getElementById('placeOrderBtn');

            function showStep(step) {
                sections.forEach(section => section.classList.remove('active'));
                sections[step - 1].classList.add('active');

                stepNumbers.forEach((number, index) => {
                    if (index + 1 <= step) {
                        number.classList.add('active');
                    } else {
                        number.classList.remove('active');
                    }
                });

                prevBtn.style.display = step === 1 ? 'none' : 'block';
                nextBtn.style.display = step === 3 ? 'none' : 'block';
                placeOrderBtn.style.display = step === 3 ? 'block' : 'none';
            }

            nextBtn.addEventListener('click', () => {
                if (validateStep(currentStep)) {
                    currentStep++;
                    showStep(currentStep);
                    if (currentStep === 3) {
                        updateReviewSection();
                    }
                }
            });

            prevBtn.addEventListener('click', () => {
                currentStep--;
                showStep(currentStep);
            });

            function validateStep(step) {
                const currentSection = sections[step - 1];
                const inputs = currentSection.querySelectorAll('input[required]');
                let valid = true;

                inputs.forEach(input => {
                    if (!input.value) {
                        valid = false;
                        input.classList.add('error');
                    } else {
                        input.classList.remove('error');
                    }
                });

                return valid;
            }

            function updateReviewSection() {
                const reviewSection = document.getElementById('orderReview');
                const formData = new FormData(form);
                let html = `
                    <div class="review-section">
                        <h3>Shipping Address</h3>
                        <p>
                            ${formData.get('firstName')} ${formData.get('lastName')}<br>
                            ${formData.get('address')}<br>
                            ${formData.get('city')}, ${formData.get('postalCode')}
                        </p>
                        <h3>Payment Method</h3>
                        <p>Card ending in ${formData.get('cardNumber').slice(-4)}</p>
                    </div>
                `;
                reviewSection.innerHTML = html;
            }

            // Card input formatting
            const cardInput = document.querySelector('input[name="cardNumber"]');
            cardInput.addEventListener('input', (e) => {
                let value = e.target.value.replace(/\D/g, '');
                value = value.replace(/(\d{4})/g, '$1 ').trim();
                e.target.value = value;
            });

            const expiryInput = document.querySelector('input[name="expiryDate"]');
            expiryInput.addEventListener('input', (e) => {
                let value = e.target.value.replace(/\D/g, '');
                if (value.length >= 2) {
                    value = value.slice(0,2) + '/' + value.slice(2,4);
                }
                e.target.value = value;
            });

            const cvvInput = document.querySelector('input[name="cvv"]');
            cvvInput.addEventListener('input', (e) => {
                e.target.value = e.target.value.replace(/\D/g, '').slice(0,3);
            });
        </script>
    </body>
    </html>