<?php

 if(!defined('ABSPATH')){
     die('you cant access this plugin directly');
 }

 class Taxonomy_column{
     public function __construct() {
         /* Doctor Taxonomy */
        add_filter('manage_edit-doctor_columns', [$this, 'doctor_taxonomy_column']);
        add_filter('manage_doctor_custom_column', [$this, 'add_data_to_tax'], 10, 3);
        add_filter('manage_edit-doctor_sortable_columns',  [$this, 'sort_columns']); 

         /* Disease Taxonomy */
        add_filter('manage_edit-typeofdisease_columns', [$this, 'typeofdisease_taxonomy_column']);
        add_filter('manage_typeofdisease_custom_column', [$this, 'add_data_to_tax_typeofdisease'], 10, 3);
        add_filter('manage_edit-typeofdisease_sortable_columns',  [$this, 'sort_columns_typeofdisease']); 
     }

     public function doctor_taxonomy_column($columns)
     {
         $columns['occupation'] = "Occupation";
         return $columns;
     }
     public function add_data_to_tax($content, $column, $term_id)
     {
        $term_value = get_term_meta( $term_id, '_occupation', true );
        switch ($column) {
            case 'occupation': 
                $content = $term_value; 
                break;
            default:
                break;
        }
        return $content;   
     }
    public function sort_columns($columns)
    {
        $columns['occupation'] = 'Occupation';
        return $columns;
    }
    public function typeofdisease_taxonomy_column($columns)
     {
         $columns['doctor_type'] = "Doctor Type";
         return $columns;
     }
     public function add_data_to_tax_typeofdisease($content, $column, $term_id)
     {
         	$term_value = get_term_meta( $term_id, '_profession', true );
            switch ($column) {
                case 'doctor_type': 
                    $content = $term_value; 
                    break;
                default:
                    break;
            }
            return $content;   
     }
    public function sort_columns_typeofdisease($columns)
    {
        $columns['doctor_type'] = 'Doctor Type';
        return $columns;
    }
 }
 new Taxonomy_column;