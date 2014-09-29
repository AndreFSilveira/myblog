<?php
/*
Plugin Name: Widget PostsRelacionados
Description: Box de PostsRelacionados
Author: Agência de Intenet PONTOCOM
Author URI: http://agenciadeinternet.com
*/

class PostsRelacionados extends WP_Widget{
	public function __construct(){
		parent::WP_Widget('post_relacionados_widget','PostsRelacionados', array('description' => 'box de Posts relacionados'));
	}
	public function widget($args, $instance){ ?>
	<h1 class="h1-boxzinha">Posts Mais Lidos</h1>
        <ul id="listinha-thumbs">
        <?php
            $month = date('m');
            $year = date('Y');
            $popularpost = new WP_Query( array( 'posts_per_page' => 3, 'meta_key' => 'wpb_post_views_count', 'orderby' => 'meta_value_num', 'order' => 'DESC' , 'year' => $year) );
            while ( $popularpost->have_posts() ) : $popularpost->the_post(); ?>
            	<li>
                    <?php if (has_post_thumbnail()) {?>
                        <a href="<?php the_permalink() ?>" class="listinha-thumbs-img" title="<?php the_title() ?>"><?php the_post_thumbnail('posts-rel-wg');?></a>
                    <?php } else { ?>
                        <a href="<?php the_permalink() ?>" class="listinha-thumbs-img" title="<?php the_title() ?>">
                            <img src="<?php bloginfo('stylesheet_directory') ?>/imgs/thumb-p-01.jpg" alt="<?php the_title() ?>" />
                        </a>
                    <?php } ?>                    
                    <a href="<?php the_permalink() ?>" class="listinha-thumbs-txt" title="<?php the_title() ?>"><?php the_title() ?></a>
                </li>
            <?php endwhile;?>
            <div class="clear"></div>
        </ul>
        <?php
	}
}
add_action( 'widgets_init', create_function( '', 'return register_widget( "PostsRelacionados" );' ) );
