<?php namespace Okie\Http\Controllers;

use Okie\Http\Requests;
use Okie\Http\Controllers\Controller;
use View;
use Illuminate\Http\Request;

class ViewHandlerController extends Controller {

	/**
	 * @return \Illuminate\View\View
	 */
	public function getSearchBoxView()
	{
		return view( 'public.searchbox' );
	}

	public function getIndexPublicView( $view )
	{
		switch ( $view ) {
			case 'searchbox':
				return view( 'public.searchbox' );
			break;

			case 'index':
				return view( 'index' );
			break;

			case 'lightbox':
				return view( 'public.item_lightbox' );
			break;

			default:
				if( View::exists( 'public.a_' . $view ) )
					return view( 'public.a_' . $view );
				else
					return abort( 404, 'Not found' );
			break;
		}
	}

	public function getPublicView( $type, $view )
	{
		switch ( $type )
		{
			case 'items':
				return $this->getItemPublicView( $view );
			break;

			case 'messages':
				return $this->getMessagesPublicView( $view );
			break;

			case 'products':
				return $this->getProductsPublicView( $view );
			break;

			case 'product':
				return $this->getProductPublicView( $view );
			break;

			case 'settings':
				return $this->getSettingsPublicView( $view );
			break;

			case 'reviews':
				return $this->getReviewPublicView( $view );
			break;

			case 'views':
				return $this->getViewsPublicView( $view );
			break;
			
			default:
				# code...
			break;
		}
	}

	public function getViewsPublicView( $view )
	{
		switch ( $view )
		{
			case 'lightbox':
				return view( 'public.item_lightbox' );
			break;
			
			default:
				
			break;
		}
	}

	private function getMessagesPublicView( $view )
	{
		switch ( $view ) {
			case 'inquiries':
				return view( 'messages.a_messages_inquiries' );
			break;

			case 'inbox':
				return view( 'messages.a_messages_inbox' );
			break;

			case 'deliver':
				return view( 'messages.a_messages_delivered' );
			break;

			default:
				if( View::exists( 'messages.a_' . $view ) )
					return view( 'messages.a_' . $view );
				else
					return abort( 404, 'Not found' );
			break;
		}
	}

	private function getItemPublicView( $view )
	{
		switch ( $view )
		{
			case 'index':
				return view( 'public.items' );
			break;
			
			case 'item':
				return view( 'public.item' );
			break;

			default:
				return abort( 404, 'Not found' );
			break;
		}
	}

	private function getProductsPublicView( $view )
	{
		switch ( $view )
		{
			default:
				if( View::exists( 'product.a_' . $view ) )
					return view( 'product.a_' . $view );
				else
					return abort( 404, 'Not Found' );
			break;
		}
	}

	private function getProductPublicView( $view )
	{
		switch ( $view ) {
			case 'lightbox':
				return view( 'product.template_lightbox' );
			break;
			
			default:
				if( View::exists( 'product.a_' . $view ) )
					return view( 'product.a_' . $view );
				else 
					return abort( 404, 'Not Found' );
			break;
		}
	}

	private function getSettingsPublicView( $view )
	{
		switch ( $view )
		{
			case 'newsletter':
				return view( 'settings.a_newsletter' );
			break;
			
			default:
				if( View::exists( 'settings.a_' . $view ) )
					return view( 'settings.a_' . $view );
				else
					return abort( 404, 'Not Found' );
			break;
		}
	}

	private function getReviewPublicView( $view )
	{
		switch ( $view )
		{
			default:
				if( View::exists( 'reviews.a_' . $view ) )
					return view( 'reviews.a_' . $view );
				else
					return abort( 404, 'Not Found' );
			break;
		}
	}

	public function getMessagesView( $view )
	{
		switch ( $view )
		{
			case 'lightbox-receipt':
				return view( 'messages.lightbox-receipt' );
			break;

			default:
				if( View::exists( 'messages.a_' . $view ) )
					return view( 'messages.a_' . $view );
				else
					return abort( 404, 'Not Found' );
			break;
		}
	}

}
