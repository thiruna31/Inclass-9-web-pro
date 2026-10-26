<?php
declare(strict_types=1);

/**
 * Campus Crust - PHP form validation practice
 * Prepared by Thirunavukkarasu Palaniappa
 * Classroom exercise only: no order is saved or sent.
 */

function e(string $value): string {
    return htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

$errors = [];
$clean = [];

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    header('Allow: POST');
    $errors[] = 'Please open order.html and submit the form using POST.';
} else {
    // Validate the chef name.
    $customer = $_POST['customer'] ?? null;
    if (!is_string($customer) || trim($customer) === '' || strlen(trim($customer)) > 40) {
        $errors[] = 'Enter one chef name containing 1–40 bytes.';
    } else {
        $clean['customer'] = trim($customer);
    }

    // Validate the selected pizza size.
    $size = $_POST['size'] ?? null;
    if (!is_string($size) || !in_array($size, ['small', 'medium', 'large'], true)) {
        $errors[] = 'Choose small, medium, or large.';
    } else {
        $clean['size'] = ucfirst($size);
    }

    // Validate quantity as a whole number from 1 through 5.
    $quantity = $_POST['quantity'] ?? null;
    if (!is_string($quantity)) {
        $errors[] = 'Enter a quantity from 1 to 5.';
    } else {
        $validatedQuantity = filter_var(
            $quantity,
            FILTER_VALIDATE_INT,
            ['options' => ['min_range' => 1, 'max_range' => 5]]
        );
        if ($validatedQuantity === false) {
            $errors[] = 'Quantity must be a whole number from 1 to 5.';
        } else {
            $clean['quantity'] = $validatedQuantity;
        }
    }

    // Validate all toppings. Reject the request if any submitted value is invalid.
    $toppings = $_POST['toppings'] ?? [];
    $allowedToppings = ['mushroom', 'olive', 'pineapple'];
    if (!is_array($toppings)) {
        $errors[] = 'Toppings must be submitted as a list.';
    } else {
        $acceptedToppings = [];
        foreach ($toppings as $topping) {
            if (!is_string($topping) || !in_array($topping, $allowedToppings, true)) {
                $errors[] = 'One or more topping choices are invalid. Please use the listed options.';
                break;
            }
            $acceptedToppings[] = $topping;
        }
        if (!in_array('One or more topping choices are invalid. Please use the listed options.', $errors, true)) {
            $clean['toppings'] = $acceptedToppings;
        }
    }

    // Validate the optional kitchen note.
    $notes = $_POST['notes'] ?? '';
    if (!is_string($notes)) {
        $errors[] = 'The kitchen note must be plain text.';
    } else {
        $notes = trim($notes);
        if (strlen($notes) > 200) {
            $errors[] = 'The kitchen note must be no more than 200 bytes.';
        } else {
            $clean['notes'] = $notes;
        }
    }

    // Ensure every expected field was processed successfully.
    if ($errors === []) {
        foreach (['customer', 'size', 'quantity', 'toppings', 'notes'] as $key) {
            if (!array_key_exists($key, $clean)) {
                $errors[] = 'The form could not be processed completely. Please try again.';
                break;
            }
        }
    }

    if ($errors !== []) {
        http_response_code(422);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Campus Crust | Practice Ticket</title>
  <style>
    body { font: 16px/1.6 system-ui, sans-serif; max-width: 700px; margin: 32px auto; padding: 20px; background: #faf5e9; color: #352b22; }
    main { background: #fff; padding: 24px; border: 1px solid #ddcfb5; border-radius: 14px; box-shadow: 0 8px 24px rgba(53,43,34,.06); }
    h1 { margin-top: 0; }
    li { margin: 10px 0; overflow-wrap: anywhere; }
    .errors { color: #932d22; }
    .success { color: #24633b; }
    a { color: #a53d23; }
    footer { margin-top: 22px; color: #65594a; font-size: .85rem; text-align: center; }
  </style>
</head>
<body>
<main>
<?php if ($errors !== []): ?>
  <h1>Almost ready, Chef!</h1>
  <p>Please check the following and submit the form again:</p>
  <ul class="errors" role="alert">
    <?php foreach ($errors as $error): ?>
      <li><?= e($error) ?></li>
    <?php endforeach; ?>
  </ul>
<?php else: ?>
  <h1>Nice work, Chef <?= e($clean['customer']) ?>!</h1>
  <h2>Your practice ticket</h2>
  <ul>
  <?php
    $labels = [
        'customer' => 'Chef name',
        'size' => 'Pizza size',
        'quantity' => 'Quantity',
        'toppings' => 'Toppings',
        'notes' => 'Kitchen note'
    ];
    foreach ($labels as $key => $label):
        $value = $clean[$key];
        if ($key === 'toppings') {
            $value = $value === [] ? 'Plain cheese' : implode(', ', $value);
        } elseif ($key === 'notes' && $value === '') {
            $value = 'No special requests';
        }
  ?>
    <li><strong><?= e($label) ?>:</strong> <?= e((string) $value) ?></li>
  <?php endforeach; ?>
  </ul>
  <p class="success">Practice complete! Your input passed the PHP checks.</p>
  <p>No order was saved, purchased, or sent to a kitchen.</p>
<?php endif; ?>
  <p><a href="order.html">← Back to the pizza form</a></p>
</main>
<footer>Prepared by Thirunavukkarasu Palaniappa · PHP Form Practice</footer>
</body>
</html>
