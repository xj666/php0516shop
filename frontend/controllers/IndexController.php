<?php
namespace frontend\controllers;

use backend\models\Goods;
use backend\models\GoodsCategory;
use backend\models\GoodsGallery;
use backend\models\GoodsIntro;
//use frontend\models\Index;
use frontend\models\Address;
use frontend\models\Cart;
use frontend\models\Member;
use frontend\models\Order;
use yii\web\Controller;
use Yii;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use common\models\LoginForm;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use frontend\models\ContactForm;
use yii\web\Cookie;

class IndexController extends Controller{
    public function actionIndex(){
        $goods= GoodsCategory::find()->where(['parent_id'=>0])->all();
        return $this->renderPartial('index',['goods'=>$goods]);
    }
    public function actionList(){
        $id=\Yii::$app->request->get('id');
        $categorys=GoodsCategory::find()->where(['parent_id'=>$id])->all();
        foreach ($categorys as $category){
            $id=$category->id;
        }
        $goods=Goods::find()->where(['goods_category_id'=>$id])->all();
        return $this->renderPartial('list',['goods'=>$goods]);
    }
    public function actionGoods(){
        $id=\Yii::$app->request->get('id');
        $goods=Goods::findOne(['goods_category_id'=>$id]);
       $gallerys=GoodsGallery::find()->where(['goods_id'=>$goods->id])->all();
        $intro=GoodsIntro::findOne(['goods_id'=>$goods->id]);
        return $this->renderPartial('goods',['goods'=>$goods,'gallerys'=>$gallerys,'intro'=>$intro]);
    }

    //添加到购物车页面  完成添加到购物车的操作
    public function actionAddcart($goods_id,$amount){

        if (\Yii::$app->user->isGuest){
            $cookies=\Yii::$app->request->cookies;
            $value=$cookies->getValue('cart');
            if ($value){
                $carts=unserialize($value);
            }else{
                $carts=[];
            }
            if (array_key_exists($goods_id,$carts)){
                $carts[$goods_id] +=$amount;
            }else{
                $carts[$goods_id]=$amount;
            }
//            var_dump($carts);exit;

            $cookies=\Yii::$app->response->cookies;
            $cookie=new Cookie();
            $cookie->name='cart';
            $cookie->value=serialize($carts);
            $cookie->expire=time()+7*24*3600;
            $cookies->add($cookie);
//            var_dump($cookies);exit;
        }else{
            Member::CookieToTable();
            $cart=new Cart();
            $cart->goods_id=$goods_id;
            $cart->amount=$amount;
            $cart->member_id=\Yii::$app->user->id;
            $cart->save();

        }
        return $this->redirect(['cart']);
    }
    public function actionCart(){
        if (\Yii::$app->user->isGuest){
            $cookies=\Yii::$app->request->cookies;
            $value=$cookies->getValue('cart');
            if ($value){
                $carts=unserialize($value);
            }else{
                $carts=[];
            }
//            var_dump($carts);exit;
            $models=Goods::find()->where(['in','id',array_keys($carts)])->all();
            $mon='';
            foreach ($models as $model){
                $mon+=$model->shop_price*$carts[$model->id];
            }
            return $this->renderPartial('cart',['models'=>$models,'carts'=>$carts,'mon'=>$mon,]);
        }else{
            $id=\Yii::$app->user->getId();
            $carts=Cart::find()->select(['goods_id','amount'])->where(['member_id'=>$id])->asArray()->all();
//            var_dump($carts);exit();
            $mon='';
            $data=[];
            foreach ($carts as $cart){
                $data[$cart['goods_id']]=$cart['amount'];
            }
            $models=Goods::find()->where(['in','id',array_keys($data)])->all();
            foreach ($models as $model){
                $mon+=$model->shop_price*$data[$model->id];
            }
            return $this->renderPartial('cart',['models'=>$models,'carts'=>$data,'mon'=>$mon,]);
        }

    }
    //AJAX修改购物车商品数量
    public function actionAjax(){
        // goods_id  amount  2=>1
        $goods_id = Yii::$app->request->post('goods_id');
        $amount = Yii::$app->request->post('amount');
        if(Yii::$app->user->isGuest){
            $cookies = Yii::$app->request->cookies;
            $value = $cookies->getValue('carts');
            if($value){
                $carts = unserialize($value);
            }else{
                $carts = [];
            }
            //检查购物车中是否存在当前需要添加的商品
            if(array_key_exists($goods_id,$carts)){
                $carts[$goods_id] = $amount;
            }
            $cookies = Yii::$app->response->cookies;
            $cookie = new Cookie();
            $cookie->name = 'carts';
            $cookie->value = serialize($carts);
            $cookie->expire = time()+7*24*3600;//过期时间戳
            $cookies->add($cookie);
        }else{
        }
    }

    /**
     * Logs in a user.
     *
     * @return mixed
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Logs out the current user.
     *
     * @return mixed
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }
    /**
     * Displays contact page.
     *
     * @return mixed
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail(Yii::$app->params['adminEmail'])) {
                Yii::$app->session->setFlash('success', 'Thank you for contacting us. We will respond to you as soon as possible.');
            } else {
                Yii::$app->session->setFlash('error', 'There was an error sending your message.');
            }

            return $this->refresh();
        } else {
            return $this->render('contact', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Displays about page.
     *
     * @return mixed
     */
    public function actionAbout()
    {
        return $this->render('about');
    }

    /**
     * Signs user up.
     *
     * @return mixed
     */
    public function actionSignup()
    {
        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post())) {
            if ($user = $model->signup()) {
                if (Yii::$app->getUser()->login($user)) {
                    return $this->goHome();
                }
            }
        }
        return $this->render('signup', [
            'model' => $model,
        ]);
    }

    /**
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');

                return $this->goHome();
            } else {
                Yii::$app->session->setFlash('error', 'Sorry, we are unable to reset password for the provided email address.');
            }
        }
        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }

    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionResetPassword($token)
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', 'New password saved.');

            return $this->goHome();
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }


}