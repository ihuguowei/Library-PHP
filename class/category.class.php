<?php
/**
 * 奇葩的面向对象用法！！
 * 把类作为一个命名空间一个容器~~
 */

/**
 * CREATE TABLE category (
 * ID INT NOT NULL AUTO_INCREMENT,
 * cate_name VARCHAR(255) NOT NULL,
 * description VARCHAR(255) NULL,
 * add_time TIMESTAMP NOT NULL,
 * PRIMARY KEY (ID)
 * );
 */
class Category {

	/**
	 * 添加新的目录
	 */
	public static function add_new($name, $description, &$WARN_MESSAGE) {

		
		global $DATABASE_CONFIG;

		$sql = new MySQLDatabase($DATABASE_CONFIG);

		$query = "SELECT COUNT(cate_name) AS total
			FROM category
			WHERE cate_name = '$name'";

		$result = $sql->query_db($query);

		// 处理重复添加的检测		
		if ($result) {
			$row = $sql->fetch_assoc();
			if ($row['total'] >= 1) {
				array_push($WARN_MESSAGE, "该分类已经存在不能重复添加");
				return ;
			}
		}

		$query = "INSERT INTO category
			(cate_name, add_time, description)
			VALUES('$name', NOW(), '$description')";

		$result = $sql->query_db($query);

		if ($result) {
			if ($sql->affected_rows() === 1) {
				return TRUE;
			} else {
				array_push($WARN_MESSAGE, "添加失败！");
				return FALSE;
			}
		}	
	}

	public static function get_all() {

		global $DATABASE_CONFIG;

		$sql = new MySQLDatabase($DATABASE_CONFIG);

		$query = "SELECT *
			FROM category";

		$result = $sql->query_db($query);
		$result_arr = array();

		if ($result) {
			while($row = $sql->fetch_array()) {

				$temp = array(
					"id"	=>$row['ID'],
					'name'	=>$row['cate_name'],
					'des'	=>$row['description'],
					'time'	=>$row['add_time']
					);

				array_push($result_arr	, $temp);
			}

			return $result_arr;
		} else {
			return FALSE;
		}
	}	

	/**
	 * 计算使用了该目录的图书数目
	 * @param  [type] $ID [description]
	 * @return [type]     [description]
	 */
	public static function count_books_in_a_categoery($ID) {







	} 

	/** 
	 * 删除目录
	 *
	 * 删除同时会将所分类设置为改分类的图书分类为未知
 	 * @param  [type] $ID [description]
	 * @return [type]     [description]
	 */
	public static function delete_by_id($ID) {
		global $DATABASE_CONFIG;

		$sql = new MySQLDatabase($DATABASE_CONFIG);

		$query = "DELETE FROM category
			WHERE ID = $ID
			LIMIT 1";

		$result = $sql->query_db($query);

		if ($result) {
			if ($sql->affected_rows() === 1) {
				return TRUE;
			} else {
				return FALSE;
			}
		}
	} 
}
?>