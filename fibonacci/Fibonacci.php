<?php
function nums($n)
{
	if ($n == 1) return 1;
	if ($n == 2) return 2;
	if ($n == 3) return 4;
	if ($n > 3) return nums($n-1)+nums($n-2)+nums($n-3);
	if ($n == 0) return 0;	
}
echo nums(10);