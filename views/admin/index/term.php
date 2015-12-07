<?php
$pageTitle = __('Add Dependency');
echo head(array('title'=>$pageTitle));
echo flash();
?>
<form method="post" action="<?php echo url('conditional-elements/index/save'); ?>">
  <section class="seven columns alpha">
    <?php
    $dependent_id = $dependee_id = 0;
         if (isset($_POST['dependent'])) { $dependent_id = intval($_POST['dependent']); }
    else if (isset($_GET['dependent'])) { $dependent_id = intval($_GET['dependent']); }
         if (isset($_POST['dependee'])) { $dependee_id = intval($_POST['dependee']); }
    else if (isset($_GET['dependee'])) { $dependee_id = intval($_GET['dependee']); }

    $backURL = $this->url('conditional-elements/index/dependee',
                          array('dependent' => $dependent_id, 'dependee' => $dependee_id));

    $dependentName = $dependeeName = null;
    if ( ($dependee_id) and ($dependent_id) )
    {
      $db = get_db();
      $selectDependent = "SELECT name FROM $db->Element WHERE id = $dependent_id";
      $selectDependee = "SELECT name FROM $db->Element WHERE id = $dependee_id";
      $dependentName = $db->fetchOne($selectDependent);
      $dependeeName = $db->fetchOne($selectDependee);
    }

    $term = null;
    if ($dependeeName) {
      $db = get_db();
      $selectTerms = "SELECT terms from `$db->SimpleVocabTerm` where element_id = $dependee_id";
      $terms = $db->fetchOne($selectTerms);
      if ($terms) { $term = explode("\n", $terms); }
    }

    if ( ($dependentName) and ($dependeeName) and ($term) )
    {
        ?>
    <fieldset class="bulk-metadata-editor-fieldset" id='bulk-metadata-editor-items-set' style="border: 1px solid black; padding:15px; margin:10px;">
      <h2><?php echo __("Step 3: Select Dependee Value to Affect Dependent"); ?></h2>
      <div class="field">
        <p><?php echo __("Choose one of the dependee element's possible values. If this value is selected, ".
                         "the dependent element will become visible; otherwise, it will be hidden."); ?></p>
      </div>
      <table>
        <tbody>
          <tr><th><?php echo __("Dependee"); ?>:</th><td><?php echo $dependeeName; ?></td></tr>
          <tr><th><?php echo __("Term"); ?>:</th>
          <td>
          <?php
           $fullterm = array( -1 => __('Select Below') );
           foreach($term as $value) { $fullterm[] = $value; }
           echo $this->formSelect('term', $fullterm , array(), $fullterm);
          ?>
          </td></tr>
          <tr><th><?php echo __("Dependent"); ?>:</th><td><?php echo $dependentName; ?></td></tr>
        </tbody>
      </table>
    </fieldset>
  </section>
  <section class="three columns omega">
    <div id="save" class="panel">
      <input type="submit" class="big green button" name="submit" value="<?php echo __('Save'); ?>">
      <a href="<?php echo $backURL; ?>" class="big green button"><?php echo __('Back'); ?></a>
    </div>
  </section>
  <input type="hidden" name="dependee" value="<?php echo $dependee_id; ?>">
  <input type="hidden" name="dependent" value="<?php echo $dependent_id; ?>">
  <?php
}
else {
  echo "<h3>".__('Please choose a dependee to proceed.')."</h3>\n"; ?>
 <a href="<?php echo $backURL; ?>" class="green button" ><?php echo __('Back'); ?></a>
  <?php  }  ?>
</form>
<?php echo foot(); ?>
