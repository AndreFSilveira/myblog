<?php

/**
 * Plugin Name: PontoCom Functions
 * Plugin URI: http://agenciadeinternet.com/
 * Description: Funções personalizadas.
 * Author: PontoCom Ag&ecirc;ncia de Internet
 * Author URI: http://agenciadeinternet.com/
**/


define ('pcom_functions_DIR', ABSPATH.PLUGINDIR.'/pcom-functions/');
define ('pcom_functions_URL', get_bloginfo('siteurl').'/'.PLUGINDIR.'/pcom-functions');

// ----------------------------------------------------------------------------------------
//	MUDAR LOGO FORMULARIO DE LOGIN E TOPO ADMIN
// ----------------------------------------------------------------------------------------

function meu_logo_admin() {
echo '
<style type="text/css">
#header-logo {background-image: url('.pcom_functions_URL.'/img/pcom.png) !important;}
</style>
';
}
add_action('admin_head', 'meu_logo_admin');

function meu_login_logo() {
echo '
<style type="text/css">
h1 a {background:url('.pcom_functions_URL.'/img/pcom_login.png) 50% 50% no-repeat !important;}
</style>
';
}
add_action('login_head', 'meu_login_logo');
function meu_wp_login_url() {
return 'http://www.agenciadeinternet.com/';
}
add_filter('login_headerurl', 'meu_wp_login_url');
function meu_wp_login_title() {
return 'PontoCom - Ag&ecirc;ncia de Internet';
}
add_filter('login_headertitle', 'meu_wp_login_title');

// ----------------------------------------------------------------------------------------
//	MUDAR TEXTO DO RODAPE DO ADMIN
// ----------------------------------------------------------------------------------------

function meu_footer_css () {
echo '
<style type="text/css">
#footer-upgrade {visibility:visible !important;}
#footer-left {float:left !important;}
</style>
';
}
add_action('admin_head', 'meu_footer_css');
function meu_footer_admin () {
echo '<a href="http://www.agenciadeinternet.com/" title="PontoCom" alt="PontoCom">PontoCom</a> - Ag&ecirc;ncia de Internet e ponto - <b>41 3023-9194</b>';
}
add_filter('admin_footer_text', 'meu_footer_admin');


// ----------------------------------------------------------------------------------------
// DESATIVA BARRA DO ADMIN
// ----------------------------------------------------------------------------------------

	add_filter( 'show_admin_bar', '__return_false' );
	
// ----------------------------------------------------------------------------------------
//	SHORTCUT ICON
// ----------------------------------------------------------------------------------------

/* 	function blog_favicon() {
		echo '<link rel="Shortcut Icon" type="image/x-icon" href="'.get_bloginfo('wpurl').'/favicon.ico" />';
	}
	add_action('wp_head', 'blog_favicon');
 */
// ----------------------------------------------------------------------------------------
//	DEFINIÇOES GERAIS DO THEME
// ----------------------------------------------------------------------------------------

/* 	$twitter_user = "http://twitter.com/".get_option('_theme_twitter');
    define (TWITTER_URL, $twitter_user);

	$facebook_user = "http://www.facebook.com.br/".get_option('_theme_facebook');
    define (FACEBOOK_URL, $facebook_user);

	$youtube_user = "http://www.youtube.com.br/".get_option('_theme_youtube');
    define (YOUTUBE_URL, $youtube_user);
 */
// ----------------------------------------------------------------------------------------
//	ADICIONANDO RESUMOS NAS PAGINAS
// ----------------------------------------------------------------------------------------
	
	add_post_type_support( 'page', 'excerpt' );

// ----------------------------------------------------------------------------------------
//	INCLUDE JAVASCRIPTS
// ----------------------------------------------------------------------------------------
/* 
	add_action ('wp_footer', 'theme_javascript');
	function theme_javascript () {
		?>
		<script type="text/javascript" src="<?php bloginfo('stylesheet_directory'); ?>/js/winnikes.js"></script>
		<?php
	}
 */

// ----------------------------------------------------------------------------------------
//	MENUS DO SITE
// ----------------------------------------------------------------------------------------

	add_action ('init', 'theme_register_menus');
    function theme_register_menus () {
        register_nav_menus (
            array (
				'menu-topo' => 'Menu Topo',
				'menu-rodape' => 'Menu Rodapé',
				'links-rodape1' => 'Links Rodapé 1',
				'links-rodape2' => 'Links Rodapé 2',
				'links-rodape3' => 'Links Rodapé 3',
				'links-rodape4' => 'Links Rodapé 4',
				'links-rodape5' => 'Links Rodapé 5'
            )
        );
    }
    //para chamar o menu utiliza o seguinte código
    //wp_nav_menu(array('theme_location'=>'menu-topo'));
	
// ----------------------------------------------------------------------------------------
//	REGISTRO E CONFIGURAÇOES DAS IMAGENS DESTACADAS
// ----------------------------------------------------------------------------------------

	add_theme_support( 'post-thumbnails' );


// ----------------------------------------------------------------------------------------
//	REGISTRO DAS ÁREAS DE WIDGETS
// ----------------------------------------------------------------------------------------

function pcom_widgets_init() {
	
	register_sidebar( array(
		'name' => 'Sidebar 1',
		'id' => 'sidebar-1',
		'description' => 'Sidebar 1',
		'before_widget' => '<div class="widget">',
		'after_widget' => '</div>',
		'before_title' => '<div class="header"><h2>',
		'after_title' => '</h2></div>',
	) );

	register_sidebar( array(
		'name' => 'Sidebar 2',
		'id' => 'sidebar-2',
		'description' => 'Sidebar 2',
		'before_widget' => '<div class="widget">',
		'after_widget' => '</div>',
		'before_title' => '<div class="header"><h2>',
		'after_title' => '</h2></div>',
	) );

	register_sidebar( array(
		'name' => 'Sidebar 3',
		'id' => 'sidebar-3',
		'description' => 'Sidebar 3',
		'before_widget' => '<div class="widget">',
		'after_widget' => '</div>',
		'before_title' => '<div class="header"><h2>',
		'after_title' => '</h2></div>',
	) );

	register_sidebar( array(
		'name' => 'Sidebar 4',
		'id' => 'sidebar-4',
		'description' => 'Sidebar 4',
		'before_widget' => '<div class="widget">',
		'after_widget' => '</div>',
		'before_title' => '<div class="header"><h2>',
		'after_title' => '</h2></div>',
	) );

	register_sidebar( array(
		'name' => 'Sidebar 5',
		'id' => 'sidebar-5',
		'description' => 'Sidebar 5',
		'before_widget' => '<div class="widget">',
		'after_widget' => '</div>',
		'before_title' => '<div class="header"><h2>',
		'after_title' => '</h2></div>',
	) );

	register_sidebar( array(
		'name' => 'Center Home',
		'id' => 'center-home',
		'description' => 'Center Home',
		'before_widget' => '<div class="widget">',
		'after_widget' => '</div>',
		'before_title' => '<div class="header"><h2>',
		'after_title' => '</h2></div>',
	) );

}
add_action( 'widgets_init', 'pcom_widgets_init' );

// ----------------------------------------------------------------------------------------
//	PERMITE UPLOAD DE ARQUIVOS EM POST TYPES
// ----------------------------------------------------------------------------------------
add_action( 'post_edit_form_tag' , 'post_edit_form_tag' );
function post_edit_form_tag( ) {
    echo ' enctype="multipart/form-data"';
}

// ----------------------------------------------------------------------------------------
//	TRADUÇÂO EM TAXONOMIES
// ----------------------------------------------------------------------------------------
function qtranslate_edit_taxonomies(){
   $args=array(
      'public' => true ,
      '_builtin' => false
   );
   $output = 'object'; // or objects
   $operator = 'and'; // 'and' or 'or'

   $taxonomies = get_taxonomies($args,$output,$operator);

   if  ($taxonomies) {
     foreach ($taxonomies  as $taxonomy ) {
         add_action( $taxonomy->name.'_add_form', 'qtrans_modifyTermFormFor');
         add_action( $taxonomy->name.'_edit_form', 'qtrans_modifyTermFormFor');
     }
   }
}
add_action('admin_init', 'qtranslate_edit_taxonomies');


// ----------------------------------------------------------------------------------------
//	Funções de Upload e Remoção de arquivos em Post Types
// ----------------------------------------------------------------------------------------
function wp_upload_arquivo_post_type($key, $post_id){
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

function wp_remove_arquivo_post_type($key, $post_id){
	$attach_key = $key;
    $existing_download = (int) get_post_meta($post_id, $attach_key, true);

    if(is_numeric($existing_download)) {
		wp_delete_attachment($existing_download);
		update_post_meta($post_id, $attach_key, 'vazio');
    }
}