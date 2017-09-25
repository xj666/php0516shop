<?php

namespace backend\controllers;

use backend\models\PermissionForm;
use backend\models\RoleForm;
use yii\rbac\Permission;
use yii\web\NotFoundHttpException;

class RbacController extends \yii\web\Controller
{
    //权限列表
    public function actionPermissionIndex()
    {
        /*$auth = \Yii::$app->authManager;
        */
        $auth = \Yii::$app->authManager;
        $permissions = $auth->getPermissions();

        return $this->render('permission-index', ['permissions' => $permissions]);
    }

    //['scenario'=>PermissionForm::SCENARIO_ADD]
    public function actionAddPermission()
    {
        $model = new PermissionForm();
        $requst = \Yii::$app->request;
        if ($requst->isPost) {
            $model->load($requst->post());
            if ($model->validate()) {
                $auth = \Yii::$app->authManager;
                //添加权限
                //1.创建权限
                $permission = $auth->createPermission($model->name);
                $permission->description = $model->description;
                //2.保存到数据表
                $auth->add($permission);
                \Yii::$app->session->setFlash('success', '添加成功');

                return $this->redirect(['permission-index']);
            }
        }
        return $this->render('permission', ['model' => $model]);
    }

    public function actionEditPermission($name)
    {
        //根据name来查询数据
        //查询出来会多出type这个字段.
        $auth = \Yii::$app->authManager;
        $permission = $auth->getPermission($name);
        //var_dump($permission);exit();
        if ($permission == null) {
            throw new NotFoundHttpException('权限不存在');
        }
        $model = new PermissionForm();
        $model->name = $permission->name;
        $model->description = $permission->description;
        $requst = \Yii::$app->request;
        if ($requst->isPost) {
            $model->load($requst->post());
            if ($model->validate()) {
                $permission->name = $model->name;
                $permission->description = $model->description;
                $auth->update($name, $permission);
                \Yii::$app->session->setFlash('success', '权限修改成功');

                return $this->redirect(['permission-index']);
            }
        }
        return $this->render('permission', ['model' => $model]);
    }

    public function actionDelPermission($name)
    {
        $permission = \Yii::$app->authManager->getPermission($name);
        if ($permission == null) {
            throw new NotFoundHttpException('权限不存在');
        }
        \Yii::$app->authManager->remove($permission);
        \Yii::$app->session->setFlash('success', '权限删除成功');
        return $this->redirect(['permission-index']);
    }
    //添加角色
    //显示角色列表
    public function actionRoleIndex()
    {
        $auth = \Yii::$app->authManager;
        $roles = $auth->getRoles();
        return $this->render('role-index', ['roles' => $roles]);

    }
    public function actionAddRole()
    {
        $model = new RoleForm();
        $request = \Yii::$app->request;
        if ($request->isPost) {
            $model->load($request->post());
            if ($model->validate()) {
                //保存角色
                $auth = \Yii::$app->authManager;
                //添加角色
                // 创建新角色
                $role = $auth->createRole($model->name);
                $role->description = $model->description;
                // 保存到数据表
                $auth->add($role);
                //给角色分配权限
                \Yii::$app->session->setFlash('success', '添加成功');

                if ($model->permissions) {
                    foreach ($model->permissions as $permissionName) {
                        $permission = $auth->getPermission($permissionName);
                        $auth->addChild($role, $permission);//角色  权限
                        }
                }
                return $this->redirect(['role-index']);
            }
        }
        return $this->render('role',['model'=>$model]);
    }

    public function actionEditRole($name)
    {
        //根据name来查询数据
        //查询出来会多出type这个字段.
        $auth = \Yii::$app->authManager;
        $role = $auth->getRole($name);
        //var_dump($permission);exit();
        if ($role == null) {
            throw new NotFoundHttpException('角色不存在');
        }
        $model = new RoleForm();
        $model->name = $role->name;
        $model->description = $role->description;
        $requst = \Yii::$app->request;
        if ($requst->isPost) {
            $model->load($requst->post());
            if ($model->validate()) {
                $role->name = $model->name;
                $role->description = $model->description;
                $auth->update($name, $role);
                \Yii::$app->session->setFlash('success', '角色修改成功');

                return $this->redirect(['role-index']);
            }
        }
        return $this->render('role', ['model' => $model]);
    }
    public function actionDelRole($name)
    {
        $role = \Yii::$app->authManager->getRole($name);
        if ($role == null) {
            throw new NotFoundHttpException('角色不存在');
        }
        \Yii::$app->authManager->remove($role);
        \Yii::$app->session->setFlash('success', '角色删除成功');
        return $this->redirect(['role-index']);
    }

}
