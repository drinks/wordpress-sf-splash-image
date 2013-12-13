(function($){
  $(function(){
    var postID = $('#post_ID').attr('value');
    $(document).on('autosave-enable-buttons', function(){
      console.log('loading meta box...');
      $('#sf_splash_image .inside').load('/wp-content/plugins/sf-splash-image/ajax.php?action=update_meta_box&post_id=' + postID);
    });
  });
})(jQuery);