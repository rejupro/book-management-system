<?php

    if ( ! class_exists( 'WP_List_Table' ) ) {
        require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
    }

    class BookListTable extends WP_List_Table {

        public function prepare_items() {
            $this->_column_headers = array($this->get_columns());
        }


        public function get_columns() {
           $columns = [
                'id' => __('ID', 'bms-system'),
                'name' => __('Name', 'bms-system'),
                'author' => __('Author', 'bms-system'),
                'profile_image' => __('Cover Image', 'bms-system'),
                'book_price' => __('Price', 'bms-system'),
                'created_at' => __('Created At', 'bms-system'),
            ];
            return $columns;
        }

        public function no_items() {
            echo __('No books found.', 'bms-system');
        }
    }