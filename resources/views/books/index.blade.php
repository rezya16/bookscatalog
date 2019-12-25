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
