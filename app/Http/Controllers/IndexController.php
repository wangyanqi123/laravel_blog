<?php

/**
 * 首页控制器
 * User: zfs
 * Date: 2019/8/17
 * Time: 22:34
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\CommentRequest;
use App\Models\Article;
use App\Models\Category;
use App\Models\Comment;
use App\Handlers\Level;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;
use App\Common\Tools\MarkDowner;

class IndexController extends BaseController
{
    //首页
    public function index()
    {
        ///Redis::set('name', 'guwenjie');
        //$values = Redis::get('article_1');
        //echo $values;
        $list = Article::getRecent();
        return view('index.index', ['list' => $list]);
    }

    //列表
    public function category(Category $category, Level $level)
    {
        $categorys     = Category::all();
        $childs_id_arr = $level->formatChild($categorys, $category->id);
        $list          = Article::where('status', 1)->whereIn('category_id', $childs_id_arr)->orderBy('id', 'desc')->paginate(10);

        return view('index.category', ['list' => $list, 'category' => $category]);
    }

    //详情
    public function article(Article $article)
    {
        $markdown         = new MarkDowner; //实例化
        $article->content = $markdown->convertMarkdownToHtml($article->content); //markdown转换html
        Article::where('id', $article->id)->increment('views');
        return view('index.article', ['post' => $article, 'comments' => $article->comments()->with('member')->where('status', 1)->get()]);
    }

    /*public function article($id)
    {
        /*echo "mysql开始时间<br/>";
        echo(microtime());
        echo "<br/>";
        echo "<img src='http://www.wangyanqi.cc/bg.jpg'>";
        echo "mysql结束时间<br/>";
        echo(microtime());
        exit;*/
    /*echo "mysql开始时间<br/>";
    echo(microtime());
    echo "<br/>";
    $article = Article::where('id', $id)->first();
    echo $article->content;
    echo "<br/>";
    echo "mysql结束时间<br/>";
    echo(microtime());
    echo "redis开始时间<br/>";
    echo(microtime());
    echo "<br/>";
    $redis = Redis::get('article_9');
    print_r(json_decode($redis,true)['content']);
    echo "<br/>";
    echo "rerdis结束时间<br/>";
    echo(microtime());exit;
}*/

    //评论
    public function comment(Post $post, CommentRequest $request)
    {
        $user = Auth::user();
        //防刷评论
        $cache_key = $user->id;

        if (Cache::get($cache_key)) {
            return redirect()->back()->with('danger', '评论的间隔时间太短');
        }

        Comment::create([
            'user_id' => $user->id,
            'post_id' => $post->id,
            'content' => $request->content,
            'at_id'   => 0,
            'ip'      => $request->getClientIp(),
            'read'    => 0,
            'status'  => 1
        ]);
        Cache::put($cache_key, 'short', 1);

        return redirect()->back()->with('success', '评论成功');
    }

    //搜索
    public function search(Request $request)
    {
        $data = Article::getSearch($request);
        return view('index.search', $data);
    }

    public function submit()
    {
        $arr = Article::all();
        $urls = [];
        $urls[] = 'http://www.wangyanqi.cc';
        foreach ($arr as $key => $value)
        {
            $id = $value->id;
            $urls[] = 'http://www.wangyanqi.cc/article/'.$id;
        }
        /*$urls    = array(
            '11',
            'http://www.wangyanqi.cc/article/6',
        );*/
        $api     = 'http://data.zz.baidu.com/urls?site=www.wangyanqi.cc&token=n1MZ4b6510afqDNl';
        $ch      = curl_init();
        $options = array(
            CURLOPT_URL            => $api,
            CURLOPT_POST           => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POSTFIELDS     => implode("\n", $urls),
            CURLOPT_HTTPHEADER     => array('Content-Type: text/plain'),
        );
        curl_setopt_array($ch, $options);
        $result = curl_exec($ch);
        echo $result;
    }
}
