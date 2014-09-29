<?php

/**
 * Plugin Name: PontoCom Eventos
 * Plugin URI: http://agenciadeinternet.com/
 * Description: Cadastro de eventos.
 * Author: PontoCom Ag&ecirc;ncia de Internet
 * Author URI: http://agenciadeinternet.com/
**/

define ('pcom_eventos_DIR', ABSPATH.PLUGINDIR.'/pcom-eventos/');
define ('pcom_eventos_URL', get_bloginfo('siteurl').'/'.PLUGINDIR.'/pcom-eventos');


//-----------------------------------------------------------------------
//DEFININDO ICONS
//-----------------------------------------------------------------------

add_action( 'admin_head', 'z_icon_eventos' );
 
function z_icon_eventos() {
    ?>
    <style type="text/css" media="screen">
		#menu-posts-eventos .wp-menu-image { background: url(<?php echo pcom_eventos_URL; ?>/img/icon-evento-p.png) no-repeat 6px 6px !important; }
		#menu-posts-eventos .wp-menu-image, #menu-posts-eventos:hover .wp-menu-image, #menu-posts-eventos.wp-has-current-submenu .wp-menu-image { background-position: 4px 5px !important; }
		#icon-edit.icon32-posts-eventos { background: url(<?php echo pcom_eventos_URL; ?>/img/icon-evento-m.png) no-repeat; }
    </style>
<?php }

	
//----------------------------------------------------------------------------------
//REGISTRO DO POST_TYPE
//----------------------------------------------------------------------------------

	/* Post Type Eventos*/
	add_action('init','post_type_eventos');
	function post_type_eventos(){
		register_post_type('eventos', array(
			'labels' => array(
				'name' => 'Eventos',
				'singular_name' => 'Evento',
				'add_new' => 'Adicionar Evento',
				'add_new_item' => 'Adicionar Evento',
				'edit_item' => 'Editar Evento'
			),
			'has_archive' => true,
			'public' => true,
			'hierarchical' => true,
			'supports' => array('title', 'thumbnail', 'editor', 'page-attributes')
		));
		flush_rewrite_rules();
	}
	
	add_action('admin_init', 'admin_eventos_init');
	
	function admin_eventos_init(){
		add_meta_box('eventos_meta_box', 'Dados', 'eventos_meta_box', 'eventos');
	}
	
	function eventos_meta_box(){
		
		global $post;
		$html = '
			<table class="form-table">
				<tbody>
					<tr>
						<td class="first" valign="top">Banner Destaque</td>
						<td>
							<input type="file" name="banner" />
						</td>
					</tr> ';
					if(get_post_meta($post->ID, 'banner', true)) {
					$html.='<tr>
							<td colspan="2"><img style="max-width:830px" src="'.wp_get_attachment_url( get_post_meta($post->ID, 'banner', true) ).'" alt="" /></td>
						</tr>';
					}
					$html.='
					<tr>
						<td class="first" valign="top">Local</td>
						<td>
							<input type="text" name="cidade" size="100" value="' . get_post_meta($post->ID, 'cidade', true) . '" />
						</td>
					</tr>
					<tr>
						<td class="first" valign="top">Data</td>
						<td>
							<input type="text" name="data" size="100" value="' . get_post_meta($post->ID, 'data', true) . '" />
						</td>
					</tr>
					<tr>
						<td class="first" valign="top">Tipo</td>
						<td>
							<select name="tipo">
								<option '.((get_post_meta($post->ID, "tipo", true) == "atual") ? "selected=selected":"").' value="atual">Atual</option>
								<option '.((get_post_meta($post->ID, "tipo", true) == "anterior") ? "selected=selected":"").' value="anterior">Anterior</option>
							</select>
						</td>
					</tr>
					<tr>
						<td class="first" valign="top">Horário</td>
						<td>
							<input type="text" name="horario" size="100" value="' . get_post_meta($post->ID, 'horario', true) . '" />
						</td>
					</tr>

					<tr>
						<td class="first" valign="top">Site</td>
						<td>
							<input type="text" name="link" size="100" value="' . get_post_meta($post->ID, 'link', true) . '" />
						</td>
					</tr>
									
				</tbody>
			</table>
		';
		echo $html;
	}
	
	add_action('save_post', 'save_eventos');
	function save_eventos(){
		global $post;
		if('eventos' == $_POST['post_type']){
			if(!current_user_can('edit_page', $post_id)){
				return $post_id;
			}
		}
	
		if(defined('DOING_AUTOSAVE') && DOING_AUTOSAVE){ 
	    	return;
	    }
	    
	    $campos = array('cidade', 'data', 'horario', 'link');
	    foreach($_POST as $key => $value){
	    	if(in_array($key, $campos)){
	    		update_post_meta($post->ID, $key, $value);
	    	}
	    }

	    if(strlen($_FILES['banner']['tmp_name']) > 0) {
			wp_upload_arquivo_eventos('banner', $post->ID);
		}
	}

	function wp_upload_arquivo_eventos($key, $post_id){
		$file   = $_FILES[$key];
		$upload = wp_handle_upload($file, array('test_form' => false));
		if(!isset($upload['error']) && isset($upload['file'])) {
		    $filetype   = wp_check_filetype(basename($upload['file']), null);
		    $title      = $file['name'];
		    $ext        = strrchr($title, '.');
		    $title      = ($ext !== false) ? substr($title, 0, -strlen($ext)) : $title;
		    $attachment = array(
			'post_mime_type'    => $wp_filetype['type'],
			'post_title'        => addslashes($title),
			'post_content'      => '',
			'post_status'       => 'inherit',
			'post_parent'       => $post_id
		    );

		    $attach_key = $key;
		    $attach_id  = wp_insert_attachment($attachment, $upload['file']);
		    $existing_download = (int) get_post_meta($post_id, $attach_key, true);

		    if(is_numeric($existing_download)) {
			wp_delete_attachment($existing_download);
		    }

		    update_post_meta($post_id, $attach_key, $attach_id);
		}
	}