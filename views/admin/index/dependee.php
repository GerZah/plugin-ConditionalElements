<?php
$pageTitle = __('Add Dependency');
echo head(array('title'=>$pageTitle));
echo flash();

$def_dependee_id = null;
if (isset($_GET['dependee'])) { $def_dependee_id = intval($_GET['dependee']); }

?>
<form method="post" action="<?php echo url('conditional-elements/index/term'); ?>">
  <section class="seven columns alpha">
    <?php
    $dependent_id = $dependee_id = 0;
    if (isset($_POST['dependent'])) { $dependent_id = intval($_POST['dependent']); }
    else if (isset($_GET['dependent'])) { $dependent_id = intval($_GET['dependent']); }

    $backURL = $this->url('conditional-elements/index/add', array('dependent' => $dependent_id));

    $dependentName = null;
    if($dependent_id != 0) {
      $db = get_db();
      $selectDependent = "SELECT name FROM $db->Element WHERE id = $dependent_id";
      $dependentName = $db->fetchOne($selectDependent);
    }

    if($dependentName)
    {
      ?>
      <fieldset class="bulk-metadata-editor-fieldset" id='bulk-metadata-editor-items-set' style="border: 1px solid black; padding:15px; margin:10px;">
        <h2><?php echo __("Step 2: Select Dependee for Dependency"); ?></h2>
        <div class="field">
          <p><?php echo __("Choose a dependee element from the list below that will, based on the selection, ".
          "will affect the dependent element to become visible or hidden."); ?></p>
          <p><?php echo __("<em>Please note:</em> You will need to supply a list of possible selections ".
          "via \"Simple Vocabulary\" to an element to become a possible dependee."); ?></p>
          <p><?php echo __("<em>Please note:</em> One dependee can affect multiple dependents, ".
          "based on multiple values to choose from."); ?></p>
        </div>
        <table>
          <tbody>
            <?php
            $json=get_option('conditional_elements_dependencies');
            if (!$json) { $json="null"; }
            $dependencies = json_decode($json,true);

            $validElementSets = conditionalElementsValidElementSets();
            $elementSetsClause = ( $validElementSets ?
                                        "AND e.element_set_id in (".implode(",", $validElementSets).")"
                                        : "" );

            // $select = "SELECT es.name AS name, es.id AS id, e.element_id AS vocab_id
            //            FROM {$db->Element} es
            //            JOIN {$db->SimpleVocabTerm} e
            //            ON es.id = e.element_id
            //            WHERE es.id <> $dependent_id $elementSetsClause
            //            ORDER BY name";
            # echo "<pre>$select</pre>";
            $db = get_db();
            $select = "
            SELECT es.name AS element_set_name, e.id AS element_id,
            e.name AS element_name, it.name AS item_type_name
            FROM {$db->ElementSet} es
            JOIN {$db->Element} e ON es.id = e.element_set_id
            JOIN {$db->SimpleVocabTerm} s ON e.id = s.element_id
            LEFT JOIN {$db->ItemTypesElements} ite ON e.id = ite.element_id
            LEFT JOIN {$db->ItemType} it ON ite.item_type_id = it.id
            WHERE es.id <> $dependent_id $elementSetsClause
            ORDER BY e.name";
            $elements = $db->fetchAll($select);
            $dependee = array(0 => __('Select Below'));
            foreach ($elements as $element) {
                $optGroup = $element['item_type_name']
                          ? __('Item Type') . ': ' . __($element['item_type_name'])
                          : __($element['element_set_name']);
                $value = __($element['element_name']);
                $dependee[$optGroup][$element['element_id']] = $value;
            }
            #$dependee = array(0 => __('Select Below'));
            #foreach($results as $result) {
              #$dependee[$result['id']] = $result['name'];
            #}

            echo "<tr><th>".__("Dependee").":</th>\n<td>\n".
                 $this->formSelect('dependee', $def_dependee_id , array(), $dependee).
                 "</td></tr>\n";
            ?>
            <tr><th><?php echo __("Dependent");?>:</th><td><?php echo $dependentName; ?></td></tr>
          </tbody>
        </table>
      </fieldset>
    </section>
    <section class="three columns omega">
      <div id="save" class="panel">
        <!-- <a href="<?php echo html_escape(url('conditional-elements/index/add')); ?>" class="add big green button"><?php echo __('Previous'); ?></a> -->
        <input type="submit" class="big green button" name="submit" value="<?php echo __('Next'); ?>">
        <a href="<?php echo $backURL; ?>" class="big green button"><?php echo __('Back'); ?></a>
      </div>
    </section>
    <input type="hidden" name="dependent" value="<?php echo $dependent_id; ?>">
    <?php
  }
  else {
    echo "<h3>".__('Please choose a dependent to proceed.')."</h3>\n"; ?>
    <a href="<?php echo $backURL; ?>" class="green button" ><?php echo __('Back'); ?></a>
    <?php  }  ?>
  </form>
  <?php echo foot(); ?>
