<?php

function akb_save_post_meta( $post_id ) {
    if ( isset( $_POST['akb_meta_box_nonce'] ) && wp_verify_nonce( $_POST['akb_meta_box_nonce'], 'akb_save_post_meta' ) ) {
        if ( isset( $_POST['akb_post_meta'] ) ) {
            $post_meta = sanitize_text_field( $_POST['akb_post_meta'] );
            update_post_meta( $post_id, 'akb_post_meta', $post_meta );
        }
    }
}
add_action( 'save_post_knowledge_base', 'akb_save_post_meta' );

function akb_add_post_meta_box() {
    add_meta_box(
        'akb_post_meta_box',
        esc_html__( 'Additional Information', 'admin-knowledge-base' ),
        'akb_render_post_meta_box',
        'knowledge_base',
        'normal',
        'default'
    );
}
add_action( 'add_meta_boxes_knowledge_base', 'akb_add_post_meta_box' );

function akb_render_post_meta_box( $post ) {
    $post_meta = get_post_meta( $post->ID, 'akb_post_meta', true );
    wp_nonce_field( 'akb_save_post_meta', 'akb_meta_box_nonce' );
    ?>
    <label for="akb_post_meta"><?php esc_html_e( 'Additional Information', 'admin-knowledge-base' ); ?></label>
    <textarea id="akb_post_meta" name="akb_post_meta" rows="4" cols="50"><?php echo esc_textarea( $post_meta ); ?></textarea>
    <?php
}