<?php
require 'connected.php';

if (isset($_POST['submit'])) {

    if (!is_dir('image')) {
        mkdir('image', 0777, true);
    }

    $name = $_POST['pro_name'];
    $qty = (int)$_POST['qty'];
    $price = (float)$_POST['price'];
    $total = $qty * $price;

    if ($total <= 10) {
        $dis = 5;
    } elseif ($total <= 20) {
        $dis = 10;
    } elseif ($total <= 30) {
        $dis = 15;
    } elseif ($total <= 40) {
        $dis = 20;
    } else {
        $dis = 30;
    }

    $payment = $total - ($total * $dis / 100);

    $file = $_FILES['file']['name'];
    $tmp_name = $_FILES['file']['tmp_name'];
    $path = 'image/' . $file;
    move_uploaded_file($tmp_name, $path);

    $insert = "INSERT INTO tbl_product(pro_name, qty, price, total, discount, payment, image)
               VALUES ('$name', '$qty', '$price', '$total', '$dis', '$payment', '$file')";

    $result = $conn->query($insert);
    if ($result) {
        echo '<script>window.location.href="table.php";</script>';
    }
}
?>
