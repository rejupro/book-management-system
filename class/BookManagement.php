<?php

class BookManagement {
   

    public function __construct() {
       add_action("admin_menu", array($this, "addBMSMenus"));
    }

    public function addBMSMenus() {
        add_menu_page(
            __('Book Management', 'bms-system'),
            __('Book System', 'bms-system'),
            'manage_options',
            'book-management',
            array($this, 'renderAddNewBookPage'),
            'dashicons-book-alt',
            6
        );
        add_submenu_page(
            'book-management',
            __('Add New Book', 'bms-system'),
            __('Add New', 'bms-system'),
            'manage_options',
            'book-management',
            array($this, 'renderAddNewBookPage')
        );
        add_submenu_page(
            'book-management',
            __('Book List', 'bms-system'),
            __('Book List', 'bms-system'),
            'manage_options',
            'book-list',
            array($this, 'renderBookListPage')
        );
    }
    public function renderAddNewBookPage() {
        echo '<h1>' . __('Add New Book', 'bms-system') . '</h1>';
        // Here you can add the code to display the form for adding a new book
        echo '<p>' . __('This is where you can add a new book.', 'bms-system') . '</p>';
    }
    public function renderBookListPage() {
        echo '<h1>' . __('Book List', 'bms-system') . '</h1>';
        // Here you can add the code to display the list of books
        echo '<p>' . __('This is where you can view the list of books.', 'bms-system') . '</p>';
    }
}


