<?php
include 'connected.php';

if (isset($_POST['update'])) {
    $id       = $_POST['id'];
    $pro_name = $_POST['pro_name'];
    $qty      = $_POST['qty'];
    $price    = $_POST['price'];
    $total = $qty * $price;
    if($total <= 10){
        $dis = 5;
    } elseif($total <= 20){
        $dis = 10;
    } elseif($total <= 30){
        $dis = 15;
    } elseif($total <= 40){
        $dis = 20;
    } else {
        $dis = 30;
    }
    $payment = $total - ($total * $dis / 100);
    $old_image = $_POST['old_image'];
    // upload new image if selected
    if (!empty($_FILES['file']['name'])) {
        $image_name = time() . '_' . $_FILES['file']['name'];
        move_uploaded_file($_FILES['file']['tmp_name'],"image/" . $image_name);
    } else {
        $image_name = $old_image;
    }
    // update query
    $stmt = $conn->prepare("UPDATE tbl_product 
        SET pro_name=?, qty=?, price=?, total=?, discount=?, payment=?, image=? 
        WHERE id=?");

    $stmt->bind_param(
        "siddddsi",
        $pro_name,
        $qty,
        $price,
        $total,
        $dis,  
        $payment,
        $image_name,
        $id
    );

    if ($stmt->execute()) {
        header("Location: table.php");
        exit;
    } else {
        echo "Update failed: " . $stmt->error;
    }
}
?>
