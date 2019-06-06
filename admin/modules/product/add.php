
<!-- Hàm var_dump() sẽ in ra thông tin của biến gồm kiểu dữ liệu của biến và giá trị -->
<?php
	$open="product";
	require_once __DIR__. "/../../autoload/autoload.php";


//danh sach danh muc sp

   $category = $db->fetchALL("category");
   if($_SERVER["REQUEST_METHOD"]=="POST")
   {
      $data = 
      [
         "name"=>postInput('name'),
         "slug"=>to_slug(postInput("name")),
         "category_id" =>postInput("category_id"),
         "price" =>postInput("price"),
         "content" =>postInput("content"),
         "number" => postInput("number")
      ];

      $error=[];

      if(postInput("name")=='')
      {
         $error['name']="Mời bạn nhập đầy đủ tên danh mục";
      }

       if(postInput("category_id")=='')
      {
         $error['category_id']="Mời bạn chọn tên danh mục";
      }
       if(postInput("price")=='')
      {
         $error['price']="Mời bạn nhập giá sản phẩm";
      }
      if(postInput("number")=='')
      {
         $error['number']="Mời bạn nhập số lượng";
      }

       if(postInput("content")=='')
      {
         $error['content']="Mời bạn nhập nội dung";
      }

      if (! isset($_FILES['thunbar'])) {
         $error['thunbar'] = "mời bạn chon hình ảnh";
      }
      //error trống có nghĩa không có lỗi
      if(empty($error))
      {
         if (isset($_FILES['thunbar'])) {

            $file_name = $_FILES['thunbar']['name'];
            $file_tmp  = $_FILES['thunbar']['tmp_name'];
            $file_type = $_FILES['thunbar']['type'];
            $file_erro = $_FILES['thunbar']['error'];
            # code...
            if ($file_erro==0) {
               $part =ROOT."product/";
               $data['thunbar']= $file_name;
               # code...
            }
         }
        $id_insert = $db->insert("product",$data);
        if ($id_insert) {

            move_uploaded_file($file_tmp, $part.$file_name);
            $_SESSION['success'] = "Thêm mới thành công";
            redirectAdmin("product");
        }
        else
        {
            $_SESSION['error']="Thêm mới thất bại";
        } 
         
      }

   }



 ?>

<!-- Lệnh require, require_once, include và include_once dùng để import một file PHP A vào một file PHP B với mục đích giúp file PHP B có thể sử dụng được các thư viện trong file PHP A. -->
<?php require_once __DIR__. "/../../layouts/header.php"; ?>
   <!-- Page Heading -->
   <div class="row">
      <div class="col-lg-12">
         <h1 class="page-header">
            Thêm mới sản phẩm
            <small>Subheading</small>
         </h1>
         <ol class="breadcrumb">
            <li>
               <i class="fa fa-dashboard"></i>  <a href="">Dashboard</a>
            </li>
            <li>
               <i class="fa fa-dashboard"></i>  <a href="">Sản phẩm</a>
            </li>
            <li class="active">
               <i class="fa fa-file"></i> Thêm mới sản phẩm
            </li>
         </ol>
         <div class="clearfix"></div>
         <!-- Thông báo lỗi -->
         <?php if(isset($_SESSION['error'])):  ?>
            <div class="alert alert-danger">
               <?php echo $_SESSION['error']; unset($_SESSION['error']) ?>
            </div>
         <?php endif;  ?>
      </div>
   </div>
   <div class="col-md-12">
      <!-- Thuộc tính enctype=”multipart/form-data” ở trong thẻ <form mục đích của thuộc tính này để trình duyệt có thể hiểu và mã hóa dữ liệu thành nhiều phần. -->
      <form class="form-horizontal" action="" method="POST" enctype="multipart/form-data">

         <div class="form-group row">
            <label for="inputEmail3" class="col-sm-2 col-form-label">Danh Sách sản phẩn</label>
            <div class="col-sm-8 col-md-8" name="category">
               <select class="form-control col-md-8" name="category_id">
                  <option value=""> - Mời bạn chọn danh mục sản phẩm - </option>

                  <?php foreach ($category as $item):?> 
                    <option value="<?php echo $item['id'] ?>"><?php echo $item['name']?></option>
                  <?php endforeach ?> 

               </select>
               <?php if (isset($error['category_id'])):  ?>
                     <p class="text-danger"> <?php echo $error['category_id'] ?> </p>
               <?php endif ?>
            </div>  
         </div>

         <div class="form-group row">
            <label for="inputEmail3" class="col-sm-2 col-form-label">Tên sản phẩm</label>
            <div class="col-sm-8">
               <input type="text" class="form-control" id="inputEmail3" placeholder="Tên sản phẩm" name="name">
               <?php if (isset($error['name'])):  ?>
                     <p class="text-danger"> <?php echo $error['name'] ?> </p>
               <?php endif ?>
            </div>  
         </div>

         <div class="form-group row">
            <label for="inputEmail3" class="col-sm-2 col-form-label">Giá sản phẩm</label>
            <div class="col-sm-8">
               <input type="number" class="form-control" id="inputEmail3" placeholder="9.000.000" name="price">
               <?php if (isset($error['price'])):  ?>
                     <p class="text-danger"> <?php echo $error['price'] ?> </p>
               <?php endif ?>
            </div>  
         </div>

         <div class="form-group row">
            <label for="inputEmail3" class="col-sm-2 col-form-label">Số Lượng</label>
            <div class="col-sm-8">
               <input type="number" class="form-control" id="inputEmail3" name="number">
               <?php if (isset($error['number'])):  ?>
                     <p class="text-danger"> <?php echo $error['number'] ?> </p>
               <?php endif ?>
            </div>  
         </div>

         <div class="form-group row">
            <label for="inputEmail3" class="col-sm-2 col-form-label">Giảm giá</label>
            <div class="col-sm-3">
               <input type="number" class="form-control" id="inputEmail3" placeholder="10%" name="sale" value="0">
            </div>

            <label for="inputEmail3" class="col-sm-1 col-form-label">Hình ảnh</label>
            <div class="col-sm-3">
               <input type="file" class="form-control" id="inputEmail3" placeholder="10%" name="thunbar" value="0">
               <?php if (isset($error['thunbar'])):  ?>
                     <p class="text-danger"> <?php echo $error['thunbar'] ?> </p>
               <?php endif ?>
            </div>   
         </div>

         <!-- <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
               <div class="checkbox">
                  <label>
                  <input type="checkbox" name="">
                  Remember me
                  </label>
               </div>
            </div>
         </div> -->
          <div class="form-group row">
            <label for="inputEmail3" class="col-sm-2 col-form-label">Nội Dung</label>
            <div class="col-sm-8">
              <textarea class="form-control" name="content" rows="10"></textarea>
               <?php if (isset($error['price'])):  ?>
                     <p class="text-danger"> <?php echo $error['price'] ?> </p>
               <?php endif ?>
            </div>  
         </div>
         <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
               <button type="submit" class="btn btn-success">Lưu</button>
            </div>
         </div>
      </form>
   </div>
   <!-- /.row -->
<?php require_once __DIR__. "/../../layouts/footer.php";?>