<?php

namespace Motor\Backend\Transformers;

use League\Fractal;
use Motor\Backend\Models\User;
use Spatie\MediaLibrary\HasMedia\Interfaces\HasMedia;

class UserTransformer extends Fractal\TransformerAbstract
{

    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected $availableIncludes = [
        'client',
        'roles',
        'permissions',
        'files'
    ];


    public function transform(User $record)
    {
        return [
            'id'        => (int) $record->id,
            'client_id' => ( is_null($record->client_id) ? null : (int) $record->client_id ),
            'name'      => $record->name,
            'email'     => $record->email
        ];
    }


    /**
     * Include client
     *
     * @return \League\Fractal\Resource\Item
     */
    public function includeClient(User $record)
    {
        if ( ! is_null($record->client)) {
            return $this->item($record->client, new ClientTransformer());
        }
    }


    /**
     * Include permissions
     *
     * @return \League\Fractal\Resource\Collection
     */
    public function includePermissions(User $record)
    {
        if ( ! is_null($record->permissions)) {
            return $this->collection($record->permissions, new PermissionTransformer());
        }
    }


    /**
     * Include permissions
     *
     * @return \League\Fractal\Resource\Collection
     */
    public function includeRoles(User $record)
    {
        if ( ! is_null($record->roles)) {
            return $this->collection($record->roles, new RoleTransformer());
        }
    }


    /**
     * Include files
     *
     * @return \League\Fractal\Resource\Collection
     */
    public function includeFiles(HasMedia $record)
    {
        if (count($record->getMedia()) > 0) {
            return $this->collection($record->getMedia(), new MediaTransformer());
        }
    }
}