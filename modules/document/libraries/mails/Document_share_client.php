<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Document_share_client extends App_mail_template
{

    protected $for = 'client';

    protected $document_client;

    public $slug = 'document-share-assigned-client';
    public function __construct($document_client)
    {
        parent::__construct();

        $this->document_client = $document_client;
        // For SMS and merge fields for email
        $this->set_merge_fields('document_share_merge_fields', $this->document_client);
    }
    public function build()
    {
        $this->to($this->document_client->receiver);
    }
}
