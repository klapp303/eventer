<?php

Router::connect('/', array('controller' => 'Top', 'action' => 'index'));

/**
 * ...and connect the rest of 'Pages' controller's URLs.
 */
/* Router::connect('/pages/*',
  array('controller' => 'Pages', 'action' => 'index')); */

Router::connect('/event/:id', array('controller' => 'Events', 'action' => 'index'), array('id' => '[0-9]+')); //idを数字のみに制限

Router::connect('/places/place_detail/:id', array('controller' => 'Places', 'action' => 'place_detail'), array('id' => '[0-9]+')); //idを数字のみに制限

Router::connect('/user/:id', array('controller' => 'Users', 'action' => 'index'), array('id' => '[0-9]+')); //idを数字のみに制限

/**
 * Load all plugin routes. See the CakePlugin documentation on
 * how to customize the loading of plugin routes.
 */
CakePlugin::routes();

/**
 * Load the CakePHP default routes. Only remove this if you do not want to use
 * the built-in default routes.
 */
require CAKE . 'Config' . DS . 'routes.php';
