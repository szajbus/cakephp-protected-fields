<?php
App::import('Model', array('AppModel'));
class Article extends AppModel {
  var $name = 'Article';
  var $useTable = false;
  var $belongsTo = array(
    'User'
  );
  var $protectedFields = array(
    'title'
  );
}

class User extends AppModel {
  var $name = 'User';
  var $useTable = false;
  var $hasMany = array(
    'Article'
  );
  var $hasOne = array(
    'Profile'
  );
  var $protectedFields = array(
    'password'
  );
}

class Profile extends AppModel {
  var $name = 'Profile';
  var $useTable = false;
  var $protectedFields = array(
    'signature'
  );
}
?>