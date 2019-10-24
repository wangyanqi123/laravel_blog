
@extends('layouts.main')
@section('title', '王延琦技术博客')

@section('content')
	@include('index._article', ['list'=> $list])
@stop
