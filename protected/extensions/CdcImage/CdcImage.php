<?php
class CdcImage
{
    private $_version = '1.0';
    private $_author = 'Chris Chen(cdcchen@gmail.com)';
    private $_site = 'http://www.24beta.com/';
    
    private $_image;
    private $_original;
    private $_imageType;
    
    private static $_createFunctions = array(
        IMAGETYPE_GIF => 'imagecreatefromgif',
        IMAGETYPE_JPEG => 'imagecreatefromjpeg',
        IMAGETYPE_PNG => 'imagecreatefrompng',
        IMAGETYPE_WBMP => 'imagecreatefromwbmp',
        IMAGETYPE_XBM => 'imagecreatefromxmb',
    );
    private static $_outputFuntions = array(
        IMAGETYPE_GIF => 'imagegif',
        IMAGETYPE_JPEG => 'imagejpeg',
        IMAGETYPE_PNG => 'imagepng',
        IMAGETYPE_WBMP => 'imagewbmp',
        IMAGETYPE_XBM => 'imagexmb',
    );
    
    public function __construct($filename)
    {
        $this->load($filename);
    }
    
    public function load($filename)
    {
        if (!file_exists($filename)) return false;
        
        $info = getimagesize($filename);
        $this->_imageType = $info[2];
        
        if (!array_key_exists($this->_imageType, self::$_createTypes)) return false;
        
        $this->_image = $this->_original = self::$_createFunctions[$this->_imageType]($filename);
        return $this;
    }
    
    public function loadFromStream($data)
    {
        if (empty($data)) return false;
        $this->_image = $this->_original = imagecreatefromstring($data);
        return $this;
    }
    
    public function width()
    {
        return imagesx($this->_image);
    }
    
    public function height()
    {
        return imagesy($this->_image);
    }
    
    public function revert()
    {
        $this->_image = $this->_original;
        return $this;
    }
    
    public function save($filename, $permissions = null)
    {
        $func = self::$_outputFuntions[$this->_imageType];
        $func($this->_image, $filename);
        if ($permissions !== null) {
            chmod($filename, $permissions);
        }
        return $this;
    }
    public function saveAsGif($filename, $permissions = null)
    {
        imagegif($this->_image, $filename);
        if ($permissions !== null) {
            chmod($filename, $permissions);
        }
        return $this;
    }
    
    public function saveAsJpeg($filename, $quality = 75, $permissions = null)
    {
        imagejpeg($this->_image, $filename, $quality);
        if ($permissions !== null) {
            chmod($filename, $permissions);
        }
        return $this;
    }
    
    public function saveAsPng($filename, $quality = 75, $filters = 0, $permissions = null)
    {
        imagepng($this->_image, $filename, $quality, $filters);
        if ($permissions !== null) {
            chmod($filename, $permissions);
        }
        return $this;
    }
    
    public function saveAsWbmp($filename, $foreground  = 0, $permissions = null)
    {
        imagewbmp($this->_image, $filename);
        if ($permissions !== null) {
            chmod($filename, $permissions);
        }
        return $this;
    }
    
    public function saveAsXbm($filename, $foreground  = 0, $permissions = null)
    {
        imagewxbm($this->_image, $filename);
        if ($permissions !== null) {
            chmod($filename, $permissions);
        }
        return $this;
    }
    
    public function output($filename, $permissions = null)
    {
        $contentType = image_type_to_mime_type($this->_imageType);
        header('Content-Type: ' . $contentType);
        $func = self::$_outputFuntions[$this->_imageType];
        $func($this->_image);
    }
    public function outputGif($filename, $permissions = null)
    {
        header('Content-Type: ' . image_type_to_mime_type(IMAGETYPE_GIF));
        imagegif($this->_image);
    }
    
    public function outputJpeg($filename, $quality = 75, $permissions = null)
    {
        header('Content-Type: ' . image_type_to_mime_type(IMAGETYPE_JPEG));
        imagejpeg($this->_image);
    }
    
    public function outputPng($filename, $quality = 75, $filters = 0, $permissions = null)
    {
        header('Content-Type: ' . image_type_to_mime_type(IMAGETYPE_PNG));
        imagepng($this->_image);
    }
    
    public function outputWbmp($filename, $foreground  = 0, $permissions = null)
    {
        header('Content-Type: ' . image_type_to_mime_type(IMAGETYPE_WBMP));
        imagewbmp($this->_image);
    }
    
    public function outputXbm($filename, $foreground  = 0, $permissions = null)
    {
        header('Content-Type: ' . image_type_to_mime_type(IMAGETYPE_XBM));
        imagewxbm($this->_image);
    }
    
    public function outputRaw($filename, $permissions = null)
    {
        ob_start();
        $func = self::$_outputFuntions[$this->_imageType];
        $func($this->_image);
        $content = ob_get_contents();
        ob_end_clean();
        return $content;
    }
    
    public function outputRawGif($filename, $permissions = null)
    {
        ob_start();
        imagegif($this->_image);
        $content = ob_get_contents();
        ob_end_clean();
        return $content;
    }
    
    public function outputRawJpeg($filename, $quality = 75, $permissions = null)
    {
        ob_start();
        imagejpeg($this->_image);
        $content = ob_get_contents();
        ob_end_clean();
        return $content;
    }
    
    public function outputRawPng($filename, $quality = 75, $filters = 0, $permissions = null)
    {
        ob_start();
        imagepng($this->_image);
        $content = ob_get_contents();
        ob_end_clean();
        return $content;
    }
    
    public function outputRawWbmp($filename, $foreground  = 0, $permissions = null)
    {
        ob_start();
        imagewbmp($this->_image);
        $content = ob_get_contents();
        ob_end_clean();
        return $content;
    }
    
    public function outputRawXbm($filename, $foreground  = 0, $permissions = null)
    {
        ob_start();
        imagewxbm($this->_image);
        $content = ob_get_contents();
        ob_end_clean();
        return $content;
    }
    
    public function scale($scale)
    {
        $width = $this->width() * $scale/100;
        $height = $this->height() * $scale/100;
        $this->resize($width, $height);
        return $this;
    }
    
    public function resizeToHeight($height)
    {
        $ratio = $height / $this->height();
        $width = $this->width() * $ratio;
        $this->resize($width, $height);
        return $this;
    }

    public function resizeToWidth($width)
    {
        $ratio = $width / $this->width();
        $height = $this->height() * $ratio;
        $this->resize($width, $height);
        return $this;
    }
    
    public function resize($width, $height)
    {
        $image = imagecreatetruecolor($width, $height);
        imagecopyresampled($image, $this->_image, 0, 0, 0, 0, $width, $height, $this->width(), $this->height());
        $this->_image = $image;
        return $this;
    }
    
    public function crop($width, $height)
    {
        $image = imagecreatetruecolor($width, $height);
        $wm = $this->width() / $width;
        $hm = $this->height() / $height;
        $h_height = $height / 2;
        $w_height = $width / 2;
        
        if ($this->width() > $this->height()) {
            $adjusted_width = $this->width() / $hm;
            $half_width = $adjusted_width / 2;
            $int_width = $half_width - $w_height;
            
            imagecopyresampled($image, $this->_image, -$int_width, 0, 0, 0, $adjusted_width, $height, $this->width(), $this->height());
        }
        elseif (($this->width() < $this->height()) || ($this->width() == $this->height())) {
            $adjusted_height = $this->height() / $wm;
            $half_height = $adjusted_height / 2;
            $int_height = $half_height - $h_height;
        
            imagecopyresampled($image, $this->_image, 0, -$int_height, 0, 0, $width, $adjusted_height, $this->width(), $this->height());
        }
        else {
            imagecopyresampled($image, $this->_image, 0, 0, 0, 0, $width, $height, $this->width(), $this->height());
        }
        $this->_image = $image;
        return $this;
    }
}