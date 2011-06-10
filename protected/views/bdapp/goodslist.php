<?php foreach ((array)$data as $v):?>
<li><?php echo l($v['name'], aurl('bdapp/goodsinfo', array('goodsid'=>$v['id'])));?></li>
<?php endforeach;?>
<?php $this->widget('CLinkPager', array('pages'=>$pages));?>