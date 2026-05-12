<?php

namespace App\Controllers\Admin;

class UserController extends BaseController
{

    public function index()
    {
        helper('sanitize');
        helper('rowset');
        $pager = service('pager');
        $userModel = model('UserModel');
        $cityModel = model('CityModel');

        $page = (int) $this->request->getGet('page', FILTER_VALIDATE_INT);
        $page = $page > 0 ? $page : 1;

        $username = $this->request->getPost('username', FILTER_CALLBACK, ['options' => 'sanitizeStripTags']);
        $full_name = $this->request->getPost('full_name', FILTER_CALLBACK, ['options' => 'sanitizeStripTags']);
        $role = $this->request->getPost('role', FILTER_CALLBACK, ['options' => 'sanitizeStripTags']);
        $city_id = $this->request->getPost('city_id', FILTER_VALIDATE_INT);

        $condition = [];

        if (strlen($username) > 0)      $condition['username'] = $username;
        if (strlen($full_name) > 0)     $condition['full_name'] = $full_name;
        if (strlen($role) > 0)          $condition['role'] = $role;
        if ($city_id > 0)               $condition['city_id'] = $city_id;

        $per_page = 5;
        $total_rows = $userModel->getData($condition, null, 0, true);
        $rowset = $userModel->getData($condition, $per_page, ($page - 1) * $per_page);
        $pagination = $pager->makeLinks($page, $per_page, $total_rows, 'admin_pagination');

        if ($total_rows == 0 && !empty($condition)) {
            $this->flash('no_result');
        }

        $this->viewData['search_fields'] = [
            'username' => [
                'input' => 'form_input',
                'data' => ['class' => 'form-control search-input', 'placeholder' => 'نام کاربری'],
                'type' => 'text'
            ],
            'full_name' => [
                'input' => 'form_input',
                'data' => ['class' => 'form-control search-input', 'placeholder' => 'نام کامل'],
                'type' => 'text'
            ],
            'role' => [
                'input' => 'form_dropdown',
                'data' => ['class' => 'form-control search-input search-input-dropdown'],
                'options' => [
                    '' => 'همه نقش‌ها',
                    'admin' => 'مدیر',
                    'editor' => 'ویرایشگر',
                    'viewer' => 'بازدیدکننده'
                ]
            ],
            'city_id' => [
                'input' => 'form_dropdown',
                'data' => ['class' => 'form-control search-input search-input-dropdown'],
                'options' => ['' => 'همه شهرها'] + \ROWSET::toKeyValue($cityModel->findAll(), 'city_id', 'city_name')
            ]
        ];

        $this->viewData['pagination'] = $pagination;
        $this->viewData['rowset'] = $rowset;
        $this->viewData['edit_pk'] = $userModel->primaryKey;

        if ($this->request->isAJAX()) {
            return view($this->viewPath . 'user/index_data_table', $this->viewData);
        }

        return view($this->viewPath . 'user/index', $this->viewData);
    }

    public function create($task = null)
    {
        helper('fields');

        if ($task == 'handle') {
            return $this->formHandler('create', 0);
        }

        $fieldModel = model('FieldModel');
        $stateModel = model('StateModel');
        $cityModel = model('CityModel');

        $fields_name = $fieldModel->getFieldName(['user', 'city', 'state']);

        $state_rowset = $stateModel->findAll();
        $city_rowset = $cityModel->findAll();

        $state_options = ['' => 'انتخاب کنید'];
        foreach ($state_rowset as $row) {
            $state_options[$row['state_id']] = $row['state_name'];
        }

        $city_options = ['' => 'انتخاب کنید'];
        foreach ($city_rowset as $row) {
            $city_options[$row['city_id']] = $row['city_name'];
        }

        $this->viewData['inputs'] = [
            'username' => [
                'input' => 'form_input',
                'data' => ['placeholder' => $fields_name['username'] ?? 'نام کاربری', 'class' => 'form-control', 'id' => 'username', 'name' => 'username'],
                'type' => 'text'
            ],
            'full_name' => [
                'input' => 'form_input',
                'data' => ['placeholder' => $fields_name['full_name'] ?? 'نام کامل', 'class' => 'form-control', 'id' => 'full_name', 'name' => 'full_name'],
                'type' => 'text'
            ],
            'password' => [
                'input' => 'form_input',
                'data' => ['placeholder' => $fields_name['password'] ?? 'گذرواژه', 'class' => 'form-control', 'id' => 'password', 'name' => 'password'],
                'type' => 'password'
            ],
            'confirm_password' => [
                'input' => 'form_input',
                'data' => ['placeholder' => 'تکرار گذرواژه', 'class' => 'form-control', 'id' => 'confirm_password', 'name' => 'confirm_password'],
                'type' => 'password'
            ],
            'role' => [
                'input' => 'form_dropdown',
                'data' => ['class' => 'form-control js-role-select', 'id' => 'role', 'name' => 'role'],
                'options' => [
                    '' => 'انتخاب نقش',
                    'admin' => 'مدیر',
                    'editor' => 'ویرایشگر',
                    'viewer' => 'بازدیدکننده'
                ]
            ],
            'state_id' => [
                'input' => 'form_dropdown',
                'data' => [
                    'class' => 'form-control js-state-select', // اضافه شد
                    'id' => 'state_id',
                    'name' => 'state_id',
                    'placeholder' => 'انتخاب استان',
                    'onchange' => "updateCityOptions()"
                ],
                'options' => $state_options
            ],
            'city_id' => [
                'input' => 'form_dropdown',
                'data' => ['class' => 'form-control js-city-select', 'id' => 'city_id', 'name' => 'city_id', 'placeholder' => 'انتخاب شهر'],
                'options' => $city_options
            ],
            'avatar' => [
                'input' => 'form_upload',
                'data' => ['accept' => 'image/*', 'class' => 'form-control', 'id' => 'avatar', 'name' => 'avatar'],
                'type' => 'file'
            ]
        ];

        $this->viewData['fields_name'] = mergeFieldsName($fields_name, $this->viewData['inputs']);
        $this->viewData['form_action'] = $this->viewData['form_action'] ?? $this->viewPath . $this->viewData['className'] . '/create/handle';

        return view($this->viewPath . 'user/create', $this->viewData);
    }

    public function edit($id, $task = null)
    {
        $id = (int) $id;

        if ($task == 'handle') {
            return $this->formHandler('edit', $id);
        }

        $userModel = model('UserModel');
        $edit_row = $userModel->getUser(['id' => $id]);

        if ($edit_row == null) {
            $this->flash('user_not_found');
            return redirect()->to('admin/' . $this->viewData['className']);
        }
        $edit_row['confirm_password'] = $edit_row['password'] ?? '';

        $this->viewData['form_action'] = $this->viewPath . $this->viewData['className'] . '/edit/' . $id . '/handle';
        $this->viewData['edit_row'] = $edit_row;

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
            'role'      => 'required|in_list[admin,editor,viewer]',
            'city_id'   => 'required|is_natural_no_zero'
        ];

        $avatar = $this->request->getFile('avatar');
        if ($avatar && $avatar->isValid() && !$avatar->hasMoved()) {
            $validation_rules['avatar'] = 'is_image[avatar]|max_size[avatar,2048]|ext_in[avatar,jpg,jpeg,png,gif]';
        }

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
                'label' => array_key_exists($field_name, $field_label) ? $field_label[$field_name] : lang('Fields.' . $field_name, [], 'fa'),
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
        $city_id = $this->request->getPost('city_id', FILTER_VALIDATE_INT);

        $model_data = [
            'full_name' => $full_name,
            'username'  => $username,
            'role'      => $role,
            'city_id'   => $city_id
        ];

        if (!empty($password)) {
            $model_data['password'] = $password;
        }


        if ($avatar && $avatar->isValid() && !$avatar->hasMoved()) {

            $uploadPath = FCPATH . 'uploads/avatars/';

            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0777, true);

                if (!file_exists($uploadPath . 'index.html')) {
                    file_put_contents($uploadPath . 'index.html', '<!DOCTYPE html><html><head><title>403 Forbidden</title></head><body><h1>Access Denied</h1></body></html>');
                }
            }

            if ($task == 'edit') {
                $existingUser = $userModel->find($id);
                if ($existingUser && !empty($existingUser['avatar'])) {
                    $oldFile = FCPATH . $existingUser['avatar'];
                    if (file_exists($oldFile)) {
                        unlink($oldFile);
                    }
                }
            }

            // ذخیره فایل جدید
            $newName = $avatar->getRandomName();
            $avatar->move($uploadPath, $newName);
            $model_data['avatar'] = 'uploads/avatars/' . $newName;

        } elseif ($task == 'edit') {
            // در ویرایش، اگه عکس جدید نیومد، قبلی رو نگه دار
            $existingUser = $userModel->find($id);
            if ($existingUser && isset($existingUser['avatar'])) {
                $model_data['avatar'] = $existingUser['avatar'];
            }
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

    public function updateCityOptions()
    {
        $state_id = $this->request->getPost('state_id');
        $selected_city_id = $this->request->getPost('selected_city_id') ?? 0;

        $cityModel = model('CityModel');
        $cities = $cityModel->where('state_id', $state_id)->findAll();


        return view($this->viewPath . 'user/_city_dropdown', [
            'cities' => $cities,
            'selected_city_id' => $selected_city_id
        ]);
    }

    public function delete($id)
    {
        $userModel = model('UserModel');
        $user = $userModel->find($id);

        if (!$user) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'کاربر یافت نشد'
            ]);
        }

        if (!empty($user['avatar'])) {
            $avatarPath = WRITEPATH . '../public/' . $user['avatar'];
            if (file_exists($avatarPath)) {
                unlink($avatarPath);
            }
        }

        if ($userModel->delete($id)) {
            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'کاربر با موفقیت حذف شد'
            ]);
        } else {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'خطا در حذف کاربر'
            ]);
        }
    }
}