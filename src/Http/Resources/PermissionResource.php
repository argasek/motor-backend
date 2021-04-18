<?php

namespace Motor\Backend\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *   schema="PermissionResource",
 *   @OA\Property(
 *     property="id",
 *     type="integer",
 *     example="1"
 *   ),
 *   @OA\Property(
 *     property="name",
 *     type="string",
 *     example="dashboard.read"
 *   ),
 *   @OA\Property(
 *     property="guard_name",
 *     type="string",
 *     example="web"
 *   ),
 *   @OA\Property(
 *     property="permission_group",
 *     type="string",
 *     example="administration"
 *   )
 * )
 */
class PermissionResource extends JsonResource
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
            'id'               => (int) $this->id,
            'name'             => $this->name,
            'guard_name'       => $this->guard_name,
            'permission_group' => (! is_null($this->group) ? $this->group->name : null),
        ];
    }
}