<?php	if( ! defined('APP_NAMESPACE')) exit('No direct script access allowed');

class ROWSET
{
	public static function removeNumericKeys($array) 
	{
	    $temp = array();
	    
	    foreach ($array as $key => $value) 
	    {
	    	if (is_numeric($key)) 
	    	{
	            $temp[] = is_array($value) ? ROWSET::removeNumericKeys($value) : $value;
	        } 
	        else 
	        {
	            $temp[$key] = is_array($value) ? ROWSET::removeNumericKeys($value) : $value;
	        }
	    }
	    
	    return $temp;
	}
	
	public static function toKeyValue($rowset,$key,$val)
	{
		$key_value = array();
		
		foreach ($rowset as $row)
		{
			$key_value[$row[$key]] = $row[$val];
		}
		
		return $key_value ;
		
	}
	
	public static function toValue($rowset, $key)
	{
		$value_array = array();
		foreach ($rowset as $row)
			$value_array[] = $row[$key] ;
		
		return $value_array ;
	}

    public static function toKey($rowset, $key)
    {
        $value_array = array();
        foreach ($rowset as $row)
            $value_array[$row[$key]] = $row ;

        return $value_array ;
    }
	
	public static function toTree($flatStructure, $pidKey, $idKey = null)
	{
		/**
		*	https://glenneggleton.com/page/menu-building-expanding-flat-data-into-a-tree
		*/
		$parents = array();

		foreach ($flatStructure as $item){

			$parents[$item[$pidKey]][] = $item;
		}

		$fnBuilder = function($items, $parents, $idKey) use (&$fnBuilder) {

			foreach ($items as $position => $item) {

				$id = $item[$idKey];

				if(isset($parents[$id])) { //is the parent set
					$item['children'] = $fnBuilder($parents[$id], $parents, $idKey); //add children
				}

				//reset the value as children have changed
				$items[$position] = $item;
			}

			//return the item
			return $items;
		};

		return $fnBuilder($parents[0], $parents, $idKey);
	}
}

?>