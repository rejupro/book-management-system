<?php

    if(!class_exists('BookListTable')) {
        include_once BMS_PLUGIN_PATH . 'class/BookListTable.php';
    }

    ?>
    <div class="wrap">
        <h1><?php echo __('Book List', 'bms-system'); ?></h1>
        <form id="frm_search" method="get">
            <input type="hidden" name="page" value="book-list">
        <?php
            $bookListTable = new BookListTable();
            $bookListTable->prepare_items();
            $bookListTable->search_box(__('Search Books', 'bms-system'), 'search_books');
            $bookListTable->display();
        ?>
        </form>
    </div>    
