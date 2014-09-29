<?php
/**
 * Plugin Name: PontoCom Vitrine
 * Plugin URI: http://agenciadeinternet.com/
 * Description: Vitrine personalizada.
 * Author: PontoCom Ag&ecirc;ncia de Internet
 * Author URI: http://agenciadeinternet.com/
**/

define ('pcom_vitrine_DIR', ABSPATH.PLUGINDIR.'/pcom-vitrine/');
define ('pcom_vitrine_URL', get_bloginfo('siteurl').'/'.PLUGINDIR.'/pcom-vitrine');


//-----------------------------------------------------------------------
//DEFININDO ICONS
//-----------------------------------------------------------------------

/*add_action( 'admin_head', 'z_icon_vitrine' );
 
function z_icon_vitrine() {
    ?>
    <style type="text/css" media="screen">
			#menu-posts-vitrine .wp-menu-image { background: url(<?php echo pcom_vitrine_URL; ?>/img/icon-vitrine-p.png) no-repeat 6px 6px !important; }
			#menu-posts-vitrine .wp-menu-image, #menu-posts-vitrine:hover .wp-menu-image, #menu-posts-vitrine.wp-has-current-submenu .wp-menu-image { background-position: 4px 5px !important; }
			#icon-edit.icon32-posts-vitrine { background: url(<?php echo pcom_vitrine_URL; ?>/img/icon-vitrine-m.png) no-repeat; }
    </style>
<?php }
*/

//--------------------------------------------------------------------
//REGISTRO DO POST_TYPE
//--------------------------------------------------------------------

	add_action ('init', 'vitrine_custom_post_types');
	function vitrine_custom_post_types () {
		$args = array(
			'label' => 'Vitrines',
			'labels' => array (
				'name' => 'Vitrines',
				'singular_name' => 'Vitrine',
				'add_new' => 'Adicionar Vitrine',
				'add_new_item' => 'Adicionar Vitrine'
			),
			'hierarchical' => false,
			'supports' => array ('title','thumbnail','page-attributes'),
			'public' => true,
            'has_archive' => false,
            'can_export' => true,
		);
		register_post_type ('vitrine', $args);
        flush_rewrite_rules ();
	}
	
//--------------------------------------------------------------------
// COLUNAS DA LISTAGEM
//--------------------------------------------------------------------
	
	add_action('manage_edit-vitrine_columns', 'vitrine_colunas');
	function vitrine_colunas ($colunas) {
		$colunas = array(
			'cb' => '<input type="checkbox" />',
			'titulo-vitrine' => 'Titulo',
            'link-vitrine' => 'Link',
            'imagem-vitrine' => 'Imagem'
		);
		return $colunas;
	}

	add_filter('manage_posts_custom_column', 'vitrine_campos_func');
	function vitrine_campos_func ($coluna) {
		global $post;
		?>
        <style type="text/css">
            .imagem-vitrine img {width:250px !important; height: 70px !important; }
        </style>
		<?php
		switch ($coluna) {
			case 'titulo-vitrine' : echo '<b><a href="post.php?action=edit&post='.$post->ID.'">'.$post->post_title.'</a></b>'; break;
			case 'link-vitrine' : echo get_post_meta($post->ID, '_vitrine_link', 1); break;
			case 'imagem-vitrine' : if(has_post_thumbnail($post->ID)){ echo get_the_post_thumbnail( $post->ID, 'vitrine-admin');} else {echo '<img src="'.get_bloginfo("template_url").'/img/vitrine.png" />';} break;
		}
	}

//--------------------------------------------------------------------
// META_BOX
//--------------------------------------------------------------------

	add_action ('admin_menu', 'vitrine_metabox_link');
	function vitrine_metabox_link () {
		add_meta_box ('vitrine_meta_link', 'Link', 'vitrine_meta_box_link', 'vitrine');
	}
	
	function vitrine_meta_box_link () {
		global $post, $wpdb;
		?>
        <style type="text/css">
            #sigla {width:5%;}
            #pequeno {width:17%;}
            #grande {width:100%;}
            .space {margin-left:30px;}
        </style>
        
		<fieldset id="vitrine_fields">
			<table class="widefat">
				<tr>
					<td><input id="grande" type="text" name="vitrine[link]" value="<?php echo get_post_meta($post->ID, '_vitrine_link', 1); ?>" /></td>
				</tr>
			</table>
		</fieldset>
		<?php
	}
	
	if ($_POST['post_type'] == 'vitrine') add_action('save_post', 'vitrine_save_postdata');
	function vitrine_save_postdata ( $post_id ) {
		
		global $wpdb;

        if (!current_user_can('edit_post', $post_id))
			return $post_id;
			
		if (isset($_POST['vitrine'])){
			foreach ($_POST['vitrine'] as $k => $v) {
				update_post_meta ($post_id, '_vitrine_'.$k, $v);
			}
		}	

	}

?>

