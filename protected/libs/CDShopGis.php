<?php
class CDShopGis
{
    public static function fetchShopListId(array $point)
    {
        if (empty($point)) return null;
        
        $point = sprintf('ST_MakePoint(%s, %s)', $point[0], $point[1]);
        $data = app()->pgdb->createCommand()
            ->select('id')
            ->from('wm_shops')
            ->where($point . '&& region')
            ->queryAll();
            
       if (null === $data) return null;
       
       foreach ($data as $v) $ids[] = $v['id'];
       
       return $ids;
    }
    
    public static function fetchShopRegion($shopid, $index = null)
    {
        $shopid = (int)$shopid;
        if (empty($shopid)) return null;
        
        $data = app()->pgdb->createCommand()
            ->select(array('shop_id', 'shop_name', 'ST_AsGeoJSON(region) as shop_region'))
            ->from('wm_shops')
            ->where('shop_id = :shopid', array(':shopid' => $shopid))
            ->queryRow();
            
       if (null === $data) return null;
       $data = json_decode($data['shop_region'], true);
       
       if (null === $index)
           return $data['coordinates'];
       else
           return $data['coordinates'][$index];
    }
    
    public static function setShopRegion($shopid, array $region, $index = 0)
    {
        if (empty($region)) return false;

        /*
         * 判断给出的折线是否一个简单的闭合曲线
         */
        foreach ($region as $v)
            $points[] = $v[0] . ' ' . $v[1];
        $line_string = "'LINESTRING(" . join(', ', $points) . ")'::geometry";
        
        $ring = app()->pgdb->createCommand("select ST_IsRing({$line_string}) as isring")->queryRow();
        $isring = $ring['isring'];

        /*
         * 如果不是一个简单的闭合的话，就抛出异常
         */
        if (!isring)
            throw new Exception('region is not a ring');

        /*
         * 先取出原来的3个送餐范围
         */
        $polygons = self::fetchShopRegion($shopid);

        /*
         * 把需要更新的替换掉
         */
        $polygons[$index] = $region;

        /*
         * 组合成wkt的格式
         */
        foreach ($polygons as $k => $p) {
            foreach ($p as $v)
                $data[$k][] = $v[0] . ' ' . $v[1];
        }
        
        /*
         * 组成成Polygon的wkt格式
         */
        $new_region = sprintf("ST_PolygonFromText('Polygon((%s), (%s), (%s))')", join(', ', $data[0]), join(', ', $data[1]), join(', ', $data[2]));
        
        /*
         * 更新数据表
         */
        $result = app()->pgdb->createCommand("update wm_shops set region=$new_region where shop_id=$shopid")->query();
        
        return $result;
    }
}

