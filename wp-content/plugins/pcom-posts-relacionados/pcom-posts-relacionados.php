<?php

/**
 * Plugin Name: PontoCom Posts Relacionados
 * Plugin URI: http://agenciadeinternet.com/
 * Description: Mostrar posts relacionados.
 * Author: PontoCom Ag&ecirc;ncia de Internet
 * Author URI: http://agenciadeinternet.com/
**/


	function get_related_posts ($post_id = false, $limit = false) {
	   global $wpdb, $post,$table_prefix;

		if(!$limit)
			$limit = 4;
		
		if (!$post_id)
			$post_id = $post->ID;

			if (!$post_id)
				return;

		$now = current_time('mysql', 1);

		$tags = wp_get_post_tags($post->ID);
		$tagcount = count($tags);
		$taglist = '';
		
		if ($tagcount)
			$taglist = "'" . $tags[0]->term_id. "'";

		if ($tagcount > 1) {
			for ($i = 1; $i < $tagcount; $i++) {
				$taglist = $taglist . ", '" . $tags[$i]->term_id . "'";
			}
		}

		if ($taglist)
			$taglist = "AND (t_t.term_id IN ($taglist))";
		
		$q = "
			SELECT p.ID FROM `wp_posts` as p
			INNER JOIN wp_term_relationships as t_r
			ON p.ID = t_r.object_ID
			INNER JOIN wp_term_taxonomy as t_t
			ON t_r.term_taxonomy_id = t_t.term_taxonomy_id
			INNER JOIN wp_terms as t
			ON t_t.term_id = t.term_id

			WHERE p.ID <> $post->ID AND
			p.post_date > DATE_SUB(CURDATE(), INTERVAL 1 MONTH)  AND
			p.post_date_gmt < NOW() AND
			p.post_status = 'publish' AND
			p.post_type = 'post'
			$taglist
			
			GROUP BY p.ID
			ORDER BY RAND()
			
			LIMIT ". $limit
      ;
			// DEBUG
			//echo $q;
			//exit();

		$related_posts = $wpdb->get_results($q);

		if (!$related_posts) {
			$q = "
				SELECT ID
				FROM $wpdb->posts
				WHERE 1
					AND post_date > DATE_SUB(CURDATE(), INTERVAL 1 MONTH)
					AND post_status = 'publish'
					AND post_type = 'post'
					AND ID != $post->ID
				ORDER BY RAND()
				LIMIT ". $limit
			;
			// DEBUG
			//echo $q;
			//exit();
			$related_posts = $wpdb->get_results($q);
		}

		foreach ($related_posts as $r) {
			$p[] = get_post ($i = $r->ID);
		}
		
		if (count($p) > 0)
			return $p;
	   
		return false;		
	}