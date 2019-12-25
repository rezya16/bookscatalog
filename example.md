{{--@extends('parent')

@section('main')

    <div align="right">
        <button type="button" id="create_record"
--}}{{--                href="{{ route('book.create') }}" --}}{{--
                class="btn btn-success btn-sm">
            Добавить</button>
    </div>

    @if($message = Session::get('success'))
        <div class="alert alert-success">
            <p> {{ $message }} </p>
        </div>
    @endif
    <div class="table-responsive">
        <table class="table table-bordered table-striped" id="book_table">
            <thead>
                <tr>
                    <th width="10%">Картинка</th>
                    <th width="15%">Название</th>
                    <th width="15%">Автор</th>
                    <th width="30%">Описание</th>
                    <th width="20%">Дата</th>
                    <th width="10%">Действие</th>
                </tr>
            </thead>
            @foreach($data as $row)
                <tr>
                    <td>{{ $row->title }}</td>
                    <td><a href="#">{{ $row->author }}</a></td>
                    <td><img src="{{ URL::to('/') }}/images/{{ $row->image }}"
                             class="img-thumbnail" width="75"></td>
                    <td>{{ $row->description }}</td>
                    <td>{{ $row->publication_date }}</td>
                    <td>
                        <a href="{{ route('book.show', $row->id) }}" class="btn btn-primary">Показать</a>
                        <a href="{{ route('book.edit', $row->id) }}" class="btn btn-warning">Изменить</a>
                        <form action="{{ route('book.destroy', $row->id) }}" method="post">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">
                                Удалить
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </table>
    </div>
    {!! $data->links() !!}
@endsection--}}