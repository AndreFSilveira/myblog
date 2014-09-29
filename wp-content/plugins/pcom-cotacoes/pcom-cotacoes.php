<?php

/**
 * Plugin Name: PontoCom Cotações
 * Plugin URI: http://agenciadeinternet.com/
 * Description: Cadastro de cotações.
 * Author: PontoCom Ag&ecirc;ncia de Internet
 * Author URI: http://agenciadeinternet.com/
**/

define ('pcom_cotacoes_DIR', ABSPATH.PLUGINDIR.'/pcom-cotacoes/');
define ('pcom_cotacoes_URL', get_bloginfo('siteurl').'/'.PLUGINDIR.'/pcom-cotacoes');

//-----------------------------------------------------------------------
//DEFININDO ICONS
//-----------------------------------------------------------------------

add_action( 'admin_head', 'z_icon_cotacoes' );
 
function z_icon_cotacoes() {
    ?>
    <style type="text/css" media="screen">
		#menu-posts-cotacao .wp-menu-image { background: url(<?php echo pcom_cotacoes_URL; ?>/img/icon-cotacao-p.png) no-repeat 6px 6px !important; }
		#menu-posts-cotacao .wp-menu-image, #menu-posts-cotacao:hover .wp-menu-image, #menu-posts-cotacao.wp-has-current-submenu .wp-menu-image { background-position: 4px 5px !important; }
		#icon-edit.icon32-posts-cotacao { background: url(<?php echo pcom_cotacoes_URL; ?>/img/icon-cotacao-m.png) no-repeat; }
    </style>
<?php }

	
//--------------------------------------------------------------------
//REGISTRO DO POST_TYPE
//--------------------------------------------------------------------
	
	add_action('init','post_type_cotacoes');
	function post_type_cotacoes(){
		register_post_type('cotacao', array(
			'labels' => array(
				'name' => 'Cotações',
				'singular_name' => 'Cotação',
				'add_new' => 'Adicionar Cotação',
				'add_new_item' => 'Adicionar Cotação',
				'edit_item' => 'Editar Cotação'
			),
			'public' => true,
			'hierarchical' => true,
			'supports' => array('title')
		));
	}
	add_action('admin_init', 'admin_cotacoes_init');
	
	function admin_cotacoes_init(){
		add_meta_box('soja_meta_box', 'Soja', 'soja_meta_box', 'cotacao');
		add_meta_box('trigo_meta_box', 'Trigo', 'trigo_meta_box', 'cotacao');
		add_meta_box('dolar_meta_box', 'Dólar', 'dolar_meta_box', 'cotacao');
		add_meta_box('cafe_meta_box', 'Café', 'cafe_meta_box', 'cotacao');
		add_meta_box('milho_meta_box', 'Milho', 'milho_meta_box', 'cotacao');
		add_meta_box('boigordo_meta_box', 'Boi Gordo', 'boigordo_meta_box', 'cotacao');
		add_meta_box('feijao_meta_box', 'Feijão', 'feijao_meta_box', 'cotacao');
	}

	function soja_meta_box(){
		global $post;
		$html = '
		<table>
			<tbody>
				<tr>
					<td colspan="3"><strong>Chicago | CBOT</strong></td>
				</tr>
				<tr>
					<td>Data</td>
					<td>US$/saca</td>
					<td>R$/saca</td>
				</tr>';	
				$soja0 = unserialize(get_post_meta($post->ID, 'soja0', true));
				for($i=0; $i < 4; $i++){
					$html.='<tr>';
					for ($j=0; $j < 3; $j++) { 
						$html.='<td><input type="text" name="soja[0]['.$i.']['.$j.']" value="'.$soja0[$i][$j].'" /></td>';
					}
					$html.='</tr>';
				}
			$html.='</tbody>
		</table>
		
		<table>
			<tbody>
				<tr>
					<td colspan="1"><strong>Bolsa de Chicago</strong></td>
					<td colspan="2"><input type="text" name="data-soja1" value="'.get_post_meta($post->ID, 'data-soja1', true).'" /></td>
				</tr>
				<tr>
					<td></td>
					<td>US$/t</td>
					<td>US$/saca</td>
					<td>R$/saca</td>
				</tr>';	
				$soja1 = unserialize(get_post_meta($post->ID, 'soja1', true));
				for($i=0; $i < 3; $i++){
					$html.='<tr>';
					for ($j=0; $j < 4; $j++) { 
						$html.='<td><input type="text" name="soja[1]['.$i.']['.$j.']" value="'.$soja1[$i][$j].'" /></td>';
					}
					$html.='</tr>';
				}
			$html.='</tbody>
		</table>
		
		<table class="f-left border-right">
			<tbody>
				<tr>
					<td colspan="2"><strong>Praças | Mercado de Lotes</strong></td>
				</tr>
				<tr>
					<td>Praças</td>
					<td>R$ (saca de 60kg)</td>
				</tr>';	
				$soja2 = unserialize(get_post_meta($post->ID, 'soja2', true));
				for($i=0; $i < 11; $i++){
					$html.='<tr>';
					for ($j=0; $j < 2; $j++) { 
						$html.='<td><input type="text" name="soja[2]['.$i.']['.$j.']" value="'.$soja2[$i][$j].'" /></td>';
					}
					$html.='</tr>';
				}
			$html.='</tbody>
		</table>
		<table class="f-left">
			<tbody>
				<tr>
					<td><strong>Prêmio Exportação de soja</strong></td>
					<td><input type="text" name="data-soja3" value="'.get_post_meta($post->ID, 'data-soja3', true).'" /></td>
				</tr>
				<tr>
					<td></td>
					<td>venda</td>
				</tr>';	
				$soja3 = unserialize(get_post_meta($post->ID, 'soja3', true));
				for($i=0; $i < 7; $i++){
					$html.='<tr>';
					for ($j=0; $j < 2; $j++) { 
						$html.='<td><input type="text" name="soja[3]['.$i.']['.$j.']" value="'.$soja3[$i][$j].'" /></td>';
					}
					$html.='</tr>';
				}
			$html.='</tbody>
		</table>
		<div class="clear"></div>';
		echo $html;
	}

	function trigo_meta_box(){
		global $post;
		$html = '
			<table class="f-left border-right">
				<tbody>
					<tr>
						<td colspan="2"><strong>Praças | Mercado de Lotes</strong></td>
					</tr>
					<tr>
						<td>Praças</td>
						<td>R$/t</td>
					</tr>';	
					$trigo0 = unserialize(get_post_meta($post->ID, 'trigo0', true));
					for($i=0; $i < 6; $i++){
						$html.='<tr>';
						for ($j=0; $j < 2; $j++) { 
							$html.='<td><input type="text" name="trigo[0]['.$i.']['.$j.']" value="'.$trigo0[$i][$j].'" /></td>';
						}
						$html.='</tr>';
					}
				$html.='</tbody>
			</table>
			<table class="f-left">
				<tbody>
					<tr>
						<td colspan="3"><strong>Chicago | CBOT</strong></td>
					</tr>
					<tr>
						<td>Data</td>
						<td>US$/saca</td>
						<td>R$/saca</td>
					</tr>';	
					$trigo1 = unserialize(get_post_meta($post->ID, 'trigo1', true));
					for($i=0; $i < 4; $i++){
						$html.='<tr>';
						for ($j=0; $j < 3; $j++) { 
							$html.='<td><input type="text" name="trigo[1]['.$i.']['.$j.']" value="'.$trigo1[$i][$j].'" /></td>';
						}
						$html.='</tr>';
					}
				$html.='</tbody>
			</table>
			<div class="clear"></div>';
		echo $html;
	}

	function dolar_meta_box(){
		global $post;
		$html = '
			<table>
				<tbody>
					<tr>
						<td colspan="2"><strong>Dólar</strong></td>
					</tr>
					<tr>
						<td></td>
						<td>R$/US$</td>
					</tr>';	
					$dolar0 = unserialize(get_post_meta($post->ID, 'dolar0', true));
					for($i=0; $i < 3; $i++){
						$html.='<tr>';
						for ($j=0; $j < 2; $j++) { 
							$html.='<td><input type="text" name="dolar[0]['.$i.']['.$j.']" value="'.$dolar0[$i][$j].'" /></td>';
						}
						$html.='</tr>';
					}
				$html.='</tbody>
			</table>
			<div class="clear"></div>';
		echo $html;
	}

	function cafe_meta_box(){
		global $post;
		$html = '
			<table class="f-left border-right">
				<tbody>
					<tr>
						<td colspan="3"><strong>Nova York</strong></td>
					</tr>
					<tr>
						<td></td>
						<td>US$cents/libra peso</td>
						<td>US$/saca</td>
					</tr>';	
					$cafe0 = unserialize(get_post_meta($post->ID, 'cafe0', true));
					for($i=0; $i < 2; $i++){
						$html.='<tr>';
						for ($j=0; $j < 3; $j++) { 
							$html.='<td><input type="text" name="cafe[0]['.$i.']['.$j.']" value="'.$cafe0[$i][$j].'" /></td>';
						}
						$html.='</tr>';
					}
				$html.='</tbody>
			</table>
			<table class="f-left">
				<tbody>
					<tr>
						<td colspan="2"><strong>Londres</strong></td>
					</tr>
					<tr>
						<td>Mês</td>
						<td>US$/t</td>
					</tr>';	
					$cafe1 = unserialize(get_post_meta($post->ID, 'cafe1', true));
					for($i=0; $i < 2; $i++){
						$html.='<tr>';
						for ($j=0; $j < 2; $j++) { 
							$html.='<td><input type="text" name="cafe[1]['.$i.']['.$j.']" value="'.$cafe1[$i][$j].'" /></td>';
						}
						$html.='</tr>';
					}
				$html.='</tbody>
			</table>
			<div class="clear"></div>
			
			<table class="f-left border-right">
				<tbody>
					<tr>
						<td colspan="2"><strong>Praças</strong></td>
					</tr>
					<tr>
						<td>Praças</td>
						<td>R$/saca de 60kg</td>
					</tr>';	
					$cafe2 = unserialize(get_post_meta($post->ID, 'cafe2', true));
					for($i=0; $i < 3; $i++){
						$html.='<tr>';
						for ($j=0; $j < 2; $j++) { 
							$html.='<td><input type="text" name="cafe[2]['.$i.']['.$j.']" value="'.$cafe2[$i][$j].'" /></td>';
						}
						$html.='</tr>';
					}
				$html.='</tbody>
			</table>
			<table class="f-left">
				<tbody>
					<tr>
						<td colspan="2"><strong>BM & F</strong></td>
					</tr>
					<tr>
						<td>Mês</td>
						<td>US$/saca de 60kg</td>
					</tr>';	
					$cafe3 = unserialize(get_post_meta($post->ID, 'cafe3', true));
					for($i=0; $i < 2; $i++){
						$html.='<tr>';
						for ($j=0; $j < 2; $j++) { 
							$html.='<td><input type="text" name="cafe[3]['.$i.']['.$j.']" value="'.$cafe3[$i][$j].'" /></td>';
						}
						$html.='</tr>';
					}
				$html.='</tbody>
			</table>
			<div class="clear"></div>';
		echo $html;
	}

	function milho_meta_box(){
		global $post;
		$html = '
			<table class="f-left">
				<tbody>
					<tr>
						<td colspan="3"><strong>Chicago | CBOT</strong></td>
					</tr>
					<tr>
						<td>Mês</td>
						<td>US$/saca</td>
						<td>R$/saca</td>
					</tr>';	
					$milho0 = unserialize(get_post_meta($post->ID, 'milho0', true));
					for($i=0; $i < 4; $i++){
						$html.='<tr>';
						for ($j=0; $j < 3; $j++) { 
							$html.='<td><input type="text" name="milho[0]['.$i.']['.$j.']" value="'.$milho0[$i][$j].'" /></td>';
						}
						$html.='</tr>';
					}
				$html.='</tbody>
			</table>
			
			<table class="f-left">
				<tbody>
					<tr>
						<td colspan="2"><strong>BM & F</strong></td>
					</tr>
					<tr>
						<td>mês</td>
						<td>R$/saca</td>
					</tr>';	
					$milho1 = unserialize(get_post_meta($post->ID, 'milho1', true));
					for($i=0; $i < 3; $i++){
						$html.='<tr>';
						for ($j=0; $j < 2; $j++) { 
							$html.='<td><input type="text" name="milho[1]['.$i.']['.$j.']" value="'.$milho1[$i][$j].'" /></td>';
						}
						$html.='</tr>';
					}
				$html.='</tbody>
			</table>
			<div class="clear"></div>
			
			<table class="f-left">
				<tbody>
					<tr>
						<td colspan="2"><strong>Praças</strong></td>
					</tr>
					<tr>
						<td>Praças</td>
						<td>R$ (saca de 60kg)</td>
					</tr>';	
					$milho2 = unserialize(get_post_meta($post->ID, 'milho2', true));
					for($i=0; $i < 11; $i++){
						$html.='<tr>';
						for ($j=0; $j < 2; $j++) { 
							$html.='<td><input type="text" name="milho[2]['.$i.']['.$j.']" value="'.$milho2[$i][$j].'" /></td>';
						}
						$html.='</tr>';
					}
				$html.='</tbody>
			</table>
			<div class="clear"></div>';
		echo $html; ?>

		

		<?php 
	}

	function boigordo_meta_box(){
		global $post;
		$html = '
			<table class="f-left border-right">
				<tbody>
					<tr>
						<td colspan="3"><strong>Praças | Boi rastreado</strong></td>
					</tr>
					<tr>
						<td>Praças</td>
						<td>R$/@</td>
					</tr>';	
					$boi_gordo0 = unserialize(get_post_meta($post->ID, 'boi-gordo0', true));
					for($i=0; $i < 6; $i++){
						$html.='<tr>';
						for ($j=0; $j < 2; $j++) { 
							$html.='<td><input type="text" name="boi-gordo[0]['.$i.']['.$j.']" value="'.$boi_gordo0[$i][$j].'" /></td>';
						}
						$html.='</tr>';
					}
				$html.='</tbody>
			</table>
			<table class="f-left">
				<tbody>
					<tr>
						<td colspan="2"><strong>Relações de troca</strong></td>
					</tr>
					<tr>
						<td>Relações de troca</td>
						<td></td>
					</tr>';	
					$boi_gordo1 = unserialize(get_post_meta($post->ID, 'boi-gordo1', true));
					for($i=0; $i < 2; $i++){
						$html.='<tr>';
						for ($j=0; $j < 2; $j++) { 
							$html.='<td><input type="text" name="boi-gordo[1]['.$i.']['.$j.']" value="'.$boi_gordo1[$i][$j].'" /></td>';
						}
						$html.='</tr>';
					}
				$html.='</tbody>
			</table>
			
			<table>
				<tbody>
					<tr>
						<td colspan="2"><strong>BM & F</strong></td>
					</tr>
					<tr>
						<td>Mês</td>
						<td>R$/@</td>
					</tr>';	
					$boi_gordo2 = unserialize(get_post_meta($post->ID, 'boi-gordo2', true));
					for($i=0; $i < 4; $i++){
						$html.='<tr>';
						for ($j=0; $j < 2; $j++) { 
							$html.='<td><input type="text" name="boi-gordo[2]['.$i.']['.$j.']" value="'.$boi_gordo2[$i][$j].'" /></td>';
						}
						$html.='</tr>';
					}
				$html.='</tbody>
			</table>
			<div class="clear"></div>
		';
		echo $html;
	}

	function feijao_meta_box(){
		global $post;
		$html = '
			<table>
				<tbody>
					<tr>
						<td colspan="3"><strong>Praças | Feijão carioca</strong></td>
					</tr>
					<tr>
						<td>Praças (R$/saca 60kg)</td>
						<td>Mínimo</td>
						<td>Máximo</td>
					</tr>';	
					$feijao0 = unserialize(get_post_meta($post->ID, 'feijao0', true));
					for($i=0; $i < 5; $i++){
						$html.='<tr>';
						for ($j=0; $j < 3; $j++) { 
							$html.='<td><input type="text" name="feijao[0]['.$i.']['.$j.']" value="'.$feijao0[$i][$j].'" /></td>';
						}
						$html.='</tr>';
					}
				$html.='</tbody>
			</table>
			
			<table>
				<tbody>
					<tr>
						<td colspan="3"><strong>Praças | Feijão preto</strong></td>
					</tr>
					<tr>
						<td>Praças (R$/saca 60kg)</td>
						<td>Mínimo</td>
						<td>Máximo</td>
					</tr>';	
					$feijao1 = unserialize(get_post_meta($post->ID, 'feijao1', true));
					for($i=0; $i < 5; $i++){
						$html.='<tr>';
						for ($j=0; $j < 3; $j++) { 
							$html.='<td><input type="text" name="feijao[1]['.$i.']['.$j.']" value="'.$feijao1[$i][$j].'" /></td>';
						}
						$html.='</tr>';
					}
				$html.='</tbody>
			</table>
			<div class="clear"></div>
		';
		echo $html;?>
		<style>
			table{padding:20px;}
			.f-left{float:left;}
			.clear{clear:both;}
		</style>
		<?php
	}
	
	add_action('save_post', 'save_cotacoes');
	function save_cotacoes(){
		global $post;
		if('cotacao' == $_POST['post_type']){
			if(!current_user_can('edit_page', $post_id)){
				return $post_id;
			}
		}
	
		if(defined('DOING_AUTOSAVE') && DOING_AUTOSAVE){ 
	    	return;
	    }
	    
	    $campos = array('cultura', 'data-soja1', 'data-soja3');
	    foreach($_POST as $key => $value){
	    	if(in_array($key, $campos)){
	    		update_post_meta($post->ID, $key, $value);
	    	}
	    }

	    if(isset($_POST['soja'])){
	    	$count1 = 0;
	    	foreach($_POST['soja'] as $tabela){
	    		$value = serialize($tabela);
	    		update_post_meta($post->ID, 'soja'.$count1, $value);
	    		$count1++;
	    	}
	    }
	    if(isset($_POST['trigo'])){
	    	$count2 = 0;
	    	foreach($_POST['trigo'] as $tabela){
	    		$value = serialize($tabela);
	    		update_post_meta($post->ID, 'trigo'.$count2, $value);
	    		$count2++;
	    	}
	    }
	    if(isset($_POST['dolar'])){
	    	$count3 = 0;
	    	foreach($_POST['dolar'] as $tabela){
	    		$value = serialize($tabela);
	    		update_post_meta($post->ID, 'dolar'.$count3, $value);
	    		$count3++;
	    	}
	    }
	    if(isset($_POST['cafe'])){
	    	$count4 = 0;
	    	foreach($_POST['cafe'] as $tabela){
	    		$value = serialize($tabela);
	    		update_post_meta($post->ID, 'cafe'.$count4, $value);
	    		$count4++;
	    	}
	    }
	    if(isset($_POST['milho'])){
	    	$count5 = 0;
	    	foreach($_POST['milho'] as $tabela){
	    		$value = serialize($tabela);
	    		update_post_meta($post->ID, 'milho'.$count5, $value);
	    		$count5++;
	    	}
	    }
	    if(isset($_POST['boi-gordo'])){
	    	$count6 = 0;
	    	foreach($_POST['boi-gordo'] as $tabela){
	    		$value = serialize($tabela);
	    		update_post_meta($post->ID, 'boi-gordo'.$count6, $value);
	    		$count6++;
	    	}
	    }
	    if(isset($_POST['feijao'])){
	    	$count7 = 0;
	    	foreach($_POST['feijao'] as $tabela){
	    		$value = serialize($tabela);
	    		update_post_meta($post->ID, 'feijao'.$count7, $value);
	    		$count7++;
	    	}
	    }
	}
?>
