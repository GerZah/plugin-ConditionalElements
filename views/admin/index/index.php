<?php
$pageTitle = __('Browse Conditional Elements');
echo head(array('title' => $pageTitle,'bodyclass' => 'dependent')); ?>
<div class="table-actions">
  <a href="<?php echo html_escape(url('conditional-elements/index/add')); ?>" class="add green button"><?php echo __('Add Dependency'); ?></a>
</div>
<table>
  <thead>
    <tr>
      <th><?php echo __('Dependee'); ?></th>
      <th><?php echo __('Term'); ?></th>
      <th><?php echo __('Dependent'); ?></th>
      <th><?php echo __('Actions'); ?></th>
    </tr>
  </thead>
  <tbody>
    <?php
    $json=get_option('conditional_elements_dependencies');
    if (!$json) { $json="null"; }
    $dependencies = json_decode($json,true);
    if ($dependencies) {
      $ids = array();
      foreach ($dependencies as $d){
        $ids[]=$d[0];
        $ids[]=$d[2];
      }
      $ids=array_unique($ids);
      $ids_verb = implode(",",$ids);
      $db = get_db();
      $select = "SELECT id, name FROM $db->Element WHERE id in ($ids_verb)";
      $results = $db->fetchAll($select);
      $data = array();
      foreach($results as $result) {
        $data[$result['id']] = $result['name'];
      }
      foreach ($dependencies as $dep){
        $dependee_id = $dep[0];
        $term = $dep[1];
        $dependent_id = $dep[2];
        $json_array = array();
        if (isset($data[$dependee_id])) { $json_array[0] = $data[$dependee_id]; }
        $json_array[1] = $term;
        if (isset($data[$dependent_id])) { $json_array[2] = $data[$dependent_id]; }
      if ((isset($data[$dependee_id])) and (isset($data[$dependent_id])) ) {
          ?>
          <tr>
            <td><?php echo $json_array[0]; ?>
            </td>
            <td><?php echo $json_array[1]; ?></td>
            <td><?php echo $json_array[2]; ?>
            </td>
            <td>
              <a class="confirm" href="<?php echo $this->url('conditional-elements/index/confirm', array('dependent_id' => $dependent_id)); ?>" ><?php echo __('Delete'); ?></a>
            </td>
          </tr>
          <?php
        }
      }
    }
    else {
      $dependencies ="null";
      ?>
      <tr>
        <td><?php echo __("[n/a]"); ?></td>
        <td><?php echo __("[n/a]"); ?></td>
        <td><?php echo __("[n/a]"); ?></td>
        <td><?php echo __("[n/a]"); ?></td>
      </tr>
  <?php  }; ?>
  </tbody>
</table>
<script>
jQuery(document).ready(function()
{
  var $ = jQuery;
  $('th').click(function(){
    var table = $(this).parents('table').eq(0)
    var rows = table.find('tr:gt(0)').toArray().sort(comparer($(this).index()))
    this.asc = !this.asc
    if (!this.asc){rows = rows.reverse()}
    for (var i = 0; i < rows.length; i++){table.append(rows[i])}
  })
  function comparer(index) {
    return function(a, b) {
      var valA = getCellValue(a, index), valB = getCellValue(b, index)
      return $.isNumeric(valA) && $.isNumeric(valB) ? valA - valB : valA.localeCompare(valB)
    }
  }
  function getCellValue(row, index){ return $(row).children('td').eq(index).html() }
});
</script>
<div class="table-actions">
  <a href="<?php echo html_escape(url('conditional-elements/index/add')); ?>" class="add green button"><?php echo __('Add Dependency'); ?></a>
</div>
<?php echo foot(); ?>
