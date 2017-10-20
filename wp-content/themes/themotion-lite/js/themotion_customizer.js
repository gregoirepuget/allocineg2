
/********************************************
*** General Repeater ***
*********************************************/

function themotion_uniqid(prefix, more_entropy) {

    if (typeof prefix === 'undefined') {
        prefix = '';
    }

    var retId;
    var formatSeed = function(seed, reqWidth) {
        seed = parseInt(seed, 10)
            .toString(16); // to hex str
        if (reqWidth < seed.length) { // so long we split
            return seed.slice(seed.length - reqWidth);
        }
        if (reqWidth > seed.length) { // so short we pad
            return Array(1 + (reqWidth - seed.length))
                    .join('0') + seed;
        }
        return seed;
    };

    // BEGIN REDUNDANT
    if (!this.php_js) {
        this.php_js = {};
    }
    // END REDUNDANT
    if (!this.php_js.uniqidSeed) { // init seed with big random int
        this.php_js.uniqidSeed = Math.floor(Math.random() * 0x75bcd15);
    }
    this.php_js.uniqidSeed++;

    retId = prefix; // start with prefix, add current milliseconds hex string
    retId += formatSeed(parseInt(new Date()
            .getTime() / 1000, 10), 8);
    retId += formatSeed(this.php_js.uniqidSeed, 5); // add seed hex string
    if (more_entropy) {
        // for more entropy we add a float lower to 10
        retId += (Math.random() * 10)
            .toFixed(8)
            .toString();
    }

    return retId;
}


function themotion_refresh_general_control_values(){
    'use strict';
	jQuery('.themotion_general_control_repeater').each(function(){
		var values = [];
		var th = jQuery(this);
		th.find('.themotion_general_control_repeater_container').each(function(){
			var link = jQuery(this).find('.themotion_link_control').val();
            var text = jQuery(this).find('.themotion_text_control').val();
            var id = jQuery(this).find('.themotion_box_id').val();
            if( link !== '' && text !== '' ){
                values.push({
                    'link' : link,
                    'text' : text,
                    'id' : id
                });
            }
        });
        th.find('.themotion_repeater_colector').val(JSON.stringify(values));
        th.find('.themotion_repeater_colector').trigger('change');
    });
}




jQuery(document).ready(function(){

    'use strict';
/* Dropdown control */
  jQuery('#customize-theme-controls').on('click','.themotion-customize-control-title',function(){
      jQuery(this).next().slideToggle('medium', function() {
          if (jQuery(this).is(':visible')){
              jQuery(this).css('display','block');
          }
      });
  });


	jQuery('.themotion_general_control_new_field').on('click',function(){

		var th = jQuery(this).parent();
		var id = 'themotion_' + themotion_uniqid();
		if(typeof th !== 'undefined') {

            var field = th.find('.themotion_general_control_repeater_container:first').clone();
            if(typeof field !== 'undefined'){

                field.find('.themotion_general_control_remove_field').show();
				field.find('.themotion_box_id').val(id);
                field.find('.themotion_link_control').val('');
                field.find('.themotion_text_control').val('');
                th.find('.themotion_general_control_repeater_container:first').parent().append(field);
                themotion_refresh_general_control_values();
            }
		}
		return false;
	 });

	jQuery('#customize-theme-controls').on('click', '.themotion_general_control_remove_field',function(){
		if( typeof	jQuery(this).parent() !== 'undefined'){
			jQuery(this).parent().parent().remove();
			themotion_refresh_general_control_values();
		}
		return false;
	});

	jQuery('#customize-theme-controls').on('keyup', '.themotion_text_control',function(){
		themotion_refresh_general_control_values();
	});

    jQuery('#customize-theme-controls').on('keyup', '.themotion_link_control',function(){
        themotion_refresh_general_control_values();
    });

	/*Drag and drop to change icons order*/
	jQuery('.themotion_general_control_droppable').sortable({
		update: function(  ) {
			themotion_refresh_general_control_values();
		}
	});
});