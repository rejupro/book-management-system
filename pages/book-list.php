<?php

    if(!class_exists('BookListTable')) {
        include_once BMS_PLUGIN_PATH . 'class/BookListTable.php';
    }

    ?>
    <div class="wrap">
        <h1><?php echo __('Book List', 'bms-system'); ?></h1>
        <?php
        $bookListTable = new BookListTable();
        $bookListTable->prepare_items();
        $bookListTable->display();

        ?>
    </div>    
