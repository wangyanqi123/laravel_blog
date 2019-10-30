<?php

//创建继承自 Illuminate\Pagination\BootstrapThreePresenter 类，这里我把类放在了Controllers下面，需要修改BootstrapThreePresenter 类的哪些方法就重写哪个方法。如果觉得默认的bootstrap样式和你项目的样式不符，可以自定义样式。
namespace App\Http\Controllers;
use Illuminate\Contracts\Pagination\Paginator as PaginatorContract;
use Illuminate\Contracts\Pagination\Presenter as PresenterContract;

class ShmilyThreePresenter extends \Illuminate\Pagination\BootstrapThreePresenter
{
    /**
     * Convert the URL window into Bootstrap HTML.
     *
     * @return string
     */
    public function render()
    {
        if ($this->hasPages()) {
            return sprintf(
                '<ul class="am-pagination">%s %s %s %s %s</ul>',//自定义class样式
                $this->firstPage(),//添加首页方法
                $this->getPreviousButton('上一页'),
                $this->getLinks(),
                $this->getNextButton('下一页'),
                $this->last()//添加尾页方法
            );
        }

        return '';
    }

    /**
     * Get HTML wrapper for an available page link.
     *
     * @param string $url
     * @param int $page
     * @param string|null $rel
     * @return string
     */
    protected function getAvailablePageWrapper($url, $page, $rel = null)
    {
        $rel = is_null($rel) ? '' : ' rel="'.$rel.'"';

        return '<li><a href="'.htmlentities($url).'" rel="external nofollow" '.$rel.'>'.$page.'</a></li>';
        //这里li标签可以添加你自己的class样式
    }

    /**
     * Get HTML wrapper for disabled text.
     *
     * @param string $text
     * @return string
     */
    protected function getDisabledTextWrapper($text)
    {
        return '<li class="disabled"><span>'.$text.'</span></li>';
    }

    /**
     * Get HTML wrapper for active text.
     *
     * @param string $text
     * @return string
     */
    protected function getActivePageWrapper($text)
    {
        return '<li class="active"><span>'.$text.'</span></li>';
    }


    /**
     * Get the next page pagination element.
     *
     * @param string $text
     * @return string
     */
    //新建首页方法
    public function firstPage($text = '首页')
    {
        // If the current page is greater than or equal to the last page, it means we
        // can't go any further into the pages, as we're already on this last page
        // that is available, so we will make it the "next" link style disabled.
        if ($this->paginator->currentPage() <= 1) {
            return $this->getDisabledTextWrapper($text);
        }
        $url = $this->paginator->url(1);

        return $this->getPageLinkWrapper($url, $text, 'first');
    }

    /**
     * Get the next page pagination element.
     *
     * @param string $text
     * @return string
     */
    //新建尾页方法
    public function last($text = '尾页')
    {
        // If the current page is greater than or equal to the last page, it means we
        // can't go any further into the pages, as we're already on this last page
        // that is available, so we will make it the "next" link style disabled.

        $url = $this->paginator->url($this->paginator->lastPage());

        return $this->getPageLinkWrapper($url, $text, 'last');
    }

}