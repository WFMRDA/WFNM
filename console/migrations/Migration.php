<?php
namespace console\migrations;

use Yii;
use \yii\db\Migration as Model;
/**
 * @author Dmitry Erofeev <dmeroff@gmail.com>
 * @editedBy: Reginald Goolsby <rjgoolsby@pyrotechsolutions.com>
 */
class Migration extends Model
{
    /**
     * @var string
     */
    protected $tableOptions;
    /**
    *MySQL: LONGBLOB
    *PostgreSQL: BYTEA
    * @var string
    */
    protected $sessionBlobType;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        if(!isset(Yii::$app->db->driverName)){
            throw new yii\base\InvalidParamException('No Driver name Found');
        }
        if (Yii::$app->db->driverName == 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $this->tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
            $this->sessionBlobType = 'LONGBLOB';
        }elseif (Yii::$app->db->driverName == 'pgsql') {
            $this->tableOptions = null;
            $this->sessionBlobType = 'BYTEA';
        }else{
            throw new \RuntimeException('Your database is not supported!');
        }
    }

    /**
     * Obtains the metadata for the named table.
     * @param string $name table name. The table name may contain schema name if any. Do not quote the table name.a
     * @return TableSchema table metadata. Null if the named table does not exist.
     */
    public function getTableSchema($name = ''){
        return Yii::$app->db->schema->getTableSchema($name);
    }

    public function tableExists($name = ''){
        return (!is_null(Yii::$app->db->schema->getTableSchema($name))) ? true : false ;
    }


    public function columnExists($column, $table){
        return (isset($table->columns[$column]))? true : false ;
    }


/*    public function createTable($table, $columns, $options = null) {
        if ($this->tableExists($table)){
            echo "    > table '$table' already exists\n";
            $this->completeColumns($table,$columns);
            return null;
        }else{
            //return parent::createTable($table, $columns, $options);
        }
    }*/

/*    public function completeColumns($table,$columns){
        $schema = $this->getDb()->getTableSchema($table);\
        foreach ($columns as $column => $type){
            if (is_int($column)){
                continue;
            }
            if (is_null($schema->getColumn($column))){
                $this->addColumn($table, $column, $type);
                echo "        > column '$column' added\n";
            }
        }
    }*/
}
