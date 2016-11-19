<?php

namespace Motor\Backend\Forms\Backend;

use App\Models\Client;
use Motor\Backend\Models\Role;
use Kris\LaravelFormBuilder\Form;
use Motor\Backend\Models\User;

class UserForm extends Form
{
    public function buildForm()
    {
        $this
            ->add('client_id', 'select', ['label' => trans('backend/clients.client'), 'choices' => Client::lists('name', 'id')->toArray(), 'empty_value' => trans('backend/global.all')])
            ->add('name', 'text', ['label' => trans('backend/users.name'), 'rules' => 'required'])
            ->add('email', 'text', ['label' => trans('backend/users.email'), 'rules' => 'required'])
            ->add('password', 'password', ['value' => '', 'label' => trans('backend/users.password')])
            ->add('avatar', 'file_image', ['label' =>  trans('backend/global.image'), 'model' => User::class])
            ->add('roles', 'checkboxcollection', [
                'type' => 'checkbox',
                'label' => trans('backend/roles.roles'),
                'property' => 'id',    // Which property to use on the tags model for value, defualts to id
                'collection' => Role::lists('id', 'name')->toArray(),
                'data' => null, //Permission::lists('name', 'id')->toArray(),            // Data is automatically bound from model, here we can override it
                'options' => [    // these are options for a single type
                    'label' => false,
                    'attr' => ['class' => 'role']
                ]
            ])
            ->add('submit', 'submit', ['attr' => ['class' => 'btn btn-primary'], 'label' => trans('backend/users.save')]);
    }
}
