<?php
require_once 'config.php';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $private_key = trim($_POST['private_key']);

    $stmt = $pdo->prepare("SELECT * FROM admins WHERE username = ?");
    $stmt->execute([$username]);
    $admin = $stmt->fetch();

    if ($admin && password_verify($private_key, $admin['private_key_hash'])) {
        $_SESSION['admin_logged'] = true;
        $_SESSION['admin_user'] = $admin['username'];
        header("Location: admin.php");
        exit;
    } else {
        $error = "Username atau Private Key salah!";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login Admin - DiTitik Sudut</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="bg-slate-100 flex items-center justify-center h-screen">
    <div class="bg-white p-8 rounded-2xl shadow-md w-full max-w-md">
        <h2 class="text-2xl font-bold mb-2 text-center text-slate-800">Admin Portal</h2>
        <p class="text-sm text-slate-500 mb-6 text-center">Autentikasi menggunakan <b>Private Key</b></p>

        <?php if($error): ?>
            <div class="bg-red-100 text-red-600 p-3 rounded-lg mb-4 text-sm"><?= $error ?></div>
        <?php endif; ?>

        <form action="" method="POST" class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Username Admin</label>
                <input type="text" name="username" value="admin_oksan" required class="w-full px-4 py-2 border rounded-lg outline-none">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Private Key</label>
                <input type="password" name="private_key" placeholder="Masukkan Private Key..." required class="w-full px-4 py-2 border rounded-lg outline-none font-mono">
                <span class="text-xs text-slate-400 mt-1 block">Hint Kunci: SECRET-KEY-2026</span>
            </div>
            <button type="submit" class="w-full bg-indigo-600 text-white py-2.5 rounded-lg font-semibold hover:bg-indigo-700 transition">Buka Dashboard & Dekripsi Data</button>
        </form>
        <div class="text-center mt-4">
            <a href="index.php" class="text-sm text-indigo-600 hover:underline">← Kembali ke Landing Page</a>
        </div>
    </div>
</body>
</html>