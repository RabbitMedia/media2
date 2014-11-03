<?php

/**
 * csvファイルローダー
 */
class AppCsvLoader
{
	static $_csvs_arrays		= array();
	static $_csvs_arrays_group	= array();

	/**
	 * csvファイルのフルパスを取得する
	 */
	static public function get_fullpath($file)
	{
		return APPPATH.'resource/csv/'.$file;
	}

	/**
	 * csvファイルの読み込み
	 */
	static function load($csvfile, $key_field = NULL)
	{
		$name = str_replace('.', '_', $csvfile).$key_field;
		
		if (array_key_exists($name, self::$_csvs_arrays))
		{
			if(!is_null(self::$_csvs_arrays[$name]))
			{
				return self::$_csvs_arrays[$name];
			}
		}

		$res = self::_load_($csvfile, $key_field);

		self::$_csvs_arrays[$name] = $res;

		return $res;
	}

	/**
	 * csvファイルの読み込み
	 */
	static function load_group_by($csvfile, $key_field, $group_field)
	{
		$name = str_replace(".", "_", $csvfile).$key_field.$group_field;

		if (array_key_exists($name, self::$_csvs_arrays_group))
		{
			if(!is_null(self::$_csvs_arrays_group[$name]))
			{
				return self::$_csvs_arrays_group[$name];
			}
		}

		$res = self::_load_group_by_($csvfile, $key_field, $group_field);

		self::$_csvs_arrays_group[$name] = $res;

		return $res;
	}

	/**
	 * csvファイルを読み込んでアクセッサを返す
	 */
	static function _load_($csvfile, $key_field = NULL)
	{
		return self::__load1($csvfile, $key_field);
	}

	/**
	 * csvファイルを読み込んでアクセッサを返す
	 */
	static function _load_group_by_($csvfile, $key_field, $group_field)
	{
		return self::__load_group_by1($csvfile, $key_field, $group_field);
	}

	/**
	 * csvファイルを読み込んで配列を返す
	 */
	static function __load1($csvfile, $key_field = NULL)
	{
		$csvpath = self::get_fullpath($csvfile);
        if(!file_exists($csvpath))
        {
            show_error('csv file['.$csvfile.'] not found');
            return;
        }

		$csv = array();
		$fp = @fopen($csvpath, 'r');
		if ($fp !== FALSE)
		{
			$header_array = fgetcsv($fp);
			$use_key = !is_null($key_field) && in_array($key_field, $header_array);
			while ($field_array = fgetcsv($fp))
			{
				if ($use_key !== FALSE)
				{
					$row = array_combine($header_array, $field_array);
					$csv[$row[$key_field]] = $row;
				} 
				else
				{
					$row = array_combine($header_array, $field_array);
					$csv[] = $row;
				}
			}
			fclose($fp);

			return $csv;
		} 
		else
		{
			show_error('Unable to locate the csv you have specified: '.$csvpath);
			return NULL;
		}
	}

	/**
	 * csvファイルを読み込んで配列を返す
	 */
	static function __load_group_by1($csvfile, $key_field, $group_field)
	{
		$csvpath = self::get_fullpath($csvfile);
        if(!file_exists($csvpath))
        {
            show_error('csv file['.$csvfile.'] not found');
            return;
        }

        $csv = array();
		$fp = fopen($csvpath, 'r');
		if ($fp !== FALSE)
		{
			$header_array = fgetcsv($fp);
			$use_key = in_array($key_field, $header_array) && in_array($group_field, $header_array);
			while ($field_array = fgetcsv($fp))
			{
				if ($use_key)
				{
					$row = array_combine($header_array, $field_array);
					$csv[$row[$key_field]][$row[$group_field]] = array_combine($header_array, $field_array);
				}
				else 
				{
					$csv[] = $field_array;
				}
			}
			fclose($fp);

			return $csv;
		}
		else 
		{
			show_error('Unable to locate the csv you have specified: '.$csvpath);
			return NULL;
		}
	}
}