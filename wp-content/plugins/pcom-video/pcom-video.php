<?php

/**
 * Plugin Name: PontoCom Video
 * Plugin URI: http://agenciadeinternet.com/
 * Description: Cadastro de videos.
 * Author: PontoCom Ag&ecirc;ncia de Internet
 * Author URI: http://agenciadeinternet.com/
**/

define ('pcom_video_DIR', ABSPATH.PLUGINDIR.'/pcom-video/');
define ('pcom_video_URL', get_bloginfo('siteurl').'/'.PLUGINDIR.'/pcom-video');

//-----------------------------------------------------------------------
//DEFININDO ICONS
//-----------------------------------------------------------------------

add_action( 'admin_head', 'z_icon_video' );
 
function z_icon_video() {
    ?>
    <style type="text/css" media="screen">
		#menu-posts-video .wp-menu-image { background: url(<?php echo pcom_video_URL; ?>/img/icon-video-p.png) no-repeat 6px 6px !important; }
		#menu-posts-video .wp-menu-image, #menu-posts-video:hover .wp-menu-image, #menu-posts-video.wp-has-current-submenu .wp-menu-image { background-position: 4px 5px !important; }
		#icon-edit.icon32-posts-video { background: url(<?php echo pcom_video_URL; ?>/img/icon-video-m.png) no-repeat; }
    </style>
<?php }



//----------------------------------------------------------------------------------
//REGISTRO DO POST_TYPE
//----------------------------------------------------------------------------------

	add_action ('init', 'video_custom_post_types');
	function video_custom_post_types() {
		$args = array(
			'label' => 'Vídeos',
			'labels' => array (
				'name' => 'Vídeos',
				'singular_name' => 'Vídeo',
				'add_new' => 'Adicionar video',
				'add_new_item' => 'Adicionar video'
			),
			'hierarchical' => false,
			'supports' => array('title','thumbnail','excerpt','page-attributes'),
			'public' => true,
			'has_archive' => true
		);
		register_post_type('video', $args);
		flush_rewrite_rules();
	}
	
	add_action('manage_edit-video_columns', 'video_colunas');
	function video_colunas ($colunas) {
		$colunas = array(
			'cb' => '<input type="checkbox" />',
			'data-video' => 'Data',
			'titulo-video' => 'Título',
			'miniatura-video' => 'Miniatura'
		);
		return $colunas;
	}

	add_filter('manage_posts_custom_column', 'video_campos');
	function video_campos ($coluna) {
		global $post;
		switch ($coluna) {
			case 'data-video' : echo mysql2date('d/m/Y', $post->post_date); echo '<div class="row-actions"><a href="'.get_edit_post_link().'">Editar</a></div>'; break;
			case 'titulo-video' : echo '<a href="post.php?action=edit&post='.$post->ID.'">'.$post->post_title.'</a>'; break;
			case 'miniatura-video' : the_post_thumbnail('destaque-mini'); break;
		}
	}

	add_action ('add_meta_boxes', 'video_metaboxes');
	function video_metaboxes(){
		add_meta_box('video_info_metabox', 'Video', 'video_info_metabox', 'video');
	}
	
	function video_info_metabox() {
		global $post;
		?>
		<style type="text/css">
				#postexcerpt { display:block !important; }
		</style>
		
		<fieldset id="video_fields">
			<table class="widefat">
				<tr>
					<td>Canal de Origem do Vídeo
						<select name="video[canal]">
							<option value="">-- Selecione --</option>   
							<option value="youtube" <?php if(get_post_meta($post->ID, '_video_canal', 1) == 'youtube') echo 'selected="selected"'; ?>>YouTube</option> 
							<option value="globo" <?php if(get_post_meta($post->ID, '_video_canal', 1) == 'globo') echo ' selected="selected"'; ?>>Globo</option> 
							<option value="blip" <?php if(get_post_meta($post->ID, '_video_canal', 1) == 'blip') echo ' selected="selected"'; ?>>Blip</option> 
							<option value="vimeo" <?php if(get_post_meta($post->ID, '_video_canal', 1) == 'vimeo') echo ' selected="selected"'; ?>>Vimeo</option>                     
						</select>
					</td>
					<td>
						ID do Vídeo
						<input type="text" name="video[codigo]" value="<?php echo get_post_meta($post->ID, '_video_codigo', 1); ?>" />
						<small><i>código do vídeo</i></small>
					</td>
				</tr>
				<?php if($post->post_status!='auto-draft' && get_post_meta($post->ID, '_video_codigo',1) && get_post_meta($post->ID, '_video_canal',1)=='youtube'){ ?>
					<tr>
						<td>
							FOTO
							<div class="fotos">
								<table>
									<tr>
										<?php if(has_post_thumbnail()){ ?>
											<td>
												<input type="radio" id="foto0" name="video[foto]" value="0" <?php if(get_post_meta($post->ID, '_video_foto', 1)==0){ echo 'checked="checked"'; } ?>/> Imagem Destacada<br />
												<label for="foto0"><?php echo get_the_post_thumbnail($post->ID,'destaque-mini'); ?></label>
											</td>
										<?php } ?>
										<td>
											<input type="radio" id="foto1" name="video[foto]" value="1" <?php if(get_post_meta($post->ID, '_video_foto', 1)==1){ echo 'checked="checked"'; } ?> /> Foto 1<br />
											<label for="foto1"><img src="http://img.youtube.com/vi/<?php echo get_post_meta($post->ID, '_video_codigo', 1); ?>/1.jpg" width="140" height="100" /></label>
										</td>
										<td>
											<input type="radio" id="foto2" name="video[foto]" value="2" <?php if(get_post_meta($post->ID, '_video_foto', 1)==2){ echo 'checked="checked"'; } ?>/> Foto 2<br />
											<label for="foto2"><img src="http://img.youtube.com/vi/<?php echo get_post_meta($post->ID, '_video_codigo', 1); ?>/2.jpg" width="140" height="100" /></label>
										</td>
										<td>
											<input type="radio" id="foto3" name="video[foto]" value="3" <?php if(get_post_meta($post->ID, '_video_foto', 1)==3){ echo 'checked="checked"'; } ?>/> Foto 3<br />
											<label for="foto3"><img src="http://img.youtube.com/vi/<?php echo get_post_meta($post->ID, '_video_codigo', 1); ?>/3.jpg" width="140" height="100" /></label>
										</td>
									</tr>
								</table>
							</div>
						</td>
					</tr>
				<?php } ?>
			</table>
		</fieldset>
		<?php
	}
	
	if ($_POST['post_type'] == 'video') add_action('save_post', 'video_save_postdata');
	function video_save_postdata ( $post_id ) {

		global $wpdb;

		if (!current_user_can('edit_post', $post_id))
			return $post_id;

		foreach ($_POST['video'] as $k => $v) {
			update_post_meta ($post_id, '_video_'.$k, $v);
		}
	}
?>