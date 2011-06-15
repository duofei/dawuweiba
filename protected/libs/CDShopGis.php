<?php
/**
 * 处理与商铺地理数据有关的
 * 主要为商铺坐标及商铺的送餐范围
 * @author Chris Chen (cdcchen@gmail.com)
 *
 */
class CDShopGis
{
    /**
     * 获取可以给$point点送餐的商铺的id列表
     * @param array $point 使用google map坐标，$point[0]为lat纬度，$point[1]为lon经度
     * @param integer|null $index 商铺的第几个送餐范围，如果为null则返回3个送餐范围
     * @throws Exception
     * @return array 商铺id列表
     */
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

    /**
     * 获取某一个商铺的送餐范围
     * @param integer $shopid 商铺的ID号
     * @param integer|null $index 第几个送餐范围，如果为null，则返回3个
     * @throws Exception
     * @return array 如果$index为null，则返回二维数组，否则返回一维数组，每个元素对应一个点
     */
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
    
    /**
     * 设置商铺的送餐范围
     * @param integer $shopid 商铺ID号
     * @param array $region 商铺的送餐范围
     * @param integer $index 第几个送餐范围
     * @throws Exception
     * @return integer 更新是否成功
     */
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

    /**
     * 添加新的商铺信息
     * @param integer $shopid 商铺ID号
     * @param string $name 商铺名称
     * @param array $coordinate 商铺坐标
     * @param array $region1 第一个送餐范围
     * @param array $region2 第二个送餐范围
     * @param array $region3 第三个送餐范围
     */
    public static function insert($shopid, $name, array $coordinate, $region1, $region2, $region3)
    {
        // 先将商铺id,name存入数据库
        $result = app()->pgdb->createCommand("insert into wm_shops (shop_id, shop_name) values ($shopid, '$name')")->query();
        
        // 如果有坐标的话将坐标更新到数据库中
        if ($coordinate) {
            $point = sprintf('ST_MakePoint(%s, %s)', $coordinate[0], $coordinate[1]);
            $result = app()->pgdb->createCommand("update wm_shops set coordinate=$point where shop_id=$shopid")->query();
        }

        // 如果有送餐范围，更新到数据库中
        if ($region1) {
            foreach ($region1 as $p) {
                $data[] = trim($p[0]) . ' ' . trim($p[1]);
            }
    
            $region1 = sprintf("ST_PolygonFromText('Polygon((%s))')", @join(', ', $data));
            try {
                $result = app()->pgdb->createCommand("update wm_shops set region1=$region1 where shop_id=$shopid")->query();
            } catch (Exception $e) {
                echo '<b>region1</b>';
                print_r($e->getMessage());
            }
            unset($data);
        }

        // 如果有送餐范围，更新到数据库中
        if ($region2) {
            foreach ($region2 as $p) {
                $data[] = trim($p[0]) . ' ' . trim($p[1]);
            }
            
            $region2 = sprintf("ST_PolygonFromText('Polygon((%s))')", @join(', ', $data));
            try {
                $result = app()->pgdb->createCommand("update wm_shops set region2=$region2 where shop_id=$shopid")->query();
            } catch (Exception $e) {
                echo '<b>region2</b>';
                print_r($e->getMessage());
            }
            unset($data);
        }

        // 如果有送餐范围，更新到数据库中
        if ($region3) {
            foreach ($region3 as $p) {
                $data[] = trim($p[0]) . ' ' . trim($p[1]);
            }
    
            $region3 = sprintf("ST_PolygonFromText('Polygon((%s))')", @join(', ', $data));
            try {
                $result = app()->pgdb->createCommand("update wm_shops set region3=$region3 where shop_id=$shopid")->query();
            } catch (Exception $e) {
                echo '<b>region3</b>';
                print_r($e->getMessage());
            }
            unset($data);
        }
    }
}

