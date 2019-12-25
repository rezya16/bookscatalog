<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Book;
use Validator;
class BooksController extends Controller
{

    public function index()
    {
        if (request()->ajax())
        {
            return datatables()->of(Book::latest()->get())
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
        return view('books.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('create');
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
            'title' => 'required',
            'author' => 'required',
            'image' => 'required|image|max:2048',
            'publicated' => 'required'
        );

        $error = Validator::make($request->all(), $rules);

        if ($error->fails())
        {
            return response()->json(['errors' => $error->errors()->all()]);
        }

        $image = $request->file('image');

        $new_name = rand().'.'.$image->getClientOriginalExtension();

        $image->move(public_path('images'), $new_name);

        $form_data = array(
            'title' => $request->title,
            'author' => $request->author,
            'image' => $new_name,
            'description' => $request->description,
            'publicated' => $request->publicated
        );

        Book::create($form_data);

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
            $data = Book::findOrFail($id);
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
        $image_name = $request->hidden_image;
        $image = $request->file('image');
        if($image != '')
        {
            $rules = array(
                'title' => 'required',
                'author' => 'required',
                'image' => 'image|max:2048',
                'publicated' => 'required'
            );
            $error = Validator::make($request->all(), $rules);
            if($error->fails())
            {
                return response()->json(['errors' => $error->errors()->all()]);
            }

            $image_name = rand() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images'), $image_name);
        }
        else
        {
            $rules = array(
                'title'    =>  'required',
                'author'     =>  'required',
                'publicated' => 'required'
            );

            $error = Validator::make($request->all(), $rules);

            if($error->fails())
            {
                return response()->json(['errors' => $error->errors()->all()]);
            }
        }

        $form_data = array(
            'title' => $request->title,
            'author' => $request->author,
            'image' => $image_name,
            'description' => $request->description,
            'publicated' => $request->publicated
        );
        Book::whereId($request->hidden_id)->update($form_data);

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
        $data = Book::findOrFail($id);
        $data->delete();
    }
}
