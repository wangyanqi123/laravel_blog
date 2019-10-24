
@extends('layouts.main')
@section('title', '王延琦')

@section('content')
	@include('index._article', ['list'=> $list])
@stop
