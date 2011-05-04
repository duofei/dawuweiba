<data52wm>
<data>
  	<errorCode>0</errorCode>
  	<errorMessage></errorMessage>
  	<addressList>
  	<?php foreach($address as $a):?>
  	<address>
  		<id><?php echo $a->id;?></id>
  		<userId><?php echo $a->user_id; ?></userId>
  		<consignee><?php echo $a->consignee; ?></consignee>
  		<address><?php echo $a->address; ?></address>
  		<telphone><?php echo $a->telphone; ?></telphone>
  		<mobile><?php echo $a->mobile; ?></mobile>
  		<cityId><?php echo $a->city_id; ?></cityId>
  		<districtId><?php echo $a->district_id; ?></districtId>
  		<buildingId><?php echo $a->building_id; ?></buildingId>
  		<lon><?php echo $a->map_x; ?></lon>
  		<lat><?php echo $a->map_y; ?></lat>
  		<createTime><?php echo $a->create_time; ?></createTime>
  		<isDefault><?php echo $a->is_default; ?></isDefault>
  	</address>
  	<?php endforeach;?>
  	</addressList>
</data>
</data52wm>