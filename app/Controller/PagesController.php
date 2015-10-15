<?php
/**
 * Static content controller.
 *
 * This file will render views from views/pages/
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

App::uses('AppController', 'Controller');

/**
 * Static content controller
 *
 * Override this controller by placing a copy in controllers directory of an application
 *
 * @package       app.Controller
 * @link http://book.cakephp.org/2.0/en/controllers/pages-controller.html
 */
class PagesController extends AppController {

/**
 * This controller does not use a model
 *
 * @var array
 */
	public $uses = array('User', 'EventGenre', 'EntryGenre'); //使用するModel

/**
 * Displays a view
 *
 * @return void
 * @throws NotFoundException When the view file could not be found
 *	or MissingViewException in debug mode.
 */

  public function beforeFilter() {
      parent::beforeFilter();
      $this->layout = 'eventer_fullwidth';
  }

  public function index() {
  }

  /*public function user_lists() {
      $user_lists = $this->User->find('all', array(
          'order' => array('id' => 'asc'),
          'conditions' => array('id !=' => 1) //管理者データは非表示
      ));
      $this->set('user_lists', $user_lists);
  }*/

  public function event_genres() {
      $event_genre_lists = $this->EventGenre->find('all', array(
          'order' => array('id' => 'asc')
      ));
      $this->set('event_genre_lists', $event_genre_lists);
  }
  
  public function entry_genres() {
      $entry_genre_lists = $this->EntryGenre->find('all', array(
          'order' => array('id' => 'asc')
      ));
      $this->set('entry_genre_lists', $entry_genre_lists);
  }

  public function issue_lists() {
  }
}