<?php
/**
 * DokuWiki Zenlike template
 *
 * This is a dokuwiki adaption of the zenlike1.0 template by nodethirtythree design
 *
 * @version 20110209
 * @link   http://dokuwiki.org/templates
 * @link   http://www.nodethirtythree.com
 * @author Dennis Ploeger <develop@dieploegers.de> 
 */

// must be run from within DokuWiki
if (!defined('DOKU_INC')) die();

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<!--

	zenlike1.0 by nodethirtythree design
	http://www.nodethirtythree.com

-->
<html>
<head>
  <meta http-equiv="content-type" content="text/html; charset=utf-8" />
  <title>
    <?php tpl_pagetitle()?>
    [<?php echo strip_tags($conf['title'])?>]
  </title>
  <?php tpl_metaheaders()?>
  <link rel="shortcut icon" href="<?php echo DOKU_TPL?>images/favicon.ico" />
</head>
<body>

<div id="upbg"></div>


<div class="upper">

	<?php html_msgarea()?>

	<div id="header">
		<div id="headercontent">
        		<h1><?php print $conf['title']; ?></h1>
			<h2><?php print tpl_pagetitle($ID,true); ?></h2>
		</div>
			<div id="search">
        <?php tpl_searchform()?>&nbsp;
	</div>

	</div>


	<div id="headerpic"></div>

	<div id="menu">
		<!-- HINT: Set the class of any menu link below to "active" to make it appear active -->
		<ul>

			<?php

				$menuItems = explode(',', tpl_getConf('menu'));

				foreach ($menuItems as $item) {

					list($label, $pageId) = explode('|', $item);

					print '<li><a href="' . wl($pageId) . '"';

					if (strcmp($pageId, $ID) == 0) { 

						print ' class="active"';
					
					}

					print '>' . $label . '</a></li>';	

				}

			?>

		</ul>


	</div>
	<div id="menubottom"></div>

</div>
	
<div class="dokuwiki">
	<div id="content">

	    <?php if($conf['breadcrumbs']){?>
	    <div class="breadcrumbs">
	      <?php tpl_breadcrumbs()?>
	      <?php //tpl_youarehere() //(some people prefer this)?>
	    </div>
	    <?php }?>

	    <?php if($conf['youarehere']){?>
	    <div class="breadcrumbs">
	      <?php tpl_youarehere() ?>
	    </div>
	    <?php }?>

  <?php tpl_flush()?>

		<div class="divider1"></div>

		<!-- Normal content: Stuff that's not going to be put in the left or right column. -->
		<div id="normalcontent">

    <?php tpl_content()?>
		</div>
		    <div class="meta">
	      <div class="user">
		<?php tpl_userinfo()?>
	      </div>
	      <div class="doc">
		<?php tpl_pageinfo()?>
	      </div>
	    </div>

	</div>

  <?php tpl_flush()?>
    <div class="bar" id="bar__bottom">
      <div class="bar-left" id="bar__bottomleft">
        <?php tpl_button('edit')?>
        <?php tpl_button('history')?>
        <?php tpl_button('revert')?>
      </div>
      <div class="bar-right" id="bar__bottomright">
        <?php tpl_button('subscribe')?>
        <?php tpl_button('admin')?>
        <?php tpl_button('profile')?>
        <?php tpl_button('login')?>
        <?php tpl_button('index')?>
        <?php tpl_button('top')?>&nbsp;
      </div>
      <div class="clearer"></div>
    </div>
	<div id="footer">

		<div class="left"><?php print tpl_getConf('copyright');?></div>
			<div class="right"><?php tpl_license(false);?></div>
	</div>
	
</div>

</body>
</html>
