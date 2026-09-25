<?php
session_start();

 include "dbconn.php";  
$errors = $_SESSION['form_errors'] ?? [];
$successMessage = $_SESSION['form_success'] ?? '';
$formValues = $_SESSION['form_values'] ?? [];

unset($_SESSION['form_errors'], $_SESSION['form_success'], $_SESSION['form_values']);

$customerRequests = $_SESSION['customer_requests'] ?? [];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Onehome | Customer Parts Request</title>
    <link rel="stylesheet" href="styles.css" />
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Customer Parts Request</h1>
            <p>Onehome service support for replacement parts and equipment requests.</p>
        </div>

        <div class="content">
            <section>
                <form method="POST" action="process.php">
                    <?php if (!empty($errors)): ?>
                        <div class="alert error">
                            <?php foreach ($errors as $error): ?>
                                <div><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                    <?php if ($successMessage !== ''): ?>
                        <div class="alert success"><?php echo htmlspecialchars($successMessage, ENT_QUOTES, 'UTF-8'); ?></div>
                    <?php endif; ?>

                    <div class="two-col">
                        <div class="field">
                            <label for="customer_name">Customer Name</label>
                            <input type="text" id="customer_name" name="customer_name" value="<?php echo htmlspecialchars($formValues['customer_name'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required />
                        </div>
                        <div class="field">
                            <label for="email">Email Address</label>
                            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($formValues['email'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required />
                        </div>
                    </div>

                    <div class="two-col">
                        <div class="field">
                            <label for="phone">Phone Number</label>
                            <input type="text" id="phone" name="phone" value="<?php echo htmlspecialchars($formValues['phone'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" />
                        </div>
                        <div class="field">
                            <label for="priority">Priority</label>
                            <select id="priority" name="priority">
                                <option value="Low" <?php echo (($formValues['priority'] ?? 'Normal') === 'Low') ? 'selected' : ''; ?>>Low</option>
                                <option value="Normal" <?php echo (($formValues['priority'] ?? 'Normal') === 'Normal') ? 'selected' : ''; ?>>Normal</option>
                                <option value="High" <?php echo (($formValues['priority'] ?? 'Normal') === 'High') ? 'selected' : ''; ?>>High</option>
                                <option value="Urgent" <?php echo (($formValues['priority'] ?? 'Normal') === 'Urgent') ? 'selected' : ''; ?>>Urgent</option>
                            </select>
                        </div>
                    </div>

                    <div class="field">
                        <label for="address">Address</label>
                        <input type="text" id="address" name="address" value="<?php echo htmlspecialchars($formValues['address'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" />
                    </div>

                    <div class="two-col">
                        <div class="field">
                            <label for="product_name">Product / Equipment</label>
                            <input type="text" id="product_name" name="product_name" value="<?php echo htmlspecialchars($formValues['product_name'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required />
                        </div>
                        <div class="field">
                            <label for="part_number">Part Number</label>
                            <input type="text" id="part_number" name="part_number" value="<?php echo htmlspecialchars($formValues['part_number'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required />
                        </div>
                    </div>

                    <div class="two-col">
                        <div class="field">
                            <label for="part_name">Part Name</label>
                            <input type="text" id="part_name" name="part_name" value="<?php echo htmlspecialchars($formValues['part_name'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required />
                        </div>
                        <div class="field">
                            <label for="quantity">Quantity</label>
                            <input type="number" id="quantity" name="quantity" min="1" value="<?php echo htmlspecialchars($formValues['quantity'] ?? '1', ENT_QUOTES, 'UTF-8'); ?>" required />
                        </div>
                    </div>

                    <div class="field">
                        <label for="notes">Request Details</label>
                        <textarea id="notes" name="notes" placeholder="Describe the issue, installation notes, or any relevant warranty information."><?php echo htmlspecialchars($formValues['notes'] ?? '', ENT_QUOTES, 'UTF-8'); ?></textarea>
                    </div>

                    <button class="btn" type="submit">Submit Parts Request</button>
                </form>
            </section>

            <aside>
                <div class="card">
                    <h3>Service Snapshot</h3>
                    <div class="mini-summary">
                        <div class="stat">
                            Total Requests
                            <strong><?php echo count($customerRequests); ?></strong>
                        </div>
                        <div class="stat">
                            Urgent Cases
                            <strong>
                                <?php
                                $urgent = 0;
                                foreach ($customerRequests as $request) {
                                    if (($request['priority'] ?? '') === 'Urgent') {
                                        $urgent++;
                                    }
                                }
                                echo $urgent;
                                ?>
                            </strong>
                        </div>
                    </div>
                </div>

                <?php if (!empty($customerRequests)): ?>
                    <div class="card recent-card">
                        <h3>Recent Requests</h3>
                        <div class="requests">
                            <?php foreach (array_slice(array_reverse($customerRequests), 0, 4) as $request): ?>
                                <div class="request-item">
                                    <h4><?php echo htmlspecialchars($request['part_name'], ENT_QUOTES, 'UTF-8'); ?></h4>
                                    <p><strong>Customer:</strong> <?php echo htmlspecialchars($request['customer_name'], ENT_QUOTES, 'UTF-8'); ?></p>
                                    <p><strong>Product:</strong> <?php echo htmlspecialchars($request['product_name'], ENT_QUOTES, 'UTF-8'); ?></p>
                                    <p><strong>Priority:</strong> <?php echo htmlspecialchars($request['priority'], ENT_QUOTES, 'UTF-8'); ?></p>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </aside>
        </div>
    </div>
</body>
</html>
