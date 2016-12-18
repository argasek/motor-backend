@extends('motor-backend::layouts.backend')

@section('htmlheader_title')
    {{ trans('motor-backend::backend/global.home') }}
@endsection

@section('contentheader_title')
    {{ trans('motor-backend::backend/categories.edit') }}
    {!! link_to_route('backend.categories.index', trans('motor-backend::backend/global.back'), ['category' => $root->id], ['class' => 'pull-right btn btn-sm btn-danger']) !!}
@endsection

@section('main-content')
	@include('motor-backend::errors.list')
	@include('motor-backend::backend.categories.form')
@endsection