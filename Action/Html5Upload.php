<?php
namespace CoreBundle\Action;
use ActionKit\Action;
use Phifty\Html5UploadHandler;

class Html5Upload extends Action
{
    public $headers = array();

    public $input;

    public function init()
    {
        $this->input = $this->decodeInput();

        // for apache getallheaders
        if( function_exists("getallheaders") ) {
            $this->headers = @getallheaders();
        }

        if( $this->headers ) {
            $this->headers = array_change_key_case($this->headers, CASE_UPPER);
        }
    }

    public function decodeInput()
    {
        $input = file_get_contents('php://input');
        if(isset($_GET['base64'])) {
            $input = base64_decode( $input );
        }
        return $input;
    }

    public function getFileName()
    {
        if( isset($_SERVER['HTTP_X_UPLOAD_FILENAME']) )
            return $_SERVER['HTTP_X_UPLOAD_FILENAME'];
        if( isset( $this->headers[ 'X-UPLOAD-FILENAME' ] ) )
            return $this->headers[ 'X-UPLOAD-FILENAME' ];
    }

    public function getFileType()
    {
        if( isset($_SERVER['HTTP_X_UPLOAD_TYPE']) )
            return $_SERVER['HTTP_X_UPLOAD_TYPE'];
        if( isset($this->headers[ 'X-UPLOAD-TYPE' ]) )
            return $this->headers[ 'X-UPLOAD-TYPE' ];
    }

    public function getFileSize()
    {
        if( isset($_SERVER['HTTP_X_UPLOAD_SIZE']) )
            return $_SERVER['HTTP_X_UPLOAD_SIZE'];
        if( isset($this->headers[ 'X-UPLOAD-SIZE' ]) )
            return $this->headers[ 'X-UPLOAD-SIZE' ];
    }

    public function hasFiles()
    {
        if( count($_FILES) > 0 )
            return true;
        if( $this->input )
            return true;
        return false;
    }

    public function run()
    {
        $handler = new Html5UploadHandler('upload');
        $handler->setUploadDir( 'static/upload' );
        if( $this->hasFiles() )  {
            if( $this->getFileSize() > 1024 * 1024 * 10 )
                return $this->error('超過 10MB 大小限制。');

            $ret = $handler->move();
            if( $ret )
                return $this->success( 'File Uploaded',array( 'file' => $ret ));
            return $this->error( 'Upload failed');
        }
        return $this->error( 'File not found.');
    }
}

