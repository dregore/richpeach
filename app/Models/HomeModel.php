<?php
namespace App\Models;

use CodeIgniter\Model;

class HomeModel extends Model{
    public function set_session($data){
        $builder = $this->db->table('user_sessions');
        //$sql = $builder->set($data)->getCompiledInsert($this->table_payments);
        //return $sql;
        $builder->insert($data);
        return $this->db->insertID();
    }
    
    public function get_session($cookie){
        $builder = $this->db->table('user_sessions');
        //$builder->selectSum('sum_paid');
        $builder->where('state', $cookie);
        $builder->where('pubdate <=', 'TIMESTAMPADD(SECOND,access_time,pubdate)', false);
        $builder->limit(1);
        $query = $builder->get()->getRow();
        
        return $query;
        //return $builder->getCompiledSelect();
    }

    public function delete_accounts($user_id){
        $builder = $this->db->table('user_data');
        $builder->delete(['access_user' => $user_id]);
    }

    public function get_accounts($user_id){
        $builder = $this->db->table('user_data');
        $builder->where('access_user', $user_id);
        $query = $builder->get();
        
        return $query->getResultArray();
    }

    public function save_accounts($data){
        $builder = $this->db->table('user_data');
        
        foreach($data['getAccounts'] as $item){
            $ins[] = [
                'access_user' => $data['access_user'],
                'account_id'=> $item['account_id'],
                'account_name'=> $item['account_name'],
            ];
        }
        
        $builder->insertBatch($ins);
    }

    public function get_notes($campaing_id){
        $builder = $this->db->table('ads');
        $builder->where('campaignid', $campaing_id);
        $query = $builder->get();
        
        return $query->getResultArray();
    }
    
    public function save_note($campaign_id, $ad_id, $note){
        $builder = $this->db->table('ads');
        
        $data = [
            'campaignid' => $campaign_id,
            'adid' => $ad_id,
            'note' => $note
        ];
        
        $builder->replace($data);
    }
}