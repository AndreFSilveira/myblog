<?php
	require_once('../../../wp-load.php');

	if($_GET['latitude'] && $_GET['longitude']){
		$latitude = $_GET['latitude'];
		$longitude = $_GET['longitude'];
	} else {
		// Get lat and long by address      
	    $address = $_GET['endereco'];
	    $prepAddr = str_replace(' ','+',$address);
	    $geocode=file_get_contents('http://maps.google.com/maps/api/geocode/json?address='.$prepAddr.'&sensor=false');
	    $output= json_decode($geocode);
	    $latitude = $output->results[0]->geometry->location->lat;
	    $longitude = $output->results[0]->geometry->location->lng;
	}
?>
	<table class="form-table">
		<tbody>
			<tr>
				<td class="first" valign="top">Latitude</td>
				<td>
					<input type="text" name="latitude" id="latitude" size="30" value="<?php echo $latitude; ?>" />
				</td>
				<td class="first" valign="top">Longitude</td>
				<td>
					<input type="text" name="longitude" id="longitude" size="30" value="<?php echo $longitude; ?>" />
				</td>
			</tr>
			<tr>
				<td colspan="4">Clique <a href="javascript: void(0)" onclick="geraMapa2()">aqui</a> para gerar o mapa com a Latitude e Longitude digitadas acima.</td>
			</tr>
		</tbody>
	</table>

	<iframe width="600" height="350" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="https://maps.google.com.br/maps?f=q&amp;source=s_q&amp;hl=pt-BR&amp;geocode=&amp;q=<?php echo $latitude?>,+<?php echo $longitude?>&amp;ie=UTF8&amp;t=m&amp;z=14&amp;output=embed"></iframe><br />
	<small><a href="https://maps.google.com.br/maps?f=q&amp;source=embed&amp;hl=pt-BR&amp;geocode=&amp;q=<?php echo $latitude?>,+<?php echo $longitude?>&amp;ie=UTF8&amp;t=m&amp;z=14&amp;" style="color:#0000FF;text-align:left">Exibir mapa ampliado</a></small>