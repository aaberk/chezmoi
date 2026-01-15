<?php
if (!defined('APP_SECURE')) die('nuh uh');


class connexion {
	protected static $bdd;
	
	public function __construct(){}
	
	public static function initConnexion()  {
		self::$bdd =new PDO(
        'mysql:host=localhost;dbname=dutinfopw201627;charset=utf8',
        'dutinfopw201627',
        'tuquvyba');
       }
    }
?>
