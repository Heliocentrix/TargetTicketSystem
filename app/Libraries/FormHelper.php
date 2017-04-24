<?php

class FormHelper {
	public static function bs($type = 'text', $name = '', $text = '', $default = null, $data = null, $placeholder = null) {
		return View::make('includes.bsblock')->with(['type' => $type, 'name' => $name, 'text' => $text, 'data' => $data, 'default' => $default, 'placeholder' => $placeholder]);
	}

	static function dataGet($dataList = null, $formList = false)
	{
		$list = DataList::where('slug' , '=', $dataList)->first();

		if($list) {
			if($formList) {
				$entries = ['' => ''] + DataEntry::where('data_list_id', '=', $list->id)->orderBy('order')->lists('content', 'id');
			} else {
				$entries = DataEntry::where('data_list_id', '=', $list->id)->orderBy('order')->all();
			}
			return $entries;
		} else {
			return array();
		}
	}

	public static function dataGetLang($dataList = null, $formList = false, $lang = 'en-uk')
	{
		$list = DataList::where('slug' , '=', $dataList)->first();

		if($list) {
			if($formList) {
				$entries = ['' => ''] + DataEntry::where('data_list_id', '=', $list->id)->orderBy('order')->lists('content', 'id');
				foreach($entries as $entry_id => $entry_content) {
					if($entry_content) {
						$entries[$entry_id] = LangHelper::get('list_entry.' . $dataList . $entry_id);
					}
				}
			} else {
				$entries = DataEntry::where('data_list_id', '=', $list->id)->orderBy('order')->all();
			}

			return $entries;
		} else {
			return array();
		}
	}
}
