<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model{

	function __construct(){
		parent::__construct();

		date_default_timezone_set('Asia/Kolkata');
		$date = date("y-m-d H:i:s");
		$this->get_date = $date;
	}

	public function checkUserExists($user){
		$query = $this->db->get_where('users', array('user'=>$user));		
		
		return $query->row();
	}

	public function checkUserExistsBYIdName($id, $user){
		$this->db->where('id !=',$id);
		$this->db->where('user',$user);
		$query = $this->db->get('users');		
		
		return $query->row();
	}
	
	public function insertUser($post){
		$post['created_date'] = $this->get_date;
		
		return $this->db->insert('users', $post);
	}

	
	public function getUserList(){
		$this->db->select('id,user,email');
		$this->db->order_by('id', 'DESC');
		$query = $this->db->get_where('users', array('status'=>'Y'));

		return $query->result_array();
	}

	public function getUserByID($id){
		$this->db->select('id,user,email');
		$query = $this->db->get_where('users', array('id'=>$id));

		return $query->row();
	}


	public function updateUser($id, $post){
		$post['modified_date'] = $this->get_date;
		
		$this->db->where('id', $id);
		return $this->db->update('users', $post);
	}

	public function deleteUser($id){
		$this->db->where('id', $id);
		return $this->db->delete('users');
	}
	
}