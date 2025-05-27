<?php

    if(!class_exists('BookListTable')) {
        include_once BMS_PLUGIN_PATH . 'class/BookListTable.php';
    }


    echo "<h1>Book List</h1>";

    $bookListTable = new BookListTable();
    $bookListTable->prepare_items();
    $bookListTable->display();
