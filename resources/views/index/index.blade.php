
@extends('layouts.main')
@section('title', $cfg->title)

@section('content')
	@include('index._article', ['list'=> $list])
@stop
