<?php

namespace App\Components\Package\Modules\LatestFinancialHighlight;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;


class LatestFinancialItems extends Model
{
  	protected $table = 'latest_financial_highlights_items';

  	public static function getAll()
  	{
  		$financial_highlights = DB::table('latest_financial_highlights_items as lfhi')
  									->join('latest_financial_highlights as lfh', 'lfhi.latest_financial_highlights_id', '=', 'lfh.id' )
  									->join('company', 'lfh.company_id', '=', 'company.id')
  									->select('lfhi.*', 'lfh.quarter', 'lfh.year')
  									->get();
  		return $financial_highlights;
  	}

    public static function getOne($id)
    {
      $one_row = DB::table('latest_financial_highlights_items as lfhi')
                    ->join('latest_financial_highlights as lfh', 'lfhi.latest_financial_highlights_id', '=', 'lfh.id' )
                    ->select('lfhi.*', 'lfh.quarter', 'lfh.year')
                    ->where('lfhi.id', '=', $id)
                    ->first();
      return $one_row;
    }
}