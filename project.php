<html><head lang="en-US">
		
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta charset="UTF-8">
		<title>Project</title>		
		<meta name="description" content="This company was formed as Building / Civil Construction Company by Ms. Tebogo Maisela(now has resigned) and Mr. Tseke Mphahlele in 2005.">
		<link rel="shortcut icon" href="gallery/fav-icon.png" type="text/x-icon">
		<meta property="og:title" content="Project">
		<meta property="og:description" content="This company was formed as Building / Civil Construction Company by Ms. Tebogo Maisela(now has resigned) and Mr. Tseke Mphahlele in 2005.">	
		<meta property="og:image" content="gallery/logo.png">
		<link rel="stylesheet" type="text/css" href="control/front/front.css">	
	</head><body><div class="hair twos2"><article class="socials"><a class="icons" target="_blank" href="https://www.facebook.com/Manyaka-102072051548175"></a><a class="icons" target="_blank" href="https://www.instagram.com/"></a></article><article class="contact"><a href="tel:00+2783 568 8170"> +27 (0)83 568 8170</a></article></div><header class="head"><a href="index.html#welcome" id="logo" class="logo">Manyaka Trading</a><label for="toggle-1" class="toggle-menu">≡</label>
	<input type="checkbox" id="toggle-1"><nav><ul><li><a href="index.html#welcome">Our History</a></li><li><a href="index.html#services">Services</a></li><li><a href="#">Projects</a></li><li><a href="index.html#contact">Contact Us</a></li></ul></nav></header>
	<section id="topproject" class="ones">
<?php
	if(isset($_REQUEST["pick"])){
	$q = $_REQUEST["pick"];
	echo '<div id="project" class="project"  data-item="projects" data-display="'.$q.'" data-specific="specific" data-style="big" data-filters="no" data-paginate="no"></div>';
}

?>
</section>
<div class="ones grey"> 
<h2>Other projects</h2>


<div id="projects" class="threjes projects slideframe" data-id="projects" data-goto="1" data-item="projects" data-display="3" data-specific="all" data-style="small" data-filters="no" data-paginate="no"></div>
<a class="freebutton" href="projects.php">SEE MORE PROJECTS</a></div>
<script type="text/javascript" src="control/front/front.js" ></script>
<script>
showOffer('projects', 'projects'); 
showOffer('project', 'projects'); 
</script>
	
</section>
</body>
</html>