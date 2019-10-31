<?php

ini_set('max_execution_time', 0);
ini_set('memory_limit', '2048M');
defined('BASEPATH') OR exit('No direct script access allowed');

class DWH extends CI_Controller {

    /**
     * Index Page for this controller.
     *
     * Maps to the following URL
     * 		http://example.com/index.php/welcome
     * 	- or -
     * 		http://example.com/index.php/welcome/index
     * 	- or -
     * Since this controller is set as the default controller in
     * config/routes.php, it's displayed at http://example.com/
     *
     * So any other public methods not prefixed with an underscore will
     * map to /index.php/welcome/<method_name>
     * @see https://codeigniter.com/user_guide/general/urls.html
     */
    public function index() {
        $this->gender();
        $this->county();
        $this->sub_county();
        $this->condition();
        $this->consituency();
        $this->master_facility();
        $this->partner();
        $this->partner_facility();
        $this->groups();
        $this->marital_status();
        $this->language();
        $this->time();
        $this->message_types();
        $this->appointment_types();
        $this->clients();
        $this->appointments();
        $this->clnt_outgoing_msgs();
        $this->refresh_materialized_view();
        $this->clean_DOB();
    }
    

    function escape_output($string) {
        $newString = str_replace('\r\n', '<br/>', $string);
        $newString = str_replace('\n\r', '<br/>', $newString);
        $newString = str_replace('\r', '<br/>', $newString);
        $newString = str_replace('\n', '<br/>', $newString);
        $newString = str_replace('\'', '', $newString);
        return $newString;
    }
    // function insertClients(){
    //     $DWH_Ushauri = $this->load->database('post_Ushauri', TRUE);
    //     $mysqli_Ushauri = $this->load->database('mysqli_Ushauri', TRUE);  // the TRUE paramater tells CI that you'd like to return the database object.


    //     $mysql_ids = $mysqli_Ushauri->query('select id from tbl_client')->result();

    //     foreach($mysql_ids as $mysql_id){

           
    //         $check_existance = $DWH_Ushauri->query("select id from tbl_client where id = '$mysql_id->id'")->num_rows();

    //         if($check_existance > 0 ){
    //         }else{
    //             $get_missings = $mysqli_Ushauri->query("select * from tbl_client where id = '$mysql_id->id' ")->result();
    //             foreach($get_missings as $missing){
    //                 $status = $missing->status;
    //                 $id = $missing->id;
    //                 $ushauri_id = $missing->ushauri_id;
    //                 $group_id = $missing->group_id;
    //                 $language_id = $missing->language_id;
    //                 $facility_id = $missing->facility_id;
    //                 $clinic_number = $missing->clinic_number;
    //                 $f_name = $missing->f_name;
    //                 $m_name = $missing->m_name;
    //                 $l_name = $missing->l_name;
    //                 $created_by = $missing->created_by;
    //                 $updated_by = $missing->updated_by;
    //                 $created_at = $missing->created_at;
    //                 $updated_at = $missing->updated_at;
    //                 $dob = $missing->dob;
    //                 $client_status = $missing->client_status;
    //                 $txt_frequency = $missing->txt_frequency;
    //                 $txt_time = $missing->txt_time;
    //                 $phone_no = $missing->phone_no;
    //                 $alt_phone_no = $missing->alt_phone_no;
    //                 $shared_no_name = $missing->shared_no_name;
    //                 $smsenable = $missing->smsenable;
    //                 $partner_id = $missing->partner_id;
    //                 $mfl_code = $missing->mfl_code;
    //                 $gender = $missing->gender;
    //                 $marital = $missing->marital;
    //                 $enrollment_date = $missing->enrollment_date;
    //                 $art_date = $missing->art_date;
    //                 $wellness_enable = $missing->wellness_enable;
    //                 $motivational_enable = $missing->motivational_enable;
    //                 $client_type = $missing->client_type;
    //                 $prev_clinic = $missing->prev_clinic;
    //                 $transfer_date = $missing->transfer_date;
    //                 $entry_point = $missing->entry_point;
    //                 $welcome_sent = $missing->welcome_sent;
    //                 $stable = $missing->stable;
    //                 $physical_address = $missing->physical_address;
    //                 $consent_date = $missing->consent_date;

    //                 $data = array(
    //                     'id' => $mysql_id->id,
    //                     'group_id' => $group_id,
    //                     'language_id' => $language_id,
    //                     'facility_id' => $facility_id,
    //                     'clinic_number' => $clinic_number,
    //                     'f_name' => $f_name,
    //                     'm_name' => $m_name,
    //                     'l_name' => $l_name,
    //                     'status' => $status,
    //                     'created_by' => $created_by,
    //                     'updated_by' => $updated_by,
    //                     'created_at' => $created_at,
    //                     'updated_at' => $updated_at,
    //                     'dob' => $dob,
    //                     'client_status' => $client_status,
    //                     'txt_frequency' => '168',
    //                     'txt_time' => $txt_time,
    //                     'phone_no' => $phone_no,
    //                     'alt_phone_no' => $alt_phone_no,
    //                     'shared_no_name' => $shared_no_name,
    //                     'smsenable' => $smsenable,
    //                     'partner_id' => $partner_id,
    //                     'mfl_code' => $mfl_code,
    //                     'gender' => $gender,
    //                     'marital' => $marital,
    //                     'enrollment_date' => $enrollment_date,
    //                     'art_date' => $art_date,
    //                     'wellness_enable' => $wellness_enable,
    //                     'motivational_enable' => $motivational_enable,
    //                     'client_type' => $client_type,
    //                     'prev_clinic' => $prev_clinic,
    //                     'transfer_date' => $transfer_date,
    //                     'entry_point' => $entry_point,
    //                     'welcome_sent' => $welcome_sent,
    //                     'stable' => $stable,
    //                     'physical_address' => $physical_address,
    //                     'consent_date' => $consent_date);

    //                     $insert =  $DWH_Ushauri->insert('tbl_client', $data);

    //                if($insert){
    //                 echo "Inserted";
    //                }else{
    //                    echo "Found but not inserted";
    //                }
    //             }
    //         }

    //     }
    // }

    public function partner() {
        $DWH_Ushauri = $this->load->database('post_Ushauri', TRUE);

        $mysqli_Ushauri = $this->load->database('mysqli_Ushauri', TRUE); // the TRUE paramater tells CI that you'd like to return the database object.

        $get_last_added_consintuecy = $DWH_Ushauri->query('Select MAX(id) AS id from tbl_partner LIMIT 1');
        if ($get_last_added_consintuecy->num_rows() > 0) {


            foreach ($get_last_added_consintuecy->result() as $value) {
                $last_insered_id = $value->id;
                $mysqli_Ushauri = $this->load->database('mysqli_Ushauri', TRUE); // the TRUE paramater tells CI that you'd like to return the database object.

                $get_partner_facility = $mysqli_Ushauri->query("Select * from tbl_partner where id > '$last_insered_id'")->result();
                foreach ($get_partner_facility as $value) {
                    $status = $value->status;
                    $id = $value->id;
                    $name = $value->name;
                    $name = $this->escape_output($name);
                    $partner_type_id = $value->partner_type_id;
                    $description = $value->description;
                    $phone_no = $value->phone_no;
                    $location = $value->location;
                    $e_mail = $value->e_mail;
                    $partner_logo = $value->partner_logo;
                    $created_by = $value->created_by;
                    $updated_by = $value->updated_by;
                    $created_at = $value->created_at;
                    $updated_at = $value->updated_at;



                    //$DWH_Ushauri->trans_start();


                    $data = array(
                        'id' => $id,
                        'name' => $name,
                        'status' => $status,
                        'created_by' => $created_by,
                        'updated_by' => $updated_by,
                        'created_at' => $created_at,
                        'partner_type_id' => $partner_type_id,
                        'description' => $description,
                        'phone_no' => $phone_no,
                        'location' => $location,
                        'e_mail' => $e_mail,
                        'partner_logo' => $partner_logo
                    );

                    if ($updated_at == $created_at){
                        echo 'New Parner ID => ' . $id . '<br>';
                        $DWH_Ushauri->insert('tbl_partner', $data);
                        

                    }else{
                        echo 'Existing partner => ' . $id . '<br>';
                        $DWH_Ushauri->where('id', $id);
                        $DWH_Ushauri->update('tbl_partner', $data);
                    }

                }
            }

    }
}

    public function county() {
        $DWH_Ushauri = $this->load->database('post_Ushauri', TRUE);
        $mysqli_Ushauri = $this->load->database('mysqli_Ushauri', TRUE); // the TRUE paramater tells CI that you'd like to return the database object.


        $get_last_added_DWH = $DWH_Ushauri->query('Select MAX(id) AS id from tbl_county LIMIT 1');
        if ($get_last_added_DWH->num_rows() > 0) {
            foreach ($get_last_added_DWH->result() as $value) {
                $last_insered_id = $value->id;
                $mysqli_Ushauri = $this->load->database('mysqli_Ushauri', TRUE); // the TRUE paramater tells CI that you'd like to return the database object.

                $get_partner_facility = $mysqli_Ushauri->query("Select * from tbl_county where id > '$last_insered_id'")->result();
                foreach ($get_partner_facility as $value) {
                    $status = $value->status;
                    $id = $value->id;
                    $name = $value->name;
                    $name = $this->escape_output($name);
                    $created_by = $value->created_by;
                    $updated_by = $value->updated_by;
                    $created_at = $value->created_at;
                    $updated_at = $value->updated_at;



                    //$DWH_Ushauri->trans_start();


                    $data = array(
                        'name' => $name,
                        'id' => $id,
                        'status' => $status,
                        'created_by' => $created_by,
                        'updated_by' => $updated_by,
                        'created_at' => $created_at,
                        'updated_at' => $updated_at
                    );
                    if ($updated_at == $created_at){
                        echo 'New County ID => ' . $id . '<br>';
                        $DWH_Ushauri->insert('tbl_county', $data);
                        

                    }else{
                        echo 'Existing county => ' . $id . '<br>';
                        $DWH_Ushauri->where('id', $id);
                        $DWH_Ushauri->update('tbl_county', $data);
                    }

                  
                }
            }



        //     
        }
    }

    public function consituency() {
        $DWH_Ushauri = $this->load->database('post_Ushauri', TRUE);
        $mysqli_Ushauri = $this->load->database('mysqli_Ushauri', TRUE); // the TRUE paramater tells CI that you'd like to return the database object.

        $get_last_added_consintuecy = $DWH_Ushauri->query('Select MAX(id) AS id from tbl_consituency LIMIT 1');
        if ($get_last_added_consintuecy->num_rows() > 0) {
            foreach ($get_last_added_consintuecy->result() as $value) {
                $last_insered_id = $value->id;
                $mysqli_Ushauri = $this->load->database('mysqli_Ushauri', TRUE); // the TRUE paramater tells CI that you'd like to return the database object.

                $get_partner_facility = $mysqli_Ushauri->query("Select * from tbl_consituency where id > '$last_insered_id'")->result();
                foreach ($get_partner_facility as $value) {
                    $status = $value->status;
                    $id = $value->id;
                    $name = $value->name;
                    $name = $this->escape_output($name);
                    $created_by = $value->created_by;
                    $updated_by = $value->updated_by;
                    $created_at = $value->created_at;
                    $updated_at = $value->updated_at;



                    $DWH_Ushauri->trans_start();


                    $data = array(
                        'id' => $id,
                        'name' => $name,
                        'status' => $status,
                        'created_by' => $created_by,
                        'updated_by' => $updated_by,
                        'created_at' => $created_at,
                        'updated_at' => $updated_at
                    );

                    if ($updated_at == $created_at){
                        echo 'New Constituency ID => ' . $id . '<br>';
                        $DWH_Ushauri->insert('tbl_consituency', $data);
                        

                    }else{
                        echo 'Existing Constituency => ' . $id . '<br>';
                        $DWH_Ushauri->where('id', $id);
                        $DWH_Ushauri->update('tbl_consituency', $data);
                    }

                    // $DWH_Ushauri->insert('tbl_consituency', $data);

                    // $DWH_Ushauri->trans_complete();
                    // if ($DWH_Ushauri->trans_status() === FALSE) {
                        
                    // } else {
                        
                    // }
                }
            }
    }
}

    public function sub_county() {
        $DWH_Ushauri = $this->load->database('post_Ushauri', TRUE);

        $mysqli_Ushauri = $this->load->database('mysqli_Ushauri', TRUE); // the TRUE paramater tells CI that you'd like to return the database object.

        $get_last_added_consintuecy = $DWH_Ushauri->query('Select MAX(id) AS id from tbl_sub_county LIMIT 1');
        if ($get_last_added_consintuecy->num_rows() > 0) {
            foreach ($get_last_added_consintuecy->result() as $value) {
                $last_insered_id = $value->id;
                $mysqli_Ushauri = $this->load->database('mysqli_Ushauri', TRUE); // the TRUE paramater tells CI that you'd like to return the database object.

                $get_partner_facility = $mysqli_Ushauri->query("Select * from tbl_sub_county where id > '$last_insered_id'")->result();
                foreach ($get_partner_facility as $value) {
                    $status = $value->status;
                    $id = $value->id;
                    $name = $value->name;
                    $name = $this->escape_output($name);
                    $created_by = $value->created_by;
                    $updated_by = $value->updated_by;
                    $created_at = $value->created_at;
                    $updated_at = $value->updated_at;



                    //$DWH_Ushauri->trans_start();


                    $data = array(
                        'name' => $name,
                        'id' => $id,
                        'status' => $status,
                        'created_by' => $created_by,
                        'updated_by' => $updated_by,
                        'created_at' => $created_at,
                        'updated_at' => $updated_at
                    );

                    if ($updated_at == $created_at){
                        echo 'New Sub County ID => ' . $id . '<br>';
                        $DWH_Ushauri->insert('tbl_sub_county', $data);
                        

                    }else{
                        echo 'Existing sub county => ' . $id . '<br>';
                        $DWH_Ushauri->where('id', $id);
                        $DWH_Ushauri->update('tbl_sub_county', $data);
                    }

                }
            }

                   
        }
    }

    public function master_facility() {
        $DWH_Ushauri = $this->load->database('post_Ushauri', TRUE);
        $mysqli_Ushauri = $this->load->database('mysqli_Ushauri', TRUE); // the TRUE paramater tells CI that you'd like to return the database object.


        $get_last_added_DWH = $DWH_Ushauri->query('Select MAX(id) AS id from tbl_master_facility LIMIT 1');
        if ($get_last_added_DWH->num_rows() > 0) {
            foreach ($get_last_added_DWH->result() as $value) {
                $last_insered_id = $value->id;
                $mysqli_Ushauri = $this->load->database('mysqli_Ushauri', TRUE); // the TRUE paramater tells CI that you'd like to return the database object.

                $get_new_trans = $mysqli_Ushauri->query("Select * from tbl_master_facility where id > '$last_insered_id'")->result();
                foreach ($get_new_trans as $value) {
                    $id = $value->id;
                    $code = $value->code;
                    $name = $value->name;
                    $reg_number = $value->reg_number;
                    $name = $this->escape_output($name);
                    $keph_level = $value->keph_level;
                    $facility_type = $value->facility_type;
                    $owner = $value->owner;
                    $regulatory_body = $value->regulatory_body;
                    $beds = $value->beds;
                    $cots = $value->cots;
                    $county_id = $value->county_id;
                    $consituency_id = $value->consituency_id;
                    $sub_county_id = $value->Sub_County_ID;
                    $ward_id = $value->Ward_id;
                    $operational_status = $value->operational_status;
                    $open_whole_date = $value->Open_whole_day;
                    $open_public_holidays = $value->Open_public_holidays;
                    $open_weekends = $value->Open_weekends;
                    $open_late_night = $value->Open_late_night;
                    $service_names = $value->Service_names;
                    $approved = $value->Approved;
                    $public_visible = $value->Public_visible;
                    $closed = $value->Closed;
                    $assigned = $value->assigned;
                    $updated_by = $value->updated_by;
                    $created_by = $value->created_by;
                    $lat = $value->lat;
                    $lng = $value->lng;
                    $id = $value->id;
                    $created_at = $value->created_at;
                    $updated_at = $value->updated_at;



                    //$DWH_Ushauri->trans_start();


                    $data = array(
                        'id' => $id,
                        'code' => $code,
                        'name' => $name,
                        'reg_number' => $reg_number,
                        'keph_level' => $keph_level,
                        'faciliy_type' => $facility_type,
                        'owner' => $owner,
                        'regulatory_body' => $regulatory_body,
                        'beds' => $beds,
                        'cots' => $cots,
                        'county_id' => $county_id,
                        'consituency_id' => $consituency_id,
                        'sub_county_id' => $sub_county_id,
                        'ward_id' => $ward_id,
                        'operational_status' => $operational_status,
                        'open_whole_date' => $open_whole_date,
                        'open_public_holidays' => $open_public_holidays,
                        'open_weekends' => $open_weekends,
                        'open_late_night' => $open_late_night,
                        'service_names' => $service_names,
                        'approved' => $approved,
                        'public_visible' => $public_visible,
                        'closed' => $closed,
                        'assigned' => $assigned,
                        'lat' => $lat,
                        'lng' => $lng
                    );

                    if ($updated_at == $created_at){
                        echo 'New Facility ID => ' . $id . '<br>';
                        $DWH_Ushauri->insert('tbl_master_facility', $data);
                        

                    }else{
                        echo 'Existing facility => ' . $id . '<br>';
                        $DWH_Ushauri->where('id', $id);
                        $DWH_Ushauri->update('tbl_master_facility', $data);
                    }
                }
            }

    }
}

    

    public function partner_facility() {
        $DWH_Ushauri = $this->load->database('post_Ushauri', TRUE);
        $mysqli_Ushauri = $this->load->database('mysqli_Ushauri', TRUE); // the TRUE paramater tells CI that you'd like to return the database object.


        $get_last_added_DWH = $DWH_Ushauri->query('Select MAX(id) AS id from tbl_partner_facility LIMIT 1');
        if ($get_last_added_DWH->num_rows() > 0) {
            foreach ($get_last_added_DWH->result() as $value) {
                $last_insered_id = $value->id;
                $mysqli_Ushauri = $this->load->database('mysqli_Ushauri', TRUE); // the TRUE paramater tells CI that you'd like to return the database object.

                $get_new_trans = $mysqli_Ushauri->query("Select * from tbl_partner_facility where id > '$last_insered_id'")->result();
                foreach ($get_new_trans as $value) {
                    $id = $value->id;
                    $mfl_code = $value->mfl_code;
                    $partner_id = $value->partner_id;
                    $is_approved = $value->is_approved;
                    $reason = $value->reason;
                    $avg_clients = $value->avg_clients;
                    $county_id = $value->county_id;
                    $sub_county_id = $value->sub_county_id;
                    $updated_by = $value->updated_by;
                    $created_by = $value->created_by;
                    $id = $value->id;
                    $created_at = $value->created_at;
                    $updated_at = $value->updated_at;



                    //$DWH_Ushauri->trans_start();


                    $data = array(
                        'id' => $id,
                        'mfl_code' => $mfl_code,
                        'partner_id' => $partner_id,
                        'is_approved' => $is_approved,
                        'reason' => $reason,
                        'avg_clients' => $avg_clients,
                        'county_id' => $county_id,
                        'updated_by' => $updated_by,
                        'created_by' => $created_by,
                        'id' => $id,
                        'created_at' => $created_at,
                        'updated_at' => $updated_at
                    );

                    if ($updated_at == $created_at){
                        echo 'New Partner Facility ID => ' . $id . '<br>';
                        $DWH_Ushauri->insert('tbl_partner_facility', $data);
                        

                    }else{
                        echo 'Existing partner facility => ' . $id . '<br>';
                        $DWH_Ushauri->where('id', $id);
                        $DWH_Ushauri->update('tbl_partner_facility', $data);
                    }
                }
            }


            
        }
    }

    public function gender() {
        $DWH_Ushauri = $this->load->database('post_Ushauri', TRUE);
        $mysqli_Ushauri = $this->load->database('mysqli_Ushauri', TRUE); // the TRUE paramater tells CI that you'd like to return the database object.

        $get_last_added_DWH = $DWH_Ushauri->query('Select MAX(id) AS id from tbl_gender LIMIT 1');
        
        if ($get_last_added_DWH->num_rows() > 0) {
            
            foreach ($get_last_added_DWH->result() as $value) {
                $last_insered_id = $value->id;
                $mysqli_Ushauri = $this->load->database('mysqli_Ushauri', TRUE); // the TRUE paramater tells CI that you'd like to return the database object.

                $get_partner_facility = $mysqli_Ushauri->query("Select * from tbl_gender where id > '$last_insered_id'")->result();
                foreach ($get_partner_facility as $value) {
                    $status = $value->status;
                    $id = $value->id;
                    $name = $value->name;
                    $name = $this->escape_output($name);
                    $created_by = $value->created_by;
                    $updated_by = $value->updated_by;
                    $created_at = $value->created_at;
                    $updated_at = $value->updated_at;



                    //$DWH_Ushauri->trans_start();


                    $data = array(
                        'name' => $name,
                        'id' => $id,
                        'status' => $status,
                        'created_by' => $created_by,
                        'updated_by' => $updated_by,
                        'created_at' => $created_at,
                        'updated_at' => $updated_at
                    );

                    if ($updated_at == $created_at){
                        echo 'New Gender ID => ' . $id . '<br>';
                        $DWH_Ushauri->insert('tbl_gender', $data);
                        

                    }else{
                        echo 'Existing gender => ' . $id . '<br>';
                        $DWH_Ushauri->where('id', $id);
                        $DWH_Ushauri->update('tbl_gender', $data);
                    }

                }
            }

            
        } 
    }

    public function condition() {
        $DWH_Ushauri = $this->load->database('post_Ushauri', TRUE);
        $mysqli_Ushauri = $this->load->database('mysqli_Ushauri', TRUE); // the TRUE paramater tells CI that you'd like to return the database object.


        $get_last_added_DWH = $DWH_Ushauri->query('Select MAX(id) AS id from tbl_condition LIMIT 1');
        if ($get_last_added_DWH->num_rows() > 0) {
            foreach ($get_last_added_DWH->result() as $value) {
                $last_insered_id = $value->id;
                $mysqli_Ushauri = $this->load->database('mysqli_Ushauri', TRUE); // the TRUE paramater tells CI that you'd like to return the database object.

                $get_partner_facility = $mysqli_Ushauri->query("Select * from tbl_condition where id > '$last_insered_id'")->result();
                foreach ($get_partner_facility as $value) {
                    $status = $value->status;
                    $id = $value->id;
                    $name = $value->name;
                    $name = $this->escape_output($name);
                    $created_by = $value->created_by;
                    $updated_by = $value->updated_by;
                    $created_at = $value->created_at;
                    $updated_at = $value->updated_at;



                    //$DWH_Ushauri->trans_start();


                    $data = array(
                        'name' => $name,
                        'id' => $id,
                        'status' => $status,
                        'created_by' => $created_by,
                        'updated_by' => $updated_by,
                        'created_at' => $created_at,
                        'updated_at' => $updated_at
                    );

                    if ($updated_at == $created_at){
                        echo 'New Condition ID => ' . $id . '<br>';
                        $DWH_Ushauri->insert('tbl_condition', $data);
                        

                    }else{
                        echo 'Existing condition => ' . $id . '<br>';
                        $DWH_Ushauri->where('id', $id);
                        $DWH_Ushauri->update('tbl_condition', $data);
                    }
                }
            }
        } 
    }

    public function groups() {
        $DWH_Ushauri = $this->load->database('post_Ushauri', TRUE);
        $mysqli_Ushauri = $this->load->database('mysqli_Ushauri', TRUE); // the TRUE paramater tells CI that you'd like to return the database object.


        $get_last_added_DWH = $DWH_Ushauri->query('Select MAX(id) AS id from tbl_groups LIMIT 1');
        if ($get_last_added_DWH->num_rows() > 0) {
            foreach ($get_last_added_DWH->result() as $value) {
                $last_insered_id = $value->id;
                $mysqli_Ushauri = $this->load->database('mysqli_Ushauri', TRUE); // the TRUE paramater tells CI that you'd like to return the database object.

                $get_partner_facility = $mysqli_Ushauri->query("Select * from tbl_groups where id > '$last_insered_id'")->result();
                foreach ($get_partner_facility as $value) {
                    $status = $value->status;
                    $id = $value->id;
                    $name = $value->name;
                    $name = $this->escape_output($name);
                    $created_by = $value->created_by;
                    $updated_by = $value->updated_by;
                    $created_at = $value->created_at;
                    $updated_at = $value->updated_at;



                    //$DWH_Ushauri->trans_start();


                    $data = array(
                        'id' => $id,
                        'name' => $name,
                        'status' => $status,
                        'created_by' => $created_by,
                        'updated_by' => $updated_by,
                        'created_at' => $created_at,
                        'updated_at' => $updated_at
                    );

                    if ($updated_at == $created_at){
                        echo 'New Group ID => ' . $id . '<br>';
                        $DWH_Ushauri->insert('tbl_groups', $data);
                        

                    }else{
                        echo 'Existing group => ' . $id . '<br>';
                        $DWH_Ushauri->where('id', $id);
                        $DWH_Ushauri->update('tbl_groups', $data);
                    }
                }
            }
        } 
    }

    public function language() {
        $DWH_Ushauri = $this->load->database('post_Ushauri', TRUE);

        $mysqli_Ushauri = $this->load->database('mysqli_Ushauri', TRUE); // the TRUE paramater tells CI that you'd like to return the database object.

        $get_last_added_DWH = $DWH_Ushauri->query('Select MAX(id) AS id from tbl_language LIMIT 1');
        if ($get_last_added_DWH->num_rows() > 0) {
            foreach ($get_last_added_DWH->result() as $value) {
                $last_insered_id = $value->id;
                $mysqli_Ushauri = $this->load->database('mysqli_Ushauri', TRUE); // the TRUE paramater tells CI that you'd like to return the database object.

                $get_partner_facility = $mysqli_Ushauri->query("Select * from tbl_gender where id > '$last_insered_id'")->result();
                foreach ($get_partner_facility as $value) {
                    $status = $value->status;
                    $id = $value->id;
                    $name = $value->name;
                    $name = $this->escape_output($name);
                    $created_by = $value->created_by;
                    $updated_by = $value->updated_by;
                    $created_at = $value->created_at;
                    $updated_at = $value->updated_at;



                    //$DWH_Ushauri->trans_start();


                    $data = array(
                        'id' => $id,
                        'name' => $name,
                        'status' => $status,
                        'created_by' => $created_by,
                        'updated_by' => $updated_by,
                        'created_at' => $created_at,
                        'updated_at' => $updated_at
                    );

                    if ($updated_at == $created_at){
                        echo 'New Language ID => ' . $id . '<br>';
                        $DWH_Ushauri->insert('tbl_language', $data);
                        

                    }else{
                        echo 'Existing language => ' . $id . '<br>';
                        $DWH_Ushauri->where('id', $id);
                        $DWH_Ushauri->update('tbl_language', $data);
                    }

                    // $DWH_Ushauri->insert('tbl_language', $data);

                    // $DWH_Ushauri->trans_complete();
                    // if ($DWH_Ushauri->trans_status() === FALSE) {
                        
                    // } else {
                        
                    // }
                }
            }
        } 
    }

    public function message_types() {
        $DWH_Ushauri = $this->load->database('post_Ushauri', TRUE);

        $mysqli_Ushauri = $this->load->database('mysqli_Ushauri', TRUE); // the TRUE paramater tells CI that you'd like to return the database object.

        $get_last_added_DWH = $DWH_Ushauri->query('Select MAX(id) AS id from tbl_message_types LIMIT 1');
        if ($get_last_added_DWH->num_rows() > 0) {
            foreach ($get_last_added_DWH->result() as $value) {
                $last_insered_id = $value->id;
                $mysqli_Ushauri = $this->load->database('mysqli_Ushauri', TRUE); // the TRUE paramater tells CI that you'd like to return the database object.

                $get_partner_facility = $mysqli_Ushauri->query("Select * from tbl_message_types where id > '$last_insered_id'")->result();
                foreach ($get_partner_facility as $value) {
                    $status = $value->status;
                    $id = $value->id;
                    $name = $value->name;
                    $name = $this->escape_output($name);
                    $created_by = $value->created_by;
                    $updated_by = $value->updated_by;
                    $created_at = $value->created_at;
                    $updated_at = $value->updated_at;



                    //$DWH_Ushauri->trans_start();


                    $data = array(
                        'id' => $id,
                        'name' => $name,
                        'status' => $status,
                        'created_by' => $created_by,
                        'updated_by' => $updated_by,
                        'created_at' => $created_at,
                        'updated_at' => $updated_at
                    );

                    if ($updated_at == $created_at){
                        echo 'New Message Type ID => ' . $id . '<br>';
                        $DWH_Ushauri->insert('tbl_message_types', $data);
                        

                    }else{
                        echo 'Existing message type => ' . $id . '<br>';
                        $DWH_Ushauri->where('id', $id);
                        $DWH_Ushauri->update('tbl_message_types', $data);
                    }

                }
            }
        } 
    }

    public function marital_status() {


        $DWH_Ushauri = $this->load->database('post_Ushauri', TRUE);
        $mysqli_Ushauri = $this->load->database('mysqli_Ushauri', TRUE); // the TRUE paramater tells CI that you'd like to return the database object.


        $get_last_added_DWH = $DWH_Ushauri->query('Select MAX(id) AS id from tbl_marital_status LIMIT 1');
        if ($get_last_added_DWH->num_rows() > 0) {
            foreach ($get_last_added_DWH->result() as $value) {
                $last_insered_id = $value->id;
                $mysqli_Ushauri = $this->load->database('mysqli_Ushauri', TRUE); // the TRUE paramater tells CI that you'd like to return the database object.

                $get_partner_facility = $mysqli_Ushauri->query("Select * from tbl_marital_status where id > '$last_insered_id'")->result();
                foreach ($get_partner_facility as $value) {
                    $status = $value->status;
                    $id = $value->id;
                    $marital = $value->marital;
                    $marital = $this->escape_output($marital);
                    $created_by = $value->created_by;
                    $updated_by = $value->updated_by;
                    $created_at = $value->created_at;
                    $updated_at = $value->updated_at;



                    //$DWH_Ushauri->trans_start();


                    $data = array(
                        'id' => $id,
                        'marital' => $marital,
                        'status' => $status,
                        'created_by' => $created_by,
                        'updated_by' => $updated_by,
                        'created_at' => $created_at,
                        'updated_at' => $updated_at
                    );

                    if ($updated_at == $created_at){
                        echo 'New Marital ID => ' . $id . '<br>';
                        $DWH_Ushauri->insert('tbl_marital_status', $data);
                        

                    }else{
                        echo 'Existing marital => ' . $id . '<br>';
                        $DWH_Ushauri->where('id', $id);
                        $DWH_Ushauri->update('tbl_marital_status', $data);
                    }

                }
            }
        } 
    }

    public function time() {
        $DWH_Ushauri = $this->load->database('post_Ushauri', TRUE);
        $mysqli_Ushauri = $this->load->database('mysqli_Ushauri', TRUE); // the TRUE paramater tells CI that you'd like to return the database object.


        $get_last_added_DWH = $DWH_Ushauri->query('Select MAX(id) AS id from tbl_time LIMIT 1');
        if ($get_last_added_DWH->num_rows() > 0) {
            foreach ($get_last_added_DWH->result() as $value) {
                $last_insered_id = $value->id;
                $mysqli_Ushauri = $this->load->database('mysqli_Ushauri', TRUE); // the TRUE paramater tells CI that you'd like to return the database object.

                $get_partner_facility = $mysqli_Ushauri->query("Select * from tbl_time where id > '$last_insered_id'")->result();
                foreach ($get_partner_facility as $value) {
                    $status = $value->status;
                    $id = $value->id;
                    $name = $value->name;
                    $name = $this->escape_output($name);
                    $created_by = $value->created_by;
                    $updated_by = $value->updated_by;
                    $created_at = $value->created_at;
                    $updated_at = $value->updated_at;



                    //$DWH_Ushauri->trans_start();


                    $data = array(
                        'id' => $id,
                        'name' => $name,
                        'status' => $status,
                        'created_by' => $created_by,
                        'updated_by' => $updated_by,
                        'created_at' => $created_at,
                        'updated_at' => $updated_at
                    );

                    if ($updated_at == $created_at){
                        echo 'New Time ID => ' . $id . '<br>';
                        $DWH_Ushauri->insert('tbl_time', $data);
                        

                    }else{
                        echo 'Existing time => ' . $id . '<br>';
                        $DWH_Ushauri->where('id', $id);
                        $DWH_Ushauri->update('tbl_time', $data);
                    }

                }
            }
        } 
    }

    public function appointment_types() {
        $DWH_Ushauri = $this->load->database('post_Ushauri', TRUE);

        $mysqli_Ushauri = $this->load->database('mysqli_Ushauri', TRUE); // the TRUE paramater tells CI that you'd like to return the database object.

        $get_last_added_DWH = $DWH_Ushauri->query('Select MAX(id) AS id from tbl_appointment_types LIMIT 1');
        if ($get_last_added_DWH->num_rows() > 0) {
            foreach ($get_last_added_DWH->result() as $value) {
                $last_insered_id = $value->id;
                $mysqli_Ushauri = $this->load->database('mysqli_Ushauri', TRUE); // the TRUE paramater tells CI that you'd like to return the database object.

                $get_partner_facility = $mysqli_Ushauri->query("Select * from tbl_appointment_types where id > '$last_insered_id'")->result();
                foreach ($get_partner_facility as $value) {
                    $status = $value->status;
                    $id = $value->id;
                    $name = $value->name;
                    $name = $this->escape_output($name);
                    $created_by = $value->created_by;
                    $updated_by = $value->updated_by;
                    $created_at = $value->created_at;
                    $updated_at = $value->updated_at;



                    //$DWH_Ushauri->trans_start();


                    $data = array(
                        'id' => $id,
                        'name' => $name,
                        'status' => $status,
                        'created_by' => $created_by,
                        'updated_by' => $updated_by,
                        'created_at' => $created_at,
                        'updated_at' => $updated_at
                    );

                    if ($updated_at == $created_at){
                        echo 'New Appointment type ID => ' . $id . '<br>';
                        $DWH_Ushauri->insert('tbl_appointment_types', $data);
                        

                    }else{
                        echo 'Existing appointment type => ' . $id . '<br>';
                        $DWH_Ushauri->where('id', $id);
                        $DWH_Ushauri->update('tbl_appointment_types', $data);
                    }
                }
            }
        } 
    }

    function clients() {


        $DWH_Ushauri = $this->load->database('post_Ushauri', TRUE);
        $mysqli_Ushauri = $this->load->database('mysqli_Ushauri', TRUE); // the TRUE paramater tells CI that you'd like to return the database object.



        $get_last_added_DWH = $DWH_Ushauri->query('Select MAX(id) AS id from tbl_client LIMIT 1');
        if ($get_last_added_DWH->num_rows() > 0) {
            echo 'Client Found ....';
            foreach ($get_last_added_DWH->result() as $value) {
                $last_insered_id = $value->id;
                echo 'ID => ' . $last_insered_id . '<br>';
                $mysqli_Ushauri = $this->load->database('mysqli_Ushauri', TRUE); // the TRUE paramater tells CI that you'd like to return the database object.

                $get_partner_facility = $mysqli_Ushauri->query("Select * from tbl_client where id > '$last_insered_id'")->result();
                foreach ($get_partner_facility as $value) {
                    $status = $value->status;
                    $id = $value->id;
                    $ushauri_id = $value->ushauri_id;
                    $group_id = $value->group_id;
                    $language_id = $value->language_id;
                    $facility_id = $value->facility_id;
                    $clinic_number = $value->clinic_number;
                    $f_name = $value->f_name;
                    $m_name = $value->m_name;
                    $l_name = $value->l_name;
                    $created_by = $value->created_by;
                    $updated_by = $value->updated_by;
                    $created_at = $value->created_at;
                    $updated_at = $value->updated_at;
                    $dob = $value->dob;
                    $client_status = $value->client_status;
                    $txt_frequency = $value->txt_frequency;
                    $txt_time = $value->txt_time;
                    $phone_no = $value->phone_no;
                    $alt_phone_no = $value->alt_phone_no;
                    $shared_no_name = $value->shared_no_name;
                    $smsenable = $value->smsenable;
                    $partner_id = $value->partner_id;
                    $mfl_code = $value->mfl_code;
                    $gender = $value->gender;
                    $marital = $value->marital;
                    $enrollment_date = $value->enrollment_date;
                    $art_date = $value->art_date;
                    $wellness_enable = $value->wellness_enable;
                    $motivational_enable = $value->motivational_enable;
                    $client_type = $value->client_type;
                    $prev_clinic = $value->prev_clinic;
                    $transfer_date = $value->transfer_date;
                    $entry_point = $value->entry_point;
                    $welcome_sent = $value->welcome_sent;
                    $stable = $value->stable;
                    $physical_address = $value->physical_address;
                    $consent_date = $value->consent_date;

                    $data = array(
                        'ushauri_id' => $ushauri_id,
                        'id' => $id,
                        'group_id' => $group_id,
                        'language_id' => $language_id,
                        'facility_id' => $facility_id,
                        'clinic_number' => $clinic_number,
                        'f_name' => $f_name,
                        'm_name' => $m_name,
                        'l_name' => $l_name,
                        'status' => $status,
                        'created_by' => $created_by,
                        'updated_by' => $updated_by,
                        'created_at' => $created_at,
                        'updated_at' => $updated_at,
                        'dob' => $dob,
                        'client_status' => $client_status,
                        'txt_frequency' => '168',
                        'txt_time' => $txt_time,
                        'phone_no' => $phone_no,
                        'alt_phone_no' => $alt_phone_no,
                        'shared_no_name' => $shared_no_name,
                        'smsenable' => $smsenable,
                        'partner_id' => $partner_id,
                        'mfl_code' => $mfl_code,
                        'gender' => $gender,
                        'marital' => $marital,
                        'enrollment_date' => $enrollment_date,
                        'art_date' => $art_date,
                        'wellness_enable' => $wellness_enable,
                        'motivational_enable' => $motivational_enable,
                        'client_type' => $client_type,
                        'prev_clinic' => $prev_clinic,
                        'transfer_date' => $transfer_date,
                        'entry_point' => $entry_point,
                        'welcome_sent' => $welcome_sent,
                        'stable' => $stable,
                        'physical_address' => $physical_address,
                        'consent_date' => $consent_date
                    );

                    



                    if ($updated_at == $created_at){
                        echo 'New Client ID => ' . $id . '<br>';
                        $DWH_Ushauri->insert('tbl_client', $data);
                        

                    }else{
                        echo 'Existing client => ' . $id . '<br>';
                        $DWH_Ushauri->where('id', $id);
                        $DWH_Ushauri->update('tbl_client', $data);
                    }

                    // $DWH_Ushauri->trans_start();



                    

                    // $DWH_Ushauri->insert('tbl_client', $data);

                    //$DWH_Ushauri->trans_complete();
                    // if ($DWH_Ushauri->trans_status() === FALSE) {
                        
                    // } else {
                        
                    // }
                }
            }
        } 
        // else {
        //     echo "Client not found ...";
        //     $get_updated_records = $mysqli_Ushauri->query('Select * from tbl_client WHERE DATE(updated_at) > DATE_SUB(CURDATE(), INTERVAL 1 DAY) ');
        //     if ($get_updated_records->num_rows() > 0) {
        //         foreach ($get_updated_records->result() as $value) {
        //             $status = $value->status;
        //             $id = $value->id;
        //             $ushauri_id = $value->ushauri_id;
        //             $group_id = $value->group_id;
        //             $language_id = $value->language_id;
        //             $facility_id = $value->facility_id;
        //             $clinic_number = $value->clinic_number;
        //             $f_name = $value->f_name;
        //             $m_name = $value->m_name;
        //             $l_name = $value->l_name;
        //             $created_by = $value->created_by;
        //             $updated_by = $value->updated_by;
        //             $created_at = $value->created_at;
        //             $updated_at = $value->updated_at;
        //             $dob = $value->dob;
        //             $client_status = $value->client_status;
        //             $txt_frequency = $value->txt_frequency;
        //             $txt_time = $value->txt_time;
        //             $phone_no = $value->phone_no;
        //             $alt_phone_no = $value->alt_phone_no;
        //             $shared_no_name = $value->shared_no_name;
        //             $smsenable = $value->smsenable;
        //             $partner_id = $value->partner_id;
        //             $mfl_code = $value->mfl_code;
        //             $gender = $value->gender;
        //             $marital = $value->marital;
        //             $enrollment_date = $value->enrollment_date;
        //             $art_date = $value->art_date;
        //             $wellness_enable = $value->wellness_enable;
        //             $motivational_enable = $value->motivational_enable;
        //             $client_type = $value->client_type;
        //             $prev_clinic = $value->prev_clinic;
        //             $transfer_date = $value->transfer_date;
        //             $entry_point = $value->entry_point;
        //             $welcome_sent = $value->welcome_sent;
        //             $stable = $value->stable;
        //             $physical_address = $value->physical_address;
        //             $consent_date = $value->consent_date;

        //             echo 'Old Client ID => ' . $id . '<br>';


        //             $DWH_Ushauri->trans_start();


        //             $data = array(
        //                 'ushauri_id' => $ushauri_id,
        //                 'id' => $id,
        //                 'group_id' => $group_id,
        //                 'language_id' => $language_id,
        //                 'facility_id' => $facility_id,
        //                 'clinic_number' => $clinic_number,
        //                 'f_name' => $f_name,
        //                 'm_name' => $m_name,
        //                 'l_name' => $l_name,
        //                 'status' => $status,
        //                 'created_by' => $created_by,
        //                 'updated_by' => $updated_by,
        //                 'created_at' => $created_at,
        //                 'updated_at' => $updated_at,
        //                 'dob' => $dob,
        //                 'client_status' => $client_status,
        //                 'txt_frequency' => $txt_frequency,
        //                 'txt_time' => $txt_time,
        //                 'phone_no' => $phone_no,
        //                 'alt_phone_no' => $alt_phone_no,
        //                 'shared_no_name' => $shared_no_name,
        //                 'smsenable' => $smsenable,
        //                 'partner_id' => $partner_id,
        //                 'mfl_code' => $mfl_code,
        //                 'gender' => $gender,
        //                 'marital' => $marital,
        //                 'enrollment_date' => $enrollment_date,
        //                 'art_date' => $art_date,
        //                 'wellness_enable' => $wellness_enable,
        //                 'motivational_enable' => $motivational_enable,
        //                 'client_type' => $client_type,
        //                 'prev_clinic' => $prev_clinic,
        //                 'transfer_date' => $transfer_date,
        //                 'entry_point' => $entry_point,
        //                 'welcome_sent' => $welcome_sent,
        //                 'stable' => $stable,
        //                 'physical_address' => $physical_address,
        //                 'consent_date' => $consent_date
        //             );
        //             $DWH_Ushauri->where('id', $id);
        //             $DWH_Ushauri->update('tbl_client', $data);

        //             $DWH_Ushauri->trans_complete();
        //             if ($DWH_Ushauri->trans_status() === FALSE) {
                        
        //             } else {
                        
        //             }
        //         }
        //     } else {
        //         echo 'Do Nothing .....nothing to be updated.....<br> Bye Bye .....';
        //     }
        // }
    }

    function appointments() {


        $DWH_Ushauri = $this->load->database('post_Ushauri', TRUE);
        $mysqli_Ushauri = $this->load->database('mysqli_Ushauri', TRUE); // the TRUE paramater tells CI that you'd like to return the database object.


        $get_last_added_DWH = $DWH_Ushauri->query('Select MAX(id) AS id from tbl_appointment LIMIT 1');
        if ($get_last_added_DWH->num_rows() > 0) {
            foreach ($get_last_added_DWH->result() as $value) {
                $last_insered_id = $value->id;
                echo" Last inserted id => " . $last_insered_id . "<br>";
                $mysqli_Ushauri = $this->load->database('mysqli_Ushauri', TRUE); // the TRUE paramater tells CI that you'd like to return the database object.

                $get_partner_facility = $mysqli_Ushauri->query("Select * from tbl_appointment where id > '$last_insered_id'")->result();
                foreach ($get_partner_facility as $value) {
                    $id = $value->id;
                    $client_id = $value->client_id;
                    $appntmnt_date = $value->appntmnt_date;
                    $app_type_1 = $value->app_type_1;
                    $created_at = $value->created_at;
                    $updated_at = $value->updated_at;
                    $app_status = $value->app_status;
                    $app_msg = $value->app_msg;
                    $status = $value->status;
                    $notified = $value->notified;
                    $sent_status = $value->sent_status;
                    $created_by = $value->created_by;
                    $updated_by = $value->updated_by;
                    $entry_point = $value->entry_point;
                    $appointment_kept = $value->appointment_kept;
                    $active_app = $value->active_app;
                    $no_calls = $value->no_calls;
                    $no_msgs = $value->no_msgs;
                    $home_visits = $value->home_visits;
                    $visit_type = $value->visit_type;
                    $unscheduled_date = $value->unscheduled_date;
                    $tracer_name = $value->tracer_name;
                    $fnl_trcing_outocme = $value->fnl_trcing_outocme;
                    $fnl_outcome_dte = $value->fnl_outcome_dte;
                    $other_trcing_outcome = $value->other_trcing_outcome;

                    $data = array(
                        'id' => $id,
                        'client_id' => $client_id,
                        'appntmnt_date' => $appntmnt_date,
                        'app_type_1' => $app_type_1,
                        'created_at' => $created_at,
                        'created_by' => $created_by,
                        'updated_at' => $updated_at,
                        'updated_by' => $updated_by,
                        'app_status' => $app_status,
                        'app_msg' => $app_msg,
                        'status' => $status,
                        'notified' => $notified,
                        'sent_status' => $sent_status,
                        'entry_point' => $entry_point,
                        'appointment_kept' => $appointment_kept,
                        'active_app' => $active_app,
                        'no_calls' => $no_calls,
                        'no_msgs' => $no_msgs,
                        'home_visits' => $home_visits,
                        'visit_type' => $visit_type,
                        'unscheduled_date' => $unscheduled_date,
                        'tracer_name' => $tracer_name,
                        'fnl_trcing_outcome' => $fnl_trcing_outocme,
                        'fnl_outcome_dte' => $fnl_outcome_dte,
                        'other_trcing_outcome' => $other_trcing_outcome
                    );

                    if ($updated_at == $created_at){
                        echo 'New Appointment  ID => ' . $id . '<br>';
                        $DWH_Ushauri->insert('tbl_appointment', $data);
                        

                    }else{
                        echo 'Existing appointment => ' . $id . '<br>';
                        $DWH_Ushauri->where('id', $id);
                        $DWH_Ushauri->update('tbl_appointment', $data);
                    }
                }
            }
        } 
        
    }

    function clnt_outgoing_msgs() {


        $mysqli_Ushauri = $this->load->database('mysqli_Ushauri', TRUE); // the TRUE paramater tells CI that you'd like to return the database object.
        $DWH_Ushauri = $this->load->database('post_Ushauri', TRUE);


        $get_last_added_DWH = $DWH_Ushauri->query('Select MAX(id) AS id from tbl_clnt_outgoing LIMIT 1');
        if ($get_last_added_DWH->num_rows() > 0) {
            foreach ($get_last_added_DWH->result() as $value) {
                $last_insered_id = $value->id;
                echo "Last Insert ID => " . $last_insered_id;
                $mysqli_Ushauri = $this->load->database('mysqli_Ushauri', TRUE); // the TRUE paramater tells CI that you'd like to return the database object.

                $get_partner_facility = $mysqli_Ushauri->query("Select * from tbl_clnt_outgoing where id > '$last_insered_id'")->result();
                foreach ($get_partner_facility as $value) {
                    $id = $value->id;
                    $ushauri_id = $value->id;
                    $destination = $value->destination;
                    $source = $value->source;
                    $msg = $value->msg;
                    $updated_at = $value->updated_at;
                    $created_at = $value->created_at;
                    $status = $value->status;
                    $responded = $value->responded;
                    $message_type_id = $value->message_type_id;
                    $content_id = $value->content_id;
                    $recepient_type = $value->recepient_type;
                    $created_by = $value->created_by;
                    $updated_by = $value->updated_by;
                    $is_deleted = $value->is_deleted;
                    $clnt_usr_id = $value->clnt_usr_id;

                    // echo 'New Msg ID => ' . $id . '<br>';
                    // echo 'Created At => '.$created_at.'<br>';
                    // echo 'Updated At => '.$updated_at.'<br>';
                    $data = array(
                        'id' => $id,
                        'ushauri_id' => $ushauri_id,
                        'destination' => $destination,
                        'source' => $source,
                        'msg' => $msg,
                        'updated_at' => $updated_at,
                        'created_at' => $created_at,
                        'status' => $status,
                        'responded' => $responded,
                        'message_type_id' => $message_type_id,
                        'content_id' => $content_id,
                        'recepient_type' => $recepient_type,
                        'created_by' => $created_by,
                        'updated_by' => $updated_by,
                        'is_deleted' => $is_deleted,
                        'clnt_usr_id' => $clnt_usr_id
                    );

                    if ($updated_at == $created_at){
                        echo 'New Msg ID => ' . $id . '<br>';
                        $DWH_Ushauri->insert('tbl_clnt_outgoing', $data);
                        

                    }else{
                        echo 'Existing Msg => ' . $id . '<br>';
                        $DWH_Ushauri->where('id', $id);
                        $DWH_Ushauri->update('tbl_clnt_outgoing', $data);
                    }

                }
            }

        } 
        
    }

    function refresh_materialized_view() {
        $DWH_Ushauri = $this->load->database('post_Ushauri', TRUE);

        $mysqli_Ushauri = $this->load->database('mysqli_Ushauri', TRUE); // the TRUE paramater tells CI that you'd like to return the database object.

        $get_last_added_DWH = $DWH_Ushauri->query(" REFRESH MATERIALIZED VIEW mat_vw_tableau_Data ");
    }

    function clean_DOB() {
        $DWH_Ushauri = $this->load->database('post_Ushauri', TRUE);
        $mysqli_Ushauri = $this->load->database('mysqli_Ushauri', TRUE); // the TRUE paramater tells CI that you'd like to return the database object.


        $get_last_added_DWH = $DWH_Ushauri->query('Select * from tbl_client where clnd_dob IS NULL')->result();
        foreach ($get_last_added_DWH as $value) {
            $id = $value->id;
            $dob = $value->dob;

            if (!empty($dob)) {
                $dob2 = str_replace('/', '-', $dob);
                $cleaned_dob = date("Y-m-d", strtotime($dob2));
            }
            echo $cleaned_dob . '<br>';

            $data_update = array(
                'clnd_dob' => $cleaned_dob
            );
            $DWH_Ushauri->where('id', $id);
            $DWH_Ushauri->update('tbl_client', $data_update);
        }
    }

}
