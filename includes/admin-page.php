<?php

function akb_add_admin_menu() {
    add_menu_page(
        esc_html__( 'Knowledge Base', 'admin-knowledge-base' ),
        esc_html__( 'Knowledge Base', 'admin-knowledge-base' ),
        'edit_posts',
        'akb_knowledge_base',
        'akb_render_admin_page',
        'dashicons-book-alt',
        30
    );
}
add_action( 'admin_menu', 'akb_add_admin_menu' );

function akb_render_admin_page() {
    if ( ! current_user_can( 'edit_knowledge_base' ) ) {
        wp_die( esc_html__( 'You do not have sufficient permissions to access this page.', 'admin-knowledge-base' ) );
    }

    $current_page = isset( $_GET['paged'] ) ? absint( $_GET['paged'] ) : 1;
    $search_query = isset( $_GET['s'] ) ? sanitize_text_field( $_GET['s'] ) : '';

    $entries_per_page = akb_get_entries_per_page();

    $args = array(
        'post_type'      => 'knowledge_base',
        'posts_per_page' => $entries_per_page,
        'paged'          => $current_page,
        's'              => $search_query,
    );

    $query = new WP_Query( $args );
    ?>
    <div class="wrap">
        <h1 class="wp-heading-inline"><?php esc_html_e( 'Knowledge Base Entries', 'admin-knowledge-base' ); ?></h1>
        <a href="<?php echo esc_url( admin_url( 'post-new.php?post_type=knowledge_base' ) ); ?>" class="page-title-action"><?php esc_html_e( 'Add New', 'admin-knowledge-base' ); ?></a>
        <hr class="wp-header-end">

        <form method="get">
            <p class="search-box">
                <label for="akb-search-input"><?php esc_html_e( 'Search Entries', 'admin-knowledge-base' ); ?>:</label>
                <input type="search" id="akb-search-input" name="s" value="<?php echo esc_attr( $search_query ); ?>">
                <input type="hidden" name="page" value="akb_knowledge_base">
                <input type="submit" id="search-submit" class="button" value="<?php esc_attr_e( 'Search', 'admin-knowledge-base' ); ?>">
            </p>
        </form>

        <table class="wp-list-table widefat fixed striped">
            <thead>
            <tr>
                <th scope="col"><?php esc_html_e( 'Title', 'admin-knowledge-base' ); ?></th>
                <th scope="col"><?php esc_html_e( 'Author', 'admin-knowledge-base' ); ?></th>
                <th scope="col"><?php esc_html_e( 'Date', 'admin-knowledge-base' ); ?></th>
            </tr>
            </thead>
            <tbody>
            <?php
            if ( $query->have_posts() ) :
                while ( $query->have_posts() ) :
                    $query->the_post();
                    ?>
                    <tr>
                        <td>
                            <strong><a href="<?php echo esc_url( get_edit_post_link() ); ?>"><?php the_title(); ?></a></strong>
                            <div class="row-actions">
                                <span class="edit"><a href="<?php echo esc_url( get_edit_post_link() ); ?>"><?php esc_html_e( 'Edit', 'admin-knowledge-base' ); ?></a> | </span>
                                <span class="trash"><a href="<?php echo esc_url( get_delete_post_link() ); ?>"><?php esc_html_e( 'Trash', 'admin-knowledge-base' ); ?></a></span>
                            </div>
                        </td>
                        <td><?php the_author(); ?></td>
                        <td><?php echo esc_html( get_the_date() ); ?></td>
                    </tr>
                <?php
                endwhile;
                wp_reset_postdata();
            else :
                ?>
                <tr>
                    <td colspan="3"><?php esc_html_e( 'No entries found.', 'admin-knowledge-base' ); ?></td>
                </tr>
            <?php
            endif;
            ?>
            </tbody>
        </table>

        <?php
        $total_pages = $query->max_num_pages;

        if ( $total_pages > 1 ) {
            $current_url = set_url_scheme( 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] );
            $current_url = remove_query_arg( 'paged', $current_url );

            $pagination_args = array(
                'base'               => add_query_arg( 'paged', '%#%', $current_url ),
                'format'             => '',
                'total'              => $total_pages,
                'current'            => $current_page,
                'show_all'           => false,
                'end_size'           => 1,
                'mid_size'           => 2,
                'prev_next'          => true,
                'prev_text'          => __( '&laquo; Previous', 'admin-knowledge-base' ),
                'next_text'          => __( 'Next &raquo;', 'admin-knowledge-base' ),
                'type'               => 'plain',
                'add_args'           => false,
                'add_fragment'       => '',
                'before_page_number' => '',
                'after_page_number'  => '',
            );

            echo '<div class="pagination-links">' . paginate_links( $pagination_args ) . '</div>';
        }
        ?>
    </div>
    <?php
}

function akb_get_entries_per_page() {
    $options = get_option( 'akb_settings' );
    return isset( $options['entries_per_page'] ) ? absint( $options['entries_per_page'] ) : 10;
}