<?php
/*
Plugin Name: Widget Banner
Description: Mostra um banner cadastrado
Author: Agência de Intenet PONTOCOM
Author URI: http://agenciadeinternet.com
*/

class Banner extends WP_Widget{
	public function __construct(){
		parent::WP_Widget('post_banner_widget','Banner', array('description' => 'Mostra um banner cadastrado'));
	}
	
	public function widget($args, $instance){
		$title = apply_filters('widget_title', $instance['title']);
		$url_img = apply_filters('widget_url_img', $instance['url_img']);
		$link = apply_filters('widget_link', $instance['link']);
		$other_tab = apply_filters('widget_other_tab', $instance['other_tab']);
		?>
		<div class="box f-left">
			<a href="<?php echo $link; ?>" <?php echo ($other_tab == 1 ? 'target="_blank"' : '')?> title="<?php echo $title?>">
				<img src="<?php echo $url_img ?>" alt="<?php echo $title ?>">
			</a>
		</div>

		<?php
	}
	
	public function form($instance){
		$title = esc_attr($instance['title']);
		$url_img = esc_attr($instance['url_img']);
		$link = esc_attr($instance['link']);
		$other_tab = esc_attr($instance['other_tab']);
		?>
		
		<label for="<?php echo $this->get_field_id('title') ?>">Título (Opcional)</label><br />
		<input type="text" name="<?php echo $this->get_field_name('title') ?>" class="widefat" id="<?php echo $this->get_field_id('title') ?>" value="<?php echo $title ?>" /><br />

		<label for="<?php echo $this->get_field_id('url_img') ?>">URL Imagem</label><br />
		<input type="text" name="<?php echo $this->get_field_name('url_img') ?>" class="widefat" id="<?php echo $this->get_field_id('url_img') ?>" value="<?php echo $url_img ?>" /><br />
		
		<label for="<?php echo $this->get_field_id('link') ?>">Link</label><br />
		<input type="text" name="<?php echo $this->get_field_name('link') ?>" class="widefat" id="<?php echo $this->get_field_id('link') ?>" value="<?php echo $link ?>" /><br />

		<label>Abrir em outra aba/janela?</label><br />
		<input type="radio" name="<?php echo $this->get_field_name('other_tab') ?>" id="y_other_tab" value="1" <?php echo ($other_tab == 1 ? 'checked="checked"' : '')?>><label for="y_other_tab">Sim</label>
		<input  style="margin-left:10px" type="radio" name="<?php echo $this->get_field_name('other_tab') ?>" id="n_other_tab" value="0" <?php echo ($other_tab == 0 ? 'checked="checked"' : '')?>><label for="n_other_tab">Não</label>
		
		<?php
	}
}
add_action( 'widgets_init', create_function( '', 'return register_widget( "Banner" );' ) );
