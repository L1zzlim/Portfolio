<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        try {


            // 測試
            // return response()->json([
            //     ['id' => 1, 'name' => '測試餐廳 1'],
            //     ['id' => 2, 'name' => '測試餐廳 2'],
            // ]);

            $searchType = $request->input('search_type', 'RestInfos');
            if (!in_array($searchType, ['RestInfos', 'MemberNotes'])) {
                $searchType = 'RestInfos';
            }

            $category = $request->input('category');
            $location = $request->input('location');
            $purpose = $request->input('purpose');

            $query = DB::table($searchType);

            if ($searchType === 'RestInfos') {
                $query->join('Users', 'Users.id', '=', 'RestInfos.user_id')
                    ->join('FiltClasses', 'FiltClasses.id', '=', 'RestInfos.f_c_id');

                if ($category && $category !== 'null') {
                    $query->where('FiltClasses.restclass', $category);
                }

                if ($location && $location !== 'null') {
                    $query->where('RestInfos.address', 'like', "%$location%");
                }

                if ($purpose && $purpose !== 'null') {
                    $query->join('SuitableFor', 'SuitableFor.r_id', '=', 'RestInfos.id')
                        ->join('FiltPurposes', 'FiltPurposes.id', '=', 'SuitableFor.f_p_id')
                        ->where('FiltPurposes.purpose', $purpose);
                }

                $query->select('RestInfos.*', 'Users.name as user_name', 'FiltClasses.restclass as class', );
            } else {
                $query->join('Members', 'Members.id', '=', 'MemberNotes.m_id')
                    ->join('Users', 'Users.id', '=', 'Members.user_id')
                    ->join('RestInfos', 'RestInfos.id', '=', 'MemberNotes.r_id')
                    ->join('FiltClasses', 'FiltClasses.id', '=', 'RestInfos.f_c_id');

                if ($category && $category !== 'null') {
                    $query->where('FiltClasses.restclass', $category);
                }

                if ($location && $location !== 'null') {
                    $query->where('RestInfos.address', 'like', "%$location%");
                }

                if ($purpose && $purpose !== 'null') {
                    $query->join('SuitableFor', 'SuitableFor.r_id', '=', 'RestInfos.id')
                        ->join('FiltPurposes', 'FiltPurposes.id', '=', 'SuitableFor.f_p_id')
                        ->where('FiltPurposes.purpose', $purpose);
                }
                $query->select('MemberNotes.*', 'Users.name as user_name', 'RestInfos.address', 'FiltClasses.restclass as class');
            }

            // $results = $query->get();
            //         \Log::info('Generated SQL query: ' . $query->toSql());
            //         \Log::info('Query bindings: ', $query->getBindings());
            //         return response()->json($results);
            //     } catch (\Exception $e) {
            //         \Log::error('Search error: ' . $e->getMessage());
            //         return response()->json(['error' => $e->getMessage()], 500);
            //     }

            // }}


            // 不符合條件的其他資料
            $filteredResults = $query->get();
            $allQuery = DB::table($searchType);
            if ($searchType === 'RestInfos') {
                $allQuery->join('Users', 'Users.id', '=', 'RestInfos.user_id')
                    ->join('FiltClasses', 'FiltClasses.id', '=', 'RestInfos.f_c_id')
                    ->select('RestInfos.*', 'Users.name as user_name', 'FiltClasses.restclass as class')
                    ->whereNotIn('RestInfos.id', $filteredResults->pluck('id'));
            } else {
                // MemberNotes 
            }
            $allResults = $allQuery->take(20)->get();
            return response()->json([
                'filtered' => $filteredResults,
                'all' => $allResults
            ]);
        } catch (\Exception $e) {
            \Log::error('Search error: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}