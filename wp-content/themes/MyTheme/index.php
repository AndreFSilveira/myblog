<?php get_header();?>
<body class="loading">
<?php get_sidebar(); ?>
		<div id="wrapper">
			<div id="bg"></div>
			<div id="overlay"></div>
			<div id="main">

				<!-- Header -->
					<header id="header">
						<h1><?php bloginfo( 'name' ); ?></h1>
						<p><?php bloginfo( 'description' )?></p>
						<nav>
							<ul>
								<li><a href="https://twitter.com/AFS_AFS_AFS" class="icon fa-twitter"><span class="label">Twitter</span></a></li>
								<li><a href="https://www.facebook.com/andrefelipe.silveira" class="icon fa-facebook"><span class="label">Facebook</span></a></li>
								<li><a href="http://lnkd.in/mVitNY" class="icon fa-linkedin"><span class="label">Linkedin</span></a></li>
								<li><a href="https://github.com/AndreFSilveira" class="icon fa-github"><span class="label">Github</span></a></li>
								<li><a href="#" class="icon fa-envelope-o"><span class="label">Email</span></a></li>
							</ul>
						</nav>
					</header>
<?php get_footer();?>