<?php
require_once 'config.php';
$stmt = $pdo->query("SELECT * FROM products ORDER BY id DESC");
$raw_products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>DiTitik Sudut - Kafe & Kasir Modern</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="bg-slate-50 text-slate-800">
    <!-- Navbar -->
    <nav class="flex justify-between items-center px-8 py-4 bg-white shadow-sm sticky top-0 z-50">
        <h1 class="text-xl font-bold text-indigo-600">☕ DiTitik Sudut</h1>
        <div class="space-x-4 flex items-center">
            <a href="#menu" class="text-slate-600 hover:text-indigo-600 font-medium">Menu</a>
            <a href="#lokasi" class="text-slate-600 hover:text-indigo-600 font-medium">Lokasi Toko</a>
            <a href="login.php" class="bg-indigo-600 text-white px-4 py-2 rounded-lg font-medium hover:bg-indigo-700 transition">Login Admin</a>
        </div>
    </nav>

    <!-- Header / Hero -->
    <header class="max-w-6xl mx-auto text-center py-16 px-4">
        <h2 class="text-4xl font-extrabold mb-4">Nikmati Menu Favorit di <span class="text-indigo-600">DiTitik Sudut</span></h2>
        <p class="text-slate-600 mb-8 text-lg">Tempat nongkrong asyik dengan sistem pemesanan dan kasir terenkripsi mutakhir.</p>
        <a href="#menu" class="bg-indigo-600 text-white px-6 py-3 rounded-xl font-semibold shadow-lg hover:bg-indigo-700 transition">Lihat Menu & Harga</a>
    </header>

    <!-- Menu & Harga Section -->
    <section id="menu" class="max-w-6xl mx-auto py-12 px-4">
        <h3 class="text-2xl font-bold mb-6 text-slate-800 border-b pb-2">Daftar Menu & Harga</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <?php if(empty($raw_products)): ?>
                <p class="text-slate-500 italic">Belum ada menu yang tersedia. Silakan input melalui dashboard admin.</p>
            <?php else: foreach($raw_products as $row): 
                $name = decryptData($row['name_encrypted']);
                $price = decryptData($row['price_encrypted']);
                $image = !empty($row['image_url']) ? $row['image_url'] : 'https://images.unsplash.com/photo-1509042239860-f550ce710b93?w=500';
            ?>
                <div class="bg-white rounded-2xl shadow-sm overflow-hidden border border-slate-100 hover:shadow-md transition">
                    <img src="<?= htmlspecialchars($image) ?>" alt="Menu" class="w-full h-48 object-cover">
                    <div class="p-5">
                        <h4 class="font-bold text-lg text-slate-800 mb-1"><?= htmlspecialchars($name) ?></h4>
                        <p class="text-indigo-600 font-semibold mb-3">Rp <?= number_format($price, 0, ',', '.') ?></p>
                        <span class="text-xs bg-slate-100 text-slate-600 px-2.5 py-1 rounded-full">Stok: <?= $row['stock'] ?></span>
                    </div>
                </div>
            <?php endforeach; endif; ?>
        </div>
    </section>

    <!-- Lokasi Toko Section -->
    <section id="lokasi" class="bg-slate-200 py-16 mt-12">
        <div class="max-w-6xl mx-auto px-4 text-center">
            <h3 class="text-2xl font-bold mb-3 text-slate-800">Lokasi Tempat / Toko Kami</h3>
            <p class="text-slate-600 mb-6">Kunjungi gerai fisik kami untuk pengalaman langsung bertransaksi.</p>
            <div class="bg-white p-6 rounded-2xl shadow-sm inline-block">
                <p class="font-bold text-slate-700">📍 Kafe & POS DiTitik Sudut</p>
                <p class="text-slate-500 text-sm mt-1">Jl. Telekomunikasi No. 1, Terusan Buahbatu - Bandung</p>
            </div>
        </div>
    </section>
</body>
</html>