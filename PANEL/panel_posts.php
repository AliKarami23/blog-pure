<?php
session_start();
include "../database/pdo_connection.php";
include "../database/jdf.php";

if (!isset($_SESSION['user'])) {
    header("location:../login.php");
}

$user_id = $_SESSION['user']['id'];

$query = "SELECT * FROM posts WHERE user_id = :user_id";
$stmt = $conn->prepare($query);
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

ob_start(); // Start buffering

?>
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <link rel="stylesheet" href="css/bootstrap.rtl.min.css"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css"/>
    <link rel="stylesheet" href="css/panel.css"/>
    <title>پست‌ها</title>
</head>
<body>
<section x-data="toggleSidebar" class="">
    <!-- قسمت‌های بقیه قسمت‌ها... -->
    <section class="main" style="margin-left:100px ">
        <div class="container">
            <div class="card card-primary bg-light shadow p-4 mt-5">
                <h1 class="text-gray h4 fw-bold">
                    <i class="bi bi-plus-circle"></i>
                    <span>پست ها</span>
                </h1>
                <?php
                foreach ($result as $row) {
                    echo '<div class="article">';
                    echo '<div class="article-text" style="float: right; width: 65%;">';
                    echo '<h2>' . htmlspecialchars($row['title']) . '</h2>';
                    echo '<p>' . 'نویسنده: ' . htmlspecialchars($row['writer']) . '</p>';
                    echo '<p>' . 'تاریخ انتشار: ' . htmlspecialchars($row['date']) . '</p>';
                    echo '<p>' . htmlspecialchars($row['caption']) . '</p>';
                    echo '</div>';
                    echo '<div class="article-image" style="float: left; width: 25%;">';

                    $imagePath = '../' . $row['image'];
                    $realImagePath = realpath($imagePath);

                    if ($realImagePath !== false) {
                        $imageData = file_get_contents($realImagePath);
                        $base64Image = base64_encode($imageData);

                        // ارسال تصویر به عنوان فایل
                        header('Content-Type: image/jpeg');
                        header('Content-Disposition: inline; filename="' . basename($imagePath) . '"');
                        readfile($realImagePath);
                    } else {
                        echo '<p>تصویر یافت نشد</p>';
                    }

                    echo '</div>';
                    echo '<div style="clear: both;"></div>';
                    echo '</div>';
                    echo '<div style="border-bottom: 1px solid black;width: 100%;margin: 25px 0;"></div>';
                }
                ?>
            </div>
        </div>
    </section>
    <!-- قسمت‌های بقیه قسمت‌ها... -->
</section>

<!-- قسمت‌های بقیه صفحه... -->
</body>
</html>
<?php
ob_end_clean(); // Clear the output buffer
?>
