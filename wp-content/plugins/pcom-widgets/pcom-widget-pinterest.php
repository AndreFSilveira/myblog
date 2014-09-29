<?php
/*
Plugin Name: Widget Pinterest
Description: Box do Pinterest
Author: Agência de Intenet PONTOCOM
Author URI: http://agenciadeinternet.com
*/

class Pinterest extends WP_Widget{
	public function __construct(){
		parent::WP_Widget('post_pinterest_widget','Pinterest', array('description' => 'box do Pinterest'));
	}
	
	public function widget($args, $instance){
		?>
		<div class="clear"></div>
		<div class="sidebar-social">
			<h1 class="h1-sidebar">Pinterest</h1>
			<a data-pin-do="embedUser" href="http://www.pinterest.com/<?php echo get_option ('_theme_pinterest_user'); ?>/" data-pin-scale-width="128" data-pin-scale-height="300" data-pin-board-width="300">Visite o perfil da Royal Guide no Pinterest!</a>
    	</div>
		<?php

		/*colar este código no footer:

		<!-- Please call pinit.js only once per page -->
		<script type="text/javascript" async src="//assets.pinterest.com/js/pinit.js"></script>

		*/
	}
}
add_action( 'widgets_init', create_function( '', 'return register_widget( "Pinterest" );' ) );
