<?php

/**
 * Plugin Name: PontoCom Boletins Informativos
 * Plugin URI: http://agenciadeinternet.com/
 * Description: Cadastro de Boletins informativos.
 * Author: PontoCom Ag&ecirc;ncia de Internet
 * Author URI: http://agenciadeinternet.com/
**/

define ('pcom_boletins_DIR', ABSPATH.PLUGINDIR.'/pcom-boletins-informativos/');
define ('pcom_boletins_URL', get_bloginfo('siteurl').'/'.PLUGINDIR.'/pcom-boletins-informativos');

//-----------------------------------------------------------------------
//DEFININDO ICONS
//-----------------------------------------------------------------------

add_action( 'admin_head', 'z_icon_boletins' );
 
function z_icon_boletins() {
    ?>
    <style type="text/css" media="screen">
		#menu-posts-boletim .wp-menu-image { background: url(<?php echo pcom_boletins_URL; ?>/img/icon-boletim-p.png) no-repeat 6px 6px !important; }
		#menu-posts-boletim .wp-menu-image, #menu-posts-boletim:hover .wp-menu-image, #menu-posts-boletim.wp-has-current-submenu .wp-menu-image { background-position: 4px 5px !important; }
		#icon-edit.icon32-posts-boletins { background: url(<?php echo pcom_boletins_URL; ?>/img/icon-boletim-m.png) no-repeat; }
    </style>
<?php }

	
//--------------------------------------------------------------------
//REGISTRO DO POST_TYPE
//--------------------------------------------------------------------
	
	add_action('init','post_type_boletins');
	function post_type_boletins(){
		register_post_type('boletim', array(
			'labels' => array(
				'name' => 'Publicações',
				'singular_name' => 'Boletim',
				'add_new' => 'Adicionar Boletim',
				'add_new_item' => 'Adicionar Boletim',
				'edit_item' => 'Editar Boletim'
			),
			'public' => true,
			'hierarchical' => true,
			'supports' => array('title', 'thumbnail')
		));
		flush_rewrite_rules();
	}

	add_action('admin_init', 'admin_boletins_init');

	add_action( 'init', 'build_taxonomies_boletins', 0 );
	function build_taxonomies_boletins() {
		$labels = array(
		    'name'                => 'Categorias Publicações',
		    'singular_name'       => 'Categoria',
		    'search_items'        => 'Buscar Categorias',
		    'all_items'           => 'Todos Categorias',
		    'edit_item'           => 'Editar Categoria', 
		    'update_item'         => 'Atualizar Categoria',
		    'add_new_item'        => 'Adicionar Nova Categoria',
		    'menu_name'           => 'Categorias'
		  ); 
		register_taxonomy('publicacao', array('boletim'), 
			array(
				'hierarchical' => true,
				'show_ui' => true,
				'labels' => $labels, 
				'public' => true
			)
		);
	}
	
	function admin_boletins_init(){
		add_meta_box('boletins_meta_box', 'Dados do Boletim', 'boletins_meta_box', 'boletim');
	}	
	
	function boletins_meta_box(){
		global $post;
		$html = '
			<table class="form-table">
				<tbody>
					<tr>
						<td class="first" valign="top">Ano</td>
						<td><input type="text" size="20" name="ano" value="'. (get_post_meta($post->ID, 'ano', true) ? get_post_meta($post->ID, 'ano', true) : date('Y')) .'"/></td>
					</tr>
					<tr>
						<td class="first" valign="top">Boletim em PDF</td>
						<td>
							<input type="file" name="boletim_pdf" />
						';
					if(get_post_meta($post->ID, 'boletim_pdf', true)) {
					$html.='<a href="'.wp_get_attachment_url( get_post_meta($post->ID, 'boletim_pdf', true) ).'">Baixar PDF</a>';
					}
					$html.='
						</td>
					</tr> 	
					<tr>
						<td class="first" valign="top">Link Embed (ISSUU)</td>
						<td>
							<input type="text" size="70" name="link_embed" value="'. get_post_meta($post->ID, 'link_embed', true) .'"/>
						</td>
					</tr>
				</tbody>
			</table>
		';
		echo $html;
	}
	
	add_action('save_post', 'save_boletins');
	function save_boletins(){
		global $post;
		if('boletim' == $_POST['post_type']){
			if(!current_user_can('edit_page', $post_id)){
				return $post_id;
			}
		}
	
		if(defined('DOING_AUTOSAVE') && DOING_AUTOSAVE){ 
	    	return;
	    }
	    
	    $campos = array('link_embed', 'ano');
	    foreach($_POST as $key => $value){
	    	if(in_array($key, $campos)){
	    		update_post_meta($post->ID, $key, $value);
	    	}
	    }

	    if(strlen($_FILES['boletim_pdf']['tmp_name']) > 0) {
			wp_upload_arquivo_post_type('boletim_pdf', $post->ID);
		}
	}
?>
