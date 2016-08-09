<?php

class SubPages {

    private $pageSize; //每页显示的条目数  
    private $itemCount; //总条目数  
    private $currentPage; //当前被选中的页  
    private $pages; //每次显示的页数  
    private $pageCount; //总页数  
    private $pageArray = array(); //用来构造分页的数组  
    private $pageLink; //每个分页的链接  
    private $linkType; //显示分页的类型  

    /**
     * __construct是SubPages的构造函数，用来在创建类的时候自动运行.
     * @param $pageSize		每页显示的条目数
     * @param $itemCount	总条目数
     * @param $currentPage	当前被选中的页
     * @param $pages       	每次显示的页数
     * @param $pageLink		每个分页的链接
     * @param $linkType		显示分页的类型:1为普通分页模式example：   共4523条记录,每页显示10条,当前第1/453页 [首页] [上页] [下页] [尾页];
     * 2为经典分页样式example：   当前第1/453页 [首页] [上页] 1 2 3 4 5 6 7 8 9 10 [下页] [尾页]
     */

    function __construct($pageSize, $itemCount, $currentPage, $pages, $pageLink, $linkType) {
        $this->pageSize = intval($pageSize);
        $this->itemCount = intval($itemCount);
        if (!$currentPage) {
            $this->currentPage = 1;
        } else {
            $this->currentPage = intval($currentPage);
        }
        $this->pages = intval($pages);
        $this->pageCount = ceil($itemCount / $pageSize);
        $this->pageLink = $pageLink;
        $this->_createURL();
        $this->linkType = $linkType;
    }
    
    private function _createURL(){
        $queryString = $_SERVER['QUERY_STRING'];
        $subUrl = $_SERVER['REQUEST_URI'];
        $subUrl = substr($subUrl, 0,strlen($queryString)-strlen($queryString));
        if(strpos($queryString, $this->pageLink)!==false ){
            $queryArr = explode('&', $queryString);
            array_pop($queryArr);
            $queryString = join('&', $queryArr);
        }
        $this->pageLink = '?'.$subUrl.$queryString.$this->pageLink;
    }

   
	
    public function createPage(){
    	return $this->showSubPages( $this->linkType );
    }
    
    /**
     * showSubPages函数用在构造函数里面。而且用来判断显示什么样子的分页
     */

    private function showSubPages($linkType) {
        if ($linkType == 1) {
            return $this->pageStyle1();
        } elseif ($linkType == 2) {
            return $this->pageStyle2();
        }
    }

    /**
     *  用来给建立分页的数组初始化的函数。
     */

    function initArray() {
        for ($i = 0; $i < $this->pages; $i++) {
            $this->pageArray[$i] = $i;
        }
        return $this->pageArray;
    }

    /**
     * 该函数使用来构造显示的条目
     * 即：[1][2][3][4][5][6][7][8][9][10]
     */

    function createLinkPage() {
        if ($this->pageCount < $this->pages) {
            $current_array = array();
            for ($i = 0; $i < $this->pageCount; $i++) {
                $current_array[$i] = $i + 1;
            }
        } else {
            $current_array = $this->initArray();
            if ($this->currentPage <= 3) {
                for ($i = 0; $i < count($current_array); $i++) {
                    $current_array[$i] = $i + 1;
                }
            } elseif ($this->currentPage <= $this->pageCount && $this->currentPage > $this->pageCount - $this->sub_pages + 1) {
                for ($i = 0; $i < count($current_array); $i++) {
                    $current_array[$i] = ($this->pageCount) - ($this->sub_pages) + 1 + $i;
                }
            } else {
                for ($i = 0; $i < count($current_array); $i++) {
                    $current_array[$i] = $this->currentPage - 2 + $i;
                }
            }
        }

        return $current_array;
    }

    /**
     *	构造普通模式的分页
     *	共4523条记录,每页显示10条,当前第1/453页 [首页] [上页] [下页] [尾页]
     */

    function pageStyle1() {
        $pageStyle1Str = "";
        $pageStyle1Str.="共".$this->itemCount."条记录，";
        $pageStyle1Str.="每页显示".$this->pageSize."条，";
        $pageStyle1Str.="当前第".$this->currentPage."/".$this->pageCount."页 ";
        if ($this->currentPage > 1) {
            $firstPageUrl = $this->pageLink."1";
            $prewPageUrl = $this->pageLink.($this->currentPage - 1);
            $pageStyle1Str.="[<a href='$firstPageUrl'>首页</a>] ";
            $pageStyle1Str.="[<a href='$prewPageUrl'>上一页</a>] ";
        } else {
            $pageStyle1Str.="[首页] ";
            $pageStyle1Str.="[上一页] ";
        }

        if ($this->currentPage < $this->pageCount) {
            $lastPageUrl = $this->pageLink.$this->pageCount;
            $nextPageUrl = $this->pageLink.($this->currentPage + 1);
            $pageStyle1Str.=" [<a href='$nextPageUrl'>下一页</a>] ";
            $pageStyle1Str.="[<a href='$lastPageUrl'>尾页</a>] ";
        } else {
            $pageStyle1Str.="[下一页] ";
            $pageStyle1Str.="[尾页] ";
        }

        echo  $pageStyle1Str;
    }

    /**
     * 构造经典模式的分页
     * 当前第1/453页 [首页] [上页] 1 2 3 4 5 6 7 8 9 10 [下页] [尾页]
     */

    function pageStyle2() {
        $pageStyle2Str = "<div class='pagination'><ul>";
        $pageStyle2Str.="<li><a href='#' class='disabled'>当前第".$this->currentPage."/".$this->pageCount."页 </a></li>";
        if ($this->currentPage > 1) {
            $firstPageUrl = $this->pageLink."1";
            $prewPageUrl = $this->pageLink.($this->currentPage - 1);
            $pageStyle2Str.="<li><a href='$firstPageUrl'>首页</a></li>";
            $pageStyle2Str.="<li><a href='$prewPageUrl'>上一页</a></li>";
        } else {
            $pageStyle2Str.="<li class='disabled'><a href='#'>首页</a></li>";
            $pageStyle2Str.="<li class='disabled'><a href='#'>上一页</a></li>";
        }

        $a = $this->createLinkPage();
        for ($i = 0; $i < count($a); $i++) {
            $s = $a[$i];
            if ($s == $this->currentPage) {
                $pageStyle2Str.="<li class='active'><a href='#'>".$s."</a></li>";
            } else {
                $url = $this->pageLink.$s;
                $pageStyle2Str.="<li><a href='$url'>$s</a></li>";
            }
        }

        if ($this->currentPage < $this->pageCount) {
            $lastPageUrl = $this->pageLink.$this->pageCount;
            $nextPageUrl = $this->pageLink.($this->currentPage + 1);
            $pageStyle2Str.=" <li><a href='$nextPageUrl'>下一页</a><li>";
            $pageStyle2Str.="<li><a href='$lastPageUrl'>尾页</a></li>";
        } else {
            $pageStyle2Str.="<li class='disabled'><a href='#'>下一页</a></li>";
            $pageStyle2Str.="<li class='disabled'><a href='#'>尾页</a></li> ";
        }
        return  $pageStyle2Str."</ul></div>";
    }
    
    /**
     * __destruct析构函数，当类不在使用的时候调用，该函数用来释放资源。
     */
    function __destruct() {
    	unset($pageSize);
    	unset($itemCount);
    	unset($currentPage);
    	unset($pages);
    	unset($pageCount);
    	unset($pageArray);
    	unset($pageLink);
    	unset($linkType);
    }

}
