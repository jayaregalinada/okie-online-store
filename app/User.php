<?php namespace Okie;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

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
	protected $hidden = [ 'password', 'remember_token' ];

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
	protected $appends = [ 'full_name' ];

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

	public function getFullNameAttribute()
	{
		return $this->attributes['first_name'] .' '. $this->attributes['last_name'];
	}

}
