<?php
class Page {
	/**
	 * 返回分页数组
	 * @author nicky
	 * @since 2014-04-12
	 * @param array $params
	 * @return object
	 */
	public function createPage( $params )
	{
		$pager				=	'<ul class="pagination">';
		$maxButtonCount		=	5;	//显示的按钮个数，
		do{
			try {
				$url		=	isset( $params['url'] ) ? $params['url'] : '';
				$size		=	isset( $params['size'] ) ? $params['size'] : '';
				$page		=	isset( $params['page'] ) ? $params['page'] : 1;
				$pageSize	=	isset( $params['pageSize'] ) ? $params['pageSize'] : PAGESIZE;
				$md			=	isset( $params['maodian'] ) ? $params['maodian'] : false;
				$pageTotal	=	ceil( $size / $pageSize);
				
				$maodian	=	'';//锚点
				if(false !== $md){
					$maodian	=	'#'.$md;
				}
				if( !strpos( $url ,  '?' ) ){
					$url	.=	'?c=2';
				}
				if( 0==$size ){
					break;
				}
				$pager		.=	'<li ><a href="'.$url.'&page=1'.$maodian.'">首页</a></li>';
				if( $maxButtonCount >= $pageTotal  ){
					for( $i=1;$i<=$pageTotal;$i++ ){
						if( $page==$i ){
							$pager	.=	'<li class="active"><a href="'.$url.'&page='.$i.$maodian.'">'.$i.'</a></li>';
						}else{
							$pager	.=	'<li><a href="'.$url.'&page='.$i.$maodian.'">'.$i.'</a></li>';
						}
					}
				}else{
					if( 1 != $page ){
						$pager	.=	'<li ><a href="'.$url.'&page='. ( $page-1 ) .$maodian.'">上一页</a></li>';
					}
					if( $page + $maxButtonCount > $pageTotal ){
						$frist	=	$pageTotal - $maxButtonCount +1;
						for ( $i=$frist ;$i<=$pageTotal;$i++ )
						{
							if( $page==$i ){
								$pager	.=	'<li class="active"><a href="'.$url.'&page='.$i.$maodian.'">'.$i.'</a></li>';
							}else{
								$pager	.=	'<li><a href="'.$url.'&page='.$i.$maodian.'">'.$i.'</a></li>';
							}
						}
					}else{
						for( $i=$page ; $i<$page+$maxButtonCount ; $i++ ){
							if( $page==$i ){
								$pager	.=	'<li class="active"><a href="'.$url.'&page='.$i.$maodian.'">'.$i.'</a></li>';
							}else{
								$pager	.=	'<li><a href="'.$url.'&page='.$i.$maodian.'">'.$i.'</a></li>';
							}
						}
					}
					if( $pageTotal != $page ){
						$pager	.=	'<li ><a href="'.$url.'&page='. ($page+1) .$maodian.'">下一页</a></li>';
					}
				}
				$pager	.=	'<li ><a href="'.$url.'&page='.$pageTotal.$maodian.'">末页</a></li>';
			} catch (Exception $e) {
			}
		}while(0);
    	$pager	.=	'</ul>';
    	return $pager;
	}
	/**
	 *分页方法
	 * @param int $count 总数量
	 * @param int $page 当前页数
	 * @param int $page_count 每页的行数
	 * @return int page 处理后的页数
	 * @return int query_count	查询的数目
	 */
	public function pageCut($count,$page,$page_count){
		if($count <= 0){
			$data['page'] = 0;
			$data['query_count'] = 0;
			return $data;
		}
		if(empty($page) || $page <= 0 ){
			$page = 1;
		}
		$page_max = ceil($count / $page_count) ;
		if($page > $page_max){
			$page = $page_max;
		}
		$last = $page * $page_count;
		if($last > $count){
			$last = $count;
		}
		$first = ($page - 1) * $page_count;
		$data['page'] = $page;
		$data['query_count'] = $first;
		return $data;
	}
}

?>