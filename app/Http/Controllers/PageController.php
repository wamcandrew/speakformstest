<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\PageContent;

class PageController extends Controller
{
    public function get_all_pages(){
        $result = PageContent::all();

        return response()->json($result, 201);
    }
    
    public function get_page_data($id){
        $result = PageContent::where('id', $id)->first();

        return response()->json($result, 201);
    }

    public function update_page($id, Request $request){
        $result = PageContent::where('id', $id)->update(array('body' => $request->body));

        return response()->json($result, 201);
    }
}
