<?php require_once('../../../wp-admin/admin.php'); ?>
<?php $pid = $_GET['id']; $photos = get_posts ('post_type=attachment&nopaging=true&orderby=menu_order&order=ASC&post_parent='.$pid); ?>
<?php if (is_array($photos)) : ?>
	<?php $i = 0; ?>
	<?php foreach ($photos as $p) : ?>
		<?php $img = wp_get_attachment_image_src($p->ID, 'foto-galeria-list'); ?>
		<div class="item" id="item-<?php echo $p->ID; ?>">
			<div class="anexo-img"><img src="<?php echo $img[0]; ?>" width="100px" height="70px" alt=""/></div>
		</div>
	<?php endforeach; ?>
<?php else: ?>
	Nenhuma foto anexada no momento.
<?php endif; ?>
<?php wp_reset_query(); ?>