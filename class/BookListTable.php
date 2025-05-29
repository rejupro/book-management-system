<?php

    if ( ! class_exists( 'WP_List_Table' ) ) {
        require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
    }

    class BookListTable extends WP_List_Table {
        
        public function prepare_items() {
            $this->_column_headers = array($this->get_columns(), [], $this->get_sortable_columns());
            
            $orderby = isset($_GET['orderby']) ? sanitize_text_field($_GET['orderby']) : 'id';
            $order = isset($_GET['order']) ? sanitize_text_field($_GET['order']) : 'ASC';


            $per_page = 5;
            $current_page = $this->get_pagenum();
            $offset = ($current_page - 1) * $per_page;

            $search = isset($_GET['s']) ? $_GET['s'] : false;

            $row_action = $this->current_action();

            $current_action = isset($_GET['status']) ? sanitize_text_field($_GET['status']) : "";
            if($current_action === 'show_all'){
                $row_action = 'all';
            } elseif($current_action === 'show_published'){
                $row_action = 'published';
            } elseif($current_action === 'show_trash'){
                $row_action = 'trashpage';
            } else {
                $row_action = '';
            }
            if ($row_action ==='published') {
                $condition = "WHERE is_trash = 0";    
            }else if ($row_action === 'trashpage') {
                $condition = "WHERE is_trash = 1";
            } else {
                $condition = '';
            }

            if(!empty($row_action) && $row_action === 'trash'){
                $this->process_row_action($row_action);
            }

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
                $total_books = $wpdb->get_results("SELECT * FROM $table_name {$condition}", ARRAY_A);
                $total_books = count($total_books);

                $books = $wpdb->get_results(
                    "SELECT * FROM $table_name 
                    {$condition} 
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

        public function get_bulk_actions(){
            $actions = [
                'edit' => __('Edit', 'bms-system'),
                'trash' => __('Move to Trash', 'bms-system'),
            ];
            return $actions;
        }

        // public function handle_row_actions($item, $column_name, $primary) {
        //     $actions = [];
        //     if($column_name !== $primary) {
        //         return "";
        //     }
        //     $actions['edit'] = "<a href='#'>Edit</a>";
        //     $actions['quick_edit'] = "<a href='#'>Quick Edit</a>";
        //     $actions['trash'] = "<a href='#'>Move to Trash</a>";
        //     $actions['view'] = "<a href='#'>View</a>";
        //     return $this->row_actions($actions);
        // }

        public function column_name($item) {
            $actions = [];
            $actions['edit'] = "<a href='#'>Edit</a>";
            $actions['quick_edit'] = "<a href='#'>Quick Edit</a>";
            $actions['trash'] = "<a onclick='return confirm(\"Are you sure to delete\")' href='admin.php?page=book-list&action=trash&book_id=".$item['id']."'>Move to Trash</a>";
            $actions['view'] = "<a href='#'>View</a>";
            return sprintf(
                '<strong>%s</strong> %s',
                esc_html($item['name']),
                $this->row_actions($actions)
            );
        }

        private function process_row_action($action_type) {
            global $wpdb;
            $table_name = $wpdb->prefix . 'books_systems';
            $bookId = isset($_GET['book_id']) ? $_GET['book_id'] : "";

            if(is_array($bookId)){
                foreach($bookId as $id){
                    $wpdb->update(
                        $table_name,
                        ['is_trash' => 1],
                        ['id' => $id],
                        ['%s'],
                        ['%d']
                    );
                }
            }else{
                $wpdb->update(
                    $table_name,
                    ['is_trash' => 1],
                    ['id' => $bookId],
                    ['%s'],
                    ['%d']
                );
                
            }
            ?>
                <script>
                    window.location.href = "<?php echo admin_url('admin.php?page=book-list'); ?>";
                </script>
            <?php
        }

        public function extra_tablenav($position){
            if($position === 'top'){
                global $wpdb;
                $table_name = $wpdb->prefix . 'books_systems';
               $status_link = array(
                'all' => count($wpdb->get_results("SELECT * FROM $table_name", ARRAY_A)),
                'published' => count($wpdb->get_results("SELECT * FROM $table_name WHERE is_trash = 0 ", ARRAY_A)),
                'trash' => count($wpdb->get_results("SELECT * FROM $table_name WHERE is_trash = 1", ARRAY_A)),
               );

               $className = "";
            //    $current_action = $this->current_action();
                $current_action = isset($_GET['status']) ? sanitize_text_field($_GET['status']) : "";

                echo '<div class="alignleft actions">';
                echo '<ul class="subsubsub status-links">';
                foreach($status_link as $status => $count){
                    if($current_action === 'show_' . $status){
                        $className = 'current';
                    } else {
                        $className = 'something wrong';
                    }
                    echo '<li><a href="' . admin_url('admin.php?page=book-list&status=show_' . $status) . '" class="' . $className . '">' . ucfirst($status) . ' <span class="count">(' . $count . ')</span></a> | </li>';
                }
                echo '</ul>';
                echo '</div>';
            }
        }
    }