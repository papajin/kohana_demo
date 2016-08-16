<?php 
    $loc_str = 'en_US';
    if ( I18n::lang() === 'uk' ) $loc_str = 'uk_UA';
    elseif ( I18n::lang() === 'ru' ) $loc_str = 'ru_RU';
 ?>
<div id="fb-root"></div>
<div id="likebox-wrapper">
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/<?= $loc_str; ?>/sdk.js#xfbml=1&version=v2.7";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
<div class="fb-page" data-href="https://www.facebook.com/ovivanetz/" data-tabs="timeline" data-width="500" data-height="260" data-small-header="false" data-adapt-container-width="true" data-hide-cover="false" data-show-facepile="true"><div class="fb-xfbml-parse-ignore"><blockquote cite="https://www.facebook.com/ovivanetz/"><a href="https://www.facebook.com/ovivanetz/">Астролог Ольга Іванець</a></blockquote></div></div>
</div>