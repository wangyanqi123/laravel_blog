<?php

/**
 * 文章模型
 * User: zfs
 * Date: 2019/8/17
 * Time: 22:34
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Handlers\Level;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class Article extends Model
{
    protected static $cache_key = 'lara:article';

    protected static $expire_at = 20;

	protected $fillable = [
		'title', 'content', 'user_id' ,'category_id', 'keyword', 'description', 'thumb', 'status', 'views'
	];
	
	//所属分类
	public function category()
	{
		return $this->belongsTo(Category::class);
	}
	
	//所属用户
	public function member()
	{
		return $this->belongsTo(Member::class);
	}
	
	//文章评论
	public function comments()
	{
		return $this->hasMany(Comment::class);
	}
	
	//文章链接
	public function getLinkUrl()
	{
		return route('article', $this->id);
	}

	//时间查询链接
	public function getTimeUrl()
	{
		return route('time', $this->created_at->toDateString());
	}

	//最新文章
	public static function getRecent($limit = 10)
	{
		return self::where('status', 1)->orderBy('id', 'desc')->limit($limit)->get();
	}
	
	//热门文章
	public static function getHot($limit = 10)
	{
        $list = Cache::remember(self::$cache_key, self::$expire_at, function ($limit = 10){
            return self::where('status', 1)->orderBy('views', 'desc')->limit($limit)->get();
        });
		return $list;
	}

	//归档
	public static function getFile()
	{
		$files = DB::table('articles')->select(DB::raw('count(*) as num, substring(created_at, 1, 7) as pub_date'))->groupBy('pub_date')->orderBy('pub_date', 'desc')->get();
		return $files;
	}

	//上一篇
	public function getPrev()
	{
		$category_ids = $this->getChildArr($this->category_id);
		$article = self::where('status', 1)->where('id','<',$this->id)->whereIn('category_id', $category_ids)->orderBy('id','desc')->first();

		if($article){
			return '<a href="'.$article->getLinkUrl().'">'.e($article->title).'</a>';
		}

		return '没有了';
	}

	//下一篇
	public function getNext()
	{
		$category_ids = $this->getChildArr($this->category_id);
		$article = self::where('status', 1)->where('id','>',$this->id)->whereIn('category_id', $category_ids)->orderBy('id','asc')->first();

		if($article){
			return '<a href="'.$article->getLinkUrl().'">'.e($article->title).'</a>';
		}

		return '没有了';
	}

	//文章标签
	public function getTags()
	{
		if($this->keyword){
			return explode(',', $this->keyword);
		}

		return [];
	}

	//文章搜索
	public static function getSearch($request)
	{
		$tag = $request->tag;
		$time = $request->time;
		$keyword = $request->keyword;

		$map = [
			'status' => 1,
		];

		$search = '';

		if($tag){
			$map[] = ['keyword', 'like', "%$tag%"];
			$search = $tag;
		}
		if($keyword){
			$map[] = ['title', 'like', "%$keyword%"];
			$search = $keyword;
		}
		if($time){
			$map[] = ['created_at', 'like', "$time%"];
			$search = $time;
		}

		$list = self::where($map)->orderBy('id', 'desc')->paginate(10);

		return ['list'=> $list, 'search'=> $search];
	}
	
	//获取子级分类id数组
	public function getChildArr($category_id)
	{
		static $childs_id_arr = [];

		if(empty($childs_id_arr)){
			$categorys = Category::all();
			$level = new Level;
			$childs_id_arr = $level->formatChild($categorys, $category_id);
		}

		return $childs_id_arr;
	}
}
