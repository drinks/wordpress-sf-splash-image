<?php
try {
    require_once(dirname(dirname(dirname(dirname($_SERVER['SCRIPT_FILENAME'])))) . '/wp-config.php');
} catch (Exception $e) {
    require_once(dirname(dirname(dirname(dirname(dirname($_SERVER['SCRIPT_FILENAME']))))) . '/wp-config.php');
}
$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : false;
$post_id = (isset($_REQUEST['post_id']) && ctype_digit($_REQUEST['post_id'])) ? $_REQUEST['post_id'] : false;
if (isset($SFSplashImage)):
    switch($action):
        case 'update_meta_box':
        default:
            $SFSplashImage->render_meta_box(get_post($post_id));
        break;
    endswitch;
endif;
