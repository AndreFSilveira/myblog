<?php
/*
Plugin Name: Widget Instagram
Description: Box do Instagram
Author: Agência de Intenet PONTOCOM
Author URI: http://agenciadeinternet.com
*/

class Instagram extends WP_Widget{
	public function __construct(){
		parent::WP_Widget('post_instagram_widget','Instagram', array('description' => 'box do Instagram'));
	}
	
	public function widget($args, $instance){ ?>
	
		<div class="clear"></div>
		<div class="sidebar-social">
			<h1 class="h1-sidebar">Instagram <span>@<?php echo get_option ('_theme_instagram_user'); ?></span></h1>
			<ul id="instagram"></ul>
		</div>
	<script>
		window.onload=function(){
			jQuery.ajax({
				type : "GET",
				dataType : "jsonp",
				url : "https://api.instagram.com/v1/users/"+instagramID+"/media/recent?client_id=be52cb013dda4c47a03cdd5689896c37&count=4&callback=?",

				success : function(resp) {

				jQuery("#instagram").append("");

				jQuery.each(resp.data, function(i,photos){

				var photo = photos.images.thumbnail.url,
				url = photos.link;

				jQuery("#instagram").append("<li><a href='"+url+"' target='_blank'><img src='"+photo+"' /></a></li>");

				});

				}

			});
		}
	</script>
		<?php

		/*colar esse codigo no header:
		
		<script>
			var theme_url = "<?php bloginfo('stylesheet_directory') ?>";
			var instagramID = "<?php echo get_option ('_theme_instagram_user_id'); ?>";
		</script>
		
		*/
	}
}
add_action( 'widgets_init', create_function( '', 'return register_widget( "Instagram" );' ) );
