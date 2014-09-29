<?php

/**
 * Plugin Name: PontoCom Equipe
 * Plugin URI: http://agenciadeinternet.com/
 * Description: Cadastro de membros da equipe.
 * Author: PontoCom Ag&ecirc;ncia de Internet
 * Author URI: http://agenciadeinternet.com/
**/

define ('pcom_equipe_DIR', ABSPATH.PLUGINDIR.'/pcom-equipe/');
define ('pcom_equipe_URL', get_bloginfo('siteurl').'/'.PLUGINDIR.'/pcom-equipe');

//-----------------------------------------------------------------------
//DEFININDO ICONS
//-----------------------------------------------------------------------

add_action( 'admin_head', 'z_icon_equipe' );
 
function z_icon_equipe() {
    ?>
    <style type="text/css" media="screen">
		#menu-posts-equipe .wp-menu-image { background: url(<?php echo pcom_equipe_URL; ?>/img/icon-equipe-p.png) no-repeat 6px 6px !important; }
		#menu-posts-equipe .wp-menu-image, #menu-posts-equipe:hover .wp-menu-image, #menu-posts-equipe.wp-has-current-submenu .wp-menu-image { background-position: 4px 5px !important; }
		#icon-edit.icon32-posts-equipe { background: url(<?php echo pcom_equipe_URL; ?>/img/icon-equipe-m.png) no-repeat; }
    </style>
<?php }
	
//--------------------------------------------------------------------
//REGISTRO DO POST_TYPE
//--------------------------------------------------------------------

	add_action ('init', 'equipe_custom_post_types');
	function equipe_custom_post_types() {
		$args = array(
			'label' => 'Equipes',
			'labels' => array(
				'name' => 'Equipes',
				'singular_name' => 'Equipe',
				'add_new' => 'Adicionar Equipe',
				'add_new_item' => 'Adicionar Equipe'
			),
			'hierarchical' => true,
			'supports' => array ('title','thumbnail'),
			'public' => true,
			'has_archive' => true,
			'can_export' => true,
		);
		register_post_type('equipe', $args);
		flush_rewrite_rules();
	}
	
	add_action('init', 'equipe_custom_taxonomy');
	function equipe_custom_taxonomy(){
		$args = array (
			'labels' => array(
				'name' => 'Categoria',
				'singular_name' => 'equipe-categoria'
			),
			'hierarchical' => true,
		);
		register_taxonomy('equipe-categoria', 'equipe', $args);
	}
	
//--------------------------------------------------------------------
// COLUNAS DA LISTAGEM
//--------------------------------------------------------------------
	
/* 	add_action('manage_edit-equipe_columns', 'equipe_colunas');
	function equipe_colunas ($colunas) {
		$colunas = array(
			'cb' => '<input type="checkbox" />',
			'titulo-equipe' => 'Titulo',
            'sessao-equipe' => 'Sessão',
            'link-equipe' => 'Link',
            'imagem-equipe' => 'Imagem'
		);
		return $colunas;
	} */

/* 	add_filter('manage_posts_custom_column', 'equipe_campos_func');
	function equipe_campos_func ($coluna) {
		global $post;
		?>
        <style type="text/css">
            .imagem-equipe img {width:250px !important; height: 70px !important; }
        </style>
		<?php
		switch ($coluna) {
			case 'titulo-equipe' : echo '<b><a href="post.php?action=edit&post='.$post->ID.'">'.$post->post_title.'</a></b>'; break;
			case 'sessao-equipe' : echo get_post_meta($post->ID, '_equipe_sessao', 1); break;
			case 'link-equipe' : echo get_post_meta($post->ID, '_equipe_link', 1); break;
			case 'imagem-equipe' : if(has_post_thumbnail($post->ID)){ echo get_the_post_thumbnail( $post->ID, 'equipe-admin');} else {echo '<img src="'.get_bloginfo("template_url").'/img/equipe.png" />';} break;
		}
	}
*/
//--------------------------------------------------------------------
// META_BOX
//--------------------------------------------------------------------

	add_action ('admin_menu', 'equipe_metabox_jogadores');
	function equipe_metabox_jogadores() {
		add_meta_box ('equipe_meta_jogadores', 'Jogadores', 'equipe_meta_box_jogadores', 'equipe');
	}
	
	function equipe_meta_box_jogadores() {
		global $post, $wpdb;
		?>
		<style type="text/css">
				#sigla {width:5%;}
				.remove { width: 20px !important; }
				.pequeno {width:23%;}
				.medio {width:50%;}
				.grande {width:100%;}
				.space {margin-left:30px;}
		</style>
		
		<script type="text/javascript">
			jQuery(function(){
				jQuery('table.tb-jogadores tr.repete:first a').hide();
				jQuery('.bt-add-jogador').click(function(){
					var clone = jQuery('table.tb-jogadores tr:last').clone();
					jQuery('table.tb-jogadores').append(clone);
					jQuery('table.tb-jogadores tr:last input').val('');
					return false;
				});
			});
			
			function removeline(a){
				jQuery(a).parent().parent().remove();
			}
			
		</script>
        
		<fieldset id="equipe_fields">
			<table class="widefat tb-jogadores">
				<tr>
					<td class="medio">Nome</td>
					<td class="pequeno">Posi&ccedil;&atilde;o</td>
					<td class="pequeno">Apelido</td>
					<td class="remove">&nbsp;</td>
				</tr>
				<?php $jogadores = unserialize(get_post_meta($post->ID, '_equipe_jogadores',1)); ?>
				<?php if(is_array($jogadores) && count($jogadores)): ?>
					<?php foreach($jogadores as $item): ?>
					<tr class="repete">
						<td><input class="grande" type="text" name="jogador_nome[]" value="<?php echo $item['nome'] ?>" /></td>
						<td><input class="grande" type="text" name="jogador_posicao[]" value="<?php echo $item['posicao'] ?>" /></td>
						<td><input class="grande" type="text" name="jogador_apelido[]" value="<?php echo $item['apelido'] ?>" /></td>
						<td class="remove"><a onclick="removeline(this)" href="#"><img src="<?php echo pcom_equipe_URL; ?>/img/close_24.png" alt=""/></a></td>
					</tr>
					<?php endforeach; ?>
				<?php else: ?>
					<tr class="repete">
						<td><input class="grande" type="text" name="jogador_nome[]" value="<?php echo get_post_meta($post->ID, '_jogador_nome', 1); ?>" /></td>
						<td><input class="grande" type="text" name="jogador_posicao[]" value="<?php echo get_post_meta($post->ID, '_jogador_posicao', 1); ?>" /></td>
						<td><input class="grande" type="text" name="jogador_apelido[]" value="<?php echo get_post_meta($post->ID, '_jogador_apelido', 1); ?>" /></td>
						<td class="remove"><a onclick="removeline(this)" href="#"><img src="<?php echo pcom_equipe_URL; ?>/img/close_24.png" alt=""/></a></td>
					</tr>
				<?php endif; ?>
			</table>
			<a href="#" class="bt-add-jogador" style="float: right;">+ Adicionar Jogador</a>
		</fieldset>
		<?php
	}

//--------------------------------------------------------------------
// SALVA POST_TYPE (NÃO MEXER!!!)
//--------------------------------------------------------------------	
	
	if ($_POST['post_type'] == 'equipe') add_action('save_post', 'equipe_save_postdata');
	function equipe_save_postdata ( $post_id ) {
		
		global $wpdb;

        if (!current_user_can('edit_post', $post_id))
			return $post_id;
			
		if (isset($_POST['equipe'])){
			foreach ($_POST['equipe'] as $k => $v) {
				update_post_meta ($post_id, '_equipe_'.$k, $v);
			}
		}	
		
		// Jogadores
		$nomes = $_POST['jogador_nome'];
		$posicoes = $_POST['jogador_posicao'];
		$apelidos = $_POST['jogador_apelido'];
		$data = array();
		for($i=0;$i<count($nomes);$i++){
			if(strlen($nomes[$i]) && strlen($posicoes[$i])){
				$item = array(
					'nome' => $nomes[$i],
					'posicao' => $posicoes[$i],
					'apelido' => $apelidos[$i]
				);
				array_push($data, $item);
			}
		}
		
		update_post_meta($post_id, '_equipe_jogadores', serialize($data));
	}
?>