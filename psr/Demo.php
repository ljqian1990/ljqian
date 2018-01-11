<?php
/**
 * PHP代码文件必须以<?php或<?=标签开始
 * PHP代码文件必须以 不带BOM的 UTF-8 编码
 * 命名空间以及类必须符合 PSR 的自动加载规范：PSR-4
 * 
 * 每个 namespace 命名空间声明语句和 use 声明语句块后面，必须 插入一个空白行
 */
namespace Ljqian\psr;

use Ljqian\psr\Test;

/**
 * 类的命名必须遵循 StudlyCaps 大写开头的驼峰命名规范
 * 
 * 类的开始花括号（{） 必须 写在函数声明后自成一行，而结束花括号（}）也 必须 写在函数主体后自成一行。
 * 
 * 关键词 extends 和 implements 必须 写在类名称的同一行。
 * 
 * implements 的继承列表也 可以 分成多行，这样的话，每个继承接口名称都 必须 分开独立成行，包括第一个
 */
class DemoClass extends Base implements
    \ArrayAccess,
    \Countable,
    \Serializable
{
    /**
     * 代码 必须 使用4个空格符而不是「Tab 键」进行缩进
     * 每行的字符数 应该 软性保持在 80 个之内，理论上 一定不可 多于 120 个，但 一定不可 有硬性限制
     */
    
    /**
     * 类中的常量所有字母都必须大写，单词间用下划线分隔
     */
    const CONSTANT_NAME = '';
    
    /**
     * 类的属性命名建议遵守小写开头的驼峰式
     * 
     * 一定不可 使用关键字 var 声明一个属性
     * 
     * 不该 使用下划线作为前缀，来区分属性是 protected 或 private。
     */
    private $camelCase;
    
    /**
     * 类的属性和方法 必须 添加访问修饰符（private、protected 以及 public），static 必须 声明在访问修饰符之后。
     */
    protected static $param;
    
    /**
     * abstract 以及 final 必须 声明在访问修饰符之前
     */
    final public static $param2;
    
    /**
     * 方法名称必须符合 camelCase 式的小写开头驼峰命名规范
     * 
     * 方法的开始花括号（{） 必须 写在函数声明后自成一行,而结束花括号（}）也 必须 写在函数主体后自成一行。
     * 
     * 不该 使用下划线作为前缀，来区分方法是 protected 或 private
     * 
     * 方法名称后 一定不可 有空格符
     * 
     * 参数左括号后和右括号前 一定不可 有空格
     * 
     * 参数列表中，每个逗号后面 必须 要有一个空格，而逗号前面 一定不可 有空格
     * 
     * 有默认值的参数，必须 放到参数列表的末尾
     *
     */
    public function camelCase($a, $b, $c = [])
    {
        
        /**
         * 控制结构的关键字后 必须 要有一个空格符
         * 
         * 控制结构的开始左括号 ( 后和结束右括号 ) 前，都 一定不可 有空格符,右括号 ) 与开始花括号 { 间 必须 有一个空格
         * 
         * 控制结构的开始花括号（{） 必须 写在声明的同一行，而结束花括号（}） 必须 写在主体后自成一行。
         * 
         * 结构体主体 必须 要有一次缩进。
         */
        if ($a === $b) {
            
            /**
             * 调用方法或函数时则 一定不可 有空格符
             * 
             * 每个参数前 一定不可 有空格，但其后 必须 有一个空格
             */
            $this->test($a, $b, $c);
            
            $this->test(
                $a,
                $b,
                $c
            );
        /**
         * 应该 使用关键词 elseif 代替所有 else if ，以使得所有的控制关键字都像是单独的一个词
         */
        } elseif ($a > $b) {
            /**
             * PHP所有 关键字 必须 全部小写
             */
            return true;
        }
    }
    
    /**
     * 参数列表 可以 分列成多行，这样，包括第一个参数在内的每个参数都 必须 单独成行
     * 拆分成多行的参数列表后，结束括号以及方法开始花括号 必须 写在同一行，中间用一个空格分隔。
     */
    public function func(
        $param1,
        $param2,
        $param3
    ) {
        /**
         * case 语句 必须 相对 switch 进行一次缩进，而 break 语句以及 case 内的其它语句都 必须 相对 case 进行一次缩进
         * 
         * 如果存在非空的 case 直穿语句，主体里 必须 有类似 // no break 的注释。
         */
        switch ($param1) {
            case 0:
                echo 'First case, with a break';
                break;
            case 1:
                echo 'Second case, which falls through';
                // no break
            case 2:
            case 3:
            case 4:
                echo 'Third case, return instead of break';
            default:
                echo 'Default case';
                break;
        }
        
        /**
         * 一个规范的 while 语句应该如下所示，注意其「括号」、「空格」以及「花括号」的位置。
         */
        while ($param2) {
            
        }
        
        /**
         * 标准的 do while 语句如下所示，同样的，注意其「括号」、「空格」以及「花括号」的位置。
         */
        do {
            
        } while ($param2);
        
        /**
         * 标准的 for 语句如下所示，注意其「括号」、「空格」以及「花括号」的位置。
         */
        for ($i = 0; $i < 10; $i++) {
            // for body
        }
        
        /**
         * 标准的 foreach 语句如下所示，注意其「括号」、「空格」以及「花括号」的位置。
         */
        foreach ($iterable as $key => $value) {
            // foreach body
        }
        
        /**
         * 标准的 try catch 语句如下所示，注意其「括号」、「空格」以及「花括号」的位置。
         */
        try {
            // try body
        } catch (FirstExceptionType $e) {
            // catch body
        } catch (OtherExceptionType $e) {
            // catch body
        }
        
        /**
         * 闭包声明时,关键词 function 后以及关键词 use 的前后都 必须 要有一个空格。
         * 开始花括号 必须 写在声明的同一行，结束花括号 必须 紧跟主体结束的下一行。
         * 参数列表和变量列表的左括号后以及右括号前，一定不可 有空格。
         * 参数和变量列表中，逗号前 一定不可 有空格，而逗号后 必须 要有空格。
         * 闭包中有默认值的参数 必须 放到列表的后面
         * 标准的闭包声明语句如下所示，注意其「括号」、「空格」以及「花括号」的位置。
         */
        $closureWithArgs = function ($arg1, $arg2) {
            // body
        };
        
        $closureWithArgsAndVars = function ($arg1, $arg2) use ($var1, $var2) {
            // body
        };
        
        /**
         * 参数列表以及变量列表 可以 分成多行，这样，包括第一个在内的每个参数或变量都 必须 单独成行，而列表的右括号与闭包的开始花括号 必须 放在同一行。
         */
        $longArgs_noVars = function (
            $longArgument,
            $longerArgument,
            $muchLongerArgument
        ) {
            // body
        };
        
        $noArgs_longVars = function () use (
            $longVar1,
            $longerVar2,
            $muchLongerVar3
        ) {
            // body
        };
        
        $longArgs_longVars = function (
            $longArgument,
            $longerArgument,
            $muchLongerArgument
        ) use (
            $longVar1,
            $longerVar2,
            $muchLongerVar3
        ) {
            // body
        };
        
        $longArgs_shortVars = function (
            $longArgument,
            $longerArgument,
            $muchLongerArgument
        ) use ($var1) {
            // body
        };
        
        $shortArgs_longVars = function ($arg) use (
            $longVar1,
            $longerVar2,
            $muchLongerVar3
        ) {
            // body
        };
    }
}