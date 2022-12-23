<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Document_share extends App_mail_template
{

    protected $for = 'staff';

    protected $document;

    public $slug = 'document-share-assigned';
    public function __construct($document)
    {
        parent::__construct();

        $this->document = $document;
        // For SMS and merge fields for email
        $this->set_merge_fields('document_share_merge_fields', $this->document);
    }
    public function build()
    {
        $this->to($this->document->receiver);
    }
}
