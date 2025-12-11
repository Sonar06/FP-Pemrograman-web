<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . "/../config/db.php";

$category = isset($_GET['category']) ? $_GET['category'] : null;

$hero = null;
$heroId = 0;

if ($category) {
    $stmtHero = $conn->prepare("SELECT * FROM articles WHERE category = ? ORDER BY published_at DESC LIMIT 1");
    $stmtHero->bind_param("s", $category);
    $stmtHero->execute();
    $hero = $stmtHero->get_result()->fetch_assoc();
} else {
    $queryHero = $conn->query("SELECT * FROM articles ORDER BY published_at DESC LIMIT 1");
    $hero = $queryHero->fetch_assoc();
}

if ($hero) {
    $heroId = $hero['id'];
}

if ($category) {
    $stmtList = $conn->prepare("SELECT * FROM articles WHERE category = ? AND id != ? ORDER BY published_at DESC LIMIT 6");
    $stmtList->bind_param("si", $category, $heroId);
    $stmtList->execute();
    $result = $stmtList->get_result();
} else {
    $stmtList = $conn->prepare("SELECT * FROM articles WHERE id != ? ORDER BY published_at DESC LIMIT 6");
    $stmtList->bind_param("i", $heroId);
    $stmtList->execute();
    $result = $stmtList->get_result();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>inIBerita- Portal Berita Terkini</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <style>
        body {
            font-family: system-ui, -apple-system, sans-serif;
            background-color: #f7f7f7;
        }
        .hero-section {
            position: relative;
            height: 400px;
            border-radius: 15px;
            color: white;
            display: flex;
            align-items: flex-end;
            overflow: hidden; /* penting supaya gambar tidak keluar */
        }
        .hero-section::before {
            content: "";
            position: absolute;
            top: 0; left: 0;
            width: 100%; height: 100%;
            background-image: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.8)), url('<?= $hero['image'] ?>');
            background-size: cover;
            background-position: center;
            transition: transform 0.5s ease;
            z-index: 0;
        }
        .hero-section:hover::before {
            transform: scale(1.05);
        }
        .hero-section > * {
            position: relative;
            z-index: 1;
            padding: 1rem;
        }
        .line-clamp-3 {
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        .navbar-brand span {
            color: #ffffffff; /* ganti dengan warna yang kamu mau */
        }
        .card-img-top {
            height: 200px;
            object-fit: cover;
            transition: transform 0.3s;
        }
        .card:hover .card-img-top {
            transform: scale(1.05);
        }      
        .card {
            overflow: hidden;
        }
    </style>
</head>

<body>

    <!-- NAVBAR -->
    <nav class="navbar navbar-expand-lg navbar-light sticky-top shadow-sm" style="background-color: #b91c1c;">
        <div class="container">
            <a class="navbar-brand fw-bold fs-3" href="#">
                <span>inIBerita</span>
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0 fw-medium">
                    <li class="nav-item"><a class="nav-link active text-white" href="index.php">Beranda</a></li>
                    <li class="nav-item"><a class="nav-link text-white" href="index.php?category=business">Bisnis</a></li>
                    <li class="nav-item"><a class="nav-link text-white" href="index.php?category=sports">Olahraga</a></li>
                    <li class="nav-item"><a class="nav-link text-white" href="index.php?category=health">Kesehatan</a></li>
                    <li class="nav-item"><a class="nav-link text-white" href="index.php?category=technology">Teknologi</a></li>
                </ul>
                
                <form class="d-flex ms-lg-3">
                    <div class="input-group">
                        <input class="form-control rounded-start-pill" placeholder="Cari berita...">
                        <button class="btn btn-light rounded-end-pill" type="submit">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>
                </form>
                
                <a href="login.html" class="btn btn-link text-decoration-none text-white ms-2 fw-bold">Masuk</a>
            </div>
        </div>
    </nav>

    <div class="container py-4">
        <?php if ($hero): ?>
        <!-- BERITA UTAMA -->
        <h2 class="mb-4 border-start border-4 border-danger ps-3 fw-bold">Berita Utama</h2>
        
        
        <div class="hero-section p-4 mb-5 shadow" 
            style="background-image: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.8)), 
                    url('<?= $hero["image"] ?>');">
                    
            <div>
                <span class="badge bg-danger mb-2"><?= strtoupper($hero["category"] ?? "BERITA") ?></span>

                <h1 class="fw-bold display-6">
                    <?= $hero["title"] ?>
                </h1>

                <div class="small text-light opacity-75 mt-2">
                    <i class="bi bi-person-fill me-1"></i> <?= $hero["source_name"] ?> &bull; 
                    <i class="bi bi-calendar me-1"></i> <?= date("d M Y", strtotime($hero["published_at"])) ?>
                </div>

                <a href="article.php?id=<?= $hero["id"] ?>" 
                class="btn btn-danger mt-3 fw-bold px-4">
                Baca Selengkapnya
                </a>
            </div>
        </div>
        <?php endif; ?>
        <!-- TERKINI -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="border-start border-4 border-danger ps-3 fw-bold m-0">Terkini</h2>
        </div>

        <div class="row g-4">

            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 shadow-sm border-0">
                        <div class="position-relative">
                            <img src="<?= $row['image'] ?>" class="card-img-top" alt="thumbnail">
                            <span class="position-absolute top-0 start-0 badge bg-danger m-2">
                                <?= strtoupper($row["category"]) ?>
                            </span>
                        </div>

                        <div class="card-body d-flex flex-column">
                            <small class="text-muted mb-2">
                                <i class="bi bi-calendar3"></i> 
                                <?= date("d M Y", strtotime($row['published_at'])) ?>
                            </small>

                            <h5 class="card-title fw-bold">
                                <a href="article.php?id=<?= $row['id'] ?>" class="text-decoration-none text-dark">
                                    <?= $row['title'] ?>
                                </a>
                            </h5>

                            <p class="card-text text-secondary small line-clamp-3">
                                <?= $row['description'] ?>
                            </p>

                            <div class="mt-auto pt-3 border-top d-flex justify-content-between align-items-center">
                                <small class="text-muted"><?= $row['source_name'] ?></small>
                                <a href="article.php?id=<?= $row['id'] ?>" class="text-danger text-decoration-none fw-bold small">
                                    Baca Selengkapnya
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>

        </div>

    </div>

    <!-- FOOTER -->
    <footer class="bg-dark text-white pt-5 pb-3 mt-5">
        <div class="container">

            <div class="row g-4">
                <div class="col-lg-4 col-md-6">
                    <h4 class="fw-bold mb-3">BeritaNusantara</h4>
                    <p class="text-secondary small">Menyajikan berita terkini dan akurat dari seluruh negeri.</p>
                </div>

                <div class="col-lg-2 col-md-6">
                    <h5 class="fw-bold mb-3">Kategori</h5>
                    <ul class="list-unstyled text-secondary small">
                        <li><a href="index.php?category=nation" class="text-decoration-none text-secondary">Nasional</a></li>
                        <li><a href="#" class="text-decoration-none text-secondary">Ekonomi</a></li>
                        <li><a href="#" class="text-decoration-none text-secondary">Teknologi</a></li>
                    </ul>
                </div>

            </div>

            <hr class="border-secondary mt-4">

            <div class="text-center text-secondary small">
                © 2024 BeritaNusantara
            </div>

        </div>
    </footer>

</body>
</html>

