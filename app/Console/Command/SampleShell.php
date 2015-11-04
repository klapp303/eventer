<?php
/**
 * AppShell file
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
 * @since         CakePHP(tm) v 2.0
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

/**
 * shell の呼び出し
 * windowsコマンドライン
 * php [projectまでのpass]/app/console/cake.php Sample main(指定なしでmain実行) -app [projectまでのpass]
 * さくらサーバ
 * cd /home/[アカウント名]/www/[project名]; /usr/local/bin/php [実行ファイル名].php > /dev/null
 */

App::uses('ComponentCollection', 'Controleer');
App::uses('AuthComponent', 'Controller/Component');
//App::uses('CakeEmail', 'Network/Email');

/**
 * SampleShell
 *
 * Add your application-wide methods in the class below, your shells
 * will inherit them.
 *
 * @package       app.Console.Command
 */
class SampleShell extends AppShell {
  //public $uses = array(); //使用するModel

  public function mmain() {
      $this->out('Hello, World');
  }
}