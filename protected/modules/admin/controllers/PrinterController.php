<?php
class PrinterController extends Controller
{
    public function actionList()
    {
        $attrs = array('city_id' => $this->city['id']);
        $printers = Printer::model()->findAllByAttributes($attrs);
        
        $this->render('list', array(
            'printers' => $printers,
        ));
    }
    
    public function actionCreate($id = 0)
    {
        $id = (int)$id;
        if (0 === $id)
            $printer = new Printer;
        else
            $printer = Printer::model()->findByPk($id);
        
        if (request()->isPostRequest && isset($_POST['Printer'])) {
            $printer->attributes = $_POST['Printer'];
            $printer->city_id = $this->city['id'];
            if ($printer->save())
                $this->redirect(url('admin/printer/list'));
        }
        
        $this->render('create', array(
            'model' => $printer,
        ));
    }
    
    public function actionShop($pid)
    {
        $pid = (int)$pid;
        $printer = Printer::model()->findByPk($pid);
        
        if (null === $printer) {
            throw new CHttpException(404, $pid . ' 该打印机不存在');
        }
        
        if (request()->isPostRequest && isset($_POST['selectedshop'])) {
            $shop = Shop::model()->findByPk($_POST['selectedshop']);
            if (null === $shop) {
                throw new CHttpException(500, '该商铺不存在');
            }
            if ($printer->shop) {
                $old = $printer->shop;
                $old->printer_no = 0;
                $old->save();
                echo CHtml::errorSummary($old);
            }
            $shop->printer_no = $pid;
            $shop->save();
            echo CHtml::errorSummary($shop);
        }
        
        $this->render('shop', array(
            'model' => $printer,
        ));
    }
    
    public function actionSearchShop($kw)
    {
        $kw = strip_tags(trim($kw));
        
        if (!$kw) exit(0);
        
        $cmd = app()->getDb()->createCommand()
            ->select(array('id', 'shop_name'))
            ->from('{{Shop}}')
            ->where(array('like', 'shop_name', "%$kw%"))
            ->order('id asc');
        
        $data = $cmd->queryAll();
        echo json_encode($data);
        exit(0);
    }
    
    /**
     * 设置重启日志
     * @param string $no
     */
    public function actionRestart($no)
    {
        $no = strip_tags(trim($no));
        if (empty($no)) exit('ERROR');

        $dir = app()->getRuntimePath() . DS . 'printer' . DS;
        if (!file_exists($dir))
            mkdir($dir, 0777, true);

        $filename = $dir . $no . '.log';
        $str = date('Y-m-d H:i:s') . " - $no - Printer Restart\n";
        
        $handle = fopen($filename, 'a');
        flock($handle, LOCK_EX);
        fwrite($handle, $str);
        flock($handle, LOCK_UN);
        fclose($handle);
        echo 'OKOK';
        exit(0);
    }
    
    /**
     * 读取重启日志
     * @param string $code
     */
    public function actionRestartlog($code)
    {
        $code = strip_tags(trim($code));
        if (empty($code)) exit('ERROR');
        
        $dir = app()->getRuntimePath() . DS . 'printer' . DS;
        $filename = $dir . $code . '.log';
        $logs = file($filename);
        $logs = array_reverse($logs);
        
        foreach ($logs as $v)
            echo '<li>' . $v . '</li>';
        
        exit(0);
    }
}