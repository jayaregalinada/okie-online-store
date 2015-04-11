<?php namespace Okie\Http\Requests;

use Okie\Newsletter;
use Okie\Http\Requests\Request;
use Okie\Exceptions\NewsletterException;

class NewsletterRequest extends Request {

	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize()
	{
		if( Newsletter::whereEmail( $this->get( 'email' ) )->exists() )
			return false;

		return true;
	}

	/**
	 * @return \Illuminate\Http\Response|void
	 * @throws \Okie\Exceptions\NewsletterException
	 */
	public function forbiddenResponse()
	{
		throw new NewsletterException( $this->get( 'email' ), 'Email already exists' );
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		return [
			'email' => 'required|email|unique:newsletters,email'
		];
	}

}
