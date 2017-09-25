<?php
$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'label');
echo $form->field($model,'url');/*
\backend\Controller\Menu::getParentOptions()*/
echo $form->field($model,'parent_id')->dropDownList(\backend\controllers\MenuController::getParent());
echo $form->field($model,'sort')->textInput();
echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-info']);
\yii\bootstrap\ActiveForm::end();
