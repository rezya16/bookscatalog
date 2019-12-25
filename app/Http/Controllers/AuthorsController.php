<?php

namespace App\Http\Controllers;

use Validator;
use App\Author;
use Illuminate\Http\Request;

class AuthorsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (request()->ajax())
        {
            return datatables()->of(Author::latest()->get())
                ->addColumn('action', function ($data){
                    $button = '<button type="button" name="edit" id="'.$data->id.'"
                class="edit btn btn-primary btn-sm">Изменить</button>';
                    $button .='&nbsp;&nbsp;';
                    $button .= '<button type="button" name="delete" id="'.$data->id.'" 
                class="delete btn btn-danger btn-sm">Удалить</button>';
                    return $button;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('authors.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $rules = array(
            'surname' => 'required|min:3',
            'name' => 'required',
            );

        $error = Validator::make($request->all(), $rules);

        if ($error->fails())
        {
            return response()->json(['errors' => $error->errors()->all()]);
        }

        $form_data = array(
            'surname' => $request->surname,
            'name' => $request->name,
            'patronymic' => $request->patronymic
        );

        Author::create($form_data);

        return response()->json(['success' =>'Данные успешно добавлены!']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if(request()->ajax())
        {
            $data = Author::findOrFail($id);
            return response()->json(['data' => $data]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $rules = array(
            'surname'    =>  'required|min:3',
            'name'     =>  'required'
        );

        $error = Validator::make($request->all(), $rules);

        if($error->fails())
        {
            return response()->json(['errors' => $error->errors()->all()]);
        }


        $form_data = array(
            'surname' => $request->surname,
            'name' => $request->name,
            'patronymic' => $request->patronymic
        );
        Author::whereId($request->hidden_id)->update($form_data);

        return response()->json(['success' => 'Данные успешно обновлены!']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = Author::findOrFail($id);
        $data->delete();
    }
}
