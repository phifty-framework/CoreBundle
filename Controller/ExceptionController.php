<?php

namespace CoreBundle\Controller;

use Phifty\Pygmentize;
use Phifty\Routing\Controller;
use Twig_Function_Function;
use Twig_Error_Runtime;

class TraceCodeBlock {

    public $file;
    public $line;
    public $range;
    public $lines;
    public $realpath;

    function __construct($file,$line,$range = 8) {
        $this->file = $file;
        $this->line = $line;
        $this->range = $range;
        $this->lines = file($file);
        $this->realpath = realpath($file);
    }

    function getBlockHighlightLine() {
        return ($this->line < $this->range ) 
            ? $this->line - $this->range + 1
            : $this->range + 1;
    }

    function getStartIndex() {
        return ( $this->line < $this->range ) ? 0 : $this->line - $this->range ;
    }

    function getEndIndex() {
        $lineCount = count($this->lines);
        return ($this->line + $this->range) > $lineCount ? $lineCount - 1 : $this->line + $this->range;
    }

    function getHighlightBlockString()
    {
        $startIndex = $this->getStartIndex();
        $text = join("",array_slice( $this->lines, $startIndex , $this->range * 2 ));
        return highlight_string("<?php\n". $text  . "\n?>",true);
    }

    function getBlockString() 
    {
        return $this->getBlock()->text;
    }

    function getBlock()
    {
        $startIndex = $this->getStartIndex();
        $text = join("",array_slice( $this->lines, $startIndex , $this->range * 2 ));
        return (object) array( 
            'text' => $text, 
            'start' => $startIndex,
            'end' => $this->getEndIndex(),
        );
    }

    function getBlockLines() {
        $startIndex = $this->getStartIndex();
        $endIndex = $this->getEndIndex();

        $blockLines = array();
        for( $i = $startIndex ; $i < $endIndex ; $i++ ) {
            $blockLines[] = (object) array(
                'nr' => ($i + 1),  
                'text' => $lines[ $i ],
            );
        }
        return $blockLines;
    }

    function render() 
    {
        // check if pygments exists
        $pygmentize = new Pygmentize( getenv('PYGMENTIZE_BIN') );
        if( $pygmentize->isSupported() ) {
            $pygmentize->setOption( 'hl_lines' , $this->getBlockHighlightLine() );
            $style = $pygmentize->renderStyle();
            $code = $this->getBlockString();
            $code = $pygmentize->renderString(
                        strpos($code,'<?php') === false
                            ? ("<?php\n" . $code . "\n?>")
                            : "\n" . $code   // newline is for highlight line counter
                    );   
            return "<style>" . $style . "</style>\n" . $code;
        }
        else {
            // check highlight string function
            return $this->getHighlightBlockString();
        }
    }

}

function trace_get_block($file,$line,$range = 8) {
    return new TraceCodeBlock($file,$line,$range);
}

class ExceptionController extends Controller
{

    public function codeAction() 
    {
        $file = $this->request->param('file');
        $line = $this->request->param('line');
        $range = $this->request->param('range') ?: 8;
        if( $file && $line ) {
            $block = new TraceCodeBlock($file,$line,$range);
            return $block->render();
        }
    }

    public function twigExceptionAction(Twig_Error_Runtime $exception) 
    {
        /*
            object(Twig_Error_Runtime)[164]
                protected 'lineno' => int 18
                protected 'filename' => string 'base.html.twig' (length=14)
                protected 'rawMessage' => string 'An exception has been thrown during the rendering of a template ("Undefined index: connection_options").' (length=104)
                protected 'previous' => null
                protected 'message' => string 'An exception has been thrown during the rendering of a template ("Undefined index: connection_options") in "base.html.twig" at line 18.' (length=135)
                private 'string' (Exception) => string '' (length=0)
                protected 'code' => int 0
                protected 'file' => string '/Volumes/ramdisk/git/vstock/phifty/vendor/pear/Twig/Template.php' (length=64)
                protected 'line' => int 269
         */



    }

    public function indexAction($exception) 
    {
        $view = $this->createView();
        $view->exception = $exception;
        return $view->render('@CoreBundle/exception.html');
    }
}



