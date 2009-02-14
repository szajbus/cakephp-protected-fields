<?php
require_once dirname(dirname(dirname(__FILE__))).DS.'fixtures'.DS.'models.php';
class AppModelTest extends CakeTestCase {
  var $name = 'AppModel';
  
  function startCase() {
    $this->User =& ClassRegistry::init('User');
    $this->UserWithDefaults =& ClassRegistry::init('UserWithDefaults');
    $this->Article =& ClassRegistry::init('Article');
  }
  
  function startTest() {
    $this->User->create();
    $this->UserWithDefaults->create();
    $this->Article->create();
  }
  
  function testDoesNotProtectDefaultsOnCreate() {
    $data = $this->UserWithDefaults->create();
    
    $expected = array(
      'UserWithDefaults' => array(
        'password' => 'defaultpassword'
      )
    );
    $this->assertEqual($expected, $this->UserWithDefaults->data);
  }
  
  function testProtectsFieldsOnCreate() {
    $data = array(
      'User' => array(
        'login' => 'michal',
        'password' => '123abc'
      )
    );
    $this->User->create($data);
    
    $expected = array(
      'User' => array(
        'login' => 'michal'
      )
    );
    $this->assertEqual($expected, $this->User->data);
  }
  
  function testProtectsFieldsOnSet() {
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
  }
  
  function testDoesNotProtectOnExplicitSet() {
    $this->User->set('password', '123abc');
    
    $expected = array(
      'User' => array(
        'password' => '123abc'
      )
    );
    $this->assertEqual($expected, $this->User->data);
  }
  
  function testProtectsMultipleRecord() {
    $this->User->create(null);
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
  }
  
  function testProtectsBelongsToAssociations() {
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
  }
  
  function testProtectsHasOneAssociations() {
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
  }
  
  function testProtectsHasManyAssociations() {
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
    $this->User->set($data);
    
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
    
    $this->assertEqual($expected, $this->User->data);
  }
}
?>