<?php

namespace App\Components\Package\Modules\LatestFinancialHighlight;

use Illuminate\Database\Eloquent\Model;
use Session;

class LatestFinancialHighlightDraft extends Model
{
  	protected $table = 'latest_financial_highlights_draft';


  	public function getFinancialResult()
  	{


  		$last =     LatestFinancialHighlightDraft::where('company_id', Session::get('company_id'))
  							->where('is_archive', 1)
  							->where('is_deleted', 0)
                            ->select('year')
                            ->groupBy('year')
                            ->paginate(4);

        $data 		= array();
        $arr  		= array();
        $result 	= array();

        foreach ($last as $value) {
        	$quarter = LatestFinancialHighlightDraft::where('company_id', Session::get('company_id'))
        										->where('year', $value->year)->where('is_archive', 1)->where('is_deleted', 0)
        										->orderBy('quarter', 'desc')->get();

			$arr = array('year' => $value->year, 'quarter' => $quarter);
			$result[] = $arr;

        }
        $data = array('page' => $last, 'result' => $result);
        return $data;
  	}
}