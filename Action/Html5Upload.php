<?php
namespace CoreBundle\Action;
use ActionKit\Action;

class Html5Upload extends Action
{
    public function run()
    {
        $handler = new Uploader('upload');
        $handler->setUploadDir( 'upload' );
        if( $handler->foundUpload() ) 
        {
            /*
            if ( $handler->getFileSize() > 1024 * 1024 * 10 ) {
                return $this->error('超過 10MB 大小限制。');
            }
            */
            if ( $ret = $handler->move() ) {
                return $this->success( 'File uploaded', array( 'file' => $ret ) );
            }
            return $this->error( 'Upload failed');
        }
        return $this->error( 'File not found.');
    }
}

