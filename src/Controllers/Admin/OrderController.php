<?php namespace Jiro\Extension\Order\Controllers\Admin;

use Platform\Access\Controllers\AdminController;
use Jiro\Order\Database\OrderRepositoryInterface;

class OrderController extends AdminController 
{
	/**
	 * The Orders repository.
	 *
	 * @var \Jiro\Order\Database\OrderRepositoryInterface
	 */
	protected $orders;

	/**
	 * Holds all the mass actions we can execute.
	 *
	 * @var array
	 */
	protected $actions = [
		'delete',
		'enable',
		'disable',
	];

	/**
	 * Constructor.
	 *
	 * @param  \Jiro\Order\Database\OrderRepositoryInterface  $orders
	 * @return void
	 */
	public function __construct(OrderRepositoryInterface $orders)
	{
		parent::__construct();

		$this->orders = $orders;
	}

	/**
	 * Display a listing of orders.
	 *
	 * @return \Illuminate\View\View
	 */
	public function index()
	{
		return view('jiro/order::index');
	}

	/**
	 * Datasource for the orders Data Grid.
	 *
	 * @return \Cartalyst\DataGrid\DataGrid
	 */
	public function grid()
	{
		$columns = [
			'id',
			'number',
			'state',
			'payment_state',
			'created_at',
		];

		$settings = [
			'sort'      => 'created_at',
			'direction' => 'desc',
			'pdf_view'  => 'pdf',
		];

		$transformer = function($element)
		{
			$element->edit_uri = route('admin.jiro.order.edit', $element->id);

			return $element;
		};

		return datagrid($this->orders->grid(), $columns, $settings, $transformer);
	}

	/**
	 * Show the form for creating a new order.
	 *
	 * @return \Illuminate\Http\RedirectResponse|\Illuminate\View\View
	 */
	public function create()
	{
		return $this->showForm('create');
	}

	/**
	 * Handle posting of the form for creating a new order.
	 *
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function store()
	{ 
		return $this->processForm('create');
	}

	/**
	 * Show the form for updating a page.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\RedirectResponse|\Illuminate\View\View
	 */
	public function edit($id)
	{
		return $this->showForm('update', $id);
	}

	/**
	 * Handle posting of the form for updating a page.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function update($id)
	{
		return $this->processForm('update', $id);
	}

	/**
	 * Show the form for copying a order.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\RedirectResponse|\Illuminate\View\View
	 */
	public function copy($id)
	{
		return $this->showForm('copy', $id);
	}

	/**
	 * Remove the specified order.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function delete($id)
	{
		$type = $this->orders->delete($id) ? 'success' : 'error';

		$this->alerts->{$type}(
			trans("jiro/order::message.{$type}.delete")
		);

		return redirect()->route('admin.order.all');
	}

	/**
	 * Executes the mass action.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function executeAction()
	{
		$action = request()->input('action');

		if (in_array($action, $this->actions))
		{
			foreach (request()->input('rows', []) as $row)
			{
				$this->orders->{$action}($row);
			}

			return response('Success');
		}

		return response('Failed', 500);
	}

	/**
	 * Shows the form.
	 *
	 * @param  string  $mode
	 * @param  int  $id
	 * @return \Illuminate\Http\RedirectResponse|\Illuminate\View\View
	 */
	protected function showForm($mode, $id = null)
	{
		if ( ! $data = $this->orders->getPreparedOrder($id))
		{
			$this->alerts->error(trans('jiro/order::message.not_found', compact('id')));

			return redirect()->toAdmin('orders');
		}

		$order = $data['order'];

		return view('jiro/order::form', compact(
			'order', 'mode'
		));
	}

	/**
	 * Processes the form.
	 *
	 * @param  string  $mode
	 * @param  int  $id
	 * @return \Illuminate\Http\RedirectResponse
	 */
	protected function processForm($mode, $id = null)
	{
		// Store the order
		list($messages) = $this->orders->store($id, request()->all());

		// Do we have any errors?
		if ($messages->isEmpty())
		{
			$this->alerts->success(trans("jiro/order::message.success.{$mode}"));

			return redirect()->route('admin.jiro.order.all');
		}

		$this->alerts->error($messages, 'form');

		return redirect()->back()->withInput();
	}

}
