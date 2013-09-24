<?PHP
require_once($_SERVER['DOCUMENT_ROOT']."/PedigreeImport/model/model.php");

$model = new model();

$model->InitDB('localhost',
               'root',
               '',
               'central',
               'users');

/*$model->InitDB('127.0.0.1:5528',
               'phenibquser',
               'phenibqpass',
               'iris_myisam_20121002',
               'users');
*/				  
?>