<?php

namespace Drupal\Driver\Fields\Drupal7;

/**
 * Entityreference field handler for Drupal 7.
 */
class EntityreferenceHandler extends AbstractHandler {

  /**
   * {@inheritdoc}
   */
  public function expand($values) {
    $entity_type = $this->fieldInfo['settings']['target_type'];
    $entity_info = entity_get_info($entity_type);
    // For users set label to username.
    if ($entity_type == 'user') {
      $entity_info['entity keys']['label'] = 'name';
    }

    $return = array();
    foreach ($values as $value) {
      $query = db_select($entity_info['base table'], 't')
        ->fields('t', array($entity_info['entity keys']['id']));
      if(is_numeric($value)){
        $query->condition('t.' . $entity_info['entity keys']['id'], $value);
      }
      else{
        $query->condition('t.' . $entity_info['entity keys']['label'], $value);
      }
      $target_id = $query->execute()->fetchField();
      if ($target_id) {
        $return[$this->language][] = array('target_id' => $target_id);
      }
    }
    return $return;
  }

}
