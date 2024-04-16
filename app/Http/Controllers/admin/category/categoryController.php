<?php

namespace App\Http\Controllers\admin\category;

use App\Http\Controllers\Controller;
use App\Models\category;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class categoryController extends Controller
{

    public function changeOrder(Request $request)
    {


        category::where('id', $request->id)->update([
            'order' => $request->order + 1
        ]);

        return true;

        //    return response()->json($request->all());
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {


        $Categories = category::orderBy('order', 'Asc')->paginate(25);
        return view('admin/category/index', compact('Categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin/category/create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $data = [];
        $data['name'] = 'required';
        $data['slug'] = 'required';


        $role = [];
        $role['name.required'] = "يرجي كتابة اسم التصنيف";
        $role['slug.required'] = "يرجي كتابة اسم التصنيف في الرابط";

        $data = $request->validate($data, $role);




        try {

            category::create($data);
            return Redirect::back()->with("success", "تم الاضافة بنجاح");
        } catch (\Throwable $th) {

            return Redirect::back()->with("error", "لم يتم الاضافة")->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(category $category)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(category $category)
    {
        return view('admin/category/edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, category $category)
    {
        $data = [];
        $data['name'] = 'required';
        $data['slug'] = 'required';


        $role = [];
        $role['name.required'] = "يرجي كتابة اسم التصنيف";
        $role['slug.required'] = "يرجي كتابة اسم التصنيف في الرابط";

        $data = $request->validate($data, $role);



        try {

            $category->update($data);

            return Redirect::back()->with("success", "تم التعديل بنجاح");
        } catch (\Throwable $th) {

            return Redirect::back()->with("error", "لم يتم التعديل");
        }
    }




    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {

        $category = category::findOrFail($request->category_id);



        try {
            $category->delete();


            return Redirect::back()->with("success", "تم الازالة بنجاح");
        } catch (\Exception $e) {
            return Redirect::back()->with("error", "لم يتم الازالة");
        }
    }
}
