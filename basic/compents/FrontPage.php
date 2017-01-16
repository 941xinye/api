<?php

namespace app\compents;

use yii\helpers\Html;
use yii\widgets\LinkPager;

class FrontPage extends LinkPager{

    public function init(){
        parent::init();
        $this->options['class'] = "floot";
        $this->prevPageCssClass = "left";
        $this->nextPageCssClass = "right";
        $this->nextPageLabel = "";
        $this->prevPageLabel = "";
        $this->maxButtonCount = 6;
        $this->activePageCssClass = "bgc_27c543";
    }

    public function renderPageButtons(){
        $pageCount = $this->pagination->getPageCount();
        if ($pageCount < 2 && $this->hideOnSinglePage) {
            return '';
        }

        $buttons = [];
        $currentPage = $this->pagination->getPage();

        // first page
        $firstPageLabel = $this->firstPageLabel === true ? '1' : $this->firstPageLabel;
        if ($firstPageLabel !== false) {
            $buttons[] = $this->renderPageButton($firstPageLabel, 0, $this->firstPageCssClass, $currentPage <= 0, false);
        }

        // prev page
        if ($this->prevPageLabel !== false) {
            if (($page = $currentPage - 1) < 0) {
                $page = 0;
            }
            $buttons[] = $this->renderPageButton($this->prevPageLabel, $page, $this->prevPageCssClass, $currentPage <= 0, false);
        }

        // internal pages
        list($beginPage, $endPage) = $this->getPageRange();
        for ($i = $beginPage; $i <= $endPage; ++$i) {
            $buttons[] = $this->renderPageButton($i + 1, $i, "btn1", false, $i == $currentPage);
        }

        // next page
        if ($this->nextPageLabel !== false) {
            if (($page = $currentPage + 1) >= $pageCount - 1) {
                $page = $pageCount - 1;
            }
            $buttons[] = $this->renderPageButton($this->nextPageLabel, $page, $this->nextPageCssClass, $currentPage >= $pageCount - 1, false);
        }

        // last page
        $lastPageLabel = $this->lastPageLabel === true ? $pageCount : $this->lastPageLabel;
        if ($lastPageLabel !== false) {
            $buttons[] = $this->renderPageButton($lastPageLabel, $pageCount - 1, $this->lastPageCssClass, $currentPage >= $pageCount - 1, false);
        }

        return Html::tag('div', implode("\n", $buttons), $this->options);
    }


    public function renderPageButton($label, $page, $class, $disabled, $active)
    {
        $options = ['class' => empty($class) ? $this->pageCssClass : $class];
        if ($active) {
            Html::addCssClass($options, $this->activePageCssClass);
        }
        $linkOptions = $this->linkOptions;
        $linkOptions['data-page'] = $page;

        if ($disabled) {
            Html::addCssClass($options, $this->disabledPageCssClass);

            return Html::a($label, $this->pagination->createUrl($page), $options);
        }

        //return Html::tag('div', Html::a($label, $this->pagination->createUrl($page), $linkOptions), $options);
        return Html::a($label, $this->pagination->createUrl($page), $options);
    }

}