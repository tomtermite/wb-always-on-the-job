<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Document_share_merge_fields extends App_merge_fields
{
    public function build()
    {
        return [
            [
                'name'      => 'Name document',
                'key'       => '{name_document}',
                'available' => [
                    'document',
                ],
            ],
            [
                'name'      => 'Type document',
                'key'       => '{type_document}',
                'available' => [
                    'document',
                ],
            ],
            [
                'name'      => 'Share link',
                'key'       => '{share_link_document}',
                'available' => [
                    'document',
                ],
            ],
            [
                'name'      => 'Receiver',
                'key'       => '{receiver}',
                'available' => [
                    'document',
                ],
            ],
            [
                'name'      => 'sender',
                'key'       => '{sender}',
                'available' => [
                    'document',
                ],
            ],
            [
                'name'      => 'share link client document',
                'key'       => '{share_link_client_document}',
                'available' => [
                    'document',
                ],
            ]
          
        ];
    }

    /**
     * Merge field for appointments
     * @param  mixed $hira 
     * @return array
     */
    public function format($share_obj)
    {

        $this->ci->load->model('document/document_model');
        $fields['{name_document}']   = $share_obj->name;
        $fields['{type_document}'] = $share_obj->type;

        if($share_obj->type_template == "staff_template"){
            $response = $this->ci->document_model->get_hash('staff', $share_obj->staff_share_id, $share_obj->id);

            $fields['{share_link_document}']   = admin_url('document/file_view_share/'.$response->hash);
            $fields['{receiver}']            = get_staff_full_name($share_obj->staff_share_id);        

        }elseif ($share_obj->type_template == "client_template") {
            $response = $this->ci->document_model->get_hash('client', $share_obj->client_share_id, $share_obj->id);
            $fields['{share_link_client_document}'] = site_url('document/document_client/file_view_share/'.$response->hash);
            $fields['{receiver}']            = get_company_name($share_obj->client_share_id);        
        }

        $fields['{sender}']            = get_staff_full_name(get_staff_user_id()); 

        return $fields;
    }
}
