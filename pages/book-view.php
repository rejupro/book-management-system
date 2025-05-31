<div class="bms-container">

    <h1 style="text-align: center;">View Book</h1>

    <?php 
      if(!empty($response)){

         ?>
    <p id="save-response"> <?php echo $response; ?> </p>
    <?php
      }
   ?>
   

    <form action="<?php echo admin_url('admin.php?page=book-management'); ?>" id="frm-add-book" method="post">

        <div class="form-input">

            <label for="book_name">Name</label>

            <input type="text" required name="book_name" value="<?php echo $book->name; ?>"  id="book_name" placeholder="Enter name" class="form-group" readonly>
        </div>

        <div class="form-input">

            <label for="author_name">Author Name</label>

            <input type="text" required name="author_name"  value="<?php echo $book->author; ?>" id="author_name" placeholder="Enter Author name" class="form-group" readonly>
        </div>

        <div class="form-input">

            <label for="book_price">Book Price</label>

            <input type="text" name="book_price"  value="<?php echo $book->book_price; ?>" id="book_price" placeholder="Enter price" class="form-group" readonly>
        </div>

        <div class="form-input">

            <label for="">Cover Image</label>
            <img src="<?php echo $book->profile_image; ?>" alt="<?php echo $book->name; ?>" class="cover-image" style="width: 200px; height: 200px;">
        </div>

    </form>

</div>