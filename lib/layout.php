<?php
declare(strict_types=1);

require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/text.php';

/**
 * Emit the OkayAmigo splash sidebar + content frame.
 * Pass ['title' => '...', 'header_img' => '/images/headers/forums.gif'].
 * Call render_footer() at the end of the page.
 */
function render_header(array $opts = []): void {
    $title  = $opts['title']      ?? 'Okay, Amigo!';
    $header = $opts['header_img'] ?? null;
    $u      = current_user();
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= h($title) ?></title>
<meta name="description" content="Okay, Amigo! Members area + forum.">
<meta name="theme-color" content="#0d0d0d">
<link rel="apple-touch-icon" sizes="180x180" href="/images/favi/apple-touch-icon.png">
<link rel="icon" type="image/png" sizes="32x32" href="/images/favi/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="16x16" href="/images/favi/favicon-16x16.png">
<link rel="manifest" href="/images/favi/site.webmanifest">
<link rel="stylesheet" href="/css/global.css">
<link rel="stylesheet" href="/css/forums.css">
</head>
<body bgcolor="#FFD8D4" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table border="0" cellspacing="0" cellpadding="0" width="100%" height="100%">
 <tr>
  <td align="center" valign="top">
   <table border="0" cellspacing="0" cellpadding="0" id="forum_page">
    <tr>
     <td width="230" valign="top">
      <?php render_sidebar($u); ?>
     </td>
     <td width="710" valign="top" id="forum_content">
      <div class="topstrip"><img src="/images/splash/splash_home_02.gif" width="521" height="27" alt=""></div>
      <?php if ($header): ?>
       <table cellpadding="0" cellspacing="0" border="0"><tr>
        <td align="left" class="subtitle">&nbsp;<img src="<?= h($header) ?>" border="0"></td>
       </tr></table>
      <?php else: ?>
       <table cellpadding="0" cellspacing="0" border="0"><tr>
        <td align="left" class="subtitle">&nbsp;<span class="forum_title"><?= h($opts['header_text'] ?? $title) ?></span></td>
       </tr></table>
      <?php endif; ?>
      <div id="forum_body">
<?php
}

function render_footer(): void {
?>
      </div>
     </td>
    </tr>
   </table>

   <table width="751" border="0" cellspacing="0" cellpadding="0">
    <tr>
     <td class="legal_stuff" align="left" valign="bottom" height="34">
      <a href="https://onionmadder.xyz/" target="_new"><img src="/images/powered_by.jpg" width="93" height="40" border="0"></a>
      &nbsp; <span class="copyright2">A nostalgia archive. Not affiliated with the original Halkon Media.</span>
     </td>
    </tr>
   </table>
  </td>
 </tr>
</table>
</body>
</html>
<?php
}

/**
 * The okayamigo splash sidebar adapted for member pages.
 * Top decorative gifs are kept; the link strip below them switches based
 * on whether the visitor is logged in.
 */
function render_sidebar(?array $u): void {
    $logged_in = $u !== null;
?>
<table width="230" border="0" cellspacing="0" cellpadding="0">
 <tr><td width="230" height="155"><a href="/"><img src="/images/splash/splash_home_01.jpg" width="230" height="155" name="logo" border="0"/></a></td></tr>
 <tr><td width="230" height="119"><img src="/images/splash/splash_pub_11.gif" width="230" height="119"></td></tr>
 <tr><td width="230" height="12"><img src="/images/splash/splash_pub_15.gif" width="230" height="12"></td></tr>
 <tr><td>
  <table width="230" border="0" cellspacing="0" cellpadding="0">
   <tr>
    <td width="171" valign="top">
     <div id="member_nav">
      <?php if ($logged_in): ?>
       <p class="hello">hey, <b><?= h($u['username']) ?></b>!</p>
       <ul>
        <li><a href="/members/">Members Lobby</a></li>
        <li><a href="/forum/"><b>The Forum</b></a></li>
        <li><a href="/forum/online.php">Who&apos;s Online</a></li>
        <li><a href="/members/<?= h($u['username']) ?>/">My Profile</a></li>
        <li><a href="/tour/">Re-take Tour</a></li>
        <li><a href="/logout/">Sign Off</a></li>
       </ul>
       <?php if (!empty($u['post_count'])):
            $rank = rank_for((int)$u['post_count']); ?>
        <div class="rank_card">
         <b><?= h($rank['title']) ?></b><br>
         <?= (int)$u['post_count'] ?> post<?= ((int)$u['post_count']===1?'':'s') ?>
        </div>
       <?php endif; ?>
      <?php else: ?>
       <ul>
        <li><a href="/tour/">Take The Tour</a></li>
        <li><a href="/login/"><b>Sign In</b></a></li>
        <li><a href="/joinportal/">Join Now</a></li>
        <li><a href="/lostpassword/">Lost Password</a></li>
       </ul>
       <p class="invite">behind the velvet rope:<br>a real forum.<br>get in here.</p>
      <?php endif; ?>
     </div>
    </td>
    <td width="59" valign="top"><img src="/images/splash/splash_pub_18.gif" width="59" height="215"></td>
   </tr>
   <tr>
    <td colspan="2"><table width="171" border="0" cellpadding="0" cellspacing="0">
     <tr>
      <td><img src="/images/splash/splash_pub_33.gif" width="61" height="31"></td>
      <td><a href="https://onionmadder.xyz/" target="_new"><img src="/images/splash/splash_pub_34.png" width="110" height="31" border="0"></a></td>
     </tr>
    </table></td>
   </tr>
  </table>
 </td></tr>
</table>
<?php
}

/**
 * Breadcrumb trail: pass an array of [label, href|null] pairs.
 * The first entry is automatically prefixed with the home crumb.
 */
function render_crumbs(array $crumbs): void {
    array_unshift($crumbs, ['Okay, Amigo!', '/forum/']);
    echo '<div class="crumbs">';
    $bits = [];
    foreach ($crumbs as $c) {
        if (!empty($c[1])) $bits[] = '<a href="' . h($c[1]) . '">' . h($c[0]) . '</a>';
        else               $bits[] = '<b>' . h($c[0]) . '</b>';
    }
    echo implode(' &raquo; ', $bits);
    echo '</div>';
}
