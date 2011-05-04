<?php echo CHtml::beginForm(url('at/search'), 'get');?>
<div class="pa-10px conflict lh30px" style="width:630px">
   	<h3 class="f18px lh30px f20px bg-pic">对不起，该餐厅不能配送到<?php echo $locationName;?> </h3>
   	<?php if($html):?>
    <p class="pa-l30px ma-l5px">1. 选择其他地址</p>
   	<?php echo $html;?>
    <p class="pa-l30px ma-l5px">2. 查看<?php echo $locationName;?>附近的餐厅</p>
    <p class="pa-l30px ma-l5px">3. <a href="javascript:void(0);" onclick="$.colorbox.close();">先去餐厅看看</a></p>
    <p class="pa-l30px ma-l5px">4. 搜索新地址</p>
    <?php else:?>
    <p class="pa-l30px ma-l5px">1. <a href="javascript:void(0);" onclick="$.colorbox.close();">先去餐厅看看</a></p>
    <p class="pa-l30px ma-l5px">2. 搜索新地址</p>
    <?php endif;?>
    <p class="pa-l30px ma-l5px" style="height:40px;"><input class="txt" name="kw" type="text" />
    <input class="btn-four" type="submit" value="地址搜索" /></p>
</div>
<?php echo CHtml::endForm();?>