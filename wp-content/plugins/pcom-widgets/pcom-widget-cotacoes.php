<?php
/*
Plugin Name: Widget Cotações
Description: Mostra as cotações
Author: Agência de Intenet PONTOCOM
Author URI: http://agenciadeinternet.com
*/

class Cotacoes extends WP_Widget{
	public function __construct(){
		parent::WP_Widget('post_cotacoes_widget','Cotações', array('description' => 'Motra as cotações'));
	}
	
	public function widget($args, $instance){ ?>
		<div class="box-gradiente box-cotacoes f-left">
			<h3 class="title-box f-left">Cotações</h3>
			<div class="prev-next f-right box-gradiente">
				<a href="" class="prev f-left"><</a>
				<a href="" class="next f-left">></a>
			</div>
			<ul>
				<?php 
				$cotacoes = get_posts(array('post_type'=>'cotacao_home','numberposts'=>1,'post_status'=>'publish'));
				foreach($cotacoes as $cotacao): 
					for ($i=1; $i < 10; $i++) { 
						if(get_post_meta($cotacao->ID, 'nome_cultura'.$i,true)) :?>
							<li>
								<span><?php echo get_the_title($cotacao->ID) ?></span>
								<p><?php echo get_post_meta($cotacao->ID, 'nome_cultura'.$i,true) ?></p>
								<p class="preco"><?php echo get_post_meta($cotacao->ID, 'valor_cultura'.$i, true) ?></p>
							</li>
							<?php 
						endif;
					} 
				endforeach; ?>
			</ul>
		</div>
		<?php
	}
}
add_action( 'widgets_init', create_function( '', 'return register_widget( "Cotacoes" );' ) );