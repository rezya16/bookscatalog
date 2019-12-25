1. Для установки laravel проекта использовал консольную команду (при установленном composer)
    composer create-project laravel/laravel weather_api --prefer-dist
  Для развертывание этого проекта на другой машине следует склонировать файлы с этого репозитория:
    прописать в консоли команду:
    composer install;
    переименовать .env.example в .env;
    заменить путь и доступы к вашей базе данных;
2. Создал базу данных bookscatalog, прописал подключение к бд в файле .env
      
        DB_CONNECTION=mysql
        DB_HOST=127.0.0.1
        DB_PORT=3306
        DB_DATABASE=bookscatalog
        DB_USERNAME=root
        DB_PASSWORD=
     
3. Создал модели Book и Author и миграции для них командами
        
        php artisan make:model Book -m
        php artisan make:model Author -m
        
4. Заполнил поля в миграциях таким образом:
      
        Schema::create('books', function (Blueprint $table) {
                    $table->bigIncrements('id');
                    $table->string('title');
                    $table->string('author');
                    $table->string('image');
                    $table->text('description')->nullable(true);
                    $table->date('publicated');
                    $table->timestamps();
                });
                
        Schema::create('authors', function (Blueprint $table) {
                    $table->bigIncrements('id');
                    $table->string('surname');
                    $table->string('name');
                    $table->string('patronymic')->nullable(true);
                    $table->timestamps();
                });
      
5. Мигрировал командой

    php artisan migrate
    
6. Добавил свойство fillable для моделей 

        class Book extends Model
        {
            protected $fillable = [
                'title', 'author', 'image', 'description', 'publicated'
            ];
        }
        
        class Author extends Model
        {
            protected $fillable = [
              'surname', 'name', 'patronymic'
            ];
        }
    
7. Создал контроллеры командой
    
        php artisan make:controller BookController --resource
        php artisan make:controller AuthorController --resource
    
8. Подключил jQuery плагин 

        composer require yajra/laravel-datatables-oracle
        
9. Поменял настройки 

        'providers' => [
         ....
         Yajra\Datatables\DatatablesServiceProvider::class,
        ]
        'aliases' => [
         ....
         'Datatables' => Yajra\Datatables\Facades\Datatables::class,
        ]
        
10. Выполнил команду
        
        php artisan vendor:publish
        
11. Контроллеры:
    
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
        
12. Blade шаблоны

    index.blade.php в папке books
        <html>
        <head>
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <title>Каталог книг</title>
            <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
            <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
            <script src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
            <script src="https://cdn.datatables.net/1.10.12/js/dataTables.bootstrap.min.js"></script>
            <link rel="stylesheet" href="https://cdn.datatables.net/1.10.12/css/dataTables.bootstrap.min.css" />
            <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
            <script src="//cdn.datatables.net/plug-ins/1.10.20/i18n/Russian.json"></script>
        
        </head>
        <body>
        <div class="container">
            <br />
            <h3 align="center">Каталог книг</h3>
            <br />
            <div align="right">
                <a type="button" class="btn btn-primary btn-sm" href="{{ '/' }}">На главную</a>
                <a type="button" class="btn btn-danger btn-sm" href="{{ route('author.index') }}">Список авторов</a>
                <button type="button" name="create_record" id="create_record" class="btn btn-success btn-sm">Добавить книгу</button>
            </div>
            <br />
            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="book_table">
                    <thead>
                    <tr>
                        <th width="10%">Обложка</th>
                        <th width="18%">Название</th>
                        <th width="17%">Автор</th>
                        <th width="30%">Описание</th>
                        <th width="10%">Дата</th>
                        <th width="15%">Действие</th>
                    </tr>
                    </thead>
                </table>
            </div>
            <br />
            <br />
        </div>
        </body>
        </html>
        <div id="formModal" class="modal fade" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Добавить книгу!</h4>
                    </div>
                    <div class="modal-body">
                        <span id="form_result"></span>
                        <form method="post" id="sample_form" class="form-horizontal" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <label class="control-label col-md-4" >Название : </label>
                                <div class="col-md-8">
                                    <input type="text" name="title" id="title" class="form-control" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-4">Автор : </label>
                                <div class="col-md-8">
                                    <input type="text" name="author" id="author" class="form-control" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-4">Обложка : </label>
                                <div class="col-md-8">
                                    <input type="file" name="image" id="image" />
                                    <span id="store_image"></span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-4 text-right">Описание : </label>
                                <div class="col-md-8">
                                    <input type="text" name="description" id="description" class="form-control"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-4 text-right">Дата публикации : </label>
                                <div class="col-md-8">
                                    <input type="date" name="publicated" id="publicated" class="form-control"/>
                                </div>
                            </div>
                            <br />
                            <div class="form-group" align="center">
                                <input type="hidden" name="action" id="action" />
                                <input type="hidden" name="hidden_id" id="hidden_id" />
                                <input type="submit" name="action_button" id="action_button" class="btn btn-warning" value="Добавить" />
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        
        <div id="confirmModal" class="modal fade" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h2 class="modal-title">Подтверждение</h2>
                    </div>
                    <div class="modal-body">
                        <h4 align="center" style="margin:0;">Вы точно хотите удалить эту книгу?</h4>
                    </div>
                    <div class="modal-footer">
                        <button type="button" name="ok_button" id="ok_button" class="btn btn-danger">OK</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Назад</button>
                    </div>
                </div>
            </div>
        </div>
        
        <script>
            $(document).ready(function () {
        
                $('#book_table').DataTable({
                    language:
                    {
                        processing: "Подождите...",
                        search: "Поиск:",
                        lengthMenu: "Показать _MENU_ записей",
                        info: "Записи с _START_ до _END_ из _TOTAL_ записей",
                        infoEmpty: "Записи с 0 до 0 из 0 записей",
                        infoFiltered: "(отфильтровано из _MAX_ записей)",
                        infoPostFix: "",
                        loadingRecords: "Загрузка записей...",
                        zeroRecords: "Записи отсутствуют.",
                        emptyTable: "В таблице отсутствуют данные",
                        paginate: {
                            first: "Первая",
                            previous: "Предыдущая",
                            next: "Следующая",
                            last: "Последняя"
                        },
                        aria: {
                            SortAscending: ": активировать для сортировки столбца по возрастанию",
                            sortDescending: ": активировать для сортировки столбца по убыванию"
                        },
                    },
                   processing: true,
                   serverSide: true,
                   ajax: {
                       url: "{{ route('book.index') }}"
                   },
                   columns:[
                       {
                           data:'image',
                           name:'image',
                           render: function (data, type, full, meta) {
                             return "<img src={{ URL::to('/') }}/images/" + data + " width='70' class='img-thumbnail' />"
                           },
                           orderable: false
                       },
                       {
                           data: 'title',
                           name: 'title'
                       },
                       {
                           data: 'author',
                           name: 'author'
                       },
                       {
                           data: 'description',
                           name: 'description'
                       },
                       {
                           data: 'publicated',
                           name: 'publicated'
                       },
                       {
                           data: 'action',
                           name: 'action',
                           orderable: false
                       }
                   ],
               });
        
               $('#create_record').click(function () {
                    $('.modal-title').text("Добавить новую книгу!");
                    $('#action_button').val("Добавить!");
                    $('#action').val("Add");
                    $('#formModal').modal('show');
               });
        
               $('#sample_form').on('submit', function(event){
                    event.preventDefault();
                    if($('#action').val() == 'Add')
                    {
                        $.ajax({
                            url:"{{ route('book.store') }}",
                            method:"POST",
                            data: new FormData(this),
                            contentType: false,
                            cache:false,
                            processData: false,
                            dataType:"json",
                            success:function(data)
                            {
                                var html = '';
                                if(data.errors)
                                {
                                    html = '<div class="alert alert-danger">';
                                    for(var count = 0; count < data.errors.length; count++)
                                    {
                                        html += '<p>' + data.errors[count] + '</p>';
                                    }
                                    html += '</div>';
                                }
                                if(data.success)
                                {
                                    html = '<div class="alert alert-success">' + data.success + '</div>';
                                    $('#sample_form')[0].reset();
                                    $('#user_table').DataTable().ajax.reload();
                                }
                                $('#form_result').html(html);
                            }
                        })
                    }
        
                    if ($('#action').val() == "Edit")
                    {
                        $.ajax({
                           url:"{{ route('book.update') }}",
                           method: "POST",
                           data: new FormData(this),
                           contentType: false,
                            cache: false,
                            processData: false,
                            dataType: "json",
                            success:function (data)
                            {
                                var html = '';
                                if(data.errors)
                                {
                                    html = '<div class="alert alert-danger"';
                                    for(var count = 0; count < data.errors.length; count++ )
                                    {
                                        html +='<p>' + data.errors[count] + '</p>';
                                    }
                                    html += '</div>';
                                }
                                if (data.success)
                                {
                                    html = '<div class="alert alert-success">'+data.success+'</div>';
                                    $('#sample_form')[0].reset();
                                    $('#store_image').html('');
                                    $('#book_table').DataTable().ajax.reload();
                                }
                                $('#form_result').html(html);
                            }
                        });
                    }
                });
        
                $(document).on('click', '.edit', function(){
                    var id = $(this).attr('id');
                    $('#form_result').html('');
                    $.ajax({
                        url:"/book/"+id+"/edit",
                        dataType:"json",
                        success:function(html){
                            $('#title').val(html.data.title);
                            $('#author').val(html.data.author);
                            $('#store_image').html("<img src={{ URL::to('/') }}/images/" + html.data.image + " width='70' class='img-thumbnail' />");
                            $('#store_image').append("<input type='hidden' name='hidden_image' value='"+html.data.image+"' />");
                            $('#hidden_id').val(html.data.id);
                            $('#description').val(html.data.description);
                            $('#publicated').val(html.data.publicated);
                            $('.modal-title').text("Изменить книгу!");
                            $('#action_button').val("Edit");
                            $('#action').val("Edit");
                            $('#formModal').modal('show');
                        }
                    })
                });
        
                var user_id;
        
                $(document).on('click', '.delete', function () {
                   user_id = $(this).attr('id');
                   $('#confirmModal').modal('show');
                });
        
                $('#ok_button').click(function () {
                   $.ajax({
                       url:"book/destroy/"+user_id,
                       beforeSend: function () {
                           $('#ok_button').text('Удаление');
                       },
                       success:function (data)
                       {
                           setTimeout(function () {
                               $('#confirmModal').modal('hide');
                               $('#book_table').DataTable().ajax.reload();
                           },2000);
                       }
                   })
                });
            });
        </script>
        
    index.blade.php  в папке authors
            <html>
            <head>
                <meta name="viewport" content="width=device-width, initial-scale=1">
                <title>Каталог книг</title>
                <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
                <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
                <script src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
                <script src="https://cdn.datatables.net/1.10.12/js/dataTables.bootstrap.min.js"></script>
                <link rel="stylesheet" href="https://cdn.datatables.net/1.10.12/css/dataTables.bootstrap.min.css" />
                <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
            </head>
            <body>
            <div class="container">
                <br />
                <h3 align="center">Авторы</h3>
                <br />
            
                <div align="right">
                    <a type="button" class="btn btn-primary btn-sm" href="{{ '/' }}">На главную</a>
                    <a type="button" class="btn btn-danger btn-sm" href="{{ route('book.index') }}">Список книг</a>
                    <button type="button" name="create_record" id="create_record" class="btn btn-success btn-sm">Добавить автора</button>
                </div>
                <br />
                <div class="table-responsive">
                    <table class="table table-bordered table-striped" id="author_table">
                        <thead>
                        <tr>
                            <th width="30%">Фамилия</th>
                            <th width="30%">Имя</th>
                            <th width="25%">Отчество</th>
                            <th width="15%">Действие</th>
                        </tr>
                        </thead>
                    </table>
                </div>
                <br />
                <br />
            </div>
            </body>
            </html>
            
            <div id="formModal" class="modal fade" role="dialog">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">Добавить книгу</h4>
                        </div>
                        <div class="modal-body">
                            <span id="form_result"></span>
                            <form method="post" id="sample_form" class="form-horizontal" enctype="multipart/form-data">
                                @csrf
                                <div class="form-group">
                                    <label class="control-label col-md-4" >Фамилия : </label>
                                    <div class="col-md-8">
                                        <input type="text" name="surname" id="surname" class="form-control" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-4">Имя : </label>
                                    <div class="col-md-8">
                                        <input type="text" name="name" id="name" class="form-control" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-4">Отчество : </label>
                                    <div class="col-md-8">
                                        <input type="text" name="patronymic" id="patronymic" class="form-control" />
                                    </div>
                                </div>
                                <br />
                                <div class="form-group" align="center">
                                    <input type="hidden" name="action" id="action" />
                                    <input type="hidden" name="hidden_id" id="hidden_id" />
                                    <input type="submit" name="action_button" id="action_button" class="btn btn-warning" value="Add" />
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            
            <div id="confirmModal" class="modal fade" role="dialog">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h2 class="modal-title">Подтверждение</h2>
                        </div>
                        <div class="modal-body">
                            <h4 align="center" style="margin:0;">Вы уверены, что хотите удалить автора?</h4>
                        </div>
                        <div class="modal-footer">
                            <button type="button" name="ok_button" id="ok_button" class="btn btn-danger">ОК</button>
                            <button type="button" class="btn btn-default" data-dismiss="modal">Назад</button>
                        </div>
                    </div>
                </div>
            </div>
            
            <script>
                $(document).ready(function(){
            
                    $('#author_table').DataTable({
                        language:
                        {
                            processing: "Подождите...",
                            search: "Поиск:",
                            lengthMenu: "Показать _MENU_ записей",
                            info: "Записи с _START_ до _END_ из _TOTAL_ записей",
                            infoEmpty: "Записи с 0 до 0 из 0 записей",
                            infoFiltered: "(отфильтровано из _MAX_ записей)",
                            infoPostFix: "",
                            loadingRecords: "Загрузка записей...",
                            zeroRecords: "Записи отсутствуют.",
                            emptyTable: "В таблице отсутствуют данные",
                            paginate: {
                                first: "Первая",
                                previous: "Предыдущая",
                                next: "Следующая",
                                last: "Последняя"
                            },
                            aria: {
                                SortAscending: ": активировать для сортировки столбца по возрастанию",
                                sortDescending: ": активировать для сортировки столбца по убыванию"
                            },
                        },
                        processing: true,
                        serverSide: true,
                        ajax:{
                            url: "{{ route('author.index') }}",
                        },
                        columns:[
                            {
                                data: 'surname',
                                name: 'surname'
                            },
                            {
                                data: 'name',
                                name: 'name'
                            },
                            {
                                data: 'patronymic',
                                name: 'patronymic'
                            },
                            {
                                data: 'action',
                                name: 'action',
                                orderable: false
                            }
                        ]
                    });
            
                    $('#create_record').click(function(){
                        $('.modal-title').text("Добавить нового автора!");
                        $('#action_button').val("Добавить");
                        $('#action').val("Add");
                        $('#formModal').modal('show');
                    });
            
                    $('#sample_form').on('submit', function(event){
                        event.preventDefault();
                        if($('#action').val() == 'Add')
                        {
                            $.ajax({
                                url:"{{ route('author.store') }}",
                                method:"POST",
                                data: new FormData(this),
                                contentType: false,
                                cache:false,
                                processData: false,
                                dataType:"json",
                                success:function(data)
                                {
                                    var html = '';
                                    if(data.errors)
                                    {
                                        html = '<div class="alert alert-danger">';
                                        for(var count = 0; count < data.errors.length; count++)
                                        {
                                            html += '<p>' + data.errors[count] + '</p>';
                                        }
                                        html += '</div>';
                                    }
                                    if(data.success)
                                    {
                                        html = '<div class="alert alert-success">' + data.success + '</div>';
                                        $('#sample_form')[0].reset();
                                        $('#user_table').DataTable().ajax.reload();
                                    }
                                    $('#form_result').html(html);
                                }
                            })
                        }
            
                        if($('#action').val() == "Edit")
                        {
                            $.ajax({
                                url:"{{ route('author.update') }}",
                                method:"POST",
                                data:new FormData(this),
                                contentType: false,
                                cache: false,
                                processData: false,
                                dataType:"json",
                                success:function(data)
                                {
                                    var html = '';
                                    if(data.errors)
                                    {
                                        html = '<div class="alert alert-danger">';
                                        for(var count = 0; count < data.errors.length; count++)
                                        {
                                            html += '<p>' + data.errors[count] + '</p>';
                                        }
                                        html += '</div>';
                                    }
                                    if(data.success)
                                    {
                                        html = '<div class="alert alert-success">' + data.success + '</div>';
                                        $('#sample_form')[0].reset();
                                        $('#store_image').html('');
                                        $('#user_table').DataTable().ajax.reload();
                                    }
                                    $('#form_result').html(html);
                                }
                            });
                        }
                    });
            
                    $(document).on('click', '.edit', function(){
                        var id = $(this).attr('id');
                        $('#form_result').html('');
                        $.ajax({
                            url:"/author/"+id+"/edit",
                            dataType:"json",
                            success:function(html){
                                $('#surname').val(html.data.surname);
                                $('#name').val(html.data.name);
                                $('#patronymic').val(html.data.patronymic);
                                $('#hidden_id').val(html.data.id);
                                $('.modal-title').text("Edit New Record");
                                $('#action_button').val("Edit");
                                $('#action').val("Edit");
                                $('#formModal').modal('show');
                            }
                        })
                    });
            
                    var user_id;
            
                    $(document).on('click', '.delete', function(){
                        user_id = $(this).attr('id');
                        $('#confirmModal').modal('show');
                    });
            
                    $('#ok_button').click(function(){
                        $.ajax({
                            url:"author/destroy/"+user_id,
                            beforeSend:function(){
                                $('#ok_button').text('Удаление...');
                            },
                            success:function(data)
                            {
                                setTimeout(function(){
                                    $('#confirmModal').modal('hide');
                                    $('#author_table').DataTable().ajax.reload();
                                }, 2000);
                            }
                        })
                    });
            
                });
            </script>
        
13. Добавил роуты в web.php для получения всех данных
        
            Route::get('/', function () {
                return view('welcome');
            });
            
            Route::resource('book','BooksController');
            Route::post('book/update','BooksController@update')->name('book.update');
            Route::get('book/destroy/{id}', 'BooksController@destroy');
            
            Route::resource('author','AuthorsController');
            Route::post('author/update','AuthorsController@update')->name('author.update');
            Route::get('author/destroy/{id}', 'AuthorsController@destroy');
