<?php

/*
 * Carousel Template for gallery
 */

$cnt = count($slides);  // Slides number
?>
<?php for ($i = 1; $i <= $cnt; $i++): ?>
<div class="modal" id="<?=$gallery->id.'_'.$slides[$i-1]->id;?>">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title"><?=$gallery->title;?><?php if($slides[$i-1]->title) echo ': '.$slides[$i-1]->title;?></h4>
      </div>
      <div class="modal-footer">
        <?=($i-1)
            ? HTML::anchor('#'.$gallery->id.'_'.$slides[$i-2]->id, '<span class="glyphicon glyphicon-step-backward"></span>', array('data-toggle' => 'modal', 'data-dismiss'=>'modal', 'class'=>'btn pull-left', 'title'=>'Назад', 'data-slide'=>'prev'))
            : HTML::anchor('#', '<span class="glyphicon glyphicon-step-backward"></span>', array('class'=>'btn pull-left disabled'));?>
        <?=($i < ($cnt))
            ? HTML::anchor('#'.$gallery->id.'_'.$slides[$i]->id, '<span class="glyphicon glyphicon-step-forward"></span>', array('data-toggle' => 'modal', 'data-dismiss'=>'modal', 'class'=>'btn pull-right', 'title'=>'Вперед', 'data-slide'=>'next'))
            : HTML::anchor('#', '<span class="glyphicon glyphicon-step-forward"></span>', array('class'=>'btn pull-right disabled'));?>
        <center>Слайд <?=$i;?> из <?=$cnt;?></center>
      </div>
      <div class="modal-body">
        <?=HTML::image($slides[$i-1]->path, array('class'=>'img-responsive', 'alt'=>$slides[$i-1]->title));?>
        <hr />
        <p>
            <?=$slides[$i-1]->caption;?>
        </p>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<?php endfor; ?>