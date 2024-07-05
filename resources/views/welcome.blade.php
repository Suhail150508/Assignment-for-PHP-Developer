<!DOCTYPE html>
<html>
<head>
    <title>Search Categories</title>
</head>
<body style="text-align: center">
    <h1>Search Categories</h1>
    <form action="{{ route('categories.search') }}" method="GET">
        @csrf
        <select class="form-select" name="name" aria-label="Default select example">
            <option selected> select category name </option>
            <option value="Electronics">Electronics</option>
            <option value="Accessories">Accessories</option>
            <option value="Wearable Accessories">Wearable Accessories</option>
            <option value="Smart Watch">Smart Watch</option>
        </select>
        <button type="submit">Search</button>
    </form>

    @if(isset($categories) && $categories->isNotEmpty())
    <h2>Search Results</h2>
    <ul>
        @foreach($categories as $category)
            <li>
                <h1>{{ $category->name }}</h1>
                @if($category->children->isNotEmpty())
                    <ul>
                        @foreach($category->children as $child)
                            <li>
                                <h2>{{ $child->name }}</h2>
                                {{-- You can recursively include the partial for deeper levels if needed --}}
                                {{-- @include('categories.partials.category', ['category' => $child]) --}}
                            </li>
                        @endforeach
                    </ul>
                @endif
            </li>
        @endforeach
    </ul>
@else
    <p>No categories found.</p>
@endif

</body>
</html>
