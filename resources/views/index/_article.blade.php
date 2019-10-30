<style type="text/css">
	#pull_right{
		text-align:center;
	}
	.pull-right {
		/*float: left!important;*/
	}
	.pagination {
		display: inline-block;
		padding-left: 0;
		margin: 20px 0;
		border-radius: 4px;
	}
	.pagination > li {
		display: inline;
	}
	.pagination > li > a,
	.pagination > li > span {
		position: relative;
		float: left;
		padding: 6px 12px;
		margin-left: -1px;
		line-height: 1.42857143;
		color: #428bca;
		text-decoration: none;
		background-color: #fff;
		border: 1px solid #ddd;
	}
	.pagination > li:first-child > a,
	.pagination > li:first-child > span {
		margin-left: 0;
		border-top-left-radius: 4px;
		border-bottom-left-radius: 4px;
	}
	.pagination > li:last-child > a,
	.pagination > li:last-child > span {
		border-top-right-radius: 4px;
		border-bottom-right-radius: 4px;
	}
	.pagination > li > a:hover,
	.pagination > li > span:hover,
	.pagination > li > a:focus,
	.pagination > li > span:focus {
		color: #2a6496;
		background-color: #eee;
		border-color: #ddd;
	}
	.pagination > .active > a,
	.pagination > .active > span,
	.pagination > .active > a:hover,
	.pagination > .active > span:hover,
	.pagination > .active > a:focus,
	.pagination > .active > span:focus {
		z-index: 2;
		color: #fff;
		cursor: default;
		background-color: #428bca;
		border-color: #428bca;
	}
	.pagination > .disabled > span,
	.pagination > .disabled > span:hover,
	.pagination > .disabled > span:focus,
	.pagination > .disabled > a,
	.pagination > .disabled > a:hover,
	.pagination > .disabled > a:focus {
		color: #777;
		cursor: not-allowed;
		background-color: #fff;
		border-color: #ddd;
	}
	.clear{
		clear: both;
	}
</style>
@foreach($list as $val)
<article>
	<header class="entry-header">
		<h1 class="entry-title"><a href="{{ $val->getLinkUrl() }}" title="{{ $val->title }}" rel="bookmark">{{ $val->title }}</a></h1>
	</header>

	@if($val->thumb)
		<div class="entry-content">
			<img src="{{ $val->thumb }}" alt="{{ $val->title }}" width="700">
		</div>
	@endif
	
	<div class="entry-content">{{ $val->description }}</div>
	
	@include('index._tag', ['val'=> $val])
</article>
@endforeach
<div id="pull_right">
	<div class="pull-right">
		{!! $list->render() !!}
	</div>
</div>