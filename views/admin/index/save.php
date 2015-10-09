<?php
$pageTitle = __('Add Dependency');
echo head(array('title'=>$pageTitle));
echo flash();

    $dependent_id = $dependee_id = 0;
    if (isset($_POST['dependent'])) { $dependent_id = intval($_POST['dependent']); }
    if (isset($_POST['dependee'])) { $dependee_id = intval($_POST['dependee']); }

    $backURL = $this->url('conditional-elements/index/term', array('dependent' => $dependent_id, 'dependee' => $dependee_id ));

    $term = -1;
    if (isset($_POST['term'])) { $term = intval($_POST['term']); }
?>
  <section class="seven columns alpha">
    <fieldset class="bulk-metadata-editor-fieldset" id='bulk-metadata-editor-items-set' style="border: 1px solid black; padding:15px; margin:10px;">
      <div class="field">
        <?php
        if($term<0)
        { ?>
          <h2><?php echo __("No term is selected. Please try creating the dependency again."); ?></h2>
          <a href="<?php echo $backURL; ?>" class="green button" ><?php echo __('Back'); ?></a>
        <?php }
        else { ?>
        <h2><?php echo __("You have successfully saved the dependency."); ?></h2>
          <?php }; ?>
      </div>
    </fieldset>
  </section>
<?php if($term>0) { ?>
  <section class="three columns omega">
    <div id="save" class="panel">
     <a href="<?php echo html_escape(url('conditional-elements/index')); ?>" class="add big green button"><?php echo __('Back'); ?></a>
   </div>
  </section>
<?php } ?>
<?php echo foot(); ?>
