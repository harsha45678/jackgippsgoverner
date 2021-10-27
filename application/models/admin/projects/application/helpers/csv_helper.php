<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

// ------------------------------------------------------------------------
/**
 * CSV Helpers
 * @author		Spark(Mani)
 * @copyright	Copyright (c) 2013, Sparkinfosys.com
 * @version		2.0
 * @package		Chums
 * @subpackage  CSV Helpers
 * @link         
 * */
// ------------------------------------------------------------------------
/**
 * Array to CSV
 *
 * download == "" -> return CSV string
 * download == "toto.csv" -> download file toto.csv
 */
if ( ! function_exists('array_to_csv'))
{
	function array_to_csv($array, $download = "")
	{
		if ($download != "")
		{	
			header('Content-Type: application/csv');
			header('Content-Disposition: attachement; filename="' . $download . '"');
		}		

		ob_start();
		$f = fopen('php://output', 'w') or show_error("Can't open php://output");
		$n = 0;		
		foreach ($array as $line)
		{
			$n++;
			if ( ! fputcsv($f, $line))
			{
				show_error("Can't write line $n: $line");
			}
		}
		fclose($f) or show_error("Can't close php://output");
		$str = ob_get_contents();
		ob_end_clean();

		if ($download == "")
		{
			return $str;	
		}
		else
		{	
			echo $str;
		}		
	}
}

// ------------------------------------------------------------------------

/**
 * Query to CSV
 *
 * download == "" -> return CSV string
 * download == "toto.csv" -> download file toto.csv
 */
if ( ! function_exists('query_to_csv'))
{
	function query_to_csv($query, $headers = TRUE, $download = "")
	{
		if ( ! is_object($query) OR ! method_exists($query, 'list_fields'))
		{
			show_error('invalid query');
		}
		
		$array = array();
		
		if ($headers)
		{
			$line = array();
			foreach ($query->list_fields() as $name)
			{
				$line[] = ucwords(str_replace('_',' ',$name));
			}
			$array[] = $line;
		}
		
		foreach ($query->result_array() as $row)
		{
			$line = array();
			foreach ($row as $item)
			{
				$line[] = stripslashes(str_replace('\n','',$item));
			}
			$array[] = $line;
		}

		echo array_to_csv($array, $download);
	}
}


/**
 * Query to CSV
 *
 * download == "" -> return CSV string
 * download == "toto.csv" -> download file toto.csv
 */
if ( ! function_exists('query_to_csv_sno'))
{
	function query_to_csv_sno($query, $headers = TRUE, $download = "")
	{
		if ( ! is_object($query) OR ! method_exists($query, 'list_fields'))
		{
			show_error('invalid query');
		}
		
		$array = array();
		
		if ($headers)
		{
			$line = array('SNo');
			foreach ($query->list_fields() as $name)
			{
				$line[] = ucwords(str_replace('_',' ',$name));
			}
			$array[] = $line;
		}
		$i=1;
		foreach ($query->result_array() as $row)
		{
			$line = array($i);
			foreach ($row as $item)
			{
				$line[] = stripslashes(str_replace('\n','',$item));
			}
			$array[] = $line;
			$i++;
		}

		echo array_to_csv($array, $download);
	}
}
/* End of file csv_helper.php */
/* Location: ./application/helpers/csv_helper.php */