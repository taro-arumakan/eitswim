<?php
/**
 * @param $page 0,1,2,3,4
 * @param $total_count 総件数
 * $max_count_one = param('max_count_one') ページ当たりの件数 20
 * $max_link_page = param('max_link_page') １ページに表示するリンク数 10
 * $total_page = ceil($total_count / $max_count_one)
 * $start_page : 0-9 => 0, 10-19 => 10, 20-29 => 20
 * $end_page : $start_page + $max_link_page - 1 || < $total_page
 */
?>
<div class="dataTables_paginate paging_bootstrap_number" style="text-align: center;">
    <ul class="pagination" style="visibility: visible;">
        <?php
        $prev = 'prev';
        $prev_page = $page - 1;
        if($page<1) {
            $prev_page = 0;
            $prev = 'prev disabled';
        }
        $max_count_one = param('max_count_one');
        $max_link_page = param('max_link_page');
        $total_page = ceil($total_count / $max_count_one);

        $start_page = 0;
        $end_page = $total_page;
        if($total_page > $max_link_page) {
            $start_page = $page - floor($max_link_page/2) + 1;
            if($start_page < 0) $start_page = 0;
            $end_page = $start_page + $max_link_page;
            if($end_page >= $total_page) {
                $end_page = $total_page;
                $start_page = $end_page - $max_link_page;
            }
        }

        ?>
        <li class="<?php echo $prev;?>"><a  page="<?php echo $prev_page;?>" id="link" title="Prev"><i class="fa fa-angle-left"></i></a></li>
        <?php for($i=$start_page;$i<$end_page;$i++):?>
            <?php
            $active = '';
            if($i == $page) {
                $active = ' class="active"';
            }
            ?>
            <li<?php echo $active;?>>
                <a page="<?php echo $i;?>" id="link"><?php echo $i+1;?></a></li>
        <?php endfor;?>
        <?php
        $next = 'next';
        $next_page = $page + 1;
        if($next_page>=$total_page) {
            $next = 'next disabled';
            $next_page = $page;
        }
        ?>
        <li class="<?php echo $next;?>"><a  page="<?php echo $next_page;?>" id="link" title="Next"><i class="fa fa-angle-right"></i></a></li>
    </ul>
</div>
