<?php namespace Jiro\Extension\Order\Controllers\Frontend;

use Platform\Foundation\Controllers\Controller;

class OrderController extends Controller {

	/**
	 * Return the main view.
	 *
	 * @return \Illuminate\View\View
	 */
	public function index()
	{
		return view('jiro/order::index');
	}

}
