<?php

namespace App\Admin\Controllers;

use App\Model\UserModel;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class UserController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '用户管理系统';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new UserModel());

        $grid->column('id', __('ID'));
        $grid->column('com_name', __('公司名称'));
        $grid->column('com_legal', __('法人'));
        $grid->column('com_address', __('公司地址'));
        $grid->column('com_mobile', __('联系电话'));
        $grid->column('com_email', __('邮箱'));
        $grid->column('created_at', __('Created at'));
        //$grid->column('updated_at', __('Updated at'));

        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(UserModel::findOrFail($id));

        $show->field('id', __('ID'));
        $show->field('com_name', __('公司名称'));
        $show->field('com_legal', __('法人'));
        $show->field('com_address', __('公司地址'));
        $show->field('com_logo', __('营业执照照片'));
        $show->field('com_mobile', __('手机号'));
        $show->field('com_email', __('邮箱'));
        $show->field('com_password', __('密码'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new UserModel());

        $form->text('com_name', __('公司名称'));
        $form->text('com_legal', __('法人'));
        $form->text('com_address', __('公司地址'));
        $form->text('com_logo', __('营业执照照片'));
        $form->text('com_mobile', __('手机号'));
        $form->text('com_email', __('邮箱'));
        $form->text('com_password', __('密码'));

        return $form;
    }
}
