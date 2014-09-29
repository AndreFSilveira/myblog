<?php
/*
Plugin Name: Widget Últimas Notícias
Description: Lista as últimas 4 notícias cadastradas
Author: Agência de Intenet PONTOCOM
Author URI: http://agenciadeinternet.com
*/

class Noticias extends WP_Widget{
	public function __construct(){
		parent::WP_Widget('post_noticias_widget','Últimas Notícias', array('description' => 'Lista os 4 últimos posts cadastrados'));
	}
	
	public function widget($args, $instance){ ?>
		<div class="clear"></div>
        <div class="box-gradiente box-noticias">
			<h3 class="title-box">
				Ultimas <strong>Notícias</strong>
			</h3>
			<ul>
				<?php 
				query_posts(array('post_type'=>'post', 'posts_per_page'=>3, 'post_status'=>'publish'));
				while(have_posts()): the_post(); ?>
					<li>
						<a class="img-ult-noticia" href="">
							<?php if(has_post_thumbnail()):
								the_post_thumbnail('post-sidebar');
							else: ?>
								<img src="<?php bloginfo('stylesheet_directory')?>/img/nophoto-96x64.jpg" height="64" width="96" alt=""></a>
								<?php 
							endif; ?>
						<div class="info">
							<p class="categoria"><?php the_category(', ') ?></p>
							<h4><a href="<?php the_permalink()?>"><?php the_title() ?></a></h4>
						</div>
						<div class="clear"></div>
					</li>
					<?php 
				endwhile; 
				wp_reset_query();?>
			</ul>
			<a class="ver-mais" href="<?php bloginfo('url')?>/category/noticias">Ver todas as noticias</a>
			<div class="clear"></div>
		</div>

		<?php
	}
}
add_action( 'widgets_init', create_function( '', 'return register_widget( "Noticias" );' ) );
