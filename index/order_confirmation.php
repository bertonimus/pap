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
    header('Location: index.php');
    exit();
}

$order_id = isset($_GET['order_id']) ? (int)$_GET['order_id'] : 0;
$usuario_id = $_SESSION['id_utilizadores'];

// Get user email
$email_stmt = $con->prepare("SELECT email FROM utilizadores WHERE id_utilizadores = ?");
$email_stmt->bind_param("i", $usuario_id);
$email_stmt->execute();
$email_result = $email_stmt->get_result();
$user_email = $email_result->fetch_assoc()['email'];

// Get order details
$order = null;
$order_items = [];

$stmt = $con->prepare("
    SELECT p.id, p.total, p.status, p.created_at, 
           pi.quantidade, pi.preco as item_price, 
           prod.nome, prod.imagem
    FROM pedidos p
    JOIN pedido_items pi ON p.id = pi.pedido_id
    JOIN produtos prod ON pi.produto_id = prod.id
    WHERE p.id = ? AND p.usuario_id = ?
");

$stmt->bind_param("ii", $order_id, $usuario_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        if (!$order) {
            $order = [
                'id' => $row['id'],
                'total' => $row['total'],
                'status' => $row['status'],
                'created_at' => $row['created_at']
            ];
        }
        $order_items[] = $row;
    }
}

if (!$order) {
    header('Location: produtos.php');
    exit();
}

// Prepare email content
$subject = "Recibo do Pedido #" . $order_id;
$message = "Obrigado pelo seu pedido!\n\n";
$message .= "Detalhes do Pedido:\n";
foreach ($order_items as $item) {
    $message .= $item['nome'] . " - " . $item['quantidade'] . " x €" . number_format($item['item_price'], 2) . "\n";
}
$message .= "Total: €" . number_format($order['total'], 2) . "\n";
$message .= "Status: " . ucfirst($order['status']) . "\n";
$message .= "Data do Pedido: " . $order['created_at'] . "\n";

// Send email
$headers = "From: no-reply@seusite.com\r\n"; // Altere para um e-mail válido
$headers .= "Reply-To: no-reply@seusite.com\r\n"; // Altere para um e-mail válido
$headers .= "Content-Type: text/plain; charset=utf-8\r\n";

if (!mail($user_email, $subject, $message, $headers)) {
    error_log("Falha ao enviar o e-mail para: " . $user_email);
}

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css" />
    <style>
        :root {
            --primary-color: #3b82f6;
            --success-color: #22c55e;
            --gray-50: #f9fafb;
            --gray-100: #f3f4f6;
            --gray-200: #e5e7eb;
            --gray-600: #4b5563;
            --gray-700: #374151;
            --gray-800: #1f2937;
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
            color: var(--gray-800);
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 2rem 1rem;
        }

        .confirmation-card {
            background-color: white;
            border-radius: 0.5rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            padding: 2rem;
            text-align: center;
            margin-bottom: 2rem;
        }

        .confirmation-icon {
            width: 4rem;
            height: 4rem;
            background-color: var(--success-color);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            font-size: 2rem;
        }

        .confirmation-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--gray-800);
            margin-bottom: 1rem;
        }

        .order-details {
            background-color: white;
            border-radius: 0.5rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            padding: 2rem;
        }

        .order-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid var(--gray-200);
        }

        .order-number {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--gray-800);
        }

        .order-status {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            background-color: var(--success-color);
            color: white;
            border-radius: 9999px;
            font-size: 0.875rem;
            font-weight: 500;
        }

        .order-items {
            margin-bottom: 2rem;
        }

        .order-item {
            display: flex;
            align-items: center;
            padding: 1rem 0;
            border-bottom: 1px solid var(--gray-200);
        }

        .item-image {
            width: 4rem;
            height: 4rem;
            object-fit: cover;
            border-radius: 0.375rem;
            margin-right: 1rem;
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

        .order-summary {
            background-color: var(--gray-50);
            border-radius: 0.375rem;
            padding: 1.5rem;
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
            padding-top: 0.5rem;
            border-top: 1px solid var(--gray-200);
        }

        .button {
            display: inline-block;
            padding: 0.75rem 1.5rem;
            background-color: var(--primary-color);
            color: white;
            text-decoration: none;
            border-radius: 0.375rem;
            font-weight: 500;
            margin-top: 2rem;
            transition: background-color 0.15s ease-in-out;
        }

        .button:hover {
            background-color: #2563eb;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="confirmation-card">
            <div class="confirmation-icon">
                <i class="fas fa-check"></i>
            </div>
            <h1 class="confirmation-title">Obrigado pelo seu pedido!</h1>
            <p>Seu pedido foi realizado com sucesso e está sendo processado.</p>
        </div>

        <div class="order-details">
            <div class="order-header">
                <div class="order-number">Pedido #<?php echo $order_id; ?></div>
                <div class="order-status"><?php echo ucfirst($order['status']); ?></div>
            </div>

            <div class="order-items">
                <?php foreach ($order_items as $item): ?>
                <div class="order-item">
                    <img src="utilizador/uploads/<?php echo htmlspecialchars($item['imagem']); ?>" alt="<?php echo htmlspecialchars($item['nome']); ?>" class="item-image">
                    <div class="item-details">
                        <h3 class="item-name"><?php echo htmlspecialchars($item['nome']); ?></h3>
                        <p class ="item-price">
                            <?php echo $item['quantidade']; ?> x €<?php echo number_format($item['item_price'], 2); ?>
                        </p>
                    </div>
                    <div class="item-total">
                        €<?php echo number_format($item['quantidade'] * $item['item_price'], 2); ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <div class="order-summary">
                <div class="summary-row">
                    <span>Subtotal</span>
                    <span>€<?php echo number_format($order['total'] - 9.99, 2); ?></span>
                </div>
                <div class="summary-row">
                    <span>Frete</span>
                    <span>€9.99</span>
                </div>
                <div class="summary-total">
                    <span>Total</span>
                    <span>€<?php echo number_format($order['total'], 2); ?></span>
                </div>
            </div>

            <a href="produtos.php" class="button">Continuar a comprar</a>
        </div>
    </div>
</body>
</html>