<?php
require_once __DIR__ . '/config/config.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$errors = [];
$name = '';
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['confirm_password'] ?? '';

    if ($name === '' || mb_strlen($name) > 100) {
        $errors[] = 'Name is required (max 100 characters).';
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Enter a valid email address.';
    }
    if (strlen($password) < 8) {
        $errors[] = 'Password must be at least 8 characters.';
    }
    if ($password !== $confirm) {
        $errors[] = 'Passwords do not match.';
    }

    if (!$errors) {
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $errors[] = 'This email is already registered.';
        }
    }

    if (!$errors) {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO users (name, email, password_hash) VALUES (?, ?, ?)");
        $stmt->execute([$name, $email, $hash]);
        header('Location: /pothik/login.php?registered=1');
        exit;
    }
}

$pageTitle = 'Register';
require __DIR__ . '/includes/header.php';
?>

<section class="max-w-md mx-auto px-4 py-10">
    <h1 class="text-3xl font-bold text-pothik-green mb-6">Create account</h1>

    <?php if ($errors): ?>
        <div class="mb-4 rounded-lg border border-red-300 bg-red-50 p-4 text-sm text-red-700">
            <ul class="list-disc pl-5">
                <?php foreach ($errors as $e): ?>
                    <li><?= htmlspecialchars($e) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form method="post" class="space-y-4">
        <div>
            <label class="block text-sm font-medium mb-1" for="name">Name</label>
            <input class="w-full border rounded-lg px-3 py-2" type="text" id="name" name="name"
                   value="<?= htmlspecialchars($name) ?>" required>
        </div>
        <div>
            <label class="block text-sm font-medium mb-1" for="email">Email</label>
            <input class="w-full border rounded-lg px-3 py-2" type="email" id="email" name="email"
                   value="<?= htmlspecialchars($email) ?>" required>
        </div>
        <div>
            <label class="block text-sm font-medium mb-1" for="password">Password</label>
            <input class="w-full border rounded-lg px-3 py-2" type="password" id="password" name="password" required>
        </div>
        <div>
            <label class="block text-sm font-medium mb-1" for="confirm_password">Confirm password</label>
            <input class="w-full border rounded-lg px-3 py-2" type="password" id="confirm_password" name="confirm_password" required>
        </div>
        <button class="w-full bg-pothik-green text-white font-semibold py-2 rounded-lg hover:opacity-90" type="submit">
            Register
        </button>
    </form>

    <p class="mt-4 text-sm text-gray-600">
        Already have an account? <a class="text-pothik-green underline" href="/pothik/login.php">Login</a>
    </p>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>