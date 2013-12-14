<?php
if (!class_exists('WP_PluginBase')) require_once('lib/wp-plugin-base/wp-plugin-base.php');
if (!class_exists('SFSplashImage')){

    class SFSplashImage extends WP_PluginBase {

        var $namespace = 'sf_splash_image',
            $title = 'SF Splash Image',
            $capability = 'manage_options',
            $meta_key = '_sf_splash_image',
            $settings = array(
                'url_match_regexp' =>
                    array('name' => 'URL Match RegExp',
                          'type' => 'text',
                          'value' => '/([a-f0-9_]+)-[\d]+(sq)?/'),
                'url_replacement_value' =>
                    array('name' => 'URL Replacement Value',
                          'type' => 'text',
                          'value' => '$1-180sq'),
            );

        function SFSplashImage() {
            $this->__construct();
        }

        function __construct() {
            /* WP events this plugin cares about */
            add_action('admin_enqueue_scripts', array(&$this, 'enqueue_scripts'));
            add_action('admin_init',            array(&$this, 'add_meta_box'));
            add_action('save_post',             array(&$this, 'handle_save_post'));

            parent::__construct();
        }


        // plugin methods

        function enqueue_scripts($hook) {
            if (!in_array($hook, array('edit.php', 'post.php', 'settings_page_sf_splash_image_settings_page'))) return;

            // css & js
            wp_register_style('bootstrap-scoped', plugins_url(basename(dirname(__FILE__)) . "/css/bootstrap-scoped.min.css"));
            wp_register_script('bootstrap', plugins_url(basename(dirname(__FILE__)) . "/js/bootstrap.min.js"), array('jquery'), '3.0.3', true);
            wp_register_style('jquery-selectBoxIt', plugins_url(basename(dirname(__FILE__)) . "/css/jquery.selectBoxIt.css"));
            wp_register_script('jquery-selectBoxIt', plugins_url(basename(dirname(__FILE__)) . "/js/jquery.selectBoxIt.min.js"), array('jquery', 'jquery-ui-core', 'jquery-ui-widget', 'bootstrap'), '3.6.0', true);
            wp_register_script('sf-splash-image', plugins_url(basename(dirname(__FILE__)) . "/js/sf-splash-image.js"), array('jquery-selectBoxIt'), false, true);

            wp_enqueue_style('jquery-selectBoxIt');
            wp_enqueue_style('bootstrap-scoped');
            wp_enqueue_script('sf-splash-image');
        }

        function add_meta_box() {
            add_meta_box($this->namespace,
                         __('Choose a Splash Image', $this->namespace . '_textdomain'),
                         array(&$this, 'render_meta_box'),
                         'post', 'normal', 'core');
        }

        function render_meta_box($post) {
            if(!current_user_can('edit_post', $post->ID))
                return;

            $context = array(
                'images' => $this->get_image_array($post),
                'selected_image' => get_post_meta($post->ID, $this->meta_key, true),
                'meta_key' => $this->meta_key,
                'namespace' => $this->namespace,
            );
            uTemplate::render(dirname(__FILE__) . '/templates/meta_box.php', $context);
        }

        function handle_save_post($post_id) {
            if (wp_is_post_revision($post_id ))
                return;
            if(!wp_verify_nonce($_POST[$this->namespace . '_nonce'], plugin_basename(dirname(__FILE__))))
                return;
            if(!current_user_can('edit_post', $post_id))
                return;

            update_post_meta($post_id, $this->meta_key, $_POST[$this->meta_key]);
        }


        // helper methods

        function get_splash_url($path) {
            return preg_replace($this->setting('url_match_regexp'), $this->setting('url_replacement_value'), $path);
        }

        function get_image_array($post) {
            $content = apply_filters('the_content', $post->post_content);
            $pat = '/<img [^>]*src=["\']([^"\']+\.(png|gif|jpe?g))["\'][^>]*>/';
            preg_match_all($pat, $content, $matches);
            return array_map(array(&$this, 'get_splash_url'), $matches[1]);
        }

    }

}