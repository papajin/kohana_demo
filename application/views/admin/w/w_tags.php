<div class="row">
    <div class="col-sm-3">
        <?= Form::label( 'tags', __( 'article tags' )); ?>
        <?= $tags; ?>
    </div>
    <div class="col-sm-9">
        <?= Form::label( 'all_tags', __( 'all tags' ) ); ?>
        <?= $tag_list; ?>
    </div>
</div>
<div class="row">
  <div class="col-sm-3">
      <?= Form::label( 'new_tag', __( 'add tag' ) ); ?>
      <div class="input-group">
          <?= Form::input( 'new_tag', '', [ 'id' => 'new_tag', 'class' => 'form-control' ] ); ?>
          <span class="input-group-btn">
              <?= Form::button( 'add_tag', '<i class="icon-ok"></i>', [ 'id' => 'add_tag', 'class' => 'btn btn-secondary' ] ); ?>
          </span>
      </div>
  </div>
</div>