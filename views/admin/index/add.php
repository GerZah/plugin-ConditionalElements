<?php
$pageTitle = __('Add Dependency');
echo head(array('title'=>$pageTitle));
echo flash();

$def_dependent_id = null;
if (isset($_GET['dependent'])) { $def_dependent_id = intval($_GET['dependent']); }
?>
<form method="post" action="<?php echo url('conditional-elements/index/dependee'); ?>">
  <section class="seven columns alpha">
    <fieldset class="bulk-metadata-editor-fieldset" id='bulk-metadata-editor-items-set' style="border: 1px solid black; padding:15px; margin:10px;">
      <h2><?php echo __("Step 1: Select Dependent for Dependency"); ?></h2>
      <div class="field">
        <p><?php echo __("Choose a dependent element from the list below that should become ".
                          "visible or hidden based on the selection for some other element."); ?></p>
        <p><?php echo __("<em>Please note:</em> One element can be dependent only from one other element."); ?></p>
      </div>
      <table>
        <tbody>
        <tr><th><?php echo __("Dependent"); ?>:</th>
        <td>
          <?php
          $json=get_option('conditional_elements_dependencies');
          if (!$json) { $json="null"; }
          $dependencies = json_decode($json,true);

          $whereClause = "";
          if ($dependencies) {
            $ids = array();
            foreach ($dependencies as $d) { $ids[]=$d[2]; }
            $ids = array_unique($ids);
            $ids_verb = implode(",", $ids);
            $whereClause = "WHERE e.id NOT in ($ids_verb)";
          }

          $elementSetsClause = "";
          $validElementSets = ConditionalElementsPlugin::conditionalElementsValidElementSets();
          if ($validElementSets) {
            $elementSetsClause = "e.element_set_id in (".implode(",", $validElementSets).")";
            if ($whereClause) {
              $whereClause .= " AND ".$elementSetsClause;
            }
            else {
              $whereClause = "WHERE ".$elementSetsClause;
            }
          }

          $db = get_db();
          #$select = "SELECT id, name FROM `$db->Element` $whereClause ORDER BY name";
          # echo "<pre>$select</pre>";
          #$results = $db->fetchAll($select);

          #$dependent = array(0 => __('Select Below'));
          #foreach($results as $result) { $dependent[$result['id']] = $result['name']; }
          #echo $this->formSelect('dependent', $def_dependent_id, array(), $dependent);

          $select = "
          SELECT es.name AS element_set_name, e.id AS element_id,
          e.name AS element_name, it.name AS item_type_name
          FROM {$db->ElementSet} es
          JOIN {$db->Element} e ON es.id = e.element_set_id
          LEFT JOIN {$db->ItemTypesElements} ite ON e.id = ite.element_id
          LEFT JOIN {$db->ItemType} it ON ite.item_type_id = it.id
          $whereClause
          ORDER BY e.name";
          $elements = $db->fetchAll($select);
          $dependent = array(0 => __('Select Below'));
          foreach ($elements as $element) {
              $optGroup = $element['item_type_name']
                        ? __('Item Type') . ': ' . __($element['item_type_name'])
                        : __($element['element_set_name']);
              $value = __($element['element_name']);
              $dependent[$optGroup][$element['element_id']] = $value;
          }
          echo $this->formSelect('dependent', $def_dependent_id , array(), $dependent);

          ?>
        </td></tr>
        </tbody>
      </table>
    </fieldset>
  </section>
  <section class="three columns omega">
    <div id="save" class="panel">
      <input type="submit" class="big green button" name="submit" value="<?php echo __('Next'); ?>">
    </div>
  </section>
</form>
<?php echo foot(); ?>
