<?php

namespace App\Http\Controllers;

use App\Models\AllotedChamber;
use App\Models\User;
// AllotedChamber
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;

class AutoCompleteController extends Controller
{
    public function autocompleteSearch(Request $request)
    {
        $query = $request->get('query');
        $filterResult = User::where('first_name', 'LIKE', '%' . $query . '%')->orWhere('id', 'LIKE', '%' . $query . '%')->pluck('first_name');
        return response()->json($filterResult);
    }
    
    public function chamber(Request $request)
    {
        $query = $request->get('query');
        $filterResult = DB::table('chamber')->where('name', 'LIKE', '%' . $query . '%')->pluck('name');
        return response()->json($filterResult);
    }
    
    
    public function doctors(Request $request){
        $doctors =  AllotedChamber::with('Doctors')->where('chamberId', $request->get('id'))->get();
         $filterResult = ['No Doctor Alloted to this chamber'];
        if(count($doctors) > 0){
           
            foreach($doctors[0]->doctors  as $list){
                $filterResult =[$list->name];
            }

        }
        
        return response()->json($filterResult);


    }
   
    
}
