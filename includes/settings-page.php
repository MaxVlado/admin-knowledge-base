<?php

function akb_register_settings() {
    add_option( 'akb_settings', array(
        'entries_per_page' => 10,
        'admin_email'      => get_option( 'admin_email' ),
    ) );

    register_setting(
        'akb_settings_group',
        'akb_settings',
        'akb_sanitize_settings'
    );

    add_settings_section(
        'akb_general_section',
        esc_html__( 'General Settings', 'admin-knowledge-base' ),
        'akb_render_general_section',
        'akb_settings_page'
    );

    add_settings_field(
        'akb_entries_per_page',
        esc_html__( 'Entries per page', 'admin-knowledge-base' ),
        'akb_render_entries_per_page_field',
        'akb_settings_page',
        'akb_general_section'
    );

    add_settings_field(
        'akb_admin_email',
        esc_html__( 'Admin Email', 'admin-knowledge-base' ),
        'akb_render_admin_email_field',
        'akb_settings_page',
        'akb_general_section'
    );
}
add_action( 'admin_init', 'akb_register_settings' );

function akb_sanitize_settings( $input ) {
    $sanitized_input = array();

    if ( isset( $input['entries_per_page'] ) ) {
        $sanitized_input['entries_per_page'] = absint( $input['entries_per_page'] );
    }

    if ( isset( $input['admin_email'] ) ) {
        $sanitized_input['admin_email'] = sanitize_email( $input['admin_email'] );
    }

    return $sanitized_input;
}

function akb_render_general_section() {
    // Render section description or leave it empty
}

function akb_render_entries_per_page_field() {
    $options = get_option( 'akb_settings' );
    $entries_per_page = isset( $options['entries_per_page'] ) ? $options['entries_per_page'] : 10;
    ?>
    <input type="number" name="akb_settings[entries_per_page]" value="<?php echo esc_attr( $entries_per_page ); ?>" min="1" max="100" required>
    <?php
}

function akb_render_admin_email_field() {
    $options = get_option( 'akb_settings' );
    $admin_email = isset( $options['admin_email'] ) ? $options['admin_email'] : get_option( 'admin_email' );
    ?>
    <input type="email" name="akb_settings[admin_email]" value="<?php echo esc_attr( $admin_email ); ?>" required>
    <?php
}

function akb_add_settings_page() {
    add_options_page(
        esc_html__( 'Knowledge Base Settings', 'admin-knowledge-base' ),
        esc_html__( 'Knowledge Base', 'admin-knowledge-base' ),
        'manage_options',
        'akb_settings_page',
        'akb_render_settings_page'
    );
}
add_action( 'admin_menu', 'akb_add_settings_page' );

function akb_render_settings_page() {
    ?>
    <div class="wrap">
        <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
        <form action="options.php" method="post">
            <?php
            settings_fields( 'akb_settings_group' );
            do_settings_sections( 'akb_settings_page' );
            submit_button( esc_html__( 'Save Settings', 'admin-knowledge-base' ) );
            ?>
        </form>
    </div>
    <?php
}