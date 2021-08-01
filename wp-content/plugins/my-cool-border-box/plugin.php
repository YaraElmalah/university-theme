<?php

/**
 * Plugin Name: My Cool Border Box
 * Author: Yara Elmalah
 * Version: 1.0.0
 */
function loadMyBlockFiles()
{
    wp_enqueue_script(
        'my-super-unique-handle',
        plugin_dir_url(__FILE__) . 'my-block.js',
        array('wp-blocks', 'wp-i18n', 'wp-editor'),
        true
    ); //to include dependencies for Gutenberg
}

add_action('enqueue_block_editor_assets', 'loadMyBlockFiles');
function register_gutenberg_meta()
{
    register_meta('post', 'author', array(
        'show_in_rest' => true
    ));
}
add_action('init', 'register_gutenberg_meta');
