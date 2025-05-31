<?php

class BookManagement {
   
    private $message = '';

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
        
        $this->handleBookFormSubmission();
        $response = $this->message;
        include_once BMS_PLUGIN_PATH . 'pages/add-book.php';
        ob_start();
        $contents = ob_get_contents();
        ob_end_clean();
        echo $contents;
    }
    private function handleBookFormSubmission() {
        if (!isset($_POST['btn_form_submit'])) {
            return;
        }
        global $wpdb;
        $table_name = $wpdb->prefix . 'books_systems';

        $book_name = sanitize_text_field($_POST['book_name']);
        $author_name = sanitize_text_field($_POST['author_name']);
        $book_price = sanitize_text_field($_POST['book_price']);
        $cover_image = esc_url_raw($_POST['cover_image']);

    
        $data = array(
            'name' => $book_name,
            'author' => $author_name,
            'profile_image' => $cover_image,
            'book_price' => $book_price,
        );

        $inserted = $wpdb->insert($table_name, $data);

        if ($inserted) {
            $this->message = __('Book added successfully!', 'bms-system');
        } else {
            $this->message = __('Failed to add book. Please try again.', 'bms-system');
        }
    }

    private function singleBookView($book_id) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'books_systems';

        $book_id = intval($book_id);
        $book = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE id = %d", $book_id));
        if ($book) {
            return $book;
        } else {
            return null;
        }
    }

    public function renderBookListPage() {

        $action = isset($_GET['action']) ? sanitize_text_field($_GET['action']) : '';
        $book_id = isset($_GET['book_id']) ? intval($_GET['book_id']) : 0;
        if($action == 'view_book' && $book_id > 0) {
            $book = $this->singleBookView($book_id);
            include_once BMS_PLUGIN_PATH .  'pages/book-view.php';
            ob_start();
            $contents = ob_get_contents();
            ob_end_clean();
            echo $contents;
        }elseif($action == 'edit_book' && $book_id > 0) {
            

            if(isset($_POST['btn_form_submit'])) {
                global $wpdb;
                $table_name = $wpdb->prefix . 'books_systems';

                $wpdb->update(
                    $table_name,
                    array(
                        'name' => sanitize_text_field($_POST['book_name']),
                        'author' => sanitize_text_field($_POST['author_name']),
                        'book_price' => sanitize_text_field($_POST['book_price']),
                        'profile_image' => esc_url_raw($_POST['cover_image'])
                    ),
                    array('id' => $book_id)
                );
                $this->message = __('Book updated successfully!', 'bms-system');
            } 
            
            

            $book = $this->singleBookView($book_id);
            include_once BMS_PLUGIN_PATH .  'pages/edit-book.php';
            ob_start();
            $contents = ob_get_contents();
            ob_end_clean();
            echo $contents;

        }else{
            include_once BMS_PLUGIN_PATH .  'pages/book-list.php';
            ob_start();
            $contents = ob_get_contents();
            ob_end_clean();
            echo $contents;
        }

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
            `is_trash` INT NOT NULL DEFAULT '0',
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

        $data = 'var bms_plugin_ajax_url = "' . esc_url(admin_url('admin-ajax.php')) . '";';
        wp_add_inline_script('bms-script', $data, 'before');


    }
    public function bmsEditBook() {
        global $wpdb;
        $table_name = $wpdb->prefix . 'books_systems';

        $book_id = intval($_POST['book_id']);
        $book_name = sanitize_text_field($_POST['book_name']);
        $author_name = sanitize_text_field($_POST['author_name']);
        $book_price = sanitize_text_field($_POST['book_price']);

        $data = array(
            'name' => $book_name,
            'author' => $author_name,
            'book_price' => $book_price,
        );

        $where = array('id' => $book_id);

        $updated = $wpdb->update($table_name, $data, $where);

        if ($updated !== false) {
            wp_send_json_success(__('Book updated successfully!', 'bms-system'));
        } else {
            wp_send_json_error(__('Failed to update book. Please try again.', 'bms-system'));
        }
    }
}


