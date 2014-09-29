<?php

	/**
	 * Plugin Name: PontoCom Produtos
	 * Plugin URI: http://agenciadeinternet.com/
	 * Description: Catalogo de Produtos.
	 * Author: PontoCom Ag&ecirc;ncia de Internet
	 * Author URI: http://agenciadeinternet.com/
	**/

define ('pcom_produtos_DIR', ABSPATH.PLUGINDIR.'/pcom-produtos/');
define ('pcom_produtos_URL', get_bloginfo('siteurl').'/'.PLUGINDIR.'/pcom-produtos');

//-----------------------------------------------------------------------
//DEFININDO ICONS
//-----------------------------------------------------------------------

add_action( 'admin_head', 'z_icon_produto' );
 
function z_icon_produto() {
    ?>
    <style type="text/css" media="screen">
		#menu-posts-produto .wp-menu-image { background: url(<?php echo pcom_produtos_URL; ?>/img/icon-catalogo-p.png) no-repeat 6px 6px !important; }
		#menu-posts-produto .wp-menu-image, #menu-posts-produto:hover .wp-menu-image, #menu-posts-produto.wp-has-current-submenu .wp-menu-image { background-position: 4px 5px !important; }
		#icon-edit.icon32-posts-produto { background: url(<?php echo pcom_produtos_URL; ?>/img/icon-catalogo-m.png) no-repeat; }
    </style>
<?php }


//--------------------------------------------------------------------
//REGISTRO DO POST_TYPE
//--------------------------------------------------------------------

	ini_set('display_errors', true);
	
	/*Catálogo Produto*/
	add_action('init','post_type_produto');
	
	function post_type_produto(){
		register_post_type('produto', array(
			'labels' => array(
				'name' => 'Produtos',
				'singular_name' => 'Produto',
				'add_new' => 'Adicionar Produto',
				'add_new_item' => 'Adicionar Produto',
				'edit_item' => 'Editar Produto'
			),
			'has_archive' => true,
			'public' => true,
			'supports' => array('thumbnail', 'title', 'editor')
		));
		flush_rewrite_rules();
	}
	
	//categorias
	add_action( 'init', 'build_taxonomies', 0 );
	function build_taxonomies() {
		register_taxonomy('categorias', array('produto'), 
			array(
				'hierarchical' => true,
				'show_ui' => true,
				//'rewrite' => true,
				'label' => 'Categorias', 
				'singular_label' => 'Categoria',
				'public' => true
			)
		);
	}
	
	add_action('admin_init', 'admin_produto_init');
	
	function admin_produto_init(){
		add_meta_box('produto_meta_box', 'Dados Produto', 'produto_meta_box', 'produto');
	}
	
	function produto_meta_box(){
		global $post;
		$html = '
			<table class="form-table">
				<tbody> 
					<tr>
						<td class="first" valign="top">Marca</td>
						<td>
							<input type="text" name="marca" size="70" value="' . get_post_meta($post->ID, 'marca', true) . '" />
						</td>
					</tr>
					<tr>
						<td class="first" valign="top">Modelo</td>
						<td>
							<input type="text" name="modelo" size="70" value="' . get_post_meta($post->ID, 'modelo', true) . '" />
						</td>
					</tr>
				</tbody>
			</table>
		';
		echo $html;
	}
	
	
	add_action('save_post', 'save_produto');
	function save_produto(){
		global $post;
		if('produto' == $_POST['post_type']){
			if(!current_user_can('edit_page', $post_id)){
				return $post_id;
			}
		}
	
		if(defined('DOING_AUTOSAVE') && DOING_AUTOSAVE){ 
			return;
		}
	    
		$campos = array('modelo', 'marca');
		foreach($_POST as $key => $value){
			if(in_array($key, $campos)){
				update_post_meta($post->ID, $key, $value);
			}
		}
	}

 ?>

