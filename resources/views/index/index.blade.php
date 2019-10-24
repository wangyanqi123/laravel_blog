
@extends('layouts.main')
@section('title', '')

@section('content')
	@include('index._article', ['list'=> $list])
@stop
