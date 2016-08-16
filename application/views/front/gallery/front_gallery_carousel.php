<?php

/*
 * Carousel Template for gallery
 */
?>

<div class="modal" id="<?=$gallery->id.'_'.$slides[0]->id;?>">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title"><?=$gallery->title;?></h4>
      </div>
      <div class="modal-body">
        <div id="carousel-<?=$gallery->id;?>" class="carousel slide" data-ride="carousel">
            
            <!-- Wrapper for slides -->
            <div class="carousel-inner">
                <?php $indicators = ''; ?>
              <?php for($i = 0;$i<count($slides);$i++): ?>
              <?php 
                $cls = (!$i) ? 'active' : '';
                $indicators .= '<li data-target="#'.$gallery->id.'_'.$slides[0]->id.'" data-slide-to="'.$i.'" class="'.$cls.'"></li>';
              ?>
              <div class="item <?=$cls;?>">
                <?=HTML::image($slides[$i]->path, array('alt'=>$slides[$i]->title));?>
                <div class="carousel-caption">
                  <?php if($slides[$i]->title): ?>
                    <h3><?= $slides[$i]->title; ?></h3>
                  <?php endif; ?>
                    <p><?= $slides[$i]->caption; ?></p>
                </div>
              </div>
              <?php endfor; ?>
            </div>
            <!-- Indicators -->
            <ol class="carousel-indicators">
              <?=$indicators;?>
            </ol>
            
            <!-- Controls -->
            <a class="left carousel-control" href="#<?=$gallery->id.'_'.$slides[0]->id;?>" data-slide="prev">
              <span class="glyphicon glyphicon-chevron-left"></span>
            </a>
            <a class="right carousel-control" href="#<?=$gallery->id.'_'.$slides[0]->id;?>" data-slide="next">
              <span class="glyphicon glyphicon-chevron-right"></span>
            </a>
        </div>
        
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
