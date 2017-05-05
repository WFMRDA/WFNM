<?php

namespace common\widgets;

use Yii;
use yii\base\Widget;
use yii\widgets\InputWidget;
use common\widgets\assets\BirthdayPickerAssets;
use yii\helpers\Json;
use yii\helpers\Html;

class BirthdayPicker extends Widget{

	public $form;
    public $model;
    public $attribute;
    public $pluginOptions = [];
    public $listData = [];

	public function init(){
		parent::init();
	}

	/**
     * @inheritdoc
     */
    public function run()
    {
        $this->initClientOptions();
        echo Html::tag('div',$this->activeModelInputs(),['id'=>$this->id]);
    }

    protected function activeModelInputs(){
        
        $html  = $this->form->field($this->model, 'birth_month',['selectors' => ['input' => '#birthday-month']])->dropDownList($this->listData['months'],['prompt'=>'Select Month','id'=>'birthday-month']); 
        
        $html  .= $this->form->field($this->model, 'birth_day',['selectors' => ['input' => '#birthday-day']])->dropDownList($this->listData['days'],['prompt'=>'Select Day','id'=>'birthday-day']); 

        $html  .= $this->form->field($this->model, 'birth_year',['selectors' => ['input' => '#birthday-year']])->dropDownList($this->listData['years'],['prompt'=>'Select Year','id'=>'birthday-year']); 
        
        $html  .= $this->form->field($this->model, $this->attribute)->hiddenInput(['id'=>'birthdate'])->label(false); 

        return $html;
    }

    protected function nonActiveModelInputs(){
    	return  Html::textarea($this->name, $this->value, $this->options);
    }

    protected function initClientOptions(){
    	BirthdayPickerAssets::register($this->view);
        $this->pluginOptions['fieldset'] = $this->id;
        
        $pluginOptions = Json::encode($this->pluginOptions);
        

        $js[] = "jQuery('#$this->id').birthdaypicker($pluginOptions);";

        $this->view->registerJs(implode(PHP_EOL, $js));
    }
}

/*

      var $fieldset = $("#birthday"),
      $year = $("#birthday-year");
      $month = $("#birthday-month");
      $day = $("#birthday-day");
      // Build the initial option sets
      var startYear = todayYear - settings["minAge"];
      var endYear = todayYear - settings["maxAge"];
      var hiddenDate;
      // Create the hidden date markup
      if (settings["hiddenDate"]) {
        $("<input type='hidden' name='" + settings["fieldName"] + "'/>")
            .attr("id", settings["fieldId"])
            .val(hiddenDate)
            .appendTo($fieldset);
      }



<div id="birthday">
    <?php echo $form->field($model, 'birth_month',['selectors' => ['input' => '#birthday-month']])
        ->dropDownList($listData['months'],['prompt'=>'Select Month','id'=>'birthday-month']); ?>
    <?php echo $form->field($model, 'birth_day',['selectors' => ['input' => '#birthday-day']])
        ->dropDownList($listData['days'],['prompt'=>'Select Day','id'=>'birthday-day']); ?>
    <?php echo $form->field($model, 'birth_year',['selectors' => ['input' => '#birthday-year']])
        ->dropDownList($listData['years'],['prompt'=>'Select Year','id'=>'birthday-year']); ?>
</div>


*/
