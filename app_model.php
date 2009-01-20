<?php
/**
 * AppModel for CakePHP
 *
 * Provides functionality of protected fields.
 * A protected field can't be set via multi-assignment (assigning multiple fields at once
 * via $model->set(array(...))), but it still can be set directly with $model->set($field, $value).
 * 
 * @author    Michal Szajbe (michal@szajbe.pl, http://codetunes.com)
 * @link      http://github.com/netguru/cakephp-protected-fields/tree/master
 * @license   http://www.opensource.org/licenses/mit-license.php The MIT License
 */
class AppModel extends Model {
  
  var $protectedFields = array();

  function set($one, $two = null) {
    if (!$one) {
			return;
		}
		if (is_object($one)) {
			$one = Set::reverse($one);
		}

		if (is_array($one)) {
		  if (empty($one[$this->alias])) {
				if ($this->getAssociated(key($one)) === null) {
					$one = array($this->alias => $one);
				}
			}
			foreach ($one as $alias => $data) {
			  if ($alias == $this->alias) {
			    $one[$alias] = $this->filterProtectedFields($data);
			  } else {
  			  $associated = $this->getAssociated($alias);
  			  if (!empty($associated)) {
  			    $model = $associated['className'];
  			    $one[$alias] = $this->$model->filterProtectedFields($data);
  			  }
			  }
			}
		}
    parent::set($one, $two);
  }
  
  function filterProtectedFields($array) {
    if (!empty($array) and is_numeric(key($array))) {
      foreach ($array as $key => $row) {
        $array[$key] = $this->filterProtectedFields($row);
      }
    } else {
      foreach ($this->protectedFields as $field) {
        if (array_key_exists($field, $array)) {
          unset($array[$field]);
        }
      }
    }
    return $array;
  }
}
?>