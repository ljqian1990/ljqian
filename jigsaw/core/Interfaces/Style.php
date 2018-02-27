<?php
namespace Jigsaw\Interfaces;

interface Style
{
    public function setBgColor($bgColor);
    
    public function setFontColor($fontColor);
    
    public function setOpacity($opacity);
    
    public function setBorderRadius($borderRadius);
    
    public function setPadding($padding);
    
    public function setBorder($size, $color);
}