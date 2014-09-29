<?php
/*
Plugin Name: Widget Select Categorias
Description: Mostra um select com todas as categorias de posts
Author: Agência de Intenet PONTOCOM
Author URI: http://agenciadeinternet.com
*/

class Categorias extends WP_Widget{
	public function __construct(){
		parent::WP_Widget('post_categorias_widget','Categorias', array('description' => 'Select com todas as categorias de posts'));
	}
	
	public function widget($args, $instance){ ?>
		<div class="box box-categorias">
			<div class="title-box">
				<p>Categorias</p>
			</div>
			<div class="select-categorias">
				<form action="<?php bloginfo('url'); ?>/" method="get">
			      <?php
			      $select = wp_dropdown_categories('show_option_none=Selecione&orderby=name&echo=0&id=categorias&class=box-gradiente');
			      $select = preg_replace("#<select([^>]*)>#", "<select$1 onchange='return this.form.submit()'>", $select);
			      echo $select;
			      ?>
			      <noscript><div><input type="submit" value="View" /></div></noscript>
			    </form>
			</div>
		</div>
		<?php
	}
}
add_action( 'widgets_init', create_function( '', 'return register_widget( "Categorias" );' ) );