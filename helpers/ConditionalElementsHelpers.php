<?php

/**
 * Deliver element set IDs that are suitable to become dependent / dependee elements
 * i.e. 1 (for Dublin Core elements) and (usually) 3 (for metadata elements)
 */
function conditionalElementsValidElementSets() {
  $db = get_db();
  $query = "SELECT id FROM `$db->ElementSets`".
            " WHERE name='Dublin Core'".
            " OR (record_type='Item' and name='Item Type Metadata')";
  $ids = $db->fetchAll($query);
  $result = array();
  foreach($ids as $id) { $result[] = $id["id"]; }
  return $result;
}

?>
