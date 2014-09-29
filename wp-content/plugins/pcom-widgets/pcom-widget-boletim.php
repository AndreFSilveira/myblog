<?php
/*
Plugin Name: Widget Boletim Informativo
Description: Mostra o último Boletim cadastrado
Author: Agência de Intenet PONTOCOM
Author URI: http://agenciadeinternet.com
*/

class Boletim extends WP_Widget{
	public function __construct(){
		parent::WP_Widget('post_boletim_widget','Boletim Informativo', array('description' => 'Mostra o último Boletim cadastrado'));
	}
	
	public function widget($args, $instance){ ?>
		<div class="clear"></div>
		<div class="box-gradiente box-boletim">
			<h3 class="title-box">
				Boletim <strong>Informativo</strong>
			</h3>
			<?php 
			$wpq = array('post_type' => 'boletim','posts_per_page'=>1,'tax_query' => array(array('taxonomy'=>'publicacao','field' => 'slug','terms'=>'boletins-informativos')));
			$my_query = new WP_Query ($wpq);
			while ($my_query->have_posts()): $my_query->the_post();
				global $post;?>
				<div class="imagem-boletim">
					<?php 
					if(get_post_meta($post->ID, 'link_embed', true)): ?>
						<a target="_blank" href="<?php echo get_post_meta($post->ID, 'link_embed', true) ?>">
							<?php 
							if(has_post_thumbnail()):
								the_post_thumbnail('publicacao-sidebar');
							else: ?>
								<img src="<?php bloginfo('stylesheet_directory')?>/img/nophoto-231x318.jpg" height="318" width="231" alt="">
								<?php 
							endif; ?>
						</a>
						<?php 
					else: 
						if(has_post_thumbnail()):
							the_post_thumbnail('publicacao-sidebar');
						else: ?>
							<img src="<?php bloginfo('stylesheet_directory')?>/img/nophoto-231x318.jpg" height="318" width="231" alt="">
							<?php 
						endif;
					endif;
					?>
				</div>
				<div class="title-boletim f-left">
					<p><?php the_title() ?></p>
				</div>
				<div class="baixar-pdf f-left">
					<?php if(get_post_meta($post->ID, 'boletim_pdf', true)): ?>
						<a href="<?php echo wp_get_attachment_url( get_post_meta($post->ID, 'boletim_pdf', true) ) ?>">Baixar PDF</a>
					<?php endif; ?>
				</div>
				<div class="outras-edicoes f-left">
					<a href="<?php bloginfo('url') ?>/publicacao/boletins-informativos">Outras Edições</a>
				</div>
				<?php 
			endwhile;
			wp_reset_postdata(); ?>
			<div class="clear"></div>
		</div>
		<?php
	}
}
add_action( 'widgets_init', create_function( '', 'return register_widget( "Boletim" );' ) );