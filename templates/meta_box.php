<?php
// Use nonce for verification
wp_nonce_field(plugin_basename(dirname(dirname(__FILE__))), $namespace . '_nonce' );
?>
<div class="bootstrap-enabled" id="sf_splash_image_container">

    <select name="<?php echo $meta_key ?>" id="sf_splash_image_select" rel="selectboxit">
        <option value="">--</option>
        <?php foreach ($images as $url) : ?>
            <?php $filename = array_reverse(explode('/', $url))[0]; ?>
            <option value="<?php echo $url; ?>" rel="popover"
                    <?php if ($url == $selected_image): ?>selected<?php endif; ?>
                    data-content="<?php echo htmlentities("<img src=\"$url\" width=\"180\" />"); ?>">
                <?php echo $filename; ?>
            </option>
        <?php endforeach; ?>
    </select>
</div>
<script>
(function($){
    $(function(){
        $("[rel='selectboxit']").selectBoxIt();
        $("[rel='popover']").popover({ trigger: "hover", container: "#sf_splash_image_container", html: true });
    });
})(jQuery);
</script>

