<?php

/**
 * Plugin Name: PontoCom Depoimentos
 * Plugin URI: http://agenciadeinternet.com/
 * Description: Cadastro de depoimentos.
 * Author: PontoCom Ag&ecirc;ncia de Internet
 * Author URI: http://agenciadeinternet.com/
**/

define ('pcom_depoimentos_DIR', ABSPATH.PLUGINDIR.'/pcom-depoimentos/');
define ('pcom_depoimentos_URL', get_bloginfo('siteurl').'/'.PLUGINDIR.'/pcom-depoimentos');


//-----------------------------------------------------------------------
//DEFININDO ICONS
//-----------------------------------------------------------------------

add_action( 'admin_head', 'z_icon_depoimentos' );
 
function z_icon_depoimentos() {
    ?>
    <style type="text/css" media="screen">
		#menu-posts-depoimentos .wp-menu-image { background: url(<?php echo pcom_depoimentos_URL; ?>/img/icon-depoimento-p.png) no-repeat 6px 6px !important; }
		#menu-posts-depoimentos .wp-menu-image, #menu-posts-depoimentos:hover .wp-menu-image, #menu-posts-depoimentos.wp-has-current-submenu .wp-menu-image { background-position: 4px 5px !important; }
		#icon-edit.icon32-posts-depoimentos { background: url(<?php echo pcom_depoimentos_URL; ?>/img/icon-depoimento-m.png) no-repeat; }
    </style>
<?php }

	
//----------------------------------------------------------------------------------
//REGISTRO DO POST_TYPE
//----------------------------------------------------------------------------------

	/* Post Type Depoimentos*/
	add_action('init','post_type_depoimentos');
	function post_type_depoimentos(){
		register_post_type('depoimentos', array(
			'labels' => array(
				'name' => 'Depoimentos',
				'singular_name' => 'Depoimento',
				'add_new' => 'Adicionar Depoimento',
				'add_new_item' => 'Adicionar Depoimento',
				'edit_item' => 'Editar Depoimento'
			),
			'has_archive' => true,
			'public' => true,
			'exclude_from_search' => true,
			'hierarchical' => true,
			'supports' => array('title', 'thumbnail')
		));
		flush_rewrite_rules();
	}
	
	add_action('admin_init', 'admin_depoimentos_init');
	
	function admin_depoimentos_init(){
		add_meta_box('depoimentos_meta_box', 'Dados', 'depoimentos_meta_box', 'depoimentos');
	}
	
	function depoimentos_meta_box(){
		
		global $post;
		$html = '
			<table class="form-table">
				<tbody>
					<tr>
						<td class="first" valign="top">
							Tipo do depoimento:
							<select name="tipo" onChange="mostraSelect()">
								<option '.((get_post_meta($post->ID, "tipo", true) == "normal") ? "selected=selected":"").' value="normal">Normal</option>
								<option '.((get_post_meta($post->ID, "tipo", true) == "impressora") ? "selected=selected":"").' value="impressora">Impressora</option>
								<option '.((get_post_meta($post->ID, "tipo", true) == "suprimento") ? "selected=selected":"").' value="suprimento">Suprimento</option>
							</select>
						</td>
						<td class="select-impressora" '.((get_post_meta($post->ID, "tipo", true) == "impressora") ? "":"style=\"display:none\"").'>
							Selecione a Impressora:
							<select name="id-impressora">';
								$impressoras = get_posts(array('post_type' => 'impressora', 'numberposts' => -1, 'post_status' => 'publish'));
								foreach($impressoras as $impressora){
									$html.='<option '.((get_post_meta($post->ID, "id-impressora", true) == $impressora->ID) ? "selected=selected":"").' value="'.$impressora->ID.'">'.get_the_title($impressora->ID).'</option>';
								}
							$html.='</select>
						</td>
						<td class="select-suprimento" '.((get_post_meta($post->ID, "tipo", true) == "suprimento") ? "":"style=\"display:none\"").'>
							Selecione o Suprimento:
							<select name="id-suprimento">';
								$suprimentos = get_posts(array('post_type' => 'suprimentos', 'numberposts' => -1, 'post_status' => 'publish'));
								foreach($suprimentos as $suprimento){
									$html.='<option '.((get_post_meta($post->ID, "id-suprimento", true) == $suprimento->ID) ? "selected=selected":"").' value="'.$suprimento->ID.'">'.get_the_title($suprimento->ID).'</option>';
								}
							$html.='</select>
						</td>
					<tr>
					<tr>
						<td class="first" valign="top">Depoimento</td>
						<td>
							<textarea name="depoimento" cols="120" rows="10">' . get_post_meta($post->ID, 'depoimento', true) . '</textarea>
						</td>
					</tr>

					<tr>
						<td class="first" valign="top">Empresa</td>
						<td>
							<input type="text" name="empresa" size="120" value="' . get_post_meta($post->ID, 'empresa', true) . '" />
						</td>
					</tr>
					
					<tr>
						<td class="first" valign="top">Equipamento</td>
						<td>
							<input type="text" name="equipamento" size="120" value="' . get_post_meta($post->ID, 'equipamento', true) . '" />
						</td>
					</tr>

					<tr>
						<td class="first" valign="top">Link do Site</td>
						<td>
							<input type="text" name="link" size="120" value="' . get_post_meta($post->ID, 'link', true) . '" />
						</td>
					</tr>
									
				</tbody>
			</table>

			<script type="text/javascript">
				function mostraSelect(){
					if(jQuery("select[name=\'tipo\']").val() == "impressora"){
						jQuery(".select-impressora").css("display", "block");
						jQuery(".select-suprimento").css("display", "none");
					} else if(jQuery("select[name=\'tipo\']").val() == "suprimento"){
						jQuery(".select-impressora").css("display", "none");
						jQuery(".select-suprimento").css("display", "block");
					} else {
						jQuery(".select-impressora").css("display", "none");
						jQuery(".select-suprimento").css("display", "none");
					}
				}
			</script>
		';
		echo $html;
	}
	
	add_action('save_post', 'save_depoimentos');
	function save_depoimentos(){
		global $post;
		if('depoimentos' == $_POST['post_type']){
			if(!current_user_can('edit_page', $post_id)){
				return $post_id;
			}
		}
	
		if(defined('DOING_AUTOSAVE') && DOING_AUTOSAVE){ 
	    	return;
	    }
	    
	    $campos = array('tipo', 'id-impressora', 'id-suprimento', 'depoimento', 'empresa', 'equipamento', 'link');
	    foreach($_POST as $key => $value){
	    	if(in_array($key, $campos)){
	    		update_post_meta($post->ID, $key, $value);
	    	}
	    }

	    if(get_post_meta($post->ID, 'tipo', true) == 'normal'){
	    	update_post_meta($post->ID, 'id-impressora', 0);
	    	update_post_meta($post->ID, 'id-suprimento', 0);
	    } elseif(get_post_meta($post->ID, 'tipo', true) == 'impressora'){
	    	update_post_meta($post->ID, 'id-suprimento', 0);
	    } elseif(get_post_meta($post->ID, 'tipo', true) == 'suprimento'){
	    	update_post_meta($post->ID, 'id-impressora', 0);
	    }
	}