<h2 style="text-align: center">{{ $exception->getMessage() }}</h2>
@extends('errors::minimal')
@section('title', __('Not Found'))
@section('code', '404')
@section('message', __('Not Found'))
