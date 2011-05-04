<table  class="tabcolor list-tbl ma-b5px" width="100%" cellspacing="1">
    <tr>
        <td width="120" class="ar">标题：</td>
        <td><?php echo $tuannav->title ?></td>
    </tr>
    <tr>
        <td width="120" class="ar">内容：</td>
        <td><?php echo $tuannav->content ?></td>
    </tr>
    <tr>
        <td width="120" class="ar">分类：</td>
        <td><?php echo $tuannav->category->name?></td>
    </tr>
    <tr>
        <td width="120" class="ar">连接url：</td>
        <td><?php echo $tuannav->url ?></td>
    </tr>
    <tr>
        <td width="120" class="ar">图片url：</td>
        <td><?php echo $tuannav->image ?></td>
    </tr>
    <tr>
        <td width="120" class="ar">来源：</td>
        <td><?php echo $tuannav->tuandata->name ?></td>
    </tr>
    <tr>
        <td width="120" class="ar">团购价：</td>
        <td><?php echo $tuannav->group_price ?></td>
    </tr>
    <tr>
        <td width="120" class="ar">折扣：</td>
        <td><?php echo $val->discount==0 ? '无折扣' : $val->discount;?></td>
    </tr>
    <tr>
        <td width="120" class="ar">原价：</td>
        <td><?php echo $tuannav->original_price ?></td>
    </tr><!--
    <tr>
        <td width="120" class="ar">售出件数：</td>
        <td><?php echo $tuannav->sell_num ?></td>
    </tr>
    --><tr>
        <td width="120" class="ar">截至日期：</td>
        <td><?php echo $tuannav->effective_time?></td>
    </tr>
    <tr>
        <td width="120" class="ar">收藏：</td>
        <td><?php echo $tuannav->favorite_num?></td>
    </tr>
    <tr>
        <td width="120" class="ar">添加时间：</td>
        <td><?php echo $tuannav->createTimeText?></td>
    </tr>
    <tr>
        <td width="120" class="ar">添加ip：</td>
        <td><?php echo $tuannav->create_ip?></td>
    </tr>
</table>
