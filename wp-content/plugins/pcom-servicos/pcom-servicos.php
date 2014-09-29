<?php

/**
 * Plugin Name: PontoCom Serviços
 * Plugin URI: http://agenciadeinternet.com/
 * Description: Cadastro de Serviços.
 * Author: PontoCom Ag&ecirc;ncia de Internet
 * Author URI: http://agenciadeinternet.com/
**/

define ('pcom_servicos_DIR', ABSPATH.PLUGINDIR.'/pcom-servicos/');
define ('pcom_servicos_URL', get_bloginfo('siteurl').'/'.PLUGINDIR.'/pcom-servicos');

//-----------------------------------------------------------------------
//DEFININDO ICONS
//-----------------------------------------------------------------------

add_action( 'admin_head', 'z_icon_servicos' );
 
function z_icon_servicos() {
    ?>
    <style type="text/css" media="screen">
		#menu-posts-servico .wp-menu-image { background: url(<?php echo pcom_servicos_URL; ?>/img/icon-servico-p.png) no-repeat 6px 6px !important; }
		#menu-posts-servico .wp-menu-image, #menu-posts-servico:hover .wp-menu-image, #menu-posts-servico.wp-has-current-submenu .wp-menu-image { background-position: 4px 5px !important; }
		#icon-edit.icon32-posts-servicos { background: url(<?php echo pcom_servicos_URL; ?>/img/icon-servico-m.png) no-repeat; }
    </style>
<?php }

	
//--------------------------------------------------------------------
//REGISTRO DO POST_TYPE
//--------------------------------------------------------------------
	
	add_action('init','post_type_servicos');
	function post_type_servicos(){
		register_post_type('servico', array(
			'labels' => array(
				'name' => 'Serviços',
				'singular_name' => 'Serviço',
				'add_new' => 'Adicionar Serviço',
				'add_new_item' => 'Adicionar Serviço',
				'edit_item' => 'Editar Serviço'
			),
			'public' => true,
			'hierarchical' => true,
			'supports' => array('title', 'editor', 'thumbnail')
		));
		flush_rewrite_rules();
	}

	add_action('admin_init', 'admin_servicos_init');
	
	function admin_servicos_init(){
		add_meta_box('servicos_meta_box', 'Dados do Serviço', 'servicos_meta_box', 'servico');
	}	
	
	function servicos_meta_box(){
		global $post;
		$html = '
			<table class="form-table">
				<tbody>
					<tr>
						<td class="first" valign="top">Arquivos</td>
						<td>';
							$count=0;
							if($arquivos = get_post_meta($post->ID, 'arquivos', true)):
								$items_arquivos = unserialize($arquivos);
								foreach($items_arquivos as $item):
									$id = $item;
									$html.='<div id="item'.$count.'" class="item">
												Arquivo'.($count+1).'<input type="file" name="arquivo'.$count.'" />';
												$link = wp_get_attachment_url( $id );
												$link_explode = explode('/', $link);
												$html.='<a class="download" href="'.$link.'" target="_blank">'.end($link_explode).'</a>
												<a class="remove" href="javascript: void(0)" onclick="if(!confirm(\'Tem certeza que deseja excluir o Arquivo'.($count+1).'?\')){ return false; } jQuery(\'#item'.$count.'\').fadeOut().remove();"">Remover</a>
											</div>';
									$count++;
								endforeach;
							endif;
							$html.='<input id="num_arquivos" type="hidden" name="num_arquivos" value="'.$count.'">
									<div id="item'.$count.'" class="item">
										Arquivo'.($count+1).'<input type="file" name="arquivo'.$count.'" />
										<a class="remove" href="javascript: void(0)" onclick="if(!confirm(\'Tem certeza que deseja excluir o item '.($count+1).'?\')){ return false; } jQuery(\'#item'.$count.'\').fadeOut().remove();"">Remover</a>
									</div>';
					$html.='<a id="mais-arquivos" href="javascript: void(0)" onclick="return mostraInput('.($count+1).')">+ Clique para adicionar mais arquivos</a>
						</td>
					</tr>
				</tbody>
			</table>
			<style>
				.remove{background:url('.pcom_servicos_URL.'/img/remove.png) no-repeat left center;padding-left:20px;margin-left:10px;text-decoration:none}
				.download{background:url('.pcom_servicos_URL.'/img/download.png) no-repeat left center;padding-left:20px; margin-left:10px;text-decoration:none}
			</style>
		';
		echo $html;
		?>
			<script type="text/javascript">
				function mostraInput(num){
					jQuery(function(){
						jQuery("#mais-arquivos").after('<div id="item'+num+'" class="item">Arquivo'+(num+1)+'<input type="file" name="arquivo'+num+'" /><a class="remove" href="javascript: void(0)" onclick="if(!confirm(\'Tem certeza que deseja excluir o item '+(num+1)+'?\')){ return false; } jQuery(\'#item'+num+'\').fadeOut().remove();">Remover</a></div>');
						jQuery('#mais-arquivos').remove();
					    jQuery(".item:last-child").after('<a id="mais-arquivos" href="javascript: void(0)" onclick="return mostraInput('+(num+1)+')">+ Clique para adicionar mais arquivos</a>');
					    jQuery("#num_arquivos").val(num);
					});
				}
			</script>
			<?php 
	}
	
	add_action('save_post', 'save_servicos');
	function save_servicos(){
		global $post;
		if('servico' == $_POST['post_type']){
			if(!current_user_can('edit_page', $post_id)){
				return $post_id;
			}
		}
	
		if(defined('DOING_AUTOSAVE') && DOING_AUTOSAVE){ 
	    	return;
	    }
	    
	    /*$campos = array('link_embed');
	    foreach($_POST as $key => $value){
	    	if(in_array($key, $campos)){
	    		update_post_meta($post->ID, $key, $value);
	    	}
	    }*/

	    $arquivos = unserialize(get_post_meta($post->ID, 'arquivos', true));
	    $ids_arquivos = array();
	   
	   	for ($i=0; $i <= $_POST['num_arquivos']; $i++) { 
	    	if(isset($_FILES['arquivo'.$i])){
		    	if(strlen($_FILES['arquivo'.$i]['tmp_name']) > 0){
		    		$ids_arquivos[] = wp_upload_arquivo_servicos($_FILES['arquivo'.$i], $post->ID);
		    	} elseif(count($arquivos[$i])){
		    		$ids_arquivos[] = $arquivos[$i];
		    	}
		    } else {
		    	wp_delete_attachment((int) $arquivos[$i]);
		    }
	    }

	    if(count($ids_arquivos)){
	    	$arquivos = serialize($ids_arquivos);
	    	update_post_meta($post->ID, 'arquivos', $arquivos);
	    } else {
	    	update_post_meta($post->ID, 'arquivos', 0);
	    }
	}

	function wp_upload_arquivo_servicos($file, $post_id){

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

		    $attach_id  = wp_insert_attachment($attachment, $upload['file']);

		    return $attach_id;
		}
	}
?>
