<?php

function create_test_user($count = 1)
{
    return factory(Motor\Backend\Models\User::class, $count)->create();
}

function create_test_superadmin()
{
    return create_test_user_with_roles([ 'SuperAdmin' ]);
}

function create_test_user_with_roles($roles)
{
    $user = create_test_user();
    foreach ($roles as $role) {
        $role = create_test_role_with_name($role);
        $user->assignRole($role);
    }

    return $user;
}

function create_test_user_with_permissions($permissions)
{
    $user = create_test_user();
    foreach ($permissions as $permission) {
        $permission = create_test_permission_with_name($permission);
        assign_test_permission($user, $permission);
    }

    return $user;
}

function create_test_user_with_roles_and_permissions($roles, $permissions)
{
    $user = create_test_user();
    foreach ($roles as $role) {
        $role = create_test_role_with_name($role);
        assign_test_role($user, $role);
    }

    return $user;
}

function assign_test_role($user, $role)
{
    $user->assignRole($role);
}

function assign_test_permission($user, $permission)
{
    $user->givePermissionTo($permission);
}

function create_test_permission_with_name($permission)
{
    return factory(Motor\Backend\Models\Permission::class)->create([ 'name' => $permission ]);
}

function create_test_role_with_name($role)
{
    return factory(Motor\Backend\Models\Role::class)->create([ 'name' => $role ]);
}

function create_test_permission($count = 1)
{
    return factory(Motor\Backend\Models\Permission::class, $count)->create();
}

function create_test_permission_group($count = 1)
{
    return factory(Motor\Backend\Models\PermissionGroup::class, $count)->create();
}

function create_test_role($count = 1)
{
    return factory(Motor\Backend\Models\Role::class, $count)->create();
}

function create_test_client($count = 1)
{
    return factory(Motor\Backend\Models\Client::class, $count)->create();
}

function create_test_language($count = 1)
{
    return factory(Motor\Backend\Models\Language::class, $count)->create();
}

function create_test_email_template($count = 1)
{
    return $email_template = factory(Motor\Backend\Models\EmailTemplate::class, $count)->create();

}