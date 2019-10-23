
@extends('layouts.main')
@section('title', $post->title)
{{--.' - '.$post->category->name--}}
@section('content')
	<style>
		pre{
			white-space:pre-wrap; /* css3.0 */
			white-space:-moz-pre-wrap; /* Firefox */
			white-space:-pre-wrap; /* Opera 4-6 */
			white-space:-o-pre-wrap; /* Opera 7 */
			word-wrap:break-word; /* Internet Explorer 5.5+ */
		}
	</style>

	<article>
		<header class="entry-header">
			<h1 class="entry-title"><a href="{{ $post->getLinkUrl() }}" title="{{ $post->title }}" rel="bookmark">{{ $post->title }}</a></h1>
		</header>

		<div class="entry-content ql-editor">{!! $post->content !!}</div>
		
		@include('index._tag', ['val'=> $post])
	</article>

	<nav class="nav-single">
		<div class="prev">上一篇：{!! $post->getPrev() !!}</div>
		<div class="prev">下一篇：{!! $post->getNext() !!}</div>
	</nav>

	<div id="comments" class="comments-area">
		@if(!$comments->count())
			<h2 class="comments-title">《<span>{{ $post->title }}</span>》上暂无评论!</h2>
		@else
			<h2 class="comments-title">《<span>{{ $post->title }}</span>》上有&nbsp;{{ $comments->count() }}&nbsp;条评论!</h2>
			<ol class="commentlist" id="commentWraper">
				@foreach($comments as $val)
				<li class="comment even thread-even depth-0" id="li-comment-6">
					<article id="comment-6" class="comment">
						<header class="comment-meta comment-author vcard"><img src="{{ $val->member->avatar }}" class="photo" height="44" width="44"/><cite class="fn">{{ $val->member->username }} </cite><time datetime="">{{ $val->created_at->diffForHumans() }}</time></header>
						<section class="comment-content comment" style="margin-bottom:10px;line-height:25px;">{{ $val->content }}</section>
					</article>
				</li>
				@endforeach
			</ol>
		@endif
		
		<div id="respond">
			<h3 id="reply-title">发表评论 @if(!Auth::check()) <input type="submit" value="登录" onclick="location.href = '{{ route("login") }}';">@endif</h3>
			@if(Auth::check())
			<form action="{{ route('comment', $post->id) }}" method="post" id="commentform">
				{{ csrf_field() }}
				<p class="comment-form-comment">
					<textarea id="body" name="content" cols="45" rows="4" aria-required="true">{{ old('content') or ''}}</textarea>
				</p>
				<p class="form-submit">
					<input type="submit" id="submit" value="发表评论" />
				</p>
			</form>
			@endif
		</div>
	</div>
@stop