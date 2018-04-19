<?php
use common\widgets\MyLocationsWidget;
use yii\data\ArrayDataProvider;
?>
        <?=  MyLocationsWidget::widget([
            'dataProvider' =>  new ArrayDataProvider([
                'allModels' => $models,
                'pagination' => false,
            ]),
            'columns' => [
                'address',
                'created_at:date'
            ],
            'tableOptions'=>[
                'id'=> 'myLocationsTable',
            ],
            'clientOptions'=>[
                "order" => [[ 1, "desc" ]],
                'stateSave' => true,
                'dom' => '<"row"r <"col-xs-12"f> <"col-xs-12"l> <"col-xs-12"t><"col-xs-12"i> <"col-xs-12"p> >',
                'pageLength' => 5,
            ],
        ]);?>
