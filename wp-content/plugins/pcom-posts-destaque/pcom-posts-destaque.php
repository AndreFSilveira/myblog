<?php
/**
 * Plugin Name: PontoCom Posts Destaque
 * Plugin URI: http://agenciadeinternet.com/
 * Description: Vitrine personalizada com posts.
 * Author: PontoCom Ag&ecirc;ncia de Internet
 * Author URI: http://agenciadeinternet.com/
**/

define ('pcom_vitrine_DIR', ABSPATH.PLUGINDIR.'/pcom-vitrine/');
define ('pcom_vitrine_URL', get_bloginfo('siteurl').'/'.PLUGINDIR.'/pcom-vitrine');


//-----------------------------------------------------------------------
//DEFININDO ICONS
//-----------------------------------------------------------------------

add_action( 'admin_head', 'z_icon_vitrine' );
 
function z_icon_vitrine() {
    ?>
    <style type="text/css" media="screen">
			#menu-posts-destaque .wp-menu-image { background: url(<?php echo pcom_vitrine_URL; ?>/img/icon-vitrine-p.png) no-repeat 6px 6px !important; }
			#menu-posts-destaque .wp-menu-image, #menu-posts-destaque:hover .wp-menu-image, #menu-posts-destaque.wp-has-current-submenu .wp-menu-image { background-position: 4px 5px !important; }
			#icon-edit.icon32-posts-destaque { background: url(<?php echo pcom_vitrine_URL; ?>/img/icon-vitrine-m.png) no-repeat; }
    </style>
<?php }


//--------------------------------------------------------------------
//REGISTRO DO POST_TYPE
//--------------------------------------------------------------------

	add_action("init","post_type_destaque");
	function post_type_destaque(){
		register_post_type("destaque", array(
			"labels" => array(
				"name" => "Destaques",
				"singular_name" => "Destaque",
				"add_new" => "Adicionar destaque",
				"add_new_item" => "Adicionar destaque",
				"edit_item" => "Editar destaque"
			),
			"has_archive" => true,
			"public" => true,
			"exclude_from_search" => true,
			"supports" => array("title", "thumbnail", "page-attributes")
		));
		flush_rewrite_rules();
	}
	
	add_action("admin_init", "admin_destaque_init");
	
	function admin_destaque_init(){
		add_meta_box("destaque_meta_box", "Dados destaque", "destaque_meta_box", "destaque");
		add_meta_box("destaque_posts_meta_box", "Procurar Post", "destaque_posts_meta_box", "destaque");
	}
	
	function destaque_meta_box(){
		
		global $post;
		$html = '
			<table class="form-table">
				<tbody>
					<tr>
						<td style="width:75px;" valign="top">
							ID Post
							<input type="text" name="id-post" size="10" value="' . get_post_meta($post->ID, "id-post", true) . '" />
						</td>
						<td style="width:160px;" valign="top">
							Usar Imagem personalizada:
							<select name="imagem" onChange="usaThumb()">
								<option '.((get_post_meta($post->ID, "imagem", true) == "0") ? "selected=selected":"").' value="0">Não</option>
								<option '.((get_post_meta($post->ID, "imagem", true) == "1") ? "selected=selected":"").' value="1">Sim</option>
							</select>
						</td>
					</tr>
				</tbody>
			</table>
		';
		echo $html;
	}
	
	function destaque_posts_meta_box(){
		
		global $post;
		$html = '
			<table class="wp-list-table widefat fixed posts" cellspacing="0">
				<thead>
				<tr>
					<th scope="col" id="title" class="manage-column column-title sortable desc"  style="padding-left:10px;">Título</th>
					<th scope="col" id="categories" class="manage-column column-categories"  style="">Categorias</th>
					<th scope="col" id="date" class="manage-column column-date sortable asc"  style="">Data</th>
				</tr>
				</thead>

				<tfoot>
					<tr>
						<th scope="col"  class="manage-column column-title sortable desc" style="padding-left:10px;">Título</th>
						<th scope="col"  class="manage-column column-categories"  style="">Categorias</th>
						<th scope="col"  class="manage-column column-date sortable asc"  style="">Data</th>
					</tr>
				</tfoot>

				<tbody id="the-list">';
				$posts = get_posts(array('post_status' => 'publish', 'numberposts' => -1));
				foreach($posts as $p):
				$id_post = $p->ID;
				$html.='
					<tr>
						<td class="post-title"><strong><a onClick="pegaPost('.$id_post.', \''.get_the_title($id_post).'\')" class="row-title" style="cursor:pointer">'.get_the_title($id_post).'</a></strong>
							<div class="row-actions">Clique para usar </div>
						</td>
						<td class="categories">';
						$categories = get_the_category($id_post);
						foreach($categories as $cat):
							$html.= $cat->name.' ';
						endforeach;
						$html.='</td>
						<td class="date column-date">'.mysql2date('d/m/Y', $p->post_date).' às '.mysql2date('H:i', $p->post_date).'</td>
					</tr>';
				endforeach;	
			
				$html.='</tbody>
			</table>
			
			<script>
				jQuery("#postimagediv").css("display", "none");
				
				function pegaPost(id, title){
					jQuery("input[name=\'id-post\']").val(id);
					jQuery("input[name=\'post_title\']").val(title);
					jQuery("label.hide-if-no-js").css("visibility", "hidden");
					jQuery("#posts_meta_box").addClass("closed");
					jQuery("html, body").animate({scrollTop:0}, "slow");
				}
				
				function usaThumb(){
					if(jQuery("select[name=\'imagem\']").val() == 1){
						jQuery("#postimagediv").css("display", "block");
					} else {
						jQuery("#postimagediv").css("display", "none");
					}
				}
			</script>
		';
		echo $html;
	}
	
	
	add_action("save_post", "save_destaque");
	function save_destaque(){
		global $post;
		if("destaque" == $_POST["post_type"]){
			if(!current_user_can("edit_page", $post_id)){
				return $post_id;
			}
		}
	
		if(defined("DOING_AUTOSAVE") && DOING_AUTOSAVE){ 
	    	return;
	    }
	    
	    $campos = array("imagem", "id-post");
	    foreach($_POST as $key => $value){
	    	if(in_array($key, $campos)){
	    		update_post_meta($post->ID, $key, $value);
	    	}
	    }
	}