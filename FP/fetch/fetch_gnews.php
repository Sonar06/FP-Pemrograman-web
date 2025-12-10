<?php
require_once __DIR__ . "/../config/db.php";

$apiKey = "0ee8b934d92f2fe16433eed1a37b1186";
$url = "https://gnews.io/api/v4/top-headlines?country=id&lang=id&expand=content&apikey=$apiKey";

$response = file_get_contents($url);
$data = json_decode($response, true);

if (!isset($data["articles"])) {
    die("Error: API tidak mengembalikan data artikel.");
}

foreach ($data["articles"] as $a) {
    $title = $conn->real_escape_string($a["title"]);
    $desc = $conn->real_escape_string($a["description"]);
    $content = $conn->real_escape_string($a["content"]);
    $image = $a["image"];
    $url = $a["url"];
    $source = $a["source"]["name"];
    $published = date("Y-m-d H:i:s", strtotime($a["publishedAt"]));

    $sql = "
        INSERT INTO articles (title, description, content, image, url, source_name, published_at)
        VALUES ('$title', '$desc', '$content', '$image', '$url', '$source', '$published')
        ON DUPLICATE KEY UPDATE
            description = '$desc',
            content = '$content',
            image = '$image',
            url = '$url',
            source_name = '$source',
            published_at = '$published'
    ";

    $conn->query($sql);
}

echo "Sync berita selesai.";
