<?php

/**
 * Plugin Name: PontoCom Regionais
 * Plugin URI: http://agenciadeinternet.com/
 * Description: Cadastro de Regionais.
 * Author: PontoCom Ag&ecirc;ncia de Internet
 * Author URI: http://agenciadeinternet.com/
**/

define ('pcom_regionais_DIR', ABSPATH.PLUGINDIR.'/pcom-regionais/');
define ('pcom_regionais_URL', get_bloginfo('siteurl').'/'.PLUGINDIR.'/pcom-regionais');

//--------------------------------------------------------------------
//REGISTRO DO POST_TYPE
//--------------------------------------------------------------------
	
	add_action('init','post_type_regionais');
	function post_type_regionais(){
		register_post_type('regional', array(
			'labels' => array(
				'name' => 'Regionais',
				'singular_name' => 'Regional',
				'add_new' => 'Adicionar Regional',
				'add_new_item' => 'Adicionar Regional',
				'edit_item' => 'Editar Regional'
			),
			'public' => true,
			'hierarchical' => true,
			'supports' => array('title')
		));
		flush_rewrite_rules();
	}

	//municipios
	/*add_action( 'init', 'build_taxonomies_regionais', 0 );
	function build_taxonomies_regionais() {
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
		register_taxonomy('municipio_regional', array('regional'), 
			array(
				'hierarchical' => true,
				'show_ui' => true,
				'labels' => $labels, 
				'public' => true
			)
		);
	}
*/
	add_action('admin_init', 'admin_regionais_init');
	
	function admin_regionais_init(){
		add_meta_box('regionais_meta_box', 'Dados da Regional', 'regionais_meta_box', 'regional');
		add_meta_box('regionais_mapa_meta_box', 'Mapa', 'regionais_mapa_meta_box', 'regional');
	}	
	
	function regionais_meta_box(){
		global $post;
		$html = '
			<table class="form-table">
				<tbody>';
					/*<tr>
						<td class="first" valign="top">Supervisor</td>
						<td>
							<input type="text" name="supervisor" size="100" value="' . get_post_meta($post->ID, 'supervisor', true) . '" />
						</td>
					</tr>
					<tr>
						<td class="first" valign="top">Celular</td>
						<td>
							<input type="text" name="celular" size="100" value="' . get_post_meta($post->ID, 'celular', true) . '" />
						</td>
					</tr>
					<tr>
						<td class="first" valign="top">Telefone</td>
						<td>
							<input type="text" name="telefone" size="100" value="' . get_post_meta($post->ID, 'telefone', true) . '" />
						</td>
					</tr>
					<tr>
						<td class="first" valign="top">E-mail</td>
						<td>
							<input type="text" name="email" size="100" value="' . get_post_meta($post->ID, 'email', true) . '" />
						</td>
					</tr>
					<tr>
						<td class="first" valign="top">Apoio Operacional</td>
						<td>
							<input type="text" name="apoio_operacional" size="100" value="' . get_post_meta($post->ID, 'apoio_operacional', true) . '" />
						</td>
					</tr>*/
				$html.='<tr>
						<td class="first" valign="top">ID Iframe</td>
						<td>
							<input type="text" id="id_iframe" name="id_iframe" size="30" value="' . get_post_meta($post->ID, 'id_iframe', true) . '" />
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
					</tr>';
					/*<tr>
						<td class="first" valign="top">Complemento</td>
						<td>
							<input type="text" name="complemento" size="100" value="' . get_post_meta($post->ID, 'complemento', true) . '" />
						</td>
					</tr>*/
					$html.='
				</tbody>
			</table>
		';
		echo $html;
	}

	function regionais_mapa_meta_box(){
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
				jQuery('.mapa').html('<img src="<?php echo pcom_regionais_URL; ?>/ajax-loader.gif" />');
				jQuery('.mapa').load('<?php echo pcom_regionais_URL; ?>/ajax-mapa.php?endereco='+endereco);
			}
			function geraMapa2(){
				var latitude = jQuery('#latitude').val();
				var longitude = jQuery('#longitude').val();
				jQuery('.mapa').html('<img src="<?php echo pcom_regionais_URL; ?>/ajax-loader.gif" />');
				jQuery('.mapa').load('<?php echo pcom_regionais_URL; ?>/ajax-mapa.php?latitude='+latitude+'&longitude='+longitude);
			}
		</script>
		<?php 
	}
	
	add_action('save_post', 'save_regionais');
	function save_regionais(){
		global $post;
		if('regional' == $_POST['post_type']){
			if(!current_user_can('edit_page', $post_id)){
				return $post_id;
			}
		}
	
		if(defined('DOING_AUTOSAVE') && DOING_AUTOSAVE){ 
	    	return;
	    }
	    
	    $campos = array('id_iframe',/*'supervisor', 'celular', 'telefone', 'email', 'apoio_operacional',*/'endereco', 'cidade', 'estado', 'complemento', /*'cep',*/ 'latitude', 'longitude');
	    foreach($_POST as $key => $value){
	    	if(in_array($key, $campos)){
	    		update_post_meta($post->ID, $key, $value);
	    	}
	    }

	    if($_POST['latitude'] && $_POST['longitude']){
	    	$regionais = get_posts(array('post_type'=>'regional','posts_per_page'=>-1));
	    	
	    	$regionais_mapa = array();
	    	foreach($regionais as $regional):
	    		if(get_post_meta($regional->ID, 'latitude', true) && get_post_meta($regional->ID, 'longitude', true)):
	    			$regionais_mapa[] = $regional;
	    		endif;
	    	endforeach;

	    	$json = '
	    		[';
	    	$num_regionais = count($regionais_mapa);
	    	$count = 1;
	    	foreach($regionais_mapa as $regional):
		    	$json.='
		    		{ 
		    			"Id": '.$regional->ID.',
		    			"Latitude": '.get_post_meta($regional->ID, 'latitude', true).',
		    			"Longitude": '.get_post_meta($regional->ID, 'longitude', true).',
		    			"Descricao": "'.get_the_title($regional->ID).'"
		    		}';
		    		$json.=($count!=$num_regionais) ? ',' : '';
		    		$count++;
	    	endforeach;
	    	$json.='
	    		]';

	    	$arquivo = fopen(ABSPATH.'/wp-content/uploads/regionais.json', 'w+');
	    	fwrite($arquivo, $json);
	    	fclose($arquivo);
	    }

	}
?>
