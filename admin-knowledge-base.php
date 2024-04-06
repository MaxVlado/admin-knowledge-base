<?php
/*
Plugin Name: Knowledge Base for Administrators
Plugin URI: http://housemagik.com/plugins/admin-knowledge-base
Description: Allows administrators to create free-style posts
Version: 1.0
Author: V. Kirillov HousemagiK
Author URI: hhttp://housemagik.com/author

Copyright 2024  V. Kirillov  (email: netdesopgame@gmail.com)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

function akb_register_post_type() {
    $labels = array(
        'name'               => esc_html__( 'Knowledge Base', 'admin-knowledge-base' ),
        'singular_name'      => esc_html__( 'Knowledge Base Entry', 'admin-knowledge-base' ),
        'menu_name'          => esc_html__( 'Knowledge Base', 'admin-knowledge-base' ),
        'name_admin_bar'     => esc_html__( 'Knowledge Base Entry', 'admin-knowledge-base' ),
        'add_new'            => esc_html__( 'Add New', 'admin-knowledge-base' ),
        'add_new_item'       => esc_html__( 'Add New Entry', 'admin-knowledge-base' ),
        'new_item'           => esc_html__( 'New Entry', 'admin-knowledge-base' ),
        'edit_item'          => esc_html__( 'Edit Entry', 'admin-knowledge-base' ),
        'view_item'          => esc_html__( 'View Entry', 'admin-knowledge-base' ),
        'all_items'          => esc_html__( 'All Entries', 'admin-knowledge-base' ),
        'search_items'       => esc_html__( 'Search Entries', 'admin-knowledge-base' ),
        'parent_item_colon'  => esc_html__( 'Parent Entry:', 'admin-knowledge-base' ),
        'not_found'          => esc_html__( 'No entries found.', 'admin-knowledge-base' ),
        'not_found_in_trash' => esc_html__( 'No entries found in Trash.', 'admin-knowledge-base' )
    );

    $args = array(
        'labels'             => $labels,
        'description'        => esc_html__( 'Knowledge Base entries for administrators', 'admin-knowledge-base' ),
        'public'             => false,
        'publicly_queryable' => false,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array( 'slug' => 'knowledge-base' ),
        'capability_type'    => 'post',
        'has_archive'        => false,
        'hierarchical'       => false,
        'menu_position'      => null,
        'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments' ),
        'map_meta_cap'       => true,
        'capabilities'       => array(
            'create_posts' => 'create_knowledge_base',
            'edit_posts'   => 'edit_knowledge_base',
            'delete_posts' => 'delete_knowledge_base',
        ),
    );

    register_post_type( 'knowledge_base', $args );
}
add_action( 'init', 'akb_register_post_type' );

require_once plugin_dir_path( __FILE__ ) . 'includes/settings-page.php';
require_once plugin_dir_path( __FILE__ ) . 'includes/admin-page.php';
require_once plugin_dir_path( __FILE__ ) . 'includes/post-management.php';

function akb_add_admin_capabilities() {
    $role = get_role( 'administrator' );

    if ( ! $role->has_cap( 'create_knowledge_base' ) ) {
        $role->add_cap( 'create_knowledge_base' );
    }

    if ( ! $role->has_cap( 'edit_knowledge_base' ) ) {
        $role->add_cap( 'edit_knowledge_base' );
    }

    if ( ! $role->has_cap( 'delete_knowledge_base' ) ) {
        $role->add_cap( 'delete_knowledge_base' );
    }
}
register_activation_hook( __FILE__, 'akb_add_admin_capabilities' );

function akb_remove_admin_capabilities() {
    $role = get_role( 'administrator' );

    if ( $role->has_cap( 'create_knowledge_base' ) ) {
        $role->remove_cap( 'create_knowledge_base' );
    }

    if ( $role->has_cap( 'edit_knowledge_base' ) ) {
        $role->remove_cap( 'edit_knowledge_base' );
    }

    if ( $role->has_cap( 'delete_knowledge_base' ) ) {
        $role->remove_cap( 'delete_knowledge_base' );
    }
}
register_deactivation_hook( __FILE__, 'akb_remove_admin_capabilities' );


function akb_enqueue_admin_assets() {
    wp_enqueue_style( 'akb-admin-style', plugin_dir_url( __FILE__ ) . 'assets/css/admin-knowledge-base.css', array(), '1.0.0' );
    wp_enqueue_script( 'akb-admin-script', plugin_dir_url( __FILE__ ) . 'assets/js/admin-knowledge-base.js', array( 'jquery' ), '1.0.0', true );
}
add_action( 'admin_enqueue_scripts', 'akb_enqueue_admin_assets' );