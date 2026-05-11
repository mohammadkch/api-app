<?php

namespace App\Controllers\Admin;

class UserController extends BaseController
{

    public function index()
    {

    }

    public function create($task = null)
    {
        if ($task == 'handle') {
            return $this->formHandler('create', 0);
        }

        $this->viewData['form_action'] = isset($this->viewData['form_action']) ? $this->viewData['form_action'] : 'admin/' . $this->viewData['className'] . '/create/handle';
        $this->viewData['user_roles'] = [
            'admin'   => 'مدیر',
            'editor'  => 'ویرایشگر',
            'viewer'  => 'بازدیدکننده'
        ];

//        $this->flash('loading');

        return view($this->viewPath . 'user/create', $this->viewData);
    }

    public function edit( $id , $task = null)
    {
        $id = (int) $id ;

        if ( $task == 'handle' )
        {
            return $this->formHandler('edit', $id );
        }

        $userModel = model('UserModel') ;
        $edit_row = $userModel->find( $id );

        if ( $edit_row == null )
        {
            $this->flash('user_not_found');
            return redirect()->to( 'admin/' . $this->viewData['className'] );
        }

        $this->viewData['form_action'] = 'admin/' . $this->viewData['className'] .'/edit/'.$id.'/handle' ;
        $this->viewData['edit_row'] = $edit_row ;


        return $this->create();
    }

    public function formHandler($task, $id = 0)
    {
        if (!in_array($task, ['create', 'edit'])) {
            return redirect()->to('admin/' . $this->viewData['className']);
        }

        helper('sanitize');
        $validation = \Config\Services::validation();
        $userModel = model('UserModel');
        $fieldModel = model('FieldModel');


        $validation_rules = [
            'full_name' => 'required|min_length[3]',
            'username'  => 'required|min_length[3]',
            'password'  => 'min_length[6]',
            'confirm_password' => 'matches[password]',
            'role'      => 'required|in_list[admin,editor,viewer]'
        ];


        if ($task == 'edit') {
            $validation_rules['username'] = 'required|min_length[3]|is_unique[user.username,id,' . $id . ']';
        } else {
            $validation_rules['username'] = 'required|min_length[3]|is_unique[user.username]';
        }

        if ($task == 'create') {
            $validation_rules['password'] = 'required|min_length[6]';
            $validation_rules['confirm_password'] = 'required|matches[password]';
        }

        $rules = [];
        $field_label = $fieldModel->getFieldName(['user']);

        foreach ($validation_rules as $field_name => $validation_rule) {
            $rules[$field_name] = [
                'label' => array_key_exists($field_name, $field_label) ? $field_label[$field_name] : lang('FieldsText.' . $field_name, [], 'fa'),
                'rules' => $validation_rule
            ];
        }

        if (!$this->validate($rules)) {
            $this->viewData['validation_errors'] = $validation->getErrors();

            $this->flash('validation_error');

            if ($task == 'edit') {
                return $this->edit($id);
            } else {
                return $this->create();
            }
        }

        $full_name = $this->request->getPost('full_name', FILTER_DEFAULT);
        $username = $this->request->getPost('username', FILTER_DEFAULT);
        $password = $this->request->getPost('password', FILTER_CALLBACK, ['options' => 'sanitizeStripTags']);
        $role = $this->request->getPost('role', FILTER_DEFAULT);

        $model_data = [
            'full_name' => $full_name,
            'username'  => $username,
            'role'      => $role
        ];

        if (!empty($password)) {
            $model_data['password'] = password_hash($password, PASSWORD_DEFAULT);
        }

        if ($task == 'create') {
            $id = $userModel->insert($model_data);

            if ($id) {
                $this->flash('user_create_success');
                return redirect()->to('admin/' . $this->viewData['className'] . '/create');
            } else {
                $this->flash('user_create_error');
                return redirect()->to('admin/' . $this->viewData['className'] . '/create');
            }
        } elseif ($task == 'edit') {
            $update_result = $userModel->update($id, $model_data);

            if ($update_result) {
                $this->flash('user_update_success');
                return redirect()->to('admin/' . $this->viewData['className'] . '/edit/' . $id);
            } else {
                $this->flash('user_update_error');
                return redirect()->to('admin/' . $this->viewData['className'] . '/edit/' . $id);
            }
        } else {
            return redirect()->to('admin/' . $this->viewData['className']);
        }
    }

}