@extends('motor-backend::layouts.backend')

@section('htmlheader_title')
    {{ trans('motor-backend::backend.global.home') }}
@endsection

@section('contentheader_title')
    {{ trans('motor-backend::backend/global.dashboard') }}
@endsection

@section('main-content')
    Useful information goes here!
@endsection
