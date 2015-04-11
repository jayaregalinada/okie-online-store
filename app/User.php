<?php namespace Okie;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

/**
 * Okie\User
 *
 * @property integer $id 
 * @property string $first_name 
 * @property string $last_name 
 * @property string $email 
 * @property string $avatar 
 * @property string $provider 
 * @property string $provider_id 
 * @property string $password 
 * @property boolean $verified 
 * @property string $gender 
 * @property string $link 
 * @property string $remember_token 
 * @property \Carbon\Carbon $created_at 
 * @property \Carbon\Carbon $updated_at 
 * @property integer $permission 
 * @property-read \Illuminate\Database\Eloquent\Collection|\Okie\Message[] $messages 
 * @property-read \Illuminate\Database\Eloquent\Collection|\Okie\Product[] $products 
 * @property-read mixed $full_name 
 * @property-read mixed $is_permitted 
 * @method static \Illuminate\Database\Query\Builder|\Okie\User whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\Okie\User whereFirstName($value)
 * @method static \Illuminate\Database\Query\Builder|\Okie\User whereLastName($value)
 * @method static \Illuminate\Database\Query\Builder|\Okie\User whereEmail($value)
 * @method static \Illuminate\Database\Query\Builder|\Okie\User whereAvatar($value)
 * @method static \Illuminate\Database\Query\Builder|\Okie\User whereProvider($value)
 * @method static \Illuminate\Database\Query\Builder|\Okie\User whereProviderId($value)
 * @method static \Illuminate\Database\Query\Builder|\Okie\User wherePassword($value)
 * @method static \Illuminate\Database\Query\Builder|\Okie\User whereVerified($value)
 * @method static \Illuminate\Database\Query\Builder|\Okie\User whereGender($value)
 * @method static \Illuminate\Database\Query\Builder|\Okie\User whereLink($value)
 * @method static \Illuminate\Database\Query\Builder|\Okie\User whereRememberToken($value)
 * @method static \Illuminate\Database\Query\Builder|\Okie\User whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Okie\User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Okie\User wherePermission($value)
 */
class User extends Model implements AuthenticatableContract, CanResetPasswordContract {

	use Authenticatable, CanResetPassword;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'users';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [ 'permission', 'email', 'password', 'avatar', 'provider', 'provider_id', 'gender', 'link', 'first_name', 'last_name' ];

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = [ 'password', 'remember_token', 'created_at', 'updated_at', 'provider', 'provider_id' ];

	/**
	 * Cast attributes for returning data with type in HHVM
	 *
	 * @type array
	 */
	protected $casts = [
		'id' => 'integer',
		'permission' => 'integer',
		'verified' => 'boolean'
	];

	/**
	 * @type array
	 */
	protected $appends = [ 'full_name', 'is_permitted', 'facebook_id' ];

	/**
	 * This are all available permissions
	 *
	 * @type array
	 */
	protected $permissions = [ 'admin', 'user', 'moderator' ];

	/**
	 * Messages relation
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function messages()
	{
		return $this->hasMany( 'Okie\Message' );
	}

	/**
	 * Products relation
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function products()
	{
		return $this->hasMany( 'Okie\Product' );
	}

	/**
	 * Check if user is admin
	 *
	 * @return boolean
	 */
	public function isAdmin()
	{
		if( $this->permission != array_search( 'admin', $this->permissions ) )
			return false;
		
		return true;
	}

	public function isModerator()
	{
		if( $this->permission != array_search( 'moderator', $this->permissions ) )
			return false;

		return true;
	}

	/**
	 * Check if user is just a normal user
	 *
	 * @return boolean
	 */
	public function isUser()
	{
		if( $this->permission != array_search( 'user', $this->permissions ) )
			return false;

		return true;
	}

	/**
	 * @return boolean
	 */
	public function isPermitted()
	{
		if( $this->permission != array_search( 'user', $this->permissions ) )
			return true;

		return false;
	}

	/**
	 * Get user's full name
	 *
	 * @param  boolean $arrangement Arrangement if last name will be the first
	 * @param  string  $separator
	 *
	 * @return string
	 */
	public function getFullName( $arrangement = false, $separator = ', ' )
	{
		if( $arrangement )
			return $this->attributes['last_name'] . $separator . $this->attributes['first_name'];

		return $this->attributes['first_name'] .' '. $this->attributes['last_name'];
	}

	/**
	 * @return string
	 */
	public function getFullNameAttribute()
	{
		return $this->attributes['first_name'] .' '. $this->attributes['last_name'];
	}

	/**
	 * @return bool
	 */
	public function getIsPermittedAttribute()
	{
		return $this->isPermitted();
	}

	/**
	 * @return string
	 */
	public function getFacebookIdAttribute()
	{
		return $this->provider_id;
	}

}
