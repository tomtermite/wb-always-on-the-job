<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Check whether column exists in a table
 * Custom function because Codeigniter is caching the tables and this is causing issues in migrations.
 *
 * @param string $column column name to check
 * @param string $table  table name to check
 * @param mixed  $id
 *
 * @return bool
 */

function calculate_gap($gap_yes_no, $ability_to_respond_to_solicitation)
{
    if ($gap_yes_no == 'Yes') {
        if ($ability_to_respond_to_solicitation >= 0 && $ability_to_respond_to_solicitation <= 25) {
            return [0, 'Low', 'red'];
        } else if ($ability_to_respond_to_solicitation >= 26 && $ability_to_respond_to_solicitation <= 50) {
            return [1, 'Low', 'orange'];
        } else if ($ability_to_respond_to_solicitation >= 51 && $ability_to_respond_to_solicitation <= 75) {
            return [2, 'Medium', 'yellow'];
        } else if ($ability_to_respond_to_solicitation >= 76 && $ability_to_respond_to_solicitation <= 100) {
            return [3, 'Medium', 'green'];
        }
    } else if ($gap_yes_no == 'No') {
        if ($ability_to_respond_to_solicitation >= 0 && $ability_to_respond_to_solicitation <= 25) {
            return [3, 'Low', 'orange'];
        } else if ($ability_to_respond_to_solicitation >= 26 && $ability_to_respond_to_solicitation <= 50) {
            return [4, 'Medium', 'yellow'];
        } else if ($ability_to_respond_to_solicitation >= 51 && $ability_to_respond_to_solicitation <= 75) {
            return [5, 'High', 'green'];
        } else if ($ability_to_respond_to_solicitation >= 76 && $ability_to_respond_to_solicitation <= 100) {
            return [6, 'High', 'green'];
        }
    }
    return [0, '-', ''];;
}

function calculate_risk_rating($impacts_priority, $risks_priority)
{
    if ($impacts_priority == 'low' && $risks_priority == 'low') {
        return [1, 'Low', 'yellowgreen'];
    } else if ($impacts_priority == 'low' && $risks_priority == 'medium') {
        return [2, 'Low', 'yellowgreen'];
    } else if ($impacts_priority == 'low' && $risks_priority == 'high') {
        return [3, 'Medium', 'yellow'];
    } else if ($impacts_priority == 'medium' && $risks_priority == 'low') {
        return [2, 'Low', 'yellowgreen'];
    } else if ($impacts_priority == 'medium' && $risks_priority == 'medium') {
        return [4, 'Medium', 'yellow'];
    } else if ($impacts_priority == 'medium' && $risks_priority == 'high') {
        return [6, 'High', 'red'];
    } else if ($impacts_priority == 'high' && $risks_priority == 'low') {
        return [3, 'Medium', 'yellow'];
    } else if ($impacts_priority == 'high' && $risks_priority == 'medium') {
        return [6, 'High', 'red'];
    } else if ($impacts_priority == 'high' && $risks_priority == 'high') {
        return [9, 'High', 'red'];
    }
    return [0, '-', ''];
}

function calculate_pwin($ability_to_respond_to_solicitation)
{
    if (($ability_to_respond_to_solicitation >= 83.33 && $ability_to_respond_to_solicitation <= 100)) {
        return "BID";
    }

    if (($ability_to_respond_to_solicitation >= 66.66 && $ability_to_respond_to_solicitation <= 83.33)) {
        return "Agree/Likely";
    }

    if (($ability_to_respond_to_solicitation >= 50 && $ability_to_respond_to_solicitation <= 66.66)) {
        return "Agree/Maybe";
    }

    if (($ability_to_respond_to_solicitation >= 33.33 && $ability_to_respond_to_solicitation <= 50)) {
        return "Neutral";
    }

    if (($ability_to_respond_to_solicitation >= 16.66 && $ability_to_respond_to_solicitation <= 33.33)) {
        return "Low P-win";
    }

    if (($ability_to_respond_to_solicitation >= 0 && $ability_to_respond_to_solicitation <= 16.66)) {
        return "No Bid";
    }

    return "----";
}

function pwin_table_index($pwin)
{
    if ($pwin == "BID") {
        return 0;
    } 
    
    if ($pwin == "Agree/Likely") {
        return 1;
    } 

    if ($pwin == "Agree/Maybe") {
        return 2;
    } 

     if ($pwin == "Neutral") {
        return 3;
    } 

    if ($pwin == "Low P-win") {
        return 4;
    }

    if ($pwin == "No Bid") {
        return 5;
    }

    return -1;
}

function opportunity_status_translate($id)
{
    if ($id == '' || is_null($id)) {
        return '';
    }

    $line = _l('opportunity_status_db_' . $id, '', false);

    if ($line == 'db_translate_not_found') {
        $CI = &get_instance();
        $CI->db->where('id', $id);
        $status = $CI->db->get(db_prefix() . 'opportunity_status')->row();

        return !$status ? '' : $status->name;
    }

    return $line;
}
