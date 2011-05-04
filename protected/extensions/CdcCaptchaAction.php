<?php
class CdcCaptchaAction extends CCaptchaAction
{
    public function run ()
    {
        parent::run();
    }
    protected function generateVerifyCode ()
    {
        if ($this->minLength < 3)
            $this->minLength = 3;
        if ($this->maxLength > 20)
            $this->maxLength = 20;
        if ($this->minLength > $this->maxLength)
            $this->maxLength = $this->minLength;
        $length = rand($this->minLength, $this->maxLength);
        $letters = '0123456789';
        for ($i = 0; $i < $length; ++ $i) {
            $code .= $letters[rand(0, 9)];
        }
        return $code;
    }
    /**
     * Renders the CAPTCHA image based on the code.
     * @param string the verification code
     * @return string image content
     */
    protected function renderImage ($code)
    {
        $image = imagecreatetruecolor($this->width, $this->height);
        $backColor = imagecolorallocate($image, (int) ($this->backColor % 0x1000000 / 0x10000), (int) ($this->backColor % 0x10000 / 0x100), $this->backColor % 0x100);
        imagefilledrectangle($image, 0, 0, $this->width, $this->height, $backColor);
        imagecolordeallocate($image, $backColor);
        $foreColor = imagecolorallocate($image, (int) ($this->foreColor % 0x1000000 / 0x10000), (int) ($this->foreColor % 0x10000 / 0x100), $this->foreColor % 0x100);
        if ($this->fontFile === null)
            $this->fontFile = dirname(__FILE__) . '/Duality.ttf';
        $len = strlen($code);
        $fontSize = $this->height - $this->padding * 2;
        for ($i = 0; $i < $len; $i ++) {
            $foreColor = imagecolorallocate($image, mt_rand(0, 255), mt_rand(0, 255), mt_rand(0, 255));
            $angle = mt_rand(- 20, 20);
            $x = 5 + $fontSize * $i;
            $y = 20;
            imagettftext($image, $fontSize, $angle, $x, $y, $foreColor, $this->fontFile, $code[$i]);
            imageline($image, mt_rand(0, $this->width), mt_rand(0, $this->height), mt_rand(0, $this->width), mt_rand(0, $this->height), $foreColor);
        }
        imagecolordeallocate($image, $foreColor);
        header('Pragma: public');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Content-Transfer-Encoding: binary');
        header("Content-type: image/png");
        imagepng($image);
        imagedestroy($image);
    }
}

