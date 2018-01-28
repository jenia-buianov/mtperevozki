<?php
class Unified extends Base
{
    private static $instance = array();
	
	protected $type;
	protected $suffix;
	protected $language;
	
	protected $form;
	protected $db;
	protected $split;
	
	protected $cache;
	
	protected $_attributes = array();
	
    public static function GetInstance($params = array())
    {
		$_defaults = array (
							'type' => 'cargo',
							'suffix' => '',
							'language' => 'ru',
							'form_type' => 'validation'
							);
							
		$params = array_merge($_defaults, $params);
		
        if ( !(self::$instance[md5(serialize($params))] instanceof self) )
            self::$instance[md5(serialize($params))] = new self($params);

        return self::$instance[md5(serialize($params))];
    }
	
	private function __construct($params)
    {
		$this->configure($params);
		
		//form builder
		$this->form = new Form($this->getAttribute('form_type'));
		
		//config database connection
		$db_config = include dirname(__FILE__) . '/../database.config.php';

		ORM::configure('mysql:host='.$db_config['host'].';dbname='.$db_config['database'].';charset=UTF8');
		ORM::configure('username', $db_config['username']);
		ORM::configure('password', $db_config['password']);
		ORM::configure('driver_options', array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));
		ORM::configure('logging', true);
		
		//split table
		$this->splitTable($this->suffix);
	}
	
	
	/*Country*/
	public function getCountryList()
	{	
		$arResult = array();
			
		//store the cache
		if (empty($this->cache['country']))
		{
			$this->cache['country'] = $this->getCountryListQuery();
		}

		//no data
		if (!is_array($this->cache['country']) or empty($this->cache['country'])) return $arResult;

		foreach ($this->cache['country'] as &$item)
		{
			if ($item['data_hidden'] === '0') $arResult[] = $item;
		}

		return $arResult;
	}

		
	public function getCountryListbyArray($filtered_id = false)
	{
		$arResult = array();
			
		//store the cache
		if (empty($this->cache['country']))
		{
			$this->cache['country'] = $this->getCountryListQuery();
		}
			
		//no data
		if (!is_array($this->cache['country']) or empty($this->cache['country'])) return $arResult;
			
		$arResult = $this->cache['country'];
		ksort($arResult);

		if (is_array($filtered_id) and !empty($filtered_id))
		{
			foreach ($arResult as $key => &$data)
			{
				if (!in_array($data['data_id'], $filtered_id)) unset($arResult[$key]);
			}
		}

		return $arResult;	
	}

	public function getCountryListQuery()
	{
		$arResult = array();
			
		$select = array (
						'country_name_'.$this->language => 'data_title',
						'country_name_ru_from'			=> 'data_title_from',
						'country_name_ru_to'			=> 'data_title_to',
						'country_hidden'				=> 'data_hidden',
						'country_group'					=> 'group_id',
						'id_country'					=> 'data_id',
						'alpha3'						=> 'code',
						);
		$temp = ORM::for_table('country')->select_many($select)->order_by_asc('country_group')->order_by_asc('country_name_'.$this->language)->find_array();

		foreach ($temp as &$item)
		{
			$arResult[$item['data_title']] = $item;
		}
			
		return $arResult;
	}
		
	public function buildCountryList($exception = false)
	{
		$list = "";	
		$result = $this->getCountryList();

		if (!empty($result))
		{

			$group_id = $result[0]['group_id'];
			$list .= "<optgroup label='- - - - - - - - - - - - - - - - - - - -'>\n";

			foreach ($result as $row)
			{
				if ($row['group_id'] != $group_id)
				{
					$list .="</optgroup>\n";
					$group_id = $row['group_id'];
					$list .= "<optgroup class='delimiter' label='- - - - - - - - - - - - - - - - - - - -'>\n";
				}

				$list.= "<option value='".$row['data_id']."'";
				
				if (intval($exception) == intval($row['data_id']))
					$list.= " selected";					

				$list .= ">".$row['data_title'];

				if ($row['code']) $list .= " [".$row['code']."]";

				$list .= "</option>\n";

			}

			$list .="</optgroup>\n";
			$list .= "<optgroup class='delimiter' label=''></optgroup>\n";
		}

		return $list;
	}
	
	/*City*/
	
	/*Transport type*/
	
	/*Cargo type*/
	
	/*Volumes*/
	
	/*UTILS*/
	public function splitTable($suffix = '')
	{

		if(isset($this->split) and $this->split->original == $suffix) return $this->split;
			elseif (isset($this->cache['split'][$suffix])) return $this->cache['split'][$suffix];

			
		//split orders from movers_cargo and movers_order to international, internal and seafreight
		//cache split criteria
		if (!isset($this->cache['split']['IDS']))
		{
			$IDs = ORM::for_table('transport_type')->select('id')->where('split_seafreight', '1')->find_column();
			
			$this->cache['split']['IDS'] = (string)implode(",",$IDs);	
		}
			

		$parameters = array();

		$parameters['suffix'] = $parameters['original'] = $suffix;

		if (isset($suffix))
		{
			switch ($suffix)
			{
				case "":
				case "_international":	
					$parameters['suffix'] = "";	
					$suffix = "_international";
					$parameters['merge'] = " AND m.export <> m.import AND m.type NOT IN (".$this->cache['split']['IDS'].") ";
					$parameters['split'] = " AND split = 1 ";
					break;

				case "_internal":
					$parameters['suffix'] = "";
					$parameters['merge'] = " AND m.export = m.import AND m.type NOT IN (".$this->cache['split']['IDS'].") ";
					$parameters['split'] = " AND split$suffix = 1 ";
					break;

				case "_seafreight":
					$parameters['suffix'] = "";
					$parameters['merge'] = " AND m.type IN (".$this->cache['split']['IDS'].") ";
					$parameters['split'] = " AND split$suffix = 1 ";
					$parameters['aux_select'] = ",x.container_type_".$this->language." as container_type_title ";
					$parameters['aux_join'] = " LEFT JOIN container_type as x ON x.id=m.container_type ";
					break;
			}
		}

		$parameters['default_offer_filter'] = " AND (m.hidden = 0 OR m.source = 'comstil') ";
		$parameters = (object) $parameters;
		$this->split = $this->cache['split'][$suffix] = $parameters;

		return $parameters;
	}
}
/*

$language = _LANGUAGE;

include ($_SERVER['DOCUMENT_ROOT'].'/db_connect/db_read.php');

//country list
$query = "SELECT country_group, id_country, country_name_$language as country_name FROM country ORDER BY country_group ASC, country_name_$language ASC";
$table = mysql_query($query);
$countries = "";
if (mysql_num_rows($table) > 0)
{
	$data= mysql_fetch_array($table);
	$country_group = $data['country_group'];
	$countries.="<optgroup label=''>";
	$countries.= "<option value='".$data['id_country']."'>".$data['country_name']."</option>";
	while ($data = mysql_fetch_array($table))
	{
		if ($data['country_group'] != $country_group)
		{
			$countries.="</optgroup>";
			$country_group = $data['country_group'];
			$countries.="<optgroup label='___________________________'>";
		}
		$countries.="<option value='".$data['id_country']."'>".$data['country_name']."</option>";
	}
	$countries.="</optgroup>";
}
//country list
$split = " AND split$suffix=1";
if ($suffix == "")  $split1 = " OR split_seafreight = 1";
else { $split1 = ""; $split = ""; }
//Transport type list
$query = "SELECT id as data_id, transport_type_$language as data_title,  transport_type_group as data_group FROM  transport_type$suffix WHERE transport_type_hidden != 1 $split $split1 ORDER BY transport_type_group ASC, `order` ASC, transport_type_$language ASC";
$table = mysql_query($query);
$transport_types = "";

if (mysql_num_rows($table) > 0)
{
	$data= mysql_fetch_array($table);
	$data_group = $data['data_group'];
	$transport_types.="<optgroup label=''>";
	$transport_types.= "<option value='".$data['data_id']."'>".$data['data_title']."</option>";
	
	while ($data = mysql_fetch_array($table))
	{
		if ($data['data_group'] != $data_group)
		{
			$transport_types.="</optgroup>";
			$data_group = $data['data_group'];
			$transport_types.="<optgroup label='___________________________'>";
		}
		$transport_types.= "<option value='".$data['data_id']."'>".$data['data_title']."</option>";
	}
	$transport_types.="</optgroup>";
}
//Transport type list

//Cargo type list
$query = "SELECT id as data_id, cargo_type_$language as data_title, cargo_type_group as data_group  FROM  cargo_type$suffix WHERE cargo_type_hidden != 1 ORDER BY cargo_type_group ASC, `order` ASC, cargo_type_$language ASC";
$table = mysql_query($query);
$cargo_types = "";
if (mysql_num_rows($table) > 0)
{
	$data= mysql_fetch_array($table);
	$data_group = $data['data_group'];
	$cargo_types.="<optgroup label=''>";
	$cargo_types.= "<option value='".$data['data_id']."'>".$data['data_title']."</option>";
	
	while ($data = mysql_fetch_array($table))
	{
		if ($data['data_group'] != $data_group)
		{
			$cargo_types.="</optgroup>";
			$data_group = $data['data_group'];
			$cargo_types.="<optgroup label=''>";
		}
		$cargo_types.= "<option value='".$data['data_id']."'>".$data['data_title']."</option>";
	}
	$cargo_types.="</optgroup>";
}
//Cargo type list

//Volume type list
$query = "SELECT id as data_id, cargo_volume_$language as data_title, cargo_volume_group as data_group  FROM  cargo_volume$suffix WHERE cargo_volume_hidden != 1 $split ORDER BY id ASC, cargo_volume_group ASC, `order` ASC, cargo_volume_$language ASC";
$table = mysql_query($query);
$cargo_volumes = "";
if (mysql_num_rows($table) > 0)
{
	$data= mysql_fetch_array($table);
	$data_group = $data['data_group'];
	$cargo_volumes.="<optgroup label=''>";
	$cargo_volumes.= "<option value='".$data['data_id']."'>".$data['data_title']."</option>";
	
	while ($data = mysql_fetch_array($table))
	{
		if ($data['data_group'] != $data_group)
		{
			$cargo_volumes.="</optgroup>";
			$data_group = $data['data_group'];
			$cargo_volumes.="<optgroup label=''>";
		}
		$cargo_volumes.= "<option value='".$data['data_id']."'>".$data['data_title']."</option>";
	}
	$cargo_volumes.="</optgroup>";
}
//Volume type list
//$dbo = mysql_connect('localhost', 'mweb72', 'ytsAF3aA');
//$db_select = mysql_select_db('usr_mweb72_1', $dbo);
*/
?>