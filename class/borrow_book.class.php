<?php 
class Borrow {
	public static function add($book_id, $user_id, & $ERROR_MESSAGE) {

		global $DATABASE_CONFIG;

		$sql = new MySQLDatabase($DATABASE_CONFIG);

		if (Book::borrow($book_id, $ERROR_MESSAGE)) {
			$query = "INSERT INTO borrow
				(book_id, user_id, borrow_date)
				VALUES($book_id, $user_id, NOW())";

			$result = $sql->query_db($query);

			if ($result) {
				if ($sql->affected_rows() == 1) {
					return true;
				}
			}
		}
		return false;
	}

	public static function complete($id) {

	}

	/**
	 * [get_borrowed_info_user_id description]
	 * @param  [type] $user_id   [description]
	 * @param  [type] $accepted  已经同意借阅
	 * @param  [type] $completed 书本已经归还
	 * @return [type]            [description]
	 */
	public static function get_borrowed_info_user_id($user_id, $accepted, $completed) {
		
		global $DATABASE_CONFIG;

		$sql = new MySQLDatabase($DATABASE_CONFIG);

	
		$accepted = $accepted == TRUE ? 1 : 0;
		$completed = $completed == TRUE ? 1 : 0;


		$query = "SELECT ID, book_id, borrow_date, return_date, accepte_date
			FROM borrow
			WHERE accepted = $accepted 
					AND completed = $completed
					AND user_id = $user_id
			ORDER BY borrow_date ASC";

		$result = $sql->query_db($query);

		$res_arr = array();

		if ($result) {
			while ($row = $sql->fetch_array()) {
				// print_r($row);
				$temp_arr = array(
					'id' 		=> $row['ID'],
					'book'  	=> $row['book_id'],
					'borrow'	=> $row['borrow_date'],
					'return'	=> $row['return_date'],
					'accepte' 	=> $row['accepte_date']
					);	
				array_push($res_arr, $temp_arr);
			}	

			return $res_arr;
		}

		return false;
	}


	public static function get_extended_info($user_id, $extended_days) {

		global $DATABASE_CONFIG;

		$sql = new MySQLDatabase($DATABASE_CONFIG);

		$query = "SELECT ID, book_id, borrow_date, return_date, accepte_date
			FROM borrow
			WHERE  user_id = $user_id
					AND accepted = 1 
					AND completed = 0
					AND TO_DAYS(NOW()) - TO_DAYS(accepte_date) > $extended_days
			ORDER BY borrow_date ASC";

			// echo $query;

		$result = $sql->query_db($query);

		$res_arr = array();

		if ($result) {
			while ($row = $sql->fetch_array()) {
				// print_r($row);
				$temp_arr = array(
					'id' 		=> $row['ID'],
					'book'  	=> $row['book_id'],
					'borrow'	=> $row['borrow_date'],
					'return'	=> $row['return_date'],
					'accepte' 	=> $row['accepte_date']
					);	
				array_push($res_arr, $temp_arr);
			}	

			return $res_arr;
		}

		return false;
	}
}



?>