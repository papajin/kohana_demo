<?php defined('SYSPATH') or die('No direct script access.'); ?>
<div id="share">
    <ul class="social-links clearfix">
        <li class="facebook">
            <div class="fb-like" data-href="<?= $url; ?>" data-layout="box_count" data-action="recommend" data-size="small" data-show-faces="false" data-share="true"></div>
            <?//= HTML::anchor( sprintf( 'https://www.facebook.com/plugins/like/connect?action=recommend&t=%s&u=%s&social_plugin_action=recommend', $page->title, $url ), '<i class="icon-facebook"></i>', [ 'rel' => 'nofollow', 'id' => 'fb_submit_link' ] ); ?>
        </li>
        <li class="googleplus">
            <?= HTML::anchor( sprintf( 'https://plus.google.com/share?url=%s', $url ), '<i class="icon-gplus"></i>', [ 'rel' => 'nofollow' ] ); ?>
        </li>
        <li class="twitter">
            <?= HTML::anchor( sprintf( 'http://twitter.com/timeline/home/?status=%s', $url ), '<i class="icon-twitter"></i>', [ 'rel' => 'nofollow' ] ); ?>
        </li>
    </ul>
</div>