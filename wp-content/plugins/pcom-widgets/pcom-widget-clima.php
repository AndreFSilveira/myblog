<?php
/*
Plugin Name: Widget Clima
Description: Mostra o Clima
Author: Agência de Intenet PONTOCOM
Author URI: http://agenciadeinternet.com
*/

class Clima extends WP_Widget{
	public function __construct(){
		parent::WP_Widget('post_clima_widget','Clima', array('description' => 'Motra o Clima'));
	}
	
	public function widget($args, $instance){ ?>
		<div class="box-gradiente box-clima f-left">
			<h3 class="title-box f-left">Clima</h3>
			<div class="select-cidade f-right">
				<select name="cidade" id="cidade">
					<option value="">+ cidades</option>
					<option value="curitiba">curitiba</option>
					<option value="londrina">londrina</option>
					<option value="maringá">maringá</option>
					<option value="cascavel">cascavel</option>
					<option value="paranaguá">paranaguá</option>
					<option value="fozdoiguacu">foz do iguaçu</option>
				</select>
				<input type="hidden" name="api_key" id="api_key" value="<?php echo get_option('_theme_wweather_api_key')?>">
			</div>
			<div class="clear"></div>
			<div class="clima-cidade">
				<img src="<?php bloginfo('stylesheet_directory')?>/img/ajax-loader.gif" alt="">
			</div>
		</div>
		<?php
	}
}
add_action( 'widgets_init', create_function( '', 'return register_widget( "Clima" );' ) );