<?php
$pageTitle = __('Delete Dependency');
echo head(array('title'=>$pageTitle));
echo flash();
?>
  <section class="seven columns alpha">
    <fieldset class="bulk-metadata-editor-fieldset" id='bulk-metadata-editor-items-set' style="border: 1px solid black; padding:15px; margin:10px;">
      <div class="field">
        <h2><?php echo __("You have successfully deleted the dependency."); ?></h2>
      </div>
    </fieldset>
  </section>
  <section class="three columns omega">
    <div id="save" class="panel">
     <a href="<?php echo html_escape(url('conditional-elements/index')); ?>" class="add big green button"><?php echo __('Back'); ?></a>
   </div>
  </section>
<?php echo foot(); ?>
