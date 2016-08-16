<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Subtemplate for flash astro chart.
 */
?>
<div class="row">
    <div class="col-xs-12">
        <?= HTML::anchor('/schema.html', __( 'Switch to html5' ), [ 'title' => __( 'Modern browsers option' ) ] ); ?>
    </div>
</div>
<div class="row">
  <div class="col-xs-12 embed-responsive embed-responsive-4by3">
      <object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" class="embed-responsive-item" width="100%" height="100%" id="cosmogramm" align="middle">
            <param name="movie" value="/images/flash/cosmogramm.swf">
            <param name="quality" value="high">
            <param name="bgcolor" value="#ffffff">
            <param name="play" value="true">
            <param name="loop" value="true">
            <param name="wmode" value="opaque">
            <param name="scale" value="showall">
            <param name="menu" value="true">
            <param name="devicefont" value="false">
            <param name="salign" value="">
            <param name="allowScriptAccess" value="sameDomain">
            <!--[if !IE]>-->
            <object type="application/x-shockwave-flash" class="embed-responsive-item" width="100%" height="100%" data="/images/flash/cosmogramm.swf">
                    <param name="movie" value="/images/flash/cosmogramm.swf">
                    <param name="quality" value="high">
                    <param name="bgcolor" value="#ffffff">
                    <param name="play" value="true">
                    <param name="loop" value="true">
                    <param name="wmode" value="opaque">
                    <param name="scale" value="showall">
                    <param name="menu" value="true">
                    <param name="devicefont" value="false">
                    <param name="salign" value="">
                    <param name="allowScriptAccess" value="sameDomain">
            <!--<![endif]-->
                    <a href="http://www.adobe.com/go/getflash">
                            <img src="http://www.adobe.com/images/shared/download_buttons/get_flash_player.gif" alt="Загрузить Adobe Flash Player">
                    </a>
            <!--[if !IE]>-->
            </object>
            <!--<![endif]-->
    </object>
  </div>
</div>