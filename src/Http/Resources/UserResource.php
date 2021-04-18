<?php

namespace Motor\Backend\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *   schema="UserResource",
 *   @OA\Property(
 *     property="id",
 *     type="string",
 *     example="My beautiful user name"
 *   ),
 *   @OA\Property(
 *     property="client",
 *     type="object",
 *     ref="#/components/schemas/ClientResource"
 *   ),
 *   @OA\Property(
 *     property="roles",
 *     type="array",
 *     @OA\Items(
 *       ref="#/components/schemas/RoleResource"
 *     ),
 *   ),
 *   @OA\Property(
 *     property="permissions",
 *     type="array",
 *     @OA\Items(
 *       ref="#/components/schemas/PermissionResource"
 *     ),
 *   ),
 *   @OA\Property(
 *     property="name",
 *     type="string",
 *     example="My beautiful user name"
 *   ),
 *   @OA\Property(
 *     property="email",
 *     type="string",
 *     example="user@domain.com"
 *   )
 * )
 */

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'          => (int) $this->id,
            'client'      => (!is_null($this->client_id) ? new ClientResource($this->client) : null),
            'roles'       => RoleResource::collection($this->roles),
            'permissions' => PermissionResource::collection($this->permissions),
            'name'        => $this->name,
            'email'       => $this->email,
        ];
    }
}
