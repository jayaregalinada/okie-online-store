<?php namespace Okie;

use Illuminate\Database\Eloquent\Model;

class Newsletter extends Model {

	/**
	 * @type string
	 */
	protected $table = 'newsletters';

	/**
	 * @type array
	 */
	protected $fillable = [ 'email', 'user_id' ];

	/**
	 * @type array
	 */
	protected $casts = [
		'user_id' => 'integer'
	];

	/**
	 * @type array
	 */
	protected $hidden = [ 'created_at', 'updated_at' ];

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function user()
	{
		return $this->belongsTo( 'Okie\User', 'user_id' );
	}

}
