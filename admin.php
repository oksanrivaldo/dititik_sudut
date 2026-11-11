<?php
require_once 'config.php';

if (!isset($_SESSION['admin_logged'])) {
    header("Location: login.php");
    exit;
}

// Tambah Produk / Menu Kasir (Terenkripsi otomatis ke DB)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_product'])) {
    $name = trim($_POST['name']);
    $price = trim($_POST['price']);
    $stock = (int)$_POST['stock'];
    $image_url = trim($_POST['image_url']);

    $encrypted_name = encryptData($name);
    $encrypted_price = encryptData($price);

    $stmt = $pdo->prepare("INSERT INTO products (name_encrypted, price_encrypted, stock, image_url) VALUES (?, ?, ?, ?)");
    $stmt->execute([$encrypted_name, $encrypted_price, $stock, $image_url]);
    header("Location: admin.php");
    exit;
}

$stmt = $pdo->query("SELECT * FROM products ORDER BY id DESC");
$raw_products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Kasir Admin - DiTitik Sudut</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="bg-slate-50 flex h-screen overflow-hidden">
    <!-- Sidebar -->
    <aside class="w-64 bg-slate-900 text-white flex flex-col p-6">
        <h1 class="text-xl font-bold mb-8 text-indigo-400">Kasir DiTitik Sudut</h1>
        <nav class="space-y-4 flex-1">
            <a href="admin.php" class="block py-2.5 px-4 rounded bg-indigo-600 text-white font-medium">Dashboard POS</a>
            <a href="index.php" target="_blank" class="block py-2.5 px-4 rounded text-slate-400 hover:bg-slate-800 hover:text-white">Lihat Landing Page</a>
            <a href="logout.php" class="block py-2.5 px-4 rounded text-slate-400 hover:bg-slate-800 hover:text-white">Logout</a>
        </nav>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 p-8 overflow-y-auto">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h2 class="text-2xl font-bold text-slate-800">Panel Kasir & Manajemen Toko</h2>
                <p class="text-xs text-green-600 font-semibold mt-1">✓ Dekripsi Data Berhasil Diaktifkan untuk Sesi Admin</p>
            </div>
            <span class="bg-slate-200 text-slate-700 px-3 py-1.5 rounded-lg text-sm font-medium">Admin: <?= $_SESSION['admin_user'] ?></span>
        </div>

        <!-- Form Input Menu Kasir -->
        <div class="bg-white p-6 rounded-2xl shadow-sm mb-8 border border-slate-100">
            <h3 class="text-lg font-semibold mb-4 text-slate-700">Tambah Menu Kasir Baru (Terenkripsi ke Database)</h3>
            <form action="" method="POST" class="grid grid-cols-2 gap-4">
                <input type="text" name="name" placeholder="Nama Menu (Contoh: Kopi Susu)" required class="px-4 py-2 border rounded-lg outline-none">
                <input type="number" name="price" placeholder="Harga (Contoh: 18000)" required class="px-4 py-2 border rounded-lg outline-none">
                <input type="number" name="stock" placeholder="Stok Tersedia" required class="px-4 py-2 border rounded-lg outline-none">
                <input type="text" name="image_url" placeholder="URL Gambar (Opsional/Link Unsplash)" class="px-4 py-2 border rounded-lg outline-none">
                <button type="submit" name="add_product" class="col-span-2 bg-indigo-600 text-white py-2.5 rounded-lg font-medium hover:bg-indigo-700 transition">Simpan Produk (Terenkripsi)</button>
            </form>
        </div>

        <!-- Tabel Kasir / Konten Terdekripsi -->
        <div class="bg-white rounded-2xl shadow-sm overflow-hidden border border-slate-100">
            <div class="p-5 border-b bg-slate-50">
                <h3 class="font-bold text-slate-800">Daftar Produk Kasir (Hasil Dekripsi Real-time)</h3>
            </div>
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-100 text-slate-600 text-xs uppercase tracking-wider">
                        <th class="p-4">ID</th>
                        <th class="p-4">Nama Menu (Dekripsi)</th>
                        <th class="p-4">Harga (Dekripsi)</th>
                        <th class="p-4">Stok</th>
                        <th class="p-4">Format Asli di Database (Ciphertext)</th>
                    </tr>
                </thead>
                <tbody class="divide-y text-sm">
                    <?php if(empty($raw_products)): ?>
                        <tr><td colspan="5" class="p-4 text-center text-slate-400">Belum ada data produk kasir.</td></tr>
                    <?php else: foreach($raw_products as $row): 
                        $decrypted_name = decryptData($row['name_encrypted']);
                        $decrypted_price = decryptData($row['price_encrypted']);
                    ?>
                        <tr>
                            <td class="p-4 text-slate-500"><?= $row['id'] ?></td>
                            <td class="p-4 font-bold text-slate-800"><?= htmlspecialchars($decrypted_name) ?></td>
                            <td class="p-4 text-indigo-600 font-semibold">Rp <?= number_format($decrypted_price, 0, ',', '.') ?></td>
                            <td class="p-4"><?= $row['stock'] ?></td>
                            <td class="p-4 text-xs font-mono text-slate-400 truncate max-w-xs" title="<?= $row['name_encrypted'] ?>">
                                <?= substr($row['name_encrypted'], 0, 35) ?>...
                            </td>
                        </tr>
                    <?php endforeach; endif; ?>
                </tbody>
            </table>
        </div>
    </main>
</body>
</html>