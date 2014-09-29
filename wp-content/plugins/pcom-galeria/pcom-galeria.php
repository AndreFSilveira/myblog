<?php

/**
 * Plugin Name: PontoCom Galeria
 * Plugin URI: http://agenciadeinternet.com/
 * Description: Cadastro de galeria.
 * Author: PontoCom Ag&ecirc;ncia de Internet
 * Author URI: http://agenciadeinternet.com/
**/

define ('pcom_galeria_DIR', ABSPATH.PLUGINDIR.'/pcom-galeria/');
define ('pcom_galeria_URL', get_bloginfo('siteurl').'/'.PLUGINDIR.'/pcom-galeria');


//-----------------------------------------------------------------------
//DEFININDO ICONS
//-----------------------------------------------------------------------

add_action( 'admin_head', 'z_icon_galeria' );
 
function z_icon_galeria() {
    ?>
    <style type="text/css" media="screen">
			#menu-posts-galeria .wp-menu-image { background: url(<?php echo pcom_galeria_URL; ?>/img/icon-galeria-p.png) no-repeat 6px 6px !important; }
			#menu-posts-galeria .wp-menu-image, #menu-posts-galeria:hover .wp-menu-image, #menu-posts-galeria.wp-has-current-submenu .wp-menu-image { background-position: 4px 5px !important; }
			#icon-edit.icon32-posts-galeria { background: url(<?php echo pcom_galeria_URL; ?>/img/icon-galeria-m.png) no-repeat; }
    </style>
<?php }


//----------------------------------------------------------------------------------
//REGISTRANDO TAMANHO DAS IMAGENS UTILIZADAS ( Requer 'post-thumbnails' Habilitado )
//----------------------------------------------------------------------------------

	add_image_size( 'foto-galeria-post', 175, 134, true );
	add_image_size( 'foto-galeria-list', 90, 60, true );
	add_image_size( 'foto-galeria', 800, 500, true );

	
//----------------------------------------------------------------------------------
//REGISTRO DO POST_TYPE
//----------------------------------------------------------------------------------

	add_action('init', 'galeria_custom_post_types');
	function galeria_custom_post_types(){
		$args = array(
			'label' => 'Galerias',
			'labels' => array (
				'name' => 'Galerias',
				'singular_name' => 'galeria',
				'add_new' => 'Adicionar Galeria',
				'add_new_item' => 'Adicionar Galeria'
			),
			'hierarchical' => false,
			'supports' => array ('title'),
			'public' => true,
			'has_archive' => true,
			'can_export' => true,
			'rewrite' => true,
			'exclude_from_search' => true
		);
		register_post_type('galeria', $args);
		flush_rewrite_rules();
	}
	
//----------------------------------------------------------------------------------
// COLUNAS DA LISTAGEM
//----------------------------------------------------------------------------------
	
	add_action('manage_edit-galeria_columns', 'galeria_colunas');
	function galeria_colunas ($colunas) {
		$colunas = array(
			'cb' => '<input type="checkbox" />',
			'titulo-galeria' => 'Titulo',
			'imagem-galeria' => 'Veja algumas fotos'
		);
		return $colunas;
	}

	add_filter('manage_posts_custom_column', 'galeria_campos_func');
	function galeria_campos_func ($coluna) {
		global $post;
		$photos = get_posts ('post_type=attachment&showposts=4&orderby=rand&post_parent='.$post->ID);
		?>
			<style type="text/css">
				.column-titulo-galeria { width: 30% !important; font-size: 16px !important; }
				.column-imagem-galeria { width: 70% !important; font-size: 14px !important; }
				.imagem-galeria img { margin: 3px; padding: 5px; border: 1px solid #DEDEDE; background-color: #FFF; float: left; box-shadow: 1px 1px 1px #CCC; }
			</style>

		<?php global $numf; ?>
		<?php
		switch ($coluna) {
			case 'titulo-galeria' : echo '<b><a href="post.php?action=edit&post='.$post->ID.'">'.$post->post_title.'</a></b>'; break;
			case 'sessao-galeria' : echo get_the_term_list($post->ID, 'sessao-galeria'); break;
			case 'imagem-galeria' : ?>
				<?php if (is_array($photos)) : ?>
					<div class="galeria">
						<?php $i = 0; ?>
						<?php foreach ($photos as $p) : $i++; ?>
							<?php $img = wp_get_attachment_image_src($p->ID, 'foto-galeria-list'); ?>
							<div class="item" id="item-<?php echo $p->ID; ?>">
								<div class="anexo-img"><img src="<?php echo $img[0]; ?>" width="90px" height="60px" alt=""/></div>
							</div>
						<?php endforeach; $numf = $i;  ?>
					</div>
				<?php endif; ?>
				<?php wp_reset_query(); ?>
			
			<?php break;
		}
	}

//----------------------------------------------------------------------------------
// META_BOX
//----------------------------------------------------------------------------------
	
 	add_action ('admin_menu', 'anexos_metabox_sessao');
	function anexos_metabox_sessao () {
		add_meta_box ('anexos_meta_sessao', 'Fotos Anexadas', 'anexos_meta_box_sessao', 'galeria','normal');
	}
	
	function anexos_meta_box_sessao () {
		global $post, $wpdb;
		?>
        <style type="text/css">
            #sigla {width:5%;}
            #pequeno {width:17%;}
            #grande {width:100%;}
            .space {margin-left:30px;}
						.galeria { text-align: center; font-family: arial; font-size: 14px; font-weight: bold; padding: 5px 5px 10px 5px; }
						.anexo-img img { margin: 3px; padding: 5px; border: 1px solid #DEDEDE !important; background-color: #FFF !important; float: left; box-shadow: 0px 0px 2px #AAA; }
						.anexo-img img:hover { border: 1px solid #555 !important; background-color: #111 !important; box-shadow: 0px 0px 2px #777; }
        </style>
				<script type="text/javascript">
					jQuery(function(){
						setInterval(function(){
							jQuery('.galeria').load('<?php echo pcom_galeria_URL; ?>/post-type-fotos-galeria.php?id=<?php echo $post->ID; ?>')
						},3000);
					});
				</script>
        
		<fieldset id="anexos_fields">
			<table class="widefat">
				<tr>
					<td>
						<div class="galeria"><img src="<?php echo pcom_galeria_URL; ?>/img/22.gif" alt=""/><br />Carregando...</div>
					</td>
				</tr>
			</table>
		</fieldset>
		<?php
	}
	
	
 	add_action ('admin_menu', 'upload_metabox_sessao');
	function upload_metabox_sessao () {
		add_meta_box ('upload_meta_sessao', 'Upload de Fotos', 'upload_meta_box_sessao', 'galeria');
	}
	
	function upload_meta_box_sessao () {
		global $post, $wpdb;
		?>
			<style type="text/css">
					#sigla {width:5%;}
					.pequeno {width:17%;}
					.grande {width:100%;}
					.space {margin-left:30px;}
					.anexo-img img { margin: 3px; padding: 5px; border: 1px solid #DEDEDE; background-color: #FFF; float: left; }
			</style>

			<fieldset id="upload_fields">
				<table class="widefat">
					<tr>
						<td>
							<iframe src="media-upload.php?post_id=<?php echo $post->ID; ?>&type=image" height="500" name="box-loader" id="boxloader" class="grande"></iframe>
						</td>
					</tr>
				</table>
			</fieldset>
		<?php
	}
	
	if ($_POST['post_type'] == 'galeria') add_action('save_post', 'galeria_save_postdata');
	function galeria_save_postdata ( $post_id ) {
		
		global $wpdb;

        if (!current_user_can('edit_post', $post_id))
			return $post_id;
			
		if (isset($_POST['galeria'])){
			foreach ($_POST['galeria'] as $k => $v) {
				update_post_meta ($post_id, '_galeria_'.$k, $v);
			}
		}	

	}


// ----------------------------------------------------------------------------------------
//	META BOX impressoras (ANEXAR GALERIA)
// ----------------------------------------------------------------------------------------

add_action('admin_init','post_integre_galeria');

function post_integre_galeria(){
  add_meta_box('impressoras_galeria', 'Anexar galeria', 'impressoras_galeria', 'impressora', 'side');
  add_meta_box('suprimentos_galeria', 'Anexar galeria', 'suprimentos_galeria', 'suprimento', 'side');
  add_meta_box('eventos_galeria', 'Anexar galeria', 'eventos_galeria', 'eventos', 'side');
}
 
function impressoras_galeria(){
	global $post;

	$args = array(
		'post_type' => 'galeria',
		'post_status' => 'publish'
	);
	$ga = get_posts($args);
	?>
	<style type="text/css">
		.grande { width: 100%; }
		.box_check_galeria { background: #FFF; padding: 10px; height: 100px; overflow-y: scroll; border: 1px solid #DDD; -webkit-border-radius: 5px; -moz-border-radius: 5px; border-radius: 5px; }
	</style>
	
	<?php if($ga): $checkbox = get_post_meta($post->ID, '_impressoras_galeriacheck', 1); ?>
		<div class="box_check_galeria">
		<input type="hidden" name="impressoras[galeriacheck][vazio]" value="1" />
		<?php foreach($ga as $g): ?>
			<input type="checkbox" name="impressoras[galeriacheck][<?php echo $g->post_name; ?>]" value="1" <?php if($checkbox[$g->post_name] == 1) { echo ' checked="checked"';}?>><?php echo $g->post_title; ?><br />
		<?php endforeach; ?>
		</div>          
	<?php else:
		echo 'Nenhuma galeria cadastrada até o momento';
	endif;
}

function suprimentos_galeria(){
	global $post;

	$args = array(
		'post_type' => 'galeria',
		'post_status' => 'publish'
	);
	$ga = get_posts($args);
	?>
	<style type="text/css">
		.grande { width: 100%; }
		.box_check_galeria { background: #FFF; padding: 10px; height: 100px; overflow-y: scroll; border: 1px solid #DDD; -webkit-border-radius: 5px; -moz-border-radius: 5px; border-radius: 5px; }
	</style>
	
	<?php if($ga): $checkbox = get_post_meta($post->ID, '_suprimentos_galeriacheck', 1); ?>
		<div class="box_check_galeria">
		<input type="hidden" name="suprimentos[galeriacheck][vazio]" value="1" />
		<?php foreach($ga as $g): ?>
			<input type="checkbox" name="suprimentos[galeriacheck][<?php echo $g->post_name; ?>]" value="1" <?php if($checkbox[$g->post_name] == 1) { echo ' checked="checked"';}?>><?php echo $g->post_title; ?><br />
		<?php endforeach; ?>
		</div>          
	<?php else:
		echo 'Nenhuma galeria cadastrada até o momento';
	endif;
}

function eventos_galeria(){
	global $post;

	$args = array(
		'post_type' => 'galeria',
		'post_status' => 'publish'
	);
	$ga = get_posts($args);
	?>
	<style type="text/css">
		.grande { width: 100%; }
		.box_check_galeria { background: #FFF; padding: 10px; height: 100px; overflow-y: scroll; border: 1px solid #DDD; -webkit-border-radius: 5px; -moz-border-radius: 5px; border-radius: 5px; }
	</style>
	
	<?php if($ga): $checkbox = get_post_meta($post->ID, '_eventos_galeriacheck', 1); ?>
		<div class="box_check_galeria">
		<input type="hidden" name="eventos[galeriacheck][vazio]" value="1" />
		<?php foreach($ga as $g): ?>
			<input type="checkbox" name="eventos[galeriacheck][<?php echo $g->post_name; ?>]" value="1" <?php if($checkbox[$g->post_name] == 1) { echo ' checked="checked"';}?>><?php echo $g->post_title; ?><br />
		<?php endforeach; ?>
		</div>          
	<?php else:
		echo 'Nenhuma galeria cadastrada até o momento';
	endif;
}


if ($_POST['post_type'] == 'impressora' || $_POST['post_type'] == 'suprimento' || $_POST['post_type'] == 'eventos') add_action('save_post', 'save_details');
function save_details(){
	global $post;

	if(!current_user_can('edit_post', $post->ID))
		return $post->ID;

	
	if($_POST['post_type'] == 'impressora') {
		//LIMPAR CHECKBOX
		if($_POST['impressoras']['galeriacheck']){
			$checkbox = get_post_meta($post->ID, '_impressoras_galeriacheck', 1);
			if($checkbox)
				delete_post_meta($post->ID, '_impressoras_galeriacheck');
		}
		
		//SALVAR POST METAS
		if(isset($_POST['impressoras'])){
			foreach($_POST['impressoras'] as $k => $v)
				update_post_meta($post->ID, '_impressoras_'.$k, $v);
		}

	} elseif($_POST['post_type'] == 'suprimento'){
		//LIMPAR CHECKBOX
		if($_POST['suprimentos']['galeriacheck']){
			$checkbox = get_post_meta($post->ID, '_suprimentos_galeriacheck', 1);
			if($checkbox)
				delete_post_meta($post->ID, '_suprimentos_galeriacheck');
		}
		
		//SALVAR POST METAS
		if(isset($_POST['suprimentos'])){
			foreach($_POST['suprimentos'] as $k => $v)
				update_post_meta($post->ID, '_suprimentos_'.$k, $v);
		}
	} elseif($_POST['post_type'] == 'eventos'){
		//LIMPAR CHECKBOX
		if($_POST['eventos']['galeriacheck']){
			$checkbox = get_post_meta($post->ID, '_eventos_galeriacheck', 1);
			if($checkbox)
				delete_post_meta($post->ID, '_eventos_galeriacheck');
		}
		
		//SALVAR POST METAS
		if(isset($_POST['eventos'])){
			foreach($_POST['eventos'] as $k => $v)
				update_post_meta($post->ID, '_eventos_'.$k, $v);
		}
	}
}
