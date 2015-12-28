<?php 
class Register{
	
	private $email;
	private $password;
	private $password2;
	private $encPassword;
	private $errors;
	private $membership;

	public function __construct(){
		$this->errors = array();
		$this->email = $_POST['email'];
		$this->pgg = $_POST['pgg'];
		$this->password1 = $_POST['password1'];
		$this->password2 = $_POST['password2'];
		if($this->membership = $_POST['membership']);
		$this->encPassword = md5($this->password1);
	}	


	public function validateData(){
		if(empty($this->email) || empty($this->password1) || empty($this->password2))
		{
			$this->errors[] = "Email/Password cannot be empty.";
		}
		else
		{
			if($this->emailExists())
				$this->errors[] = "Email already exists.";
			
			if($this->emailFormatError())
				$this->errors[] = "Invalid email.";

			if($this->password1 != $this->password2)
				$this->errors[] = "Passwords mismatch.";
		}
		
	}

	public function emailExists(){
		require '../../../db/connect.php';
		$email = $db->query("SELECT email FROM Members WHERE email = '{$this->email}'");
		if($email->num_rows){
			return true;
		}
		else
			return false;
	}

	public function emailFormatError(){
		if(!filter_var($this->email,FILTER_VALIDATE_EMAIL))
			return true;
		return false;
	}

	public function register(){
		$this->validateData();
		if(!count($this->errors))
		{
			require '../../../db/connect.php';
			$insert_member = $db->prepare("CALL sign_up(?,?,?,?)");
			$insert_member->bind_param('ssss',$this->email, $this->encPassword, $this->pgg, $this->membership);
			$insert_member->execute();

			switch ($this->membership) {
				case 'Normal User':
					$first_name = null;
					$last_name = null;
					$birth_date = null;
					if(isset($_POST['first_name']) && !empty($_POST['first_name']))
						$first_name = $_POST['first_name'];
					if(isset($_POST['last_name']) && !empty($_POST['last_name']))
						$last_name = $_POST['last_name'];
					if(isset($_POST['birth_date']) && !empty($_POST['birth_date']))
						$birth_date = $_POST['birth_date'];
					
					$update_member = $db->prepare("CALL update_normal_user(?,?,?,?)");
					$update_member->bind_param('ssss',$this->email, $first_name, $last_name, $birth_date);
					$update_member->execute();
					break;

				case 'Verified Reviewer':
					$first_name = null;
					$last_name = null;
					$exp_years = null;
					if(isset($_POST['first_name']) && !empty($_POST['first_name']))
						$first_name = $_POST['first_name'];
					if(isset($_POST['last_name']) && !empty($_POST['last_name']))
						$last_name = $_POST['last_name'];
					if(isset($_POST['exp_years']) && !empty($_POST['exp_years']))
						$exp_years = $_POST['exp_years'];
					
					$update_member = $db->prepare("CALL update_verified_reviewer(?,?,?,?)");
					$update_member->bind_param('ssss',$this->email, $first_name, $last_name, $exp_years);
					$update_member->execute();
					break;

				case 'Development Team':
				
					$team_name = null;
					$formation_date = null;
					$company = null;
					if(isset($_POST['team_name']) && !empty($_POST['team_name']))
						$team_name = $_POST['team_name'];
					if(isset($_POST['formation_date']) && !empty($_POST['formation_date']))
						$formation_date = $_POST['formation_date'];
					if(isset($_POST['company']) && !empty($_POST['company']))
						$company = $_POST['company'];
					
					$update_member = $db->prepare("CALL update_development_team(?,?,?,?)");
					$update_member->bind_param('ssss',$this->email, $team_name, $formation_date, $company);
					$update_member->execute();
					break;	
				
				default:
					
					break;
			}
			return true;
		}
		else
			return false;
		
	}

	public function get_errors(){
		return $this->errors;
	}

	public function get_email()
	{
		return $this->email;
	}

	public function get_membership()
	{
		return $this->membership;
	}


}
?>