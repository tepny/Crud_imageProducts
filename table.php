<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD Product</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
</head>

<body class="bg-secondary">
<div class="container mt-4 p-4 shadow rounded-3 bg-light">

    <button id="add" type="button" class="btn btn-outline-dark float-end mb-3" data-bs-toggle="modal" data-bs-target="#exampleModal">
        + Add Product
    </button>

    <table class="table table-hover text-center">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Product</th>
                <th>QTY</th>
                <th>Price</th>
                <th>Total</th>
                <th>Discount</th>
                <th>Payment</th>
                <th>Image</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        <?php 
            require 'connected.php';
            $rs = $conn->query("SELECT * FROM tbl_product");
            while($row = mysqli_fetch_assoc($rs)){
                echo "
                <tr>
                    <td>{$row['id']}</td>
                    <td>{$row['pro_name']}</td>
                    <td>{$row['qty']}</td>
                    <td>\${$row['price']}</td>
                    <td>\${$row['total']}</td>
                    <td>{$row['discount']}%</td>
                    <td>\${$row['payment']}</td>
                    <td>
                        <img src='image/{$row['image']}' width='40' height='40' class='rounded-circle product-img'>
                    </td>
                    <td>
                        <a href='delete.php?id={$row['id']}'
                           class='btn btn-outline-danger'
                           onclick=\"return confirm('⚠️ Are you sure you want to delete this product?');\">
                           Delete
                        </a>
                        <button type='button' class='btn btn-outline-warning edit-btn' data-bs-toggle='modal' data-bs-target='#exampleModal'>
                            Edit
                        </button>
                    </td>
                </tr>";
            }
        ?>
        </tbody>
    </table>
</div>

<!-- Modal -->
<div class="modal fade" id="exampleModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 id="exampleModalLabel">Add Product</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <form id="form" method="post" enctype="multipart/form-data">
                <div class="modal-body">

                    <input type="hidden" name="id" id="pid">
                    <input type="hidden" name="old_image" id="old_image">

                    <div class="mb-2">
                        <label>Product</label>
                        <input id="product" name="pro_name" type="text" class="form-control">
                    </div>

                    <div class="mb-2">
                        <label>QTY</label>
                        <input id="qty" name="qty" type="number" class="form-control">
                    </div>

                    <div class="mb-2">
                        <label>Price</label>
                        <input id="price" name="price" type="number" step="0.01" class="form-control">
                    </div>

                    <div class="mb-2">
                        <label>Image</label><br>
                        <img id="image" src="https://i.pinimg.com/736x/4c/3e/02/4c3e027d03ea726d4696eb368852a174.jpg" width="100" height="100" class="rounded-circle mb-2">
                        <input id="file" name="file" type="file" class="form-control d-none">
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" id="save" name="submit" class="btn btn-primary">Save</button>
                    <button type="submit" id="update" name="update" class="btn btn-warning">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
$(document).ready(function(){

    $('#update').hide()

    // click image to choose file
    $('#image').click(() => $('#file').click())

    // preview image
    $('#file').change(function(){
        const file = this.files[0]
        if(file){
            $('#image').attr('src', URL.createObjectURL(file))
        }
    })

    // ADD MODE
    $('#add').click(function(){
        $('#save').show()
        $('#update').hide()
        $('#form').attr('action','insert.php')
        $('#exampleModalLabel').text('Add Product')
        $('#form')[0].reset()
        $('#image').attr('src','https://i.pinimg.com/736x/4c/3e/02/4c3e027d03ea726d4696eb368852a174.jpg')
    })

    // EDIT MODE
    $(document).on('click','.edit-btn',function(){
        $('#save').hide()
        $('#update').show()
        $('#form').attr('action','update.php')
        $('#exampleModalLabel').text('Edit Product')

        const row = $(this).closest('tr')

        const id = row.find('td:eq(0)').text()
        const name = row.find('td:eq(1)').text()
        const qty = row.find('td:eq(2)').text()
        const price = row.find('td:eq(3)').text().replace('$','')
        const img = row.find('.product-img').attr('src')

        $('#pid').val(id)
        $('#product').val(name)
        $('#qty').val(qty)
        $('#price').val(price)
        $('#image').attr('src', img)
        $('#old_image').val(img.replace('image/',''))
    })
})
</script>

</body>
</html>
