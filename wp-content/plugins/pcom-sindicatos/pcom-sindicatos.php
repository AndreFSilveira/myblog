<?php

/**
 * Plugin Name: PontoCom Sindicatos
 * Plugin URI: http://agenciadeinternet.com/
 * Description: Cadastro de Sindicatos.
 * Author: PontoCom Ag&ecirc;ncia de Internet
 * Author URI: http://agenciadeinternet.com/
**/

define ('pcom_sindicatos_DIR', ABSPATH.PLUGINDIR.'/pcom-sindicatos/');
define ('pcom_sindicatos_URL', get_bloginfo('siteurl').'/'.PLUGINDIR.'/pcom-sindicatos');

//--------------------------------------------------------------------
//REGISTRO DO POST_TYPE
//--------------------------------------------------------------------
	
	add_action('init','post_type_sindicatos');
	function post_type_sindicatos(){
		register_post_type('sindicato', array(
			'labels' => array(
				'name' => 'Sindicatos',
				'singular_name' => 'Sindicato',
				'add_new' => 'Adicionar Sindicato',
				'add_new_item' => 'Adicionar Sindicato',
				'edit_item' => 'Editar Sindicato'
			),
			'public' => true,
			'hierarchical' => true,
			'supports' => array('title')
		));
		flush_rewrite_rules();
	}

	//municipios
	/*add_action( 'init', 'build_taxonomies_sindicatos', 0 );
	function build_taxonomies_sindicatos() {
		$labels = array(
		    'name'                => 'Municípios',
		    'singular_name'       => 'Município',
		    'search_items'        => 'Buscar Municípios',
		    'all_items'           => 'Todos Municípios',
		    'edit_item'           => 'Editar Município', 
		    'update_item'         => 'Atualizar Município',
		    'add_new_item'        => 'Adicionar Nova Município',
		    'menu_name'           => 'Municípios'
		  ); 
		register_taxonomy('municipio', array('sindicato'), 
			array(
				'hierarchical' => true,
				'show_ui' => true,
				'labels' => $labels, 
				'public' => true
			)
		);
	}*/

	add_action('admin_init', 'admin_sindicatos_init');
	
	function admin_sindicatos_init(){
		add_meta_box('sindicatos_meta_box', 'Dados do Sindicato', 'sindicatos_meta_box', 'sindicato');
		add_meta_box('sindicatos_mapa_meta_box', 'Mapa', 'sindicatos_mapa_meta_box', 'sindicato');
	}	
	
	function sindicatos_meta_box(){
		global $post;
		$html = '
			<table class="form-table">
				<tbody>
					<tr>
						<td class="first" valign="top">ID iframe</td>
						<td>
							<input type="text" id="id_iframe" name="id_iframe" size="100" value="' . get_post_meta($post->ID, 'id_iframe', true) . '" />
						</td>
					</tr>
					<tr>
						<td class="first" valign="top">Endereço</td>
						<td>
							<input type="text" id="endereco" name="endereco" size="100" value="' . get_post_meta($post->ID, 'endereco', true) . '" />
						</td>
					</tr>
					<tr>
						<td class="first" valign="top">Cidade</td>
						<td>
							<input type="text" id="cidade" name="cidade" size="100" value="' . get_post_meta($post->ID, 'cidade', true) . '" />
						</td>
					</tr>
					<tr>
						<td class="first" valign="top">Estado</td>
						<td>
							<input type="text" id="estado" name="estado" size="100" value="' . get_post_meta($post->ID, 'estado', true) . '" />
						</td>
					</tr>
					<tr>
						<td class="first" valign="top">CEP</td>
						<td>
							<input type="text" id="cep" name="cep" size="100" value="' . get_post_meta($post->ID, 'cep', true) . '" />
						</td>
					</tr>
				</tbody>
			</table>
		';
		echo $html;
	}

	function sindicatos_mapa_meta_box(){
		global $post;
		$html = '
			<a href="javascript: void(0)" onclick="geraMapa()">Gerar Mapa, Latitude e Longitude pelos dados de endereço</a>
			
			<div class="mapa">
				<table class="form-table">
					<tbody>
						<tr>
							<td class="first" valign="top">Latitude</td>
							<td>
								<input type="text" name="latitude" id="latitude" size="30" value="'.get_post_meta($post->ID, 'latitude', true).'" />
							</td>
							<td class="first" valign="top">Longitude</td>
							<td>
								<input type="text" name="longitude" id="longitude" size="30" value="'.get_post_meta($post->ID, 'longitude', true).'" />
							</td>
						</tr>
						<tr>
							<td colspan="4">Clique <a href="javascript: void(0)" onclick="geraMapa2()">aqui</a> para gerar o mapa com a Latitude e Longitude digitadas acima.</td>
						</tr>
					</tbody>
				</table>
			</div>
		';
		echo $html;?>
		<script type="text/javascript">
			function geraMapa(){
				var endereco = jQuery('#endereco').val()+',+'+jQuery('#cidade').val()+',+'+jQuery('#estado').val()+',+'+jQuery('#cep').val();
				endereco=endereco.replace(/ /g, '+');
				jQuery('.mapa').html('<img src="<?php echo pcom_sindicatos_URL; ?>/ajax-loader.gif" />');
				jQuery('.mapa').load('<?php echo pcom_sindicatos_URL; ?>/ajax-mapa.php?endereco='+endereco);
			}
			function geraMapa2(){
				var latitude = jQuery('#latitude').val();
				var longitude = jQuery('#longitude').val();
				jQuery('.mapa').html('<img src="<?php echo pcom_sindicatos_URL; ?>/ajax-loader.gif" />');
				jQuery('.mapa').load('<?php echo pcom_sindicatos_URL; ?>/ajax-mapa.php?latitude='+latitude+'&longitude='+longitude);
			}
		</script>
		<?php 
	}
	
	add_action('save_post', 'save_sindicatos');
	function save_sindicatos(){
		global $post;
		if('sindicato' == $_POST['post_type']){
			if(!current_user_can('edit_page', $post_id)){
				return $post_id;
			}
		}
	
		if(defined('DOING_AUTOSAVE') && DOING_AUTOSAVE){ 
	    	return;
	    }
	    
	    $campos = array('id_iframe', 'endereco', 'cidade', 'estado', 'complemento', 'cep', 'latitude', 'longitude');
	    foreach($_POST as $key => $value){
	    	if(in_array($key, $campos)){
	    		update_post_meta($post->ID, $key, $value);
	    	}
	    }

	    if($_POST['latitude'] && $_POST['longitude']){
	    	$sindicatos = get_posts(array('post_type'=>'sindicato','posts_per_page'=>-1));
	    	
	    	$sindicatos_mapa = array();
	    	foreach($sindicatos as $sindicato):
	    		if(get_post_meta($sindicato->ID, 'latitude', true) && get_post_meta($sindicato->ID, 'longitude', true)):
	    			$sindicatos_mapa[] = $sindicato;
	    		endif;
	    	endforeach;

	    	$json = '
	    		[';
	    	$num_sindicatos = count($sindicatos_mapa);
	    	$count = 1;
	    	foreach($sindicatos_mapa as $sindicato):
			    	$json.='
			    		{ 
			    			"Id": '.$sindicato->ID.',
			    			"Latitude": '.get_post_meta($sindicato->ID, 'latitude', true).',
			    			"Longitude": '.get_post_meta($sindicato->ID, 'longitude', true).',
			    			"Descricao": "'.get_the_title($sindicato->ID).'"
			    		}';
			    		$json.=($count!=$num_sindicatos) ? ',' : '';
		    		$count++;
	    	endforeach;
	    	$json.='
	    		]';

	    	$arquivo = fopen(ABSPATH.'/wp-content/uploads/sindicatos.json', 'w+');
	    	fwrite($arquivo, $json);
	    	fclose($arquivo);
	    }

	}
?>
