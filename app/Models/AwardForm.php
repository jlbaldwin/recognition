<?php
namespace App\Models;

use System\BaseModel;

error_reporting(E_ALL);
ini_set('display_errors', 1);

class AwardForm extends BaseModel
{
    public function get_award_form($awardId)
    {
        $award = $this->db->select('* FROM award WHERE awardId = :awardId', [':awardId' => $awardId]);
        $award_types = $this->db->select('* FROM awardClass ORDER BY awardName');

        $creatorId = $award[0]->awardCreatorId;
        $creator = $this->db->select('* FROM users WHERE userId = :creatorId', [':creatorId' => $creatorId]);
        
        $data = array(
            "award" => $award[0],
            "award_types" => $award_types,
            "creator" => $creator
        );
 
    return $data;
    }
}