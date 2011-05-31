<?php
class CDShopGis
{
    public static function fetchShopListId(array $point, $index = null)
    {
        if (empty($point)) return null;
        
        if (!in_array($index, array(null, 1, 2, 3)))
            throw new Exception('$index is null|1|2|3');
        
        $point = sprintf('ST_MakePoint(%s, %s)', $point[0], $point[1]);
        $cmd = app()->pgdb->createCommand()
            ->select('id, shop_id')
            ->from('wm_shops');

        if ($index === null)
            $cmd->where("$point && region1 or $point && region2 or $point && region3");
        else {
            $column = 'region' . (int)$index;
            $cmd->where($point . ' && ' . $column);
        }
        
        
       $data = $cmd->queryAll();
            
       if (null === $data) return null;
       
       foreach ($data as $v) $ids[] = $v['shop_id'];
       
       return $ids;
    }
    
    public static function fetchShopRegion($shopid, $index = null)
    {
        $shopid = (int)$shopid;
        if (empty($shopid)) return null;
        
        if (!in_array($index, array(null, 1, 2, 3)))
            throw new Exception('$index is null|1|2|3');
        
        $data = app()->pgdb->createCommand()
            ->select(array('shop_id', 'shop_name', 'ST_AsGeoJSON(region1) as shop_region1', 'ST_AsGeoJSON(region2) as shop_region2', 'ST_AsGeoJSON(region3) as shop_region3'))
            ->from('wm_shops')
            ->where('shop_id = :shopid', array(':shopid' => $shopid))
            ->queryRow();

       if (null === $data) return null;
       
       if (null === $index) {
           $r1 = json_decode($data['shop_region1'], true);
           $r2 = json_decode($data['shop_region2'], true);
           $r3 = json_decode($data['shop_region3'], true);
           return array(
               'region1' => $r1['coordinates'][0],
               'region2' => $r2['coordinates'][0],
               'region3' => $r3['coordinates'][0],
           );
       }
       
       $column = 'shop_region' . $index;
       $data = json_decode($data[$column], true);
       
       return $data['coordinates'][0];
    }
    
    public static function setShopRegion($shopid, array $region, $index = 1)
    {
        if (empty($region)) return false;
        
        if (!in_array($index, array(1, 2, 3)))
            throw new Exception('$index is 1|2|3');

        /*
         * 先取出原来的3个送餐范围
         */
        $polygons = self::fetchShopRegion($shopid);

        /*
         * 把需要更新的替换掉
         */
        $column = 'region' . $index;
        $polygons[$column] = $region;

        /*
         * 组合成wkt的格式
         */
        foreach ($polygons[$column] as $k => $p) {
            $data[] = $p[0] . ' ' . $p[1];
        }
        
        /*
         * 组成成Polygon的wkt格式
         */
        $new_region = sprintf("ST_PolygonFromText('Polygon((%s))')", @join(', ', $data));

        /*
         * 更新数据表
         */
        $result = app()->pgdb->createCommand("update wm_shops set {$column}=$new_region where shop_id=$shopid")->query();
        
        return $result;
    }

    public static function insert($shopid, $name, array $coordinate, $region1, $region2, $region3)
    {
        $result = app()->pgdb->createCommand("insert into wm_shops (shop_id, shop_name) values ($shopid, '$name')")->query();
        
        if ($coordinate) {
            $point = sprintf('ST_MakePoint(%s, %s)', $coordinate[0], $coordinate[1]);
            $result = app()->pgdb->createCommand("update wm_shops set coordinate=$point where shop_id=$shopid")->query();
        }

        if ($region1) {
            foreach ($region1 as $p) {
                $data[] = trim($p[0]) . ' ' . trim($p[1]);
            }
    
            echo $new_region = sprintf("ST_PolygonFromText('Polygon((%s))')", @join(', ', $data));echo '<hr />';
            try {
                $result = app()->pgdb->createCommand("update wm_shops set region1=$new_region where shop_id=$shopid")->query();
            } catch (Exception $e) {
                print_r($region1);
            }
        }

        if ($region2) {
            foreach ($region2 as $p) {
                $data[] = trim($p[0]) . ' ' . trim($p[1]);
            }
            
            $new_region = sprintf("ST_PolygonFromText('Polygon((%s))')", @join(', ', $data));
            try {
                $result = app()->pgdb->createCommand("update wm_shops set region2=$new_region where shop_id=$shopid")->query();
            } catch (Exception $e) {
                print_r($region2);
            }
        }

        if ($region3) {
            foreach ($region3 as $p) {
                $data[] = trim($p[0]) . ' ' . trim($p[1]);
            }
    
            $new_region = sprintf("ST_PolygonFromText('Polygon((%s))')", @join(', ', $data));
            try {
                $result = app()->pgdb->createCommand("update wm_shops set region3=$new_region where shop_id=$shopid")->query();
            } catch (Exception $e) {
                print_r($region2);
            }
        }
    }
}

