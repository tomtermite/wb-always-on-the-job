<?php

defined('BASEPATH') or exit('No direct script access allowed');

if (!$CI->db->table_exists(db_prefix() . 'document_online_my_folder')) {
    $CI->db->query('CREATE TABLE `' . db_prefix() . "document_online_my_folder` (
      `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
      `parent_id` TEXT NOT NULL,
      `name` TEXT NOT NULL,
      `type` VARCHAR(20) NULL,
      `size` text NULL,
      `staffid` int(11) NOT NULL,
      `category` varchar(20) NOT NULL DEFAULT 'my_folder',
      `realpath_data` varchar(250) NULL,
      `data_form` LONGTEXT NULL,
      `staffs_share` TEXT NULL,
      `departments_share` TEXT NULL,
      `clients_share` TEXT NULL,
      `client_groups_share` TEXT NULL,
      `rel_type` varchar(100) NULL,
      `rel_id` varchar(11) NULL,
      `group_share_staff` varchar(1) NULL,
      `group_share_client` varchar(1) NULL,
      `flag_share` TINYINT NOT NULL DEFAULT '0',
      `inserted_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
      `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
      PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ';');
	$CI->db->query('INSERT INTO `tbldocument_online_my_folder` (`parent_id`, `name`, `type`, `size`, `staffid`) VALUES ("0", "Document Online Root", "folder", "--", "'.get_staff_user_id().'");
    ');
}

if (!$CI->db->table_exists(db_prefix() . 'document_online_hash_share')) {
    $CI->db->query('CREATE TABLE `' . db_prefix() . "document_online_hash_share` (
      `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
      `rel_type` varchar(20) NOT NULL,
      `rel_id` int(11) NOT NULL,
      `id_share` int(11) NOT NULL,
      `hash` TEXT NULL,
      `role` int(1) NOT NULL DEFAULT 1,
      `inserted_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
      `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
      PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ';');
  
}

if (!$CI->db->table_exists(db_prefix() . 'document_chapter')) {
    $CI->db->query('CREATE TABLE `' . db_prefix() . "document_chapter` (
      `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
      `document_folder_id` int(11) NOT NULL,
      `pad_id` varchar(250) NOT NULL,
      `user_id` int(11) NOT NULL,
      `name` TEXT NOT NULL,
      `description` LONGTEXT NULL,
      `number_of_words` BIGINT  NULL,
      `latest_version` BIGINT  NULL,
      `ordered` int(11)  NULL,
      `inserted_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
      `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
      PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ';');
}

if (!$CI->db->table_exists(db_prefix() . 'document_chapter_version')) {
  $CI->db->query('CREATE TABLE `'. db_prefix() .'document_chapter_version` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `document_chapter_id` int(11) NOT NULL,
    `staff_id` int(11) NOT NULL,
    `client_id` int(11) NOT NULL,
    `description` LONGTEXT NULL,
    `version` int(11) NOT NULL,
    `inserted_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
  ) ENGINE=MyISAM DEFAULT CHARSET='.$CI->db->char_set.';');
}

if (!$CI->db->table_exists(db_prefix() . 'document_online_related')) {
    $CI->db->query('CREATE TABLE `' . db_prefix() . "document_online_related` (
      `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
      `parent_id` int(11) NOT NULL,
      `rel_type` varchar(20) NOT NULL,
      `rel_id` int(11) NOT NULL,
      `hash` varchar(250) NOT NULL DEFAULT '',
      `role` int(1) NOT NULL DEFAULT 1,
      `inserted_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
      `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
      PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ';');
}



$CI = & get_instance();
$CI->db->where('hash = ""');
$related = $CI->db->get(db_prefix() . 'document_online_related')->result_array();
if(count($related) > 0){
    foreach ($related as $key => $value) {
      $data['hash'] = app_generate_hash();
      $CI->db->where('id', $value['id']);
      $CI->db->update(db_prefix() . 'document_online_related', $data);
    }
}
$CI->db->where('hash = "" or hash IS NULL');
  $share = $CI->db->get(db_prefix() . 'document_online_hash_share')->result_array();
  if(count($share) > 0){
      foreach ($share as $key => $value) {
        $data['hash'] = app_generate_hash();
        $CI->db->where('id', $value['id']);
        $CI->db->update(db_prefix() . 'document_online_hash_share', $data);
      }
  }


