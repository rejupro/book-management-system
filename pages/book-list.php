<?php

    if(!class_exists('BookListTable')) {
        include_once BMS_PLUGIN_PATH . 'class/BookListTable.php';
    }

    ?>
    <div class="wrap">
        <h1><?php echo __('Book List', 'bms-system'); ?></h1>
        <?php
            $bookListTable = new BookListTable();
        ?>
        <form id="frm_search" method="get">
            <?php
                $bookListTable->extra_tablenav('top');
            ?>
            <input type="hidden" name="page" value="book-list">
        <?php
            $bookListTable->prepare_items();
            $bookListTable->search_box(__('Search Books', 'bms-system'), 'search_books');
            $bookListTable->display();
        ?>
        </form>
    </div>    
