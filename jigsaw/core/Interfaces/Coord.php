<?php
namespace Jigsaw\Interfaces;

interface Coord
{
    public function seCoord($width, $height, $xaxis, $yaxis);
    
    public function setIsPositionFix();
}