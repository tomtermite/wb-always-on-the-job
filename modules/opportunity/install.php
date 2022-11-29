<?php

defined('BASEPATH') or exit('No direct script access allowed');

if (!$CI->db->table_exists(db_prefix() . 'opportunity')) {
    $CI->db->query('CREATE TABLE `' . db_prefix() . 'opportunity` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `clientid` INT(11) NOT NULL,
    `solicitation_number` VARCHAR(250) NOT NULL,
    `solicitation_title` VARCHAR(250) NOT NULL,
    `agency` VARCHAR(250) NOT NULL,
    `solicitation_due_date` DATE NOT NULL,
    `solicitation_url` VARCHAR(250) NOT NULL,
    `solicitation_description` TEXT NOT NULL,
    `recommendation` TEXT NOT NULL,
    `recommendation_select` VARCHAR(50) NOT NULL,
    `additional_notes` TEXT NOT NULL,
    `ability_to_respond_to_solicitation` VARCHAR(250) NOT NULL,
    `opportunity_status_id` int(11) NOT NULL,
    `solicitation_created` DATETIME,
    PRIMARY KEY (`id`));');
}

if (!$CI->db->table_exists(db_prefix() . 'requirement')) {
    $CI->db->query('CREATE TABLE `' . db_prefix() . 'requirement` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `requirement` varchar(250) NOT NULL,
    `risks` varchar(250) NOT NULL,
    `risks_priority` varchar(250) NOT NULL,
    `impacts` varchar(250) NOT NULL,
    `impacts_priority` varchar(250) NOT NULL,
    `action_items` varchar(250) NOT NULL,
    `gap_yes_no` enum("Yes","No") NOT NULL,
    `opportunity_id` int(11) NOT NULL,
    PRIMARY KEY (`id`));');
}

if (!$CI->db->table_exists(db_prefix() . 'opportunity_status')) {
    $CI->db->query('CREATE TABLE `' . db_prefix() . 'opportunity_status` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `name` varchar(250) NOT NULL,
    `status_order` int(11) NOT NULL,
    PRIMARY KEY (`id`));');
}

if (!$CI->db->table_exists(db_prefix() . 'opportunity_mail')) {
    $CI->db->query('CREATE TABLE `' . db_prefix() . 'opportunity_mail` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `opportunity_id` int(11) NOT NULL,
        `cc` text NOT NULL,
        PRIMARY KEY (`id`));');
}

if (!$CI->db->table_exists(db_prefix() . 'opportunity_status')) {
    $CI->db->query('INSERT INTO `' . db_prefix() . 'opportunity_status` (id, name, status_order)
        VALUES
        ("1", "Draft", "1"), 
        ("2", "In Review", "2"), 
        ("3", "Bid", "3"),
        ("4" , "No Bid", "4"),
        ("5", "Cancelled", "5"), 
        ("6", "Archived", "6");'
    );
};

