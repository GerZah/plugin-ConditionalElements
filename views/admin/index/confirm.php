<?php
$pageTitle = __('Delete Dependency');
echo head(array('title'=>$pageTitle));
echo flash();
?>

<form method="get" action="<?php echo url('conditional-elements/index/delete'); ?>">
  <div class="field">
    <?php
    if (isset($_GET['dependent_id'])) {
      $dependent_id = intval($_GET['dependent_id']);
      $dependee_id = null;
      $term = null;

      $json=get_option('conditional_elements_dependencies');
      if (!$json) { $json="null"; }
      $dependencies = json_decode($json,true);
			if ($dependencies) {
	      foreach($dependencies as $dependency) {
	        if ($dependency[2] == $dependent_id) {
	          $dependee_id = $dependency[0];
	          $term = $dependency[1];
	        }
	      }
			}

			if ( ($dependent_id) and ($term) and ($dependee_id) ) {
	      $db = get_db();
	      $select = "SELECT id, name FROM $db->Element WHERE id in ($dependent_id, $dependee_id)";
	      $results = $db->fetchAll($select);

				$dependee = $dependent = null;
	      foreach($results as $result) {
					switch ($result['id']) {
						case $dependent_id : $dependent = $result['name']; break;
						case $dependee_id : $dependee = $result['name']; break;
					}
	      }

				if ( ($dependent) and ($dependee) ) {
    ?>
		<h3><?php echo __('Are you sure you wish to delete this dependency?'); ?></h3>
		<table>
			<tbody>
				<tr><th><?php echo __("Dependee"); ?>:</th><td><?php echo $dependee; ?></td></tr>
				<tr><th><?php echo __("Term"); ?>:</th><td><?php echo $term; ?></td></tr>
				<tr><th><?php echo __("Dependent"); ?>:</th><td><?php echo $dependent; ?></td></tr>
			</tbody>
		</table>
    <a href="<?php
               echo html_escape(url('conditional-elements/index/delete'));
             ?>?dependent_id=<?php
               echo $_GET['dependent_id'];
             ?>" class="button remove flr mrr4"><?php echo __("Yes"); ?></a>
    <a href="<?php echo html_escape(url('conditional-elements/index')); ?>" class="button buttonGreen cancel flr"><?php echo __("No"); ?></a>
    <?php
				}
				else { echo "<h3>".__('Dependency element names of dependency that should be deleted not found.')."</h3>\n"; }
			}
			else { echo "<h3>".__('Dependency that should be deleted not found.')."</h3>\n"; }
    }
		else { echo "<h3>".__('No dependency given that could be deleted.')."</h3>\n"; }
    ?>
  </div>
</form>
<?php echo foot(); ?>
