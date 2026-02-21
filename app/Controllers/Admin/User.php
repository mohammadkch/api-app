<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use ROWSET;

class User extends BaseController
{

    public function index()
    {

    }

    public function create($task = null)
    {

        if ( $task == 'handle' )
        {
            return $this->formHandler('create', 0 );
        }

        helper('rowset_helper');
        $userRoles = model('App\Models\UserRoleModel');

        $this->viewData['user_roles'] = ROWSET::toKeyValue($userRoles->getAll(), 'id', 'user_role_text');
        $this->viewData['form_action'] = isset( $this->viewData['form_action'] ) ? $this->viewData['form_action'] : 'admin/' . $this->viewData['className'] .'/create/handle' ;
        return view( $this->viewPath . 'user/create' , $this->viewData );

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
            return redirect()->to( 'admin/' . $this->viewData['className'] );
        }

        $this->viewData['form_action'] = 'admin/' . $this->viewData['className'] .'/edit/'.$id.'/handle' ;
        $this->viewData['edit_row'] = $edit_row ;


        return $this->create();
    }

    public function formHandler( $task, $id)
    {
        if ( ! in_array( $task, [ 'create','edit' ] ) )
        {
            return redirect()->to( 'admin/'.$this->viewData['className']  );
        }

        helper('sanitize');
        $validation = \Config\Services::validation();
        $userModel = model('UserModel') ;
        $fieldModel = model('FieldModel') ;

        $validation_rules = [
            'user_firstname'        => 'required|min_length[3]',
            'user_last_name'        => 'required|min_length[3]' ,
            'username'              => 'required|min_length[3]|is_unique[user.username]' ,
            'password'   => 'min_length[6]' ,
            'user_role_id' => 'is_natural_no_zero'
        ];

        if ( $task == 'edit' )
        {
        }

        $rules = [] ;
        $field_label = $fieldModel->getFieldName(['user']);



        foreach( $validation_rules as $field_name => $validation_rule )
        {
            $rules[$field_name] = [
                'label'	=> array_key_exists( $field_name , $field_label ) ? $field_label[$field_name] : lang( 'FieldsText.'.$field_name, [] ,'fa' ),
                'rules'	=> $validation_rule
            ];
        }

        if (!$this->validate($rules))
        {
            $this->viewData['validation_errors'] = $validation->getErrors();


            if ( $task == 'edit' )
                return $this->edit( $id );
            else
                return $this->create();
        }

        $user_firstname = $this->request->getPost( 'firstname' , FILTER_DEFAULT );
        $user_lastname = $this->request->getPost('lastname' , FILTER_DEFAULT );
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password' , FILTER_CALLBACK, ['options' => 'sanitizeStripTags'] );
        $user_role_id = $this->request->getPost('user_role_id' , FILTER_VALIDATE_INT);

        $model_data = [
            'user_firstname'   => $user_firstname,
            'user_lastname' => $user_lastname,
            'username' => $username,
            'password' => $password,
            'user_role_id' => $user_role_id
        ];


        if ( $task == 'create' ) {
            $id = $userModel->insert( $model_data );

            if ($id)
            {
                return redirect()->to( 'admin/' . $this->viewData['className'] .'/create?err=0');
            }
            else
            {
                return redirect()->to( 'admin/' . $this->viewData['className'] .'/create?err=1');
            }
        }
        elseif( $task == 'edit' ) {
            $update_result = $userModel->update( $id , $model_data );

            if ( $update_result ) {
                return redirect()->to( 'admin/'. $this->viewData['className'] .'/edit/'.$id.'?err=0');
            }
            else {
                return redirect()->to( 'admin/'. $this->viewData['className'] .'/edit/'.$id.'?err=1');
            }
        }
        else {
            return redirect()->to( 'admin/' . $this->viewData['className'] );
        }
    }
}