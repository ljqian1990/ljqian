<?php
namespace Projectname\Controls;

use Projectname\Models\Index as IndexModel;

class Index extends Base
{
    
    public function __construct()
    {
        parent::__construct();
    }

    public function Index()
    {                
        return 'hello world';
    }
}