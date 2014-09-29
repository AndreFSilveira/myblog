<?php
/*
Plugin Name: Widget Facebook
Description: Mostra a Like Box do Facebook
Author: Agência de Intenet PONTOCOM
Author URI: http://agenciadeinternet.com
*/

class Facebook extends WP_Widget{
	public function __construct(){
		parent::WP_Widget('post_facebook_widget','Facebook', array('description' => 'Mostra a Like box do Facebook'));
	}
	
	public function widget($args, $instance){
		$user_facebook = apply_filters('widget_user_facebook', $instance['user_facebook']);
		?>
		<div class="clear"></div>
		<div class="box box-social">
			<div class="title-box">
				<p>Social</p>
			</div>
			<div class="facebook">
				<iframe src="//www.facebook.com/plugins/likebox.php?href=https://www.facebook.com/<?php echo $user_facebook; ?>&amp;width=300&amp;height=270&amp;colorscheme=light&amp;show_faces=true&amp;header=false&amp;stream=false&amp;show_border=false" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:300px; height:258px;" allowTransparency="true"></iframe>
			</div>
		</div>
		<?php
	}
	
	public function form($instance){
		$user_facebook = esc_attr($instance['user_facebook']);
		?>
		
		<label for="<?php echo $this->get_field_id('user_facebook') ?>">Usuário facebook</label><br />
		<input type="text" name="<?php echo $this->get_field_name('user_facebook') ?>" class="widefat" id="<?php echo $this->get_field_id('user_facebook') ?>" value="<?php echo $user_facebook ?>" /><br />
		
		<?php
	}
}
add_action( 'widgets_init', create_function( '', 'return register_widget( "Facebook" );' ) );
