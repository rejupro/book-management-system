<?php

    if ( ! class_exists( 'WP_List_Table' ) ) {
        require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
    }

    class BookListTable extends WP_List_Table {

        public function prepare_items() {
            $this->_column_headers = array($this->get_columns(), [], $this->get_sortable_columns());
            
            $orderby = isset($_GET['orderby']) ? sanitize_text_field($_GET['orderby']) : 'id';
            $order = isset($_GET['order']) ? sanitize_text_field($_GET['order']) : 'ASC';


            $per_page = 2;
            $current_page = $this->get_pagenum();
            $offset = ($current_page - 1) * $per_page;

            $search = isset($_GET['s']) ? $_GET['s'] : false;

            global $wpdb;

            $table_name = $wpdb->prefix . 'books_systems';

           if($search){
                $total_books = $wpdb->get_results(
                    $wpdb->prepare(
                        "SELECT * FROM $table_name WHERE name LIKE %s OR author LIKE %s",
                        '%' . $wpdb->esc_like($search) . '%',
                        '%' . $wpdb->esc_like($search) . '%'
                    ),
                    ARRAY_A
                );
                $total_books = count($total_books);

                $books = $wpdb->get_results(
                    $wpdb->prepare(
                        "SELECT * FROM $table_name WHERE name LIKE %s OR author LIKE %s 
                        ORDER BY {$orderby} {$order}  
                        LIMIT %d, %d",
                        '%' . $wpdb->esc_like($search) . '%',
                        '%' . $wpdb->esc_like($search) . '%',
                        $offset,
                        $per_page
                    ),
                    ARRAY_A
                );
                
           }else{
                $total_books = $wpdb->get_results("SELECT * FROM $table_name", ARRAY_A);
                $total_books = count($total_books);
                $books = $wpdb->get_results(
                        "SELECT * FROM $table_name 
                        ORDER BY {$orderby} {$order}  
                        LIMIT {$offset}, {$per_page}", 
                        ARRAY_A
                );
                
           }

           $this->items = $books;

            $this->set_pagination_args( array(
                'total_items' => $total_books,
                'per_page'    => $per_page,
                'total_pages' => ceil( $total_books / $per_page ),
            ) );

        }


        public function get_columns() {
           $columns = [
                'cb' => '<input type="checkbox" />', // Checkbox for bulk actions
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

        public function column_default($item, $column_name) {
            return isset($item[$column_name]) ? $item[$column_name] : '';
        }

        public function get_sortable_columns() {
            $columns = [
                'id' => ['id', true],
                'name' => ['name', true],
            ];
            return $columns;
        }

        public function column_cb($item) {
            return sprintf(
                '<input type="checkbox" name="book_id[]" value="%d" />',
                $item['id']
            );
        }

        public function column_profile_image($item) {
            if ( ! empty( $item['profile_image'] ) ) {
                return '<img src="' . esc_url( $item['profile_image'] ) . '" alt="' . esc_attr( $item['name'] ) . '" style="width: 80px; height: auto;">';
            }
            return __('No Image', 'bms-system');
        }
    }