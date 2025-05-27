<?php

class BookManagement {
   

    public function __construct() {
       add_action("admin_menu", array($this, "addBMSMenus"));
       add_action("admin_enqueue_scripts", array($this, "enqueueBMSAssets"));
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
        include_once BMS_PLUGIN_PATH . 'pages/add-book.php';
        ob_start();
        $contents = ob_get_contents();
        ob_end_clean();
        echo $contents;
    }
    public function renderBookListPage() {
        echo '<h1>' . __('Book List', 'bms-system') . '</h1>';
        // Here you can add the code to display the list of books
        echo '<p>' . __('This is where you can view the list of books.', 'bms-system') . '</p>';
    }
    public function bmsCreateTable() {
        global $wpdb;
        $table_name = $wpdb->prefix . 'books_systems';
        $charset_collate = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE $table_name (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `name` varchar(255) NOT NULL,
            `author` varchar(255) NOT NULL,
            `profile_image` varchar(255) DEFAULT NULL,
            `book_price` varchar(255) DEFAULT NULL,
            `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }

    public function bmsDropTable() {
        global $wpdb;
        $table_name = $wpdb->prefix . 'books_systems';
        $sql = "DROP TABLE IF EXISTS $table_name;";
        $wpdb->query($sql);
    }

    public function enqueueBMSAssets() {
        wp_enqueue_style('bms-style', BMS_PLUGIN_URL . 'assets/css/style.css', array(), BMS_SYSTEM_VERSION);
        wp_enqueue_script('bms-script', BMS_PLUGIN_URL . 'assets/js/script.js', array('jquery'), BMS_SYSTEM_VERSION, true);
        wp_enqueue_media();
        wp_enqueue_script('bms-validation', BMS_PLUGIN_URL. '/assets/js/jquery.validate.min.js', array('jquery'), BMS_SYSTEM_VERSION, true);
    }
}


