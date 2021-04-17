<?php

namespace Motor\Backend\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Motor\Backend\Database\Factories\UserFactory;
use Motor\Backend\Notifications\ResetPassword;
use Illuminate\Notifications\Notifiable;
use Motor\Core\Traits\Searchable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Motor\Core\Traits\Filterable;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Permission\Traits\HasRoles;
use Spatie\MediaLibrary\InteractsWithMedia;
/**
 * Motor\Backend\Models\User
 *
 * @property int
 *               $id
 * @property int|null
 *               $client_id
 * @property string
 *               $name
 * @property string
 *               $email
 * @property string
 *               $password
 * @property string
 *               $api_token
 * @property string|null
 *               $remember_token
 * @property string|null
 *               $password_last_changed_at
 * @property \Illuminate\Support\Carbon|null
 *               $created_at
 * @property \Illuminate\Support\Carbon|null
 *               $updated_at
 * @property-read \Motor\Backend\Models\Client|null
 *                    $client
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\MediaLibrary\Models\Media[]
 *                    $media
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[]
 *                $notifications
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Permission\Models\Permission[]
 *                    $permissions
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Permission\Models\Role[]
 *                    $roles
 * @method static \Illuminate\Database\Eloquent\Builder|\Motor\Backend\Models\User filteredBy(\Motor\Core\Filter\Filter $filter, $column )
 * @method static \Illuminate\Database\Eloquent\Builder|\Motor\Backend\Models\User filteredByMultiple(\Motor\Core\Filter\Filter $filter )
 * @method static \Illuminate\Database\Eloquent\Builder|\Motor\Backend\Models\User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Motor\Backend\Models\User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Motor\Backend\Models\User permission( $permissions )
 * @method static \Illuminate\Database\Eloquent\Builder|\Motor\Backend\Models\User query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Motor\Backend\Models\User role( $roles, $guard = null )
 * @method static \Illuminate\Database\Eloquent\Builder|\Motor\Backend\Models\User search( $q, $full_text = false )
 * @method static \Illuminate\Database\Eloquent\Builder|\Motor\Backend\Models\User whereApiToken( $value )
 * @method static \Illuminate\Database\Eloquent\Builder|\Motor\Backend\Models\User whereClientId( $value )
 * @method static \Illuminate\Database\Eloquent\Builder|\Motor\Backend\Models\User whereCreatedAt( $value )
 * @method static \Illuminate\Database\Eloquent\Builder|\Motor\Backend\Models\User whereEmail( $value )
 * @method static \Illuminate\Database\Eloquent\Builder|\Motor\Backend\Models\User whereId( $value )
 * @method static \Illuminate\Database\Eloquent\Builder|\Motor\Backend\Models\User whereName( $value )
 * @method static \Illuminate\Database\Eloquent\Builder|\Motor\Backend\Models\User wherePassword( $value )
 * @method static \Illuminate\Database\Eloquent\Builder|\Motor\Backend\Models\User wherePasswordLastChangedAt( $value )
 * @method static \Illuminate\Database\Eloquent\Builder|\Motor\Backend\Models\User whereRememberToken( $value )
 * @method static \Illuminate\Database\Eloquent\Builder|\Motor\Backend\Models\User whereUpdatedAt( $value )
 * @mixin \Eloquent
 */
class User extends Authenticatable implements HasMedia
{
    use Searchable;
    use HasRoles;
    use Filterable;
    use Notifiable;
    use InteractsWithMedia;
    use HasFactory;

    protected $guard_name = 'web';

	protected static function newFactory()
	{
		return UserFactory::new();
	}

	/**
     * Send a password reset email to the user
     *
     * @param string $token
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPassword($token, $this));
    }


    /**
     * @param Media|null $media
     */
    /**
     * @param Media|null $media
     */
    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')->width(400)->height(400);
        $this->addMediaConversion('preview')->width(400)->height(400);
    }


    /**
     * Searchable columns for the searchable trait
     *
     * @var array
     */
    protected $searchableColumns = [ 'name', 'email' ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'client_id',
        'name',
        'email',
        'password',
        'api_token'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function client()
    {
        return $this->belongsTo(config('motor-backend.models.client'));
    }
}
