<?php

namespace frontend\controllers;

use frontend\models\Address;
use frontend\models\Locations;

class AddressController extends \yii\web\Controller
{
    /*    //查找省
    public function actionProvince(){
        $province=Locations::find()->where(['parent_id'=>0])->asArray()->all();
        echo json_encode($province);
    }
    //查找市
    public function actionCity(){
        $request=\Yii::$app->request;
        $pad=$request->get('pid');
        $city=Locations::find()->where(['parent_id'=>$pad])->asArray()->all();
        echo json_encode($city);
    }

    //查找区
    public function actionArea(){
        $request=\Yii::$app->request;
        $pid=$request->get('pid');
        $area=Locations::find()->where(['parent_id'=>$pid])->asArray()->all();
        echo json_encode($area);
    }*/
    public function actionAddress()
    {
        $model = new Address();
        $member_id = \Yii::$app->user->identity->getId();
        $address = Address::findOne(['id'=>$member_id]);

        $request = \Yii::$app->request;
        if ($request->isPost) {
            $model->load($request->post(), '');
            // var_dump($model);die;
            if ($model->validate()) {
                $model->save();
                return $this->redirect(['']);
            } else {
                var_dump($model->getErrors());
                exit;
            }
        }
        $addressies = Address::find()->all();
        return $this->renderPartial('address', ['model' => $model, 'addressies' => $addressies]);
    }

    public function actionEdit($id)
    {
        $model = Address::findOne(['id' => $id]);
        $request = \Yii::$app->request;
        //  var_dump($model);die;
        if ($request->isPost) {
            $model->load($request->post(), '');
            if ($model->validate()) {
                $model->save();
                return $this->redirect(['/login']);
            } else {
                var_dump($model->getErrors());
                exit;
            }
        }
        $addressies = Address::find()->all();
        return $this->renderPartial('address', ['model' => $model, 'addressies' => $addressies]);
    }

    //删除
    public function actionDelete($id)
    {
        //$id=\Yii::$app->request->post('id');
        $address = Address::findOne(['id' => $id]);
        if ($address) {
            $address->delete();
            //   return 'success';
            return $this->redirect(['address/address']);
        }
        // return 'fail';
    }


}


