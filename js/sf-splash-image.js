(function($){
  // Magic value for prompting user input
  var WILDCARD_VAL = 'OTHER';

  // Set up our subclass of selectBoxIt, to be initialized from the Meta Box.
  $.widget('sf-splash-image.SFSelectBox', $.selectBox.selectBoxIt, {
    _update: function(elem) {
      /* Runs when a list item is selected.
       *
       * Prompts for a URL if you selected WILDCARD_VAL, adds it
       * to the list and selects it.
       */
      var self = this,
          sup = function(val){
            var elem = self.list.find("li[data-val='" + val + "']");
            $.selectBox.selectBoxIt.prototype._update.call(self, elem);
          },
          pathParts,
          oldVal = this.selectBox.val(),
          newVal,
          val = elem.attr('data-val');
      if(val == WILDCARD_VAL){
        newVal = prompt('Enter the URL:');
        val = newVal ? newVal : oldVal;
        if(val != oldVal){
          pathParts = val.split('/')
          this.add({value: val, text: pathParts[pathParts.length - 1]}, function(){
            sup(val);
          });
        }
      }
      sup(val);
    },
    open: function(){
      /* Runs when the list is shown
       * Checks for a list item with WILDCARD_VAL, adds if it doesn't exist.
       */
      var moreButton = this.list.find("li[data-val='" + WILDCARD_VAL + "']");
      if(!moreButton.length){
        this.add({value: WILDCARD_VAL, text: 'Add new...'});
      }
      $.selectBox.selectBoxIt.prototype.open.call(this);
    },
    close: function(){
      /* Runs when the list is closed, after an item is selected.
       * Re-orders select items so that WILDCARD_VAL is last.
       */
      $.selectBox.selectBoxIt.prototype.close.call(this);
      function cmp(a, b){
        if($(a).val() == WILDCARD_VAL) return 1;
        if($(b).val() == WILDCARD_VAL) return -1;
      }
      this.selectBox.find('option')
                    .sort(cmp)
                    .appendTo(this.selectBox);
      this.refresh();
    }
  });

  // Do dropdown list popovers
  $(function(){
    $("#sf_splash_image_container").on('mouseenter mouseleave', '[rel="popover"]', function(evt){
      if(evt.type == 'mouseenter'){
        $(this).popover({
          trigger: "hover",
          container: "#sf_splash_image_container",
          html: true
        }).popover('show');
      }else{
        $(this).popover('destroy');
      }
    });

    // Refresh the meta box when autosave completes, in case new images were added.
    var postID = $('#post_ID').attr('value');
    $(document).on('autosave-enable-buttons', function(){
      window.console && console.log('reloading meta box...');
      $('#sf_splash_image .inside').load('/wp-content/plugins/sf-splash-image/ajax.php?action=update_meta_box&post_id=' + postID);
    });
  });
})(jQuery);