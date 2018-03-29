jQuery(document).ready(function($) {

 $('#widgets-right').on('click', '.ups-tb', function(event) {
    event.preventDefault();
    var widget = $(this).parents('.widget');
    console.log(widget);
    widget.find('.ups-tb').removeClass('active');
    $(this).addClass('active');
    widget.find('.ups-tb-item').addClass('ups-hidden-part');
    widget.find('.' + $(this).data('toggle')).removeClass('ups-hidden-part');
  });

});
 jQuery(document).ready(function($){
            var show_thumbnail = $("#<?php echo $this->get_field_id( 'show_thumbnail' ); ?>");
            var thumb_size_wrap = $("#<?php echo $this->get_field_id( 'thumb_size' ); ?>").parents('p');
            // Toggle excerpt length on click
            show_thumbnail.click(function(){
              thumb_size_wrap.toggle('fast');
            });
          });
