<?php 

/**
 * Plugin Name: PontoCom FAQ
 * Plugin URI: http://agenciadeinternet.com/
 * Description: Cadastro de perguntas FAQ.
 * Author: PontoCom Ag&ecirc;ncia de Internet
 * Author URI: http://agenciadeinternet.com/
**/

define ('pcom_perguntas_DIR', ABSPATH.PLUGINDIR.'/pcom-perguntas-faq/');
define ('pcom_perguntas_URL', get_bloginfo('siteurl').'/'.PLUGINDIR.'/pcom-perguntas-faq');

//-----------------------------------------------------------------------
//DEFININDO ICONS
//-----------------------------------------------------------------------

add_action( 'admin_head', 'z_icon_perguntas' );
 
function z_icon_perguntas() {
    ?>
    <style type="text/css" media="screen">
		#menu-posts-perguntas .wp-menu-image { background: url(<?php echo pcom_perguntas_URL; ?>/img/icon-question-p.png) no-repeat 6px 6px !important; }
		#menu-posts-perguntas .wp-menu-image, #menu-posts-perguntas:hover .wp-menu-image, #menu-posts-perguntas.wp-has-current-submenu .wp-menu-image { background-position: 4px 5px !important; }
		#icon-edit.icon32-posts-perguntas { background: url(<?php echo pcom_perguntas_URL; ?>/img/icon-question-m.png) no-repeat; }
    </style>
<?php }

	
//--------------------------------------------------------------------
//REGISTRO DO POST_TYPE
//--------------------------------------------------------------------

	/*Cadastro de Perguntas*/
	add_action('init','post_type_perguntas');
	function post_type_perguntas(){
		register_post_type('perguntas', array(
			'labels' => array(
				'name' => 'FAQ',
				'singular_name' => 'Perguntas',
				'add_new' => 'Adicionar Pergunta',
				'add_new_item' => 'Adicionar Pergunta',
				'edit_item' => 'Editar Pergunta'
			),
			'public' => true,
			'hierarchical' => true,
			'supports' => array('title', 'page-attributes', 'editor')
		));
	}

	
	// add_action('admin_init', 'admin_perguntas_init');
	
	// function admin_perguntas_init(){
	// 	add_meta_box('perguntas_meta_box', 'Dados', 'perguntas_meta_box', 'perguntas');
	// }
	
	// function perguntas_meta_box(){
		
	// 	global $post;
	// 	$html = '
	// 		<table class="form-table">
	// 			<tbody>
	// 				<tr>
	// 					<td class="first" valign="top">Resposta</td>
	// 					<td>
	// 						<textarea name="resposta" cols="100" rows="10">' . get_post_meta($post->ID, 'resposta', true) . '</textarea>
	// 					</td>
	// 				</tr>				
	// 			</tbody>
	// 		</table>
	// 	';
	// 	echo $html;
	// }
	
	add_action('save_post', 'save_perguntas');
	function save_perguntas(){
		global $post;
		if('perguntas' == $_POST['post_type']){
			if(!current_user_can('edit_page', $post_id)){
				return $post_id;
			}
		}
	
		if(defined('DOING_AUTOSAVE') && DOING_AUTOSAVE){ 
	    	return;
	    }
	    
	    // $campos = array('resposta');
	    // foreach($_POST as $key => $value){
	    // 	if(in_array($key, $campos)){
	    // 		update_post_meta($post->ID, $key, $value);
	    // 	}
	    // }
	}