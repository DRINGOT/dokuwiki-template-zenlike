<?php
/**
 * DokuWiki Starter Template
 *
 * @link     http://dokuwiki.org/template:starter
 * @author   Anika Henke <anika@selfthinker.org>
 * @license  GPL 2 (http://www.gnu.org/licenses/gpl.html)
 */

if (!defined('DOKU_INC')) die(); /* must be run from within DokuWiki */
@require_once(dirname(__FILE__).'/tpl_functions.php'); /* include hook for template functions */
header('X-UA-Compatible: IE=edge,chrome=1');

$showTools = !tpl_getConf('hideTools') || ( tpl_getConf('hideTools') && $_SERVER['REMOTE_USER'] );
$showSidebar = page_findnearest($conf['sidebar']) && ($ACT=='show');
?><!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $conf['lang'] ?>"
  lang="<?php echo $conf['lang'] ?>" dir="<?php echo $lang['direction'] ?>" class="no-js">
<head>
    <meta charset="UTF-8" />
    <title><?php tpl_pagetitle() ?> [<?php echo strip_tags($conf['title']) ?>]</title>
    <script>(function(H){H.className=H.className.replace(/\bno-js\b/,'js')})(document.documentElement)</script>
    <?php tpl_metaheaders() ?>
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <?php echo tpl_favicon(array('favicon', 'mobile')) ?>
    <?php tpl_includeFile('meta.html') ?>
</head>

<body>
    <?php /* with these Conditional Comments you can better address IE issues in CSS files,
             precede CSS rules by #IE6 for IE6, #IE7 for IE7 and #IE8 for IE8 (div closes at the bottom) */ ?>
    <!--[if IE 6 ]><div id="IE6"><![endif]--><!--[if IE 7 ]><div id="IE7"><![endif]--><!--[if IE 8 ]><div id="IE8"><![endif]-->

    <?php /* the "dokuwiki__top" id is needed somewhere at the top, because that's where the "back to top" button/link links to */ ?>
    <?php /* classes mode_<action> are added to make it possible to e.g. style a page differently if it's in edit mode,
         see http://www.dokuwiki.org/devel:action_modes for a list of action modes */ ?>
    <?php /* .dokuwiki should always be in one of the surrounding elements (e.g. plugins and templates depend on it) */ ?>
    <div id="upbg"></div>
    <div id="dokuwiki__site"><div id="dokuwiki__top"
        class="dokuwiki site mode_<?php echo $ACT ?> <?php echo ($showSidebar) ? 'hasSidebar' : '' ?>">
        <?php html_msgarea() /* occasional error and info messages on top of the page */ ?>
        <?php tpl_includeFile('header.html') ?>

        <!-- ********** HEADER ********** -->
        <div id="dokuwiki__header"><div class="pad">

            <div class="headings">
                <div id="header">
                    <div id="headercontent">
                        <h1><?php echo $conf['title']; ?></h1>
                        <?php if (tpl_getConf('tagline')): ?>
                        <h2><?php echo tpl_getConf('tagline'); ?></h2>
                        <?php elseif ($conf['tagline']): ?>
                        <h2><?php echo $conf['tagline']; ?></h2>
                        <?php endif; ?>
                    </div>
                </div>

                <div id="search">
                    <?php tpl_searchform() ?>
                </div>

                <div id="headerpic"></div>

                <ul class="a11y skip">
                    <li><a href="#dokuwiki__content"><?php echo $lang['skip_to_content'] ?></a></li>
                </ul>
                <div class="clearer"></div>
            </div>

            <div class="tools">

                <div id="menu">

                    <?php

                    $menu = array();

                    if (
                        (tpl_getConf('menuid') == '') ||
                        (! page_exists(tpl_getConf('menuid')))
                    ) {

                        $menuItems = explode(',', tpl_getConf('menu'));

                        foreach ($menuItems as $item) {

                            list ($label, $pageId) = explode('|', $item);

                            $menu[] = array(
                                'label' => $label,
                                'link' => wl($pageId)
                            );

                        }

                    } else {

                        $text = rawWiki(tpl_getConf('menuid'));

                        preg_match_all(
                            '/^  \* \[\[([^|]*)\|(.*)\]\].*$/m',
                            $text,
                            $matches
                        );

                        for ($i = 0; $i < count($matches[1]); $i = $i + 1) {

                            $link = $matches[1][$i];

                            $urire = '/((([A-Za-z]{3,9}:(?:\/\/)?)(?:[-;:&=\+\$,\w]+@)?[A-Za-z0-9.-]+|(?:www.|[-;:&=\+\$,\w]+@)[A-Za-z0-9.-]+)((?:\/[\+~%\/.\w-_]*)?\??(?:[-\+=&;%@.\w_]*)#?(?:[.\!\/\\w]*))?)/';

                            if (!preg_match($urire, $link)) {

                                // Seems that the link is a page id.

                                $link = wl($link);

                            }

                            $menu[] = array(
                                'label' => $matches[2][$i],
                                'link' => $link
                            );

                        }

                    }

                    if ($INFO['ismobile']) {

                        print '<select onChange="window.location=' .
                            'this.selectedOptions[0].value">';

                    } else {

                        print '<ul>';

                    }

                    foreach ($menu as $item) {

                        if ($INFO['ismobile']) {

                            print '<option value="' .
                                $item['link'] .
                                '"';

                            if (strcmp($pageId, $ID) == 0) {

                                print ' selected';

                            }

                            print '>' . $item['label'] . '</option>';

                        } else {

                            print '<li><a href="' . $item['link'] . '"';

                            if (strcmp($pageId, $ID) == 0) {

                                print ' class="active"';

                            }

                            print '>' . $item['label'] . '</a></li>';

                        }

                    }

                    if ($INFO['ismobile']) {

                        print '</select>';

                    } else {

                        print '</ul>';

                    }

                    ?>

                    <div id="rightmenu">

                        <?php

                        if ($INFO['ismobile']) {

                            tpl_actiondropdown($lang['tools']);

                        } else {

                            print '<ul>';

                            tpl_action('recent', 1, 'li');
                            tpl_action('media', 1, 'li');
                            tpl_action('index', 1, 'li');

                            if ($conf['useacl'] && $showTools) {

                                echo '<li class="menusplit">';

                                echo '<img src="' . tpl_basedir() .
                                     'images/icon-user.png" ' .
                                     'id="toggle_usertools" ';

                                if ($_SERVER['REMOTE_USER']) {
                                    echo 'title="';
                                    tpl_userinfo();
                                    echo '" ';
                                }

                                echo 'width="32" height="16" alt="' .
                                    $lang['user_tools'] .
                                     '" />';

                                echo '</li>';

                                echo '<div id="usertools" ' .
                                     'style="display:none"><ul>';

                                tpl_action('admin', 1, 'li');
                                _tpl_action('userpage', 1, 'li');
                                tpl_action('profile', 1, 'li');
                                tpl_action('register', 1, 'li');
                                tpl_action('login', 1, 'li');

                                echo '</ul></div>';

                            }

                            print '</ul>';

                        }

                        ?>

                    </div>
                </div>

            </div>

            <div id="menubottom"></div>

            <div class="clearer"></div>

            <!-- PAGE ACTIONS -->
            <?php if ($showTools && !$INFO['ismobile']): ?>
            <div id="dokuwiki__pagetools">
                <h3 class="a11y"><?php echo $lang['page_tools'] ?></h3>
                <?php

                print '<ul>';

                tpl_action('edit', 1, 'li');
                _tpl_action('discussion', 1, 'li');
                tpl_action('revisions', 1, 'li');
                tpl_action('backlink', 1, 'li');
                tpl_action('subscribe', 1, 'li');
                tpl_action('revert', 1, 'li');

                print '</ul>';

                ?>
            </div>
            <?php endif; ?>

            <!-- BREADCRUMBS -->
            <?php if($conf['breadcrumbs']){ ?>
                <div class="breadcrumbs"><?php tpl_breadcrumbs() ?></div>
            <?php } ?>
            <?php if($conf['youarehere']){ ?>
                <div class="breadcrumbs"><?php tpl_youarehere() ?></div>
            <?php } ?>

            <div class="clearer"></div>
            <hr class="a11y" />

        </div></div><!-- /header -->


        <div class="wrapper">

            <!-- ********** ASIDE ********** -->
            <?php if ($showSidebar): ?>
                <div id="dokuwiki__aside"><div class="pad include">
                    <?php tpl_includeFile('sidebarheader.html') ?>
                    <?php tpl_include_page($conf['sidebar'], 1, 1) /* includes the nearest sidebar page */ ?>
                    <?php tpl_includeFile('sidebarfooter.html') ?>
                    <div class="clearer"></div>
                </div></div><!-- /aside -->
            <?php endif; ?>

            <!-- ********** CONTENT ********** -->
            <div id="dokuwiki__content">
                <?php if ($showSidebar): ?>
                <div class="pad with_sidebar">
                <?php else: ?>
                <div class="pad">
                <?php endif; ?>
                <?php tpl_flush() /* flush the output buffer */ ?>
                <?php tpl_includeFile('pageheader.html') ?>

                <div class="page">
                    <!-- wikipage start -->
                    <?php tpl_content() /* the main content */ ?>
                    <!-- wikipage stop -->
                    <div class="clearer"></div>
                </div>

                <?php tpl_flush() ?>
                <?php tpl_includeFile('pagefooter.html') ?>
            </div></div><!-- /content -->

            <div class="clearer"></div>
            <hr class="a11y" />

        </div><!-- /wrapper -->

        <!-- ********** FOOTER ********** -->
        <div id="dokuwiki__footer"><div class="pad">
            <div class="left"><?php tpl_license('button') /* content license, parameters: img=*badge|button|0, imgonly=*0|1, return=*0|1 */ ?></div>
            <div class="right">
                <?php
                tpl_pageinfo(); /* 'Last modified' etc */
                echo "&nbsp;";
                tpl_action('top', 1);
                ?>
            </div>
        </div></div><!-- /footer -->

        <?php tpl_includeFile('footer.html') ?>
    </div></div><!-- /site -->

    <div class="no"><?php tpl_indexerWebBug() /* provide DokuWiki housekeeping, required in all templates */ ?></div>
    <!--[if ( IE 6 | IE 7 | IE 8 ) ]></div><![endif]-->

    <?php
    if (tpl_getConf("headerPicture") != "") {
    ?>
    <script type="text/javascript">

        // Exchange header logo

        jQuery("#headerpic").css("background-image", "url('<?php
            echo tpl_getConf("headerPicture");
            ?>')");

    </script>
    <?php
    }
    ?>

</body>
</html>
