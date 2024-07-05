<!DOCTYPE html>
<html>
<head>
    <title>Category Management</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div style="background-color:cadetblue;width:100%;padding:3px">
        <h2 style="text-align: center;">Category Management</h2>

    </div>

    <div class="container mt-5">
        <h4>Execution time using Redis</h4>
        <p>Page rendered in {{ number_format($renderTime, 4) }} seconds.</p>
    </div>


<div class="container mt-5">
    <div class="row">
        <div class="col-lg-12" style="display: flex;justify-content:space-between;margin-top:4rem">
            <div class="col-lg-6">

            </div>
            <div class="col-lg-6 ml-5">
                <h4>Search child category by parent</h4>
                <form action="{{ route('categories.search') }}" method="GET">
                    @csrf
                    <select class="form-select" name="name" aria-label="Default select example" style="height:33px;margin-top:.8rem">
                        <option selected>Select category name</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->name }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="btn btn-primary" >Search</button>
                </form>
            </div>
        </div>
        <div class="col-lg-12">
            @if ($message = Session::get('success'))
            <div class="alert alert-success">
                <p>{{ $message }}</p>
            </div>
            @endif
            <div class="col-lg-12 mb-4" style="float:right">
                <button class="btn btn-success" data-toggle="modal" data-target="#createModal">Create New Category</button>
            </div>
        </div>
        <div class="col-lg-12">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>No</th>
                        <th> Category Name</th>
                        <th>Parent</th>
                        <th>Active</th>
                        <th width="280px">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($categories as $category)
                    <tr>
                        <td>{{ $category->id }}</td>
                        <td>{{ $category->name }}</td>
                        <td>{{ $category->parent ? $category->parent->name : 'N/A' }}</td>
                        <td>{{ $category->active ? 'Active' : 'Inactive' }}</td>
                        <td>
                            <a href="{{ route('categories.deactivate', $category->id) }}" class="btn btn-warning">Deactivate</a>
                            <button class="btn btn-primary" data-toggle="modal" data-target="#editModal{{ $category->id }}">Edit</button>
                            <form action="{{ route('categories.destroy', $category->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>

                    <!-- Edit Modal -->
                    <div class="modal fade" id="editModal{{ $category->id }}" tabindex="-1" role="dialog" aria-labelledby="editModalLabel{{ $category->id }}" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editModalLabel{{ $category->id }}">Edit Category</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <form action="{{ route('categories.update', $category->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="form-group">
                                            <label for="name">Category Name:</label>
                                            <input type="text" name="name" class="form-control" value="{{ $category->name }}">
                                        </div>
                                        <div class="form-group">
                                            <label for="parent_id">Parent Category:</label>
                                            <select name="parent_id" class="form-control">
                                                <option value="">Select Parent Category</option>
                                                @foreach($categories as $cat)
                                                    <option value="{{ $cat->id }}" {{ $cat->id == $category->parent_id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Create Modal -->
    <div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="createModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createModalLabel">Create New Category</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('categories.store') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="name">Category Name:</label>
                            <input type="text" name="name" class="form-control" placeholder="Enter Category Name">
                        </div>
                        <div class="form-group">
                            <label for="parent_id">Parent Category:</label>
                            <select name="parent_id" class="form-control">
                                <option value="">Select Parent Category</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
