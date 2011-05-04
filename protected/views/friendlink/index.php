<div class=" link-left fl ma-r20px">
	<h3 class="f12px lh30px bg-ec pa-l20px">友情链接、网站合作联系</h3>
    <ul class="ma-t20px ma-l20px lh24px cgray">
    	<li>图标：<img src="<?php echo resBu('images/friendlink-logo.gif'); ?>" /></li>
    	<li>名称：我爱外卖</li>
        <li>网址：http://www.52wm.com</li>
        <li>电话：0531-55500071</li>
        <li>&nbsp;QQ：1478279460</li>
        <li>邮箱：contact@52wm.com</li>
    </ul>
</div>
<div class="link-right fl">
	<h3 class="f12px lh30px bg-ec indent24px"><a href="<?php echo url('friendlink/create');?>" class="fr pa-r10px">申请友情链接</a>文字链接</h3>
    <ul class="subfl link ma-t10px ma-l10px cgray">
    	<?php foreach ($friendlinks as $fl): if(empty($fl->logo)): ?>
    	<li><?php echo l($fl->name, $fl->homepage, array('title'=>$fl->name, 'target'=>'_blank'));?></li>
    	<?php endif; endforeach; ?>
    </ul><div class=" clear"></div>
    
    <h3 class="f12px lh30px bg-ec indent24px">图片链接</h3>
    <ul class="subfl link ma-t20px ma-l10px cgray">
    	<?php foreach ($friendlinks as $fl): if(!empty($fl->logo)): ?>
    	<li><?php echo l(CHtml::image($fl->logo, $fl->name, array('width'=>88, 'height'=>31)), $fl->homepage, array('title'=>$fl->name, 'target'=>'_blank'));?></li>
    	<?php endif; endforeach; ?>
    </ul><div class=" clear"></div>
</div><div class="clear"></div>
<div class="ma-t20px link-about">
    <ul class="lh24px cgray">
        <li><h3 class="f12px">链接说明 ：</h3></li>
        <li>1、本站链接排序不分先后。　</li>
        <li>2、与本站链接的网站必须是合法站点，要求内容完整、有自己的特色。　</li>
        <li>3、如果您提出与本站链接，请先在您站点内做好我们的网站链接之后，通知我们，经我们审核后本站会以最短时间做好链接。</li>
        <li>4、本站将定期对友情链接部分进行检查，发现无法正常打开、没有相互链接、内容不符合要求等站点，将立即清除。　</li>
        <li>5、本站更改网站时如误删了您的链接，请及时联系。　</li>
        <li>6、我们会认真对待每一个请求。</li>
        <li class="cred">本着友好合作、共同发展的原则，诚征合作伙伴。欢迎交换链接、网站合作。</li>
    </ul>
</div>
<div class="bottomline"></div>