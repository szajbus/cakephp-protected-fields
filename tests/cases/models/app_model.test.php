<?php
require_once dirname(dirname(dirname(__FILE__))).DS.'fixtures'.DS.'models.php';
class AppModelTest extends CakeTestCase {
  var $name = 'AppModel';
  
  function startCase() {
    $this->User =& ClassRegistry::init('User');
    $this->Article =& ClassRegistry::init('Article');
  }
  
  function testProtectedFields() {
    // simple
    $data = array(
      'User' => array(
        'login' => 'michal',
        'password' => '123abc'
      )
    );
    $this->User->set($data);
    $expected = array(
      'User' => array(
        'login' => 'michal'
      )
    );
    $this->assertEqual($expected, $this->User->data);
    $this->User->set('password', '123abc');
    $this->assertEqual($data, $this->User->data);
    
    // multiple records
    $data = array(
      'User' => array(
        array(
          'login' => 'michal',
          'password' => '123abc'
        ),
        array(
          'login' => 'john',
          'password' => 'qwe123'
        )
      )
    );
    $this->User->create();
    $this->User->set($data);
    $expected = array(
      'User' => array(
        array(
          'login' => 'michal'
        ),
        array(
          'login' => 'john'
        )
      )
    );
    $this->assertEqual($expected, $this->User->data);
    
    // associated models (belongsTo)
    $data = array(
      'User' => array(
        'login' => 'michal',
        'password' => '123abc'
      ),
      'Article' => array(
        'title' => 'some stuff',
        'body' => 'some text'
      )
    );
    $this->Article->set($data);
    $expected = array(
      'User' => array(
        'login' => 'michal'
      ),
      'Article' => array(
        'body' => 'some text'
      )
    );
    $this->assertEqual($expected, $this->Article->data);
    
    // associated models (hasOne)
    $data = array(
      'User' => array(
        'login' => 'michal',
        'password' => '123abc'
      ),
      'Profile' => array(
        'gender' => 'male',
        'signature' => 'greetings'
      )
    );
    $this->User->create();
    $this->User->set($data);
    $expected = array(
      'User' => array(
        'login' => 'michal'
      ),
      'Profile' => array(
        'gender' => 'male'
      )
    );
    $this->assertEqual($expected, $this->User->data);
    
    // associated models (hasMany)
    $data = array(
      'User' => array(
        'login' => 'michal',
        'password' => '123abc'
      ),
      'Article' => array(
        array(
          array(
            'title' => 'some stuff',
            'body' => 'some text'
          ),
          array(
            'title' => 'some other stuff',
            'body' => 'some other text'
          )
        )
      )
    );
    $expected = array(
      'User' => array(
        'login' => 'michal'
      ),
      'Article' => array(
        array(
          array(
            'body' => 'some text'
          ),
          array(
            'body' => 'some other text'
          )
        )
      )
    );
    $this->User->create();
    $this->User->set($data);
    $this->assertEqual($expected, $this->User->data);
  }
}
?>