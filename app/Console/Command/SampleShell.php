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
 * [libまでのパス] /lib/Cake/Console/cake.php App(Shellコントローラ名、Shell除く) (アクション名、指定なしでmainアクション) [appまでのパス]
 * cd /home/アカウント名/www/プロジェクト名/app/ ; /usr/local/bin/php /home/アカウント名/www/プロジェクト名/app/Console/cake.php Sample
 */

App::uses('CakeEmail', 'Network/Email');

/**
 * Application Shell
 *
 * Add your application-wide methods in the class below, your shells
 * will inherit them.
 *
 * @package       app.Console.Command
 */
class SampleShell extends AppShell {
  public $uses = array(); //使用するModel

  public function mmain() {
  }
}