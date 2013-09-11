<?php
class Db
{
		private $_connection;
		
		private $table;
		private $fields;
		private $where;
		private $order;
		private $limit;
		private $page = 0;
		
		public function __construct()
		{
				$driver = DB_DRIVER . '_connect';
				$this->_connection = $driver(DB_SERVER, array('Database'=>DB_DATABASE, 'UID'=>DB_USERNAME, 'PWD'=>DB_PASSWORD));
				if (!$this->_connection) die("Erro na conexão!");
		}
		
		public function setTable($table)
		{
				$this->table = $table;
				return $this;
		}
		
		public function setFields(Array $fields)
		{
				$this->fields = $fields;
				return $this;
		}
		
		public function setWhere($where)
		{
				$this->where = $where;
				return $this;
		}
		
		public function setOrder($order)
		{
				$this->order = $order;
				return $this;
		}
		
		public function setLimit($limit)
		{
				$this->limit = $limit;
				return $this;
		}
		
		public function getLimit()
		{
				return $this->limit;
		}
		
		public function setPage($page)
		{
				$this->page = $page;
				return $this;
		}
		
		public function getRowCount()
		{
				$query = sqlsrv_query($this->_connection, "SELECT * FROM {$this->table}", array(), array('Scrollable' => SQLSRV_CURSOR_KEYSET));
				return sqlsrv_num_rows($query);
		}
		
		public function select(Array $options = array())
		{
				$hasOptions = array();
				/* Set Fields on SQL Query to be executed */
				$fields = '';
				if (!(empty($this->fields)) && (is_array($this->fields))) {
						foreach ($this->fields as $k => $field) {
								if ($k != (count($this->fields) - 1))
										$fields .= $field . ', ';
								else
										$fields .= $field;
						}
				}
				elseif (!is_null($this->fields))
						$fields = $this->fields;
				else
						$fields = '*';
				
				/* Set Where and Order on SQL Query to be executed */
				if (!(empty($options) && is_array($options))) {
						foreach ($options as $option => $query) {
								if ($option == 'where') 			$where = "WHERE {$query} ";
								if ($option == 'order')				$order	= "ORDER BY {$query}";
						}
				}
				if (!is_null($this->where)) $where = "WHERE {$this->where} ";
				if (!is_null($this->order)) $order = "ORDER BY {$this->order}";
				
				if (is_null($this->limit)) {
						$sql	= "SELECT " . $fields . " FROM {$this->table} ";
						if (isset($where)) $sql .= $where . ' ';
						if (isset($order)) $sql .= $order;
						$sql .= ';';
				} else {
						$page = (!$this->page ? 0 : (($this->page * $this->limit) - 1));
						if (!isset($order)) die("Brain: Db - Para utilizar o comando 'setLimit', é necessário definir o 'ORDER BY' da Query.");
						$sql	= "WITH limit AS ( SELECT TOP ({$page} + {$this->limit}) RowNum = ROW_NUMBER() OVER ({$order}), * FROM {$this->table} ";
						if (isset($where)) $sql .= $where;
						$sql .= "{$order} ) SELECT {$fields} FROM limit WHERE RowNum >= {$page};";
				}
				/* Debug */
				// var_dump($sql);
				return $this->query($sql);
		}
		
		public function query($sql)
		{
				// var_dump($sql);
				
				$query = sqlsrv_query($this->_connection, $sql, array(), array('Scrollable' => SQLSRV_CURSOR_KEYSET));
				if (!$query) die(var_dump(sqlsrv_errors()) . "Brain: Db - Erro ao executar Db::query()!");
				
				$num_rows = sqlsrv_num_rows($query);
				
				$fetch_object = array();
				while ($row = sqlsrv_fetch_object($query)) {
						$fetch_object[] = $row;
				}
				
				$query = sqlsrv_query($this->_connection, $sql, array(), array('Scrollable' => SQLSRV_CURSOR_KEYSET));
				$fetch_array = array();
				while ($row = sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC)) {
						$fetch_array[] = $row;
				}
				
				$results = (object) array(
						'NUM_ROW'				=> $num_rows,
						'FETCH_OBJECT'	=> $fetch_object,
						'FETCH_ARRAY'		=> $fetch_array,
				);
				
				return $results;
		}
		
		public function save($sql)
		{
				$query = sqlsrv_query($this->_connection, $sql, array(), array('Scrollable' => SQLSRV_CURSOR_KEYSET));
				if (!$query) die(var_dump(sqlsrv_errors()) . "Brain: Db - Erro ao executar Db::query()!");
		}
		
		public function escape($data)
		{
        if ( !isset($data) or empty($data) ) return '';
        if ( is_numeric($data) ) return $data;

        $non_displayables = array(
            '/%0[0-8bcef]/',            // url encoded 00-08, 11, 12, 14, 15
            '/%1[0-9a-f]/',             // url encoded 16-31
            '/[\x00-\x08]/',            // 00-08
            '/\x0b/',                   // 11
            '/\x0c/',                   // 12
            '/[\x0e-\x1f]/'             // 14-31
        );
        foreach ( $non_displayables as $regex )
            $data = preg_replace( $regex, '', $data );
        $data = str_replace("'", "''", $data );
        return $data;
		}
}