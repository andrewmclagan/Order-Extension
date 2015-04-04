@extends('layouts/default')

{{-- Page title --}}
@section('title')
@parent
	{{{ trans('jiro/order::common.title') }}}
@stop 

{{-- Queue assets --}}
{{ Asset::queue('selectize', 'selectize/css/selectize.bootstrap3.css', 'styles') }}
{{ Asset::queue('bootstrap-daterange', 'bootstrap/css/daterangepicker-bs3.css', 'style') }}
{{ Asset::queue('redactor', 'redactor/css/redactor.css', 'styles') }}

{{ Asset::queue('slugify', 'platform/js/slugify.js', 'jquery') }}
{{ Asset::queue('moment', 'moment/js/moment.js', 'jquery') }}
{{ Asset::queue('bootstrap-daterange', 'bootstrap/js/daterangepicker.js', 'jquery') }}
{{ Asset::queue('validate', 'platform/js/validate.js', 'jquery') }}
{{ Asset::queue('selectize', 'selectize/js/selectize.js', 'jquery') }}
{{ Asset::queue('underscore', 'underscore/js/underscore.js', 'jquery') }}
{{ Asset::queue('redactor', 'redactor/js/redactor.min.js', 'jquery') }}
{{ Asset::queue('form', 'jiro/order::js/form.js', 'platform') }}

{{-- Inline styles --}}
@section('styles')
@parent
@stop

{{-- Inline scripts --}}
@section('scripts')
@parent
@stop

{{-- Page --}}
@section('page')
<section class="panel panel-default panel-tabs">

	{{-- Form --}}
	<form id="pages-form" action="{{ request()->fullUrl() }}" role="form" method="post" accept-char="UTF-8" autocomplete="off" data-parsley-validate>

		{{-- Form: CSRF Token --}}
		<input type="hidden" name="_token" value="{{ csrf_token() }}">

		<header class="panel-heading">

			<nav class="navbar navbar-default navbar-actions">

				<div class="container-fluid">

					<div class="navbar-header">
						<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#actions">
							<span class="sr-only">Toggle navigation</span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
						</button>

						<a class="btn btn-navbar-cancel navbar-btn pull-left tip" href="{{ route('admin.pages.all') }}" data-toggle="tooltip" data-original-title="{{{ trans('action.cancel') }}}">
							<i class="fa fa-reply"></i> <span class="visible-xs-inline">{{{ trans('action.cancel') }}}</span>
						</a>

						<span class="navbar-brand">{{{ trans("action.{$mode}") }}} <small>{{{ $order->exists ? $order->name : null }}}</small></span>
					</div>

					{{-- Form: Actions --}}
					<div class="collapse navbar-collapse" id="actions">

						<ul class="nav navbar-nav navbar-right">

							@if ($order->exists and $mode != 'copy')
							<li>
								<a href="{{ route('admin.pages.delete', $order->id) }}" class="tip" data-action-delete data-toggle="tooltip" data-original-title="{{{ trans('action.delete') }}}" type="delete">
									<i class="fa fa-trash-o"></i> <span class="visible-xs-inline">{{{ trans('action.delete') }}}</span>
								</a>
							</li>

							<li>
								<a href="{{ route('admin.pages.copy', $order->id) }}" data-toggle="tooltip" data-original-title="{{{ trans('action.copy') }}}">
									<i class="fa fa-copy"></i> <span class="visible-xs-inline">{{{ trans('action.copy') }}}</span>
								</a>
							</li>
							@endif

							<li>
								<button class="btn btn-primary navbar-btn" data-toggle="tooltip" data-original-title="{{{ trans('action.save') }}}">
									<i class="fa fa-save"></i> <span class="visible-xs-inline">{{{ trans('action.save') }}}</span>
								</button>
							</li>

						</ul>

					</div>

				</div>

			</nav>

		</header>

		<div class="panel-body">

			<div role="tabpanel">

				{{-- Form: Tabs --}}
				<ul class="nav nav-tabs" role="tablist">
					<li class="active" role="presentation">
						<a href="#general-tab" aria-controls="general" role="tab" data-toggle="tab">{{{ trans('jiro/order::common.tabs.general') }}}</a>
					</li>
					<li role="presentation">
						<a href="#tags-tab" aria-controls="tag" role="tabs-tab" data-toggle="tab">{{{ trans('jiro/order::common.tabs.tags') }}}</a>
					</li>
					<li role="presentation">
						<a href="#attributes-tab" aria-controls="attributes-tab" role="tab" data-toggle="tab">{{{ trans('jiro/order::common.tabs.attributes') }}}</a>
					</li>
				</ul>

				<div class="tab-content">

					{{-- Form: General --}}
					<div role="tabpanel" class="tab-pane fade in active" id="general-tab">

						<fieldset>

							<legend>{{{ trans('jiro/order::model.general.legend') }}}</legend>

							<div class="row">

								<div class="col-md-3">

									{{-- Created At --}}
									<div class="form-group{{ Alert::onForm('created_at', ' has-error') }}">

										<label for="created_at" class="control-label">
											{{{ trans('jiro/order::model.general.created_at') }}}
										</label>

										<p class="form-control-static">{{{ $order->created_at }}}</p>

									</div>

								</div>

								<div class="col-md-3">

									{{-- Updated At --}}
									<div class="form-group{{ Alert::onForm('updated_at', ' has-error') }}">

										<label for="updated_at" class="control-label">
											{{{ trans('jiro/order::model.general.updated_at') }}}
										</label>

										<p class="form-control-static">{{{ $order->updated_at }}}</p>

									</div>

								</div>								

							</div>							

							<div class="row">

								<div class="col-md-3">

									{{-- Name --}}
									<div class="form-group{{ Alert::onForm('name', ' has-error') }}">

										<label for="name" class="control-label">
											<i class="fa fa-info-circle" data-toggle="popover" data-content="{{{ trans('jiro/order::model.general.name_help') }}}"></i>
											{{{ trans('jiro/order::model.general.name') }}}
										</label>

										<input type="text" class="form-control" name="name" id="name" data-slugify="#slug" placeholder="{{{ trans('jiro/order::model.general.name') }}}" value="{{{ input()->old('name', $order->name) }}}" required autofocus data-parsley-trigger="change">

										<span class="help-block">{{{ Alert::onForm('name') }}}</span>

									</div>

								</div>						

								<div class="col-md-3">

									{{-- Slug --}}
									<div class="form-group{{ Alert::onForm('slug', ' has-error') }}">

										<label for="slug" class="control-label">
											<i class="fa fa-info-circle" data-toggle="popover" data-content="{{{ trans('jiro/order::model.general.slug_help') }}}"></i>
											{{{ trans('jiro/order::model.general.slug') }}}
										</label>

										<input type="text" class="form-control" name="slug" id="slug" placeholder="{{{ trans('jiro/order::model.general.slug') }}}" value="{{{ input()->old('slug', $order->slug) }}}" required data-parsley-trigger="change">

										<span class="help-block">{{{ Alert::onForm('slug') }}}</span>

									</div>

								</div>

								<div class="col-md-3">

									{{-- SKU --}}
									<div class="form-group{{ Alert::onForm('SKU', ' has-error') }}">

										<label for="SKU" class="control-label">
											<i class="fa fa-info-circle" data-toggle="popover" data-content="{{{ trans('jiro/order::model.general.SKU_help') }}}"></i>
											{{{ trans('jiro/order::model.general.SKU') }}}
										</label>

										<input type="text" class="form-control" name="SKU" id="SKU" placeholder="{{{ trans('jiro/order::model.general.SKU') }}}" value="{{{ input()->old('SKU', $order->SKU) }}}" required data-parsley-trigger="change">

										<span class="help-block">{{{ Alert::onForm('SKU') }}}</span>

									</div>

								</div>								

								<div class="col-md-3">

									{{-- Available On --}}
									<div class="form-group{{ Alert::onForm('available_on', ' has-error') }}">

										<label for="available_on" class="control-label">
											<i class="fa fa-info-circle" data-toggle="popover" data-content="{{{ trans('jiro/order::model.general.available_on_help') }}}"></i>
											{{{ trans('jiro/order::model.general.available_on') }}}
										</label>

										<input type="date" class="form-control" name="available_on" id="available_on" placeholder="" value="{{{ input()->old('available_on', $order->available_on) }}}" required data-parsley-trigger="change">

										<span class="help-block">{{{ Alert::onForm('available_on') }}}</span>

									</div>

								</div>

							</div>

							<div class="row">

								<div class="col-md-3">

									{{-- Price --}}
									<div class="form-group{{ Alert::onForm('price', ' has-error') }}">

										<label for="price" class="control-label">
											<i class="fa fa-info-circle" data-toggle="popover" data-content="{{{ trans('jiro/order::model.general.price_help') }}}"></i>
											{{{ trans('jiro/order::model.general.price') }}}
										</label>

										<div class="input-group">
    										<span class="input-group-addon">$</span>
    										<input name="price" id="price" step="any" type="number" class="form-control" value="{{{ input()->old('price', $order->price) }}}" placeholder="0.00" required autofocus data-parsley-trigger="change">
    									</div>										

										<span class="help-block">{{{ Alert::onForm('price') }}}</span>										
									</div>

								</div>	

								<div class="col-md-3">

									{{-- Sale Price --}}
									<div class="form-group{{ Alert::onForm('sale_price', ' has-error') }}">

										<label for="sale_price" class="control-label">
											<i class="fa fa-info-circle" data-toggle="popover" data-content="{{{ trans('jiro/order::model.general.sale_price_help') }}}"></i>
											{{{ trans('jiro/order::model.general.sale_price') }}}
										</label>

										<div class="input-group">
    										<span class="input-group-addon">$</span>
    										<input name="sale_price" id="sale_price" step="any" type="number" class="form-control" placeholder="0.00" required autofocus data-parsley-trigger="change">
    										<span class="input-group-btn">
            									<button class="btn btn-default" type="button" sale-price-calendar><i class="fa fa-calendar"></i></button>
          									</span>
    									</div>										

										<span class="help-block">{{{ Alert::onForm('sale_price') }}}</span>										
									</div>

								</div>									

							</div>

							<div class="row">

								<div class="col-md-12">

									{{-- Description --}}
									<div class="form-group{{ Alert::onForm('description', ' has-error') }}">

										<label for="description" class="control-label">
											<i class="fa fa-info-circle" data-toggle="popover" data-content="Please fill me {{{ trans('jiro/order::model.general.description_help') }}}"></i>
											{{{ trans('jiro/order::model.general.description') }}}
										</label>

										<textarea class="form-control redactor" name="description" id="description" rows="6" data-parsley-trigger="change">
											{{{ input()->old('description', $order->description) }}}
										</textarea>

										<span class="help-block">{{{ Alert::onForm('description') }}}</span>

									</div>									

								</div>

							</div>

							<div class="row">

								<div class="col-md-12">

									{{-- Short Description --}}
									<div class="form-group{{ Alert::onForm('short_description', ' has-error') }}">

										<label for="short_description" class="control-label">
											<i class="fa fa-info-circle" data-toggle="popover" data-content="Please fill me {{{ trans('jiro/order::model.general.short_description_help') }}}"></i>
											{{{ trans('jiro/order::model.general.short_description') }}}
										</label>

										<textarea class="form-control redactor" name="short_description" id="short_description" rows="3" data-parsley-trigger="change">
											{{{ input()->old('short_description', $order->short_description) }}}
										</textarea>

										<span class="help-block">{{{ Alert::onForm('short_description') }}}</span>

									</div>									

								</div>

							</div>	

							<div class="row">

								<div class="col-md-3">

									{{-- Status --}}
									<div class="form-group{{ Alert::onForm('enabled', ' has-error') }}">

										<label for="enabled" class="control-label">
											<i class="fa fa-info-circle" data-toggle="popover" data-content="{{{ trans('jiro/order::model.general.enabled_help') }}}"></i>
											{{{ trans('jiro/order::model.general.enabled') }}}
										</label>

										<select class="form-control" name="enabled" id="enabled" required data-parsley-trigger="change">
											<option value="1"{{ input()->old('enabled', $order->enabled) == 1 ? ' selected="selected"' : null }}>{{{ trans('common.enabled') }}}</option>
											<option value="0"{{ input()->old('enabled', $order->enabled) == 0 ? ' selected="selected"' : null }}>{{{ trans('common.disabled') }}}</option>
										</select>

										<span class="help-block">{{{ Alert::onForm('enabled') }}}</span>

									</div>	

								</div>	

								<div class="col-md-3">

									{{-- Stock --}}
									<div class="form-group{{ Alert::onForm('stock', ' has-error') }}">

										<label for="stock" class="control-label">
											<i class="fa fa-info-circle" data-toggle="popover" data-content="{{{ trans('jiro/order::model.general.stock_help') }}}"></i>
											{{{ trans('jiro/order::model.general.stock') }}}
										</label>

    									<input name="stock" id="stock" step="any" type="number" class="form-control" value="{{{ input()->old('stock', $order->stock) }}}" placeholder="0" required autofocus data-parsley-trigger="change">    					

										<span class="help-block">{{{ Alert::onForm('stock') }}}</span>										
									</div>

								</div>	

								<div class="col-md-3">

									{{-- Stock Status --}}
									<div class="form-group{{ Alert::onForm('in_stock', ' has-error') }}">

										<label for="in_stock" class="control-label">
											<i class="fa fa-info-circle" data-toggle="popover" data-content="{{{ trans('jiro/order::model.general.in_stock_help') }}}"></i>
											{{{ trans('jiro/order::model.general.in_stock') }}}
										</label>

										<select class="form-control" name="in_stock" id="in_stock" required data-parsley-trigger="change">
											<option value="1" {{ input()->old('in_stock', $order->in_stock) == 1 ? 'selected="selected"' : null }}>{{{ trans('jiro/order::model.general.stock_in') }}}</option>
											<option value="0" {{ input()->old('in_stock', $order->in_stock) == 0 ? 'selected="selected"' : null }}>{{{ trans('jiro/order::model.general.stock_out') }}}</option>
										</select>    									

										<span class="help-block">{{{ Alert::onForm('in_stock') }}}</span>										
									</div>

								</div>																						

							</div>						

						</fieldset>

					</div>

				{{-- Form: Attributes --}}
				<div role="tabpanel" class="tab-pane fade" id="attributes-tab">

					@attributes($order)

				</div>

			</div>

		</div>

	</div>

</form>

</section>
@stop
