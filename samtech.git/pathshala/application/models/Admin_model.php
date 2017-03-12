<?php

Class Admin_model extends CI_Model {

public function admin_insert($table_name , $insert_data,$groups = NULL) {
    
        $this->db->insert($table_name, $insert_data);
        $return = $this->db->insert_id();
        if(!empty($return) && !empty($groups)){
            foreach ($groups as $key => $group) {
                $this->save_groups_reference($return,$group,$table_name);
            }
        }
        return $return;
    }
    public function save_groups_reference($id , $groups,$type){
        $this->db->select('*');
        $this->db->from('group_reference');
        $this->db->where('group_id',$groups);
        $this->db->where('reference_id',$id);
        $this->db->where('reference_type',$type);
        $query = $this->db->get();
            if ($query->num_rows() == 0) {
                $data['group_id'] = $groups;
                $data['reference_id'] = $id;
                $data['reference_type'] = $type;
                $this->db->insert('group_reference', $data);
            }
    }
    
    public function delete_groups_reference($table_name,$conditions){
        $result = TRUE;
        if(!empty($conditions)){
            $conditions['reference_type'] = $table_name;
            $this->db->where($conditions); //get the results
            $result = $this->db->delete('group_reference');
        }
        return $result;
    }
public function admin_update($table_name , $update_data , $conditions,$groups = NULL) {
        $query = $this->db->where($conditions); //get the results
        $result = $this->db->update($table_name, $update_data);
        if(!empty($result) && !empty($groups)){
            $this->delete_groups_reference($table_name,$conditions);
            foreach ($groups as $key => $group) {
                $this->save_groups_reference($result,$group,$table_name);
            }
        }
        return $result;
    }
    
    public function delete_content($table_name,$id){
        
        switch ($table_name) {
            case 'groups':
                        $conditions = array('gid'=>$id);

                break;
             case 'blogs':
                        $conditions = array('blog_id'=>$id);

                break;
             case 'category':
                        $conditions = array('cat_id'=>$id);

                break;
             case 'degree':
                        $conditions = array('degree_id'=>$id);

                break;
             case 'institution':
                        $conditions = array('institute_id'=>$id);

                break;
             case 'library':
                        $conditions = array('library_id'=>$id);

                break;
             case 'permission':
                        $conditions = array('per_id'=>$id);

                break;
             case 'user_roles':
                        $conditions = array('rid'=>$id);

                break; 
            case 'subjects':
                        $conditions = array('subject_id'=>$id);

                break;
             case 'users':
                        $conditions = array('uid'=>$id);

                break;
             case 'forum':
                        $conditions = array('forum_id'=>$id);

                break;
             
            default:
                break;
        }
        $result = TRUE;
        if(!empty($conditions)){
            $this->db->where($conditions); //get the results
            $result = $this->db->delete($table_name);
        }
        return $result;
    }
}

?>