<?php

namespace App\Http\Controllers\admin\pages;

use App\Http\Controllers\Controller;
use App\Models\page;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class pageContoller extends Controller
{
  function index()
  {
    $pages = page::orderBy("order", "Asc")->get();
    return view("admin/pages/index", compact("pages"));
  }

  function create()
  {
    return view("admin/pages/create");
  }

  function store(Request $request)
  {
    $data = $request->validate([
      "title" => "required|string",
      "slug" => "nullable|string",
      "body" => "nullable",
      "show" => "nullable",
      "checkout" => "nullable",
    ]);


    $data["show"] = isset($data["show"]) ? '1' : '0';
    $data["checkout"] = isset($data["checkout"]) ? '1' : '0';

    if (!isset($data["slug"])) {
      $data["slug"] = generateSlug($data["title"]);
    }


    try {

      page::create($data);

      return Redirect::back()->with("success", "تم الاضافة بنجاح");
    } catch (\Throwable $th) {
      return Redirect::back()->with("error", "لم يتم الاضافة")->withInput();
    }
  }

  function edit(page $page)
  {
    return view("admin/pages/edit", compact("page"));
  }

  function update(Request $request, page $page)
  {
    $data = $request->validate([
      "title" => "required|string",
      "slug" => "nullable|string",
      "body" => "nullable",
      "show" => "nullable",
      "checkout" => "nullable",
    ]);


    $data["show"] = isset($data["show"]) ? '1' : '0';
    $data["checkout"] = isset($data["checkout"]) ? '1' : '0';

    if (!isset($data["slug"])) {
      $data["slug"] = generateSlug($data["title"]);
    }

    try {

      $page->update($data);

      return Redirect::back()->with("success", "تم التعديل بنجاح");
    } catch (\Throwable $th) {

      return Redirect::back()->with("error", "لم يتم التعديل");
    }
  }

  public function changeOrder(Request $request)
  {


    page::where('id', $request->id)->update([
      'order' => $request->order + 1
    ]);

    return true;
  }


  public function destroy(Request $request)
  {

    try {

      $page = page::findOrFail($request->page_id);

      $page->delete();

      return redirect('admin/pages')->with("success", "تم الازالة بنجاح");
    } catch (\Throwable $th) {
      return Redirect::back()->with("error", "لم يتم الازالة");
    }
  }
}
